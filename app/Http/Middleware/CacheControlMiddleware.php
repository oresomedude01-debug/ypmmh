<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enhanced Cache Control Middleware
 * 
 * Implements granular cache control policies based on response type and route.
 * 
 * Strategy:
 * - Dynamic content: no-cache, must-revalidate
 * - API responses: no-store, no-cache, must-revalidate
 * - Static assets: Cache with version (handled by app versioning system)
 * - Authenticated routes: no-store (prevent browser caching sensitive data)
 */
class CacheControlMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Never cache error responses
        if ($response->getStatusCode() >= 400) {
            $this->setNoCacheHeaders($response);
            return $response;
        }

        // API endpoints should never be cached
        if ($this->isApiRequest($request)) {
            $this->setNoCacheHeaders($response);
            return $response;
        }

        // Authenticated routes - prevent browser caching of sensitive data
        if ($request->user()) {
            $this->setNoStoreHeaders($response);
            return $response;
        }

        // Redirect responses should not be cached
        if ($response->isRedirect()) {
            $this->setNoCacheHeaders($response);
            return $response;
        }

        // Public routes can use standard cache headers
        // but Service Worker will handle actual caching
        $this->setPublicCacheHeaders($response);

        return $response;
    }

    /**
     * Set strict no-cache headers - don't store or cache this response
     * 
     * Used for: API responses, dynamic content, error pages
     */
    private function setNoCacheHeaders(Response $response): void
    {
        $response->header('Cache-Control', 'no-cache, no-store, must-revalidate, max-age=0, proxy-revalidate');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');
        $response->header('Vary', 'Accept-Encoding, Authorization');
    }

    /**
     * Set no-store headers for authenticated routes
     * 
     * Prevents browser from storing sensitive data in cache
     * Still allows Service Worker to cache for offline functionality
     */
    private function setNoStoreHeaders(Response $response): void
    {
        $response->header('Cache-Control', 'private, no-cache, no-store, must-revalidate, max-age=0');
        $response->header('Pragma', 'no-cache');
        $response->header('Expires', '0');
        $response->header('Vary', 'Accept-Encoding, Authorization, Cookie');
    }

    /**
     * Set cacheable headers for public content
     * 
     * Public pages can be cached but should revalidate frequently
     * Service Worker will enforce actual caching strategy
     */
    private function setPublicCacheHeaders(Response $response): void
    {
        // Max-age: 0 means always revalidate with server
        // Cache-Control: public allows proxies/CDN to cache
        // But browser will always check freshness
        $response->header('Cache-Control', 'public, max-age=0, must-revalidate');
        $response->header('Vary', 'Accept-Encoding');
    }

    /**
     * Check if this is an API request
     */
    private function isApiRequest(Request $request): bool
    {
        // Check if it's an API route
        if (strpos($request->getPathInfo(), '/api/') === 0) {
            return true;
        }

        // Check if it's requesting JSON
        if ($request->wantsJson() || $request->ajax()) {
            return true;
        }

        // Check for API-like endpoints even if not under /api/
        $apiPaths = [
            'sanctum',
            'broadcasting',
            'webhooks',
            'telescope',
            'horizon',
        ];

        foreach ($apiPaths as $path) {
            if (strpos($request->getPathInfo(), '/' . $path . '/') === 0) {
                return true;
            }
        }

        return false;
    }
}
