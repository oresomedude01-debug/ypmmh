/**
 * PWA Initialization Script
 * ==========================
 * This module should be imported early in the application to initialize
 * the PWA version checking system and service worker integration.
 * 
 * Import it in your main app or include it in your layout.
 */

// Import the version manager
import versionManager from '/resources/js/pwa/version-manager.js';

/**
 * Initialize PWA systems when DOM is ready
 * This handles version detection, cache clearing, and updates
 */
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPWA);
} else {
    // DOM already loaded
    initPWA();
}

async function initPWA() {
    try {
        console.log('[PWA] Initializing PWA systems...');

        // Initialize the version manager (this will detect version changes automatically)
        await versionManager.init();

        console.log('[PWA] ✓ PWA initialization complete');
    } catch (error) {
        console.error('[PWA] Error during initialization:', error);
    }
}

// Export for manual use if needed
export { versionManager };
export default versionManager;
