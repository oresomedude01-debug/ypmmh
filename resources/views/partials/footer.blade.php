<footer class="glass mt-8 px-6 py-4" role="contentinfo">
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Copyright -->
        <div class="text-sm" style="color: var(--text-secondary);">
            <p>&copy; {{ date('Y') }} YPMMH Madrasah. All rights reserved.</p>
        </div>

        <!-- Links -->
        <div class="flex items-center gap-6">
            <a href="/privacy" class="text-sm hover:underline" style="color: var(--text-secondary);">
                Privacy Policy
            </a>
            <a href="/terms" class="text-sm hover:underline" style="color: var(--text-secondary);">
                Terms of Service
            </a>
            <a href="/help" class="text-sm hover:underline" style="color: var(--text-secondary);">
                Help Center
            </a>
        </div>

        <!-- Version & Status -->
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                <span class="text-xs" style="color: var(--text-secondary);">System Online</span>
            </div>
            <span class="text-xs" style="color: var(--text-secondary);">v1.0.0</span>
        </div>
    </div>
</footer>