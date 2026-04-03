<!-- Cache Clear Button with AJAX - Add to Admin Dashboard -->
<!-- Place this in your admin dashboard or settings page -->

<div class="glass rounded-2xl p-6 border mb-6" style="border-color: var(--border-color);">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h3 class="text-lg font-bold mb-1">System Cache Management</h3>
            <p class="text-sm" style="color: var(--text-secondary);">
                Clear application cache, config, and views to apply latest changes immediately
            </p>
        </div>
        <button id="clearCacheBtn" class="btn btn-primary shadow-lg shadow-[#0B4D73]/20" onclick="clearApplicationCache()">
            <i class="fas fa-broom mr-2"></i>
            <span id="cacheBtnText">Clear Cache</span>
        </button>
    </div>
</div>

<script>
    /**
     * Clear all application caches via AJAX
     * Calls: POST /admin/cache/clear
     */
    async function clearApplicationCache() {
        const btn = document.getElementById('clearCacheBtn');
        const btnText = document.getElementById('cacheBtnText');
        const originalText = btnText.textContent;

        try {
            // Disable button and show loading state
            btn.disabled = true;
            btnText.textContent = 'Clearing...';
            btn.style.opacity = '0.6';

            const response = await fetch('{{ route("admin.cache.clear") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                btnText.textContent = '✓ Cache Cleared!';
                btn.classList.remove('btn-primary');
                btn.classList.add('bg-emerald-500');

                // Show toast notification if available
                if (typeof showToast === 'function') {
                    showToast(data.messages.join(' | '), 'success', 3000);
                } else {
                    alert('Application cache cleared successfully!\n\n' + data.messages.join('\n'));
                }

                // Reset after 2 seconds
                setTimeout(() => {
                    btn.disabled = false;
                    btnText.textContent = originalText;
                    btn.classList.remove('bg-emerald-500');
                    btn.classList.add('btn-primary');
                    btn.style.opacity = '1';
                }, 2000);
            }
        } catch (error) {
            console.error('Error clearing cache:', error);

            btnText.textContent = '✗ Error!';
            btn.classList.remove('btn-primary');
            btn.classList.add('bg-red-500');

            if (typeof showToast === 'function') {
                showToast('Error clearing cache: ' + error.message, 'error', 3000);
            } else {
                alert('Error clearing cache: ' + error.message);
            }

            // Reset after 3 seconds
            setTimeout(() => {
                btn.disabled = false;
                btnText.textContent = originalText;
                btn.classList.remove('bg-red-500');
                btn.classList.add('btn-primary');
                btn.style.opacity = '1';
            }, 3000);
        }
    }
</script>
