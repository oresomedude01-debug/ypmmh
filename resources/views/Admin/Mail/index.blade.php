@extends('layouts.dashboard')

@section('title', 'Send Direct Mail')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
                Send Direct Mail
            </h1>
            <p class="font-medium" style="color: var(--text-secondary);">Send personalized emails to students and parents</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex gap-3">
                <i class="fas fa-exclamation-circle text-red-500 mt-1"></i>
                <div>
                    <h3 class="font-bold text-red-800">Error</h3>
                    <ul class="text-red-700 text-sm mt-1 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
            <div class="flex gap-3">
                <i class="fas fa-check-circle text-emerald-500 mt-1"></i>
                <div>
                    <h3 class="font-bold text-emerald-800">Success</h3>
                    <p class="text-emerald-700 text-sm mt-1">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
            <div class="flex gap-3">
                <i class="fas fa-times-circle text-red-500 mt-1"></i>
                <div>
                    <h3 class="font-bold text-red-800">Error</h3>
                    <p class="text-red-700 text-sm mt-1">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Mail Form Card -->
    <div class="admin-card p-6 sm:p-8">
        <form action="{{ route('admin.mail.send') }}" method="POST" id="mailForm" class="space-y-6">
            @csrf

            <!-- Recipient Type Selection -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold mb-2" style="color: var(--text-primary);">
                        <i class="fas fa-users mr-2"></i>Recipient Type
                    </label>
                    <div class="flex gap-3">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="recipient_type" value="Student" class="w-4 h-4" id="typeStudent" required onchange="updateRecipients()">
                            <span class="ml-2 text-sm" style="color: var(--text-secondary);">Student</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="recipient_type" value="Parent" class="w-4 h-4" id="typeParent" onchange="updateRecipients()">
                            <span class="ml-2 text-sm" style="color: var(--text-secondary);">Parent</span>
                        </label>
                    </div>
                    @error('recipient_type')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Recipient Selection -->
                <div>
                    <label class="block text-sm font-bold mb-2" style="color: var(--text-primary);">
                        <i class="fas fa-user-circle mr-2"></i>Select Recipient
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="recipientSearch" 
                            placeholder="Search by name or email..."
                            class="w-full px-4 py-2 rounded-lg border transition-all"
                            style="background: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                            onkeyup="searchRecipients()"
                        >
                        <div id="searchResults" class="absolute top-full left-0 right-0 mt-2 bg-white border rounded-lg shadow-lg hidden max-h-60 overflow-y-auto z-10" style="background: var(--bg-secondary); border-color: var(--border-color);">
                        </div>
                    </div>
                    <input type="hidden" name="recipient_id" id="recipientId" required>
                    @error('recipient_id')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Selected Recipient Display -->
            <div id="selectedRecipient" class="hidden p-4 rounded-lg" style="background: var(--bg-primary); border: 1px solid var(--border-color);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold" style="color: var(--text-secondary);">Selected Recipient</p>
                        <p class="text-lg font-bold mt-1" id="selectedRecipientName"></p>
                        <p class="text-sm" style="color: var(--text-secondary);" id="selectedRecipientEmail"></p>
                    </div>
                    <button type="button" onclick="clearRecipient()" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>

            <!-- Email Subject -->
            <div>
                <label class="block text-sm font-bold mb-2" style="color: var(--text-primary);">
                    <i class="fas fa-heading mr-2"></i>Email Subject
                </label>
                <input 
                    type="text" 
                    name="subject" 
                    placeholder="Enter email subject..."
                    class="w-full px-4 py-2 rounded-lg border transition-all"
                    style="background: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                    required
                    maxlength="255"
                    value="{{ old('subject') }}"
                >
                @error('subject')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email Message -->
            <div>
                <label class="block text-sm font-bold mb-2" style="color: var(--text-primary);">
                    <i class="fas fa-envelope mr-2"></i>Message
                </label>
                <textarea 
                    name="message" 
                    placeholder="Type your message here..."
                    rows="10"
                    class="w-full px-4 py-2 rounded-lg border transition-all resize-none"
                    style="background: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
                    required
                    maxlength="5000"
                ></textarea>
                <div class="flex justify-between items-center mt-2">
                    <small class="text-xs" style="color: var(--text-secondary);">
                        Format: You can use basic text formatting. Maximum 5000 characters.
                    </small>
                    <span class="text-xs font-bold" style="color: var(--text-secondary);">
                        <span id="charCount">0</span>/5000
                    </span>
                </div>
                @error('message')
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Preview -->
            <div class="border-t" style="border-color: var(--border-color);"></div>
            <details class="group">
                <summary class="cursor-pointer font-bold flex items-center gap-2" style="color: var(--text-primary);">
                    <i class="fas fa-eye"></i>Preview Email
                </summary>
                <div class="mt-4 p-4 rounded-lg" style="background: var(--bg-primary); border: 1px solid var(--border-color);">
                    <div class="text-sm space-y-3" style="color: var(--text-secondary);">
                        <div>
                            <span class="font-bold">To:</span> 
                            <span id="previewTo">Not selected</span>
                        </div>
                        <div>
                            <span class="font-bold">Subject:</span> 
                            <span id="previewSubject">-</span>
                        </div>
                        <div class="border-t mt-4 pt-4" style="border-color: var(--border-color);">
                            <span class="font-bold block mb-2">Message Preview:</span>
                            <div id="previewMessage" class="whitespace-pre-wrap" style="color: var(--text-primary);">
                                Your message will appear here...
                            </div>
                        </div>
                    </div>
                </div>
            </details>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t" style="border-color: var(--border-color);">
                <button type="button" onclick="resetForm()" class="flex-1 px-6 py-2 rounded-lg font-bold transition-all border" style="background: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
                    <i class="fas fa-redo mr-2"></i>Clear Form
                </button>
                <button type="submit" class="flex-1 px-6 py-2 rounded-lg font-bold text-white transition-all hover:brightness-110 shadow-lg" style="background: linear-gradient(135deg, #0B4D73, #0D6FA0); box-shadow: 0 4px 12px rgba(11, 77, 115, 0.3);">
                    <i class="fas fa-paper-plane mr-2"></i>Send Email
                </button>
            </div>
        </form>
    </div>

    <!-- Info Card -->
    <div class="admin-card p-6" style="border-left: 4px solid #0B4D73;">
        <h3 class="font-bold mb-3 flex items-center gap-2">
            <i class="fas fa-info-circle text-[#0B4D73]"></i>
            Important Notes
        </h3>
        <ul class="space-y-2 text-sm" style="color: var(--text-secondary);">
            <li><i class="fas fa-check text-emerald-500 mr-2"></i>Emails are sent immediately after submission</li>
            <li><i class="fas fa-check text-emerald-500 mr-2"></i>Recipients will see your name as the sender</li>
            <li><i class="fas fa-check text-emerald-500 mr-2"></i>Keep messages professional and courteous</li>
            <li><i class="fas fa-check text-emerald-500 mr-2"></i>All email activities are logged for record-keeping</li>
        </ul>
    </div>
</div>

<script>
    // Update character count
    document.querySelector('textarea[name="message"]')?.addEventListener('input', function() {
        document.getElementById('charCount').textContent = this.value.length;
        updatePreview();
    });

    // Update subject preview
    document.querySelector('input[name="subject"]')?.addEventListener('input', function() {
        updatePreview();
    });

    // Search for recipients
    function searchRecipients() {
        const typeSelected = document.querySelector('input[name="recipient_type"]:checked');
        if (!typeSelected) {
            alert('Please select recipient type first');
            return;
        }

        const search = document.getElementById('recipientSearch').value.trim();
        const resultsDiv = document.getElementById('searchResults');

        if (search.length < 2) {
            resultsDiv.classList.add('hidden');
            return;
        }

        fetch(`{{ route('admin.mail.get-recipients') }}?type=${typeSelected.value}&search=${encodeURIComponent(search)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    resultsDiv.innerHTML = '<div class="p-3 text-sm" style="color: var(--text-secondary);">No recipients found</div>';
                } else {
                    resultsDiv.innerHTML = data.map(r => `
                        <div class="p-3 cursor-pointer hover:brightness-95 transition-all border-b" style="border-color: var(--border-color);" onclick="selectRecipient(${r.id}, '${r.name}', '${r.email}')">
                            <div class="font-bold text-sm">${r.name}</div>
                            <div class="text-xs" style="color: var(--text-secondary);">${r.email}</div>
                        </div>
                    `).join('');
                }
                resultsDiv.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                resultsDiv.classList.add('hidden');
            });
    }

    // Select a recipient
    function selectRecipient(id, name, email) {
        document.getElementById('recipientId').value = id;
        document.getElementById('recipientSearch').value = name;
        document.getElementById('searchResults').classList.add('hidden');
        
        document.getElementById('selectedRecipient').classList.remove('hidden');
        document.getElementById('selectedRecipientName').textContent = name;
        document.getElementById('selectedRecipientEmail').textContent = email;
        
        updatePreview();
    }

    // Clear selected recipient
    function clearRecipient() {
        document.getElementById('recipientId').value = '';
        document.getElementById('recipientSearch').value = '';
        document.getElementById('selectedRecipient').classList.add('hidden');
        updatePreview();
    }

    // Update recipients list on type change
    function updateRecipients() {
        clearRecipient();
        document.getElementById('recipientSearch').value = '';
    }

    // Update preview
    function updatePreview() {
        const selected = document.getElementById('selectedRecipientName').textContent;
        const email = document.getElementById('selectedRecipientEmail').textContent;
        const subject = document.querySelector('input[name="subject"]').value || '-';
        const message = document.querySelector('textarea[name="message"]').value || 'Your message will appear here...';

        document.getElementById('previewTo').textContent = email || 'Not selected';
        document.getElementById('previewSubject').textContent = subject;
        document.getElementById('previewMessage').textContent = message;
    }

    // Reset form
    function resetForm() {
        document.getElementById('mailForm').reset();
        clearRecipient();
        document.getElementById('charCount').textContent = '0';
        document.getElementById('searchResults').classList.add('hidden');
        updatePreview();
    }
</script>
@endsection
