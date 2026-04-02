@php
    $isFullScreen = $fullScreen ?? false;
@endphp

<div class="flex flex-col glass {{ !$isFullScreen ? 'rounded-3xl border border-slate-100 shadow-sm min-h-[600px] max-h-[800px]' : '' }} overflow-hidden chat-container"
    id="program-chat-{{ $program->id }}">
    <!-- Chat Header -->
    @if(!$isFullScreen)
        <div class="px-6 py-4 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#0B4D73] text-white flex items-center justify-center shadow-sm">
                    <i class="fas fa-comments"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-900 leading-tight">Program Community</h3>
                    <p class="text-[10px] text-slate-500 uppercase tracking-widest font-bold">Secure Group Chat</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- View Members Button -->
                <button onclick="document.getElementById('membersModal').classList.remove('hidden')"
                    class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                    <i class="fas fa-users text-[#0B4D73]"></i>
                    <span>Members ({{ $program->children->count() + 1 }})</span>
                </button>

                <div id="polling-indicator"
                    class="flex items-center gap-2 text-[10px] font-bold text-slate-400 bg-white px-3 py-2 rounded-xl border border-slate-100">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    LIVE
                </div>
            </div>
        </div>
    @endif

    <!-- Messages Area -->
    <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4 bg-white/50 custom-scrollbar flex flex-col">
        <div class="flex items-center justify-center h-full text-slate-400 italic text-sm py-20">
            <div class="text-center">
                <i class="fas fa-circle-notch fa-spin mb-2 text-2xl"></i>
                <p>Establishing secure connection...</p>
            </div>
        </div>
    </div>

    <!-- Message Input -->
    <div class="px-4 pt-3 md:px-6 md:pt-6 bg-slate-50/50 border-t border-slate-100 shrink-0"
        style="{{ $isFullScreen ? 'padding-bottom: max(0.5rem, env(safe-area-inset-bottom, 0.5rem));' : 'padding-bottom: 0.75rem;' }}">
        @php
            $userEnrollment = $program->enrollments()->where('user_id', auth()->id())->first();
            $isSuspended = $userEnrollment && $userEnrollment->chat_status === 'suspended';
        @endphp

        @if($isSuspended)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-amber-500"></i>
                <p class="text-sm text-amber-700 font-medium">Your chat privileges have been temporarily suspended by a
                    moderator. You can still read messages.</p>
            </div>
        @else
            <form id="chat-form" class="relative group">
                <textarea id="message-input" rows="1"
                    class="w-full pl-5 pr-14 py-3.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] transition-all resize-none shadow-sm text-sm"
                    placeholder="Type your message here..."></textarea>
                <button type="submit"
                    class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-[#0B4D73] text-white rounded-xl flex items-center justify-center hover:bg-[#093e5d] transition-all shadow-md active:scale-95 disabled:opacity-50"
                    id="send-button">
                    <i class="fas fa-paper-plane text-xs"></i>
                </button>
            </form>
        @endif
    </div>
</div>

<!-- Members Modal -->
<div id="membersModal"
    class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden animate-slide-in">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-900 uppercase tracking-widest text-xs">Community Members</h3>
            <button onclick="document.getElementById('membersModal').classList.add('hidden')"
                class="text-slate-400 hover:text-red-500 p-2">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 space-y-4 max-h-[60vh] overflow-y-auto custom-scrollbar">
            <!-- Mentor -->
            @if($program->mentor)
                <div class="flex items-center justify-between p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-[#0B4D73] text-white flex items-center justify-center font-bold ring-4 ring-white shadow-sm">
                            {{ substr($program->mentor->first_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 leading-tight">{{ $program->mentor->full_name }}</p>
                            <span class="text-[10px] font-bold text-[#0B4D73] uppercase tracking-tighter">Lead Mentor
                                (Moderator)</span>
                        </div>
                    </div>
                </div>
            @endif

            <div class="h-px bg-slate-100 mx-2"></div>

            <!-- Enrolled Mentees -->
            @foreach($program->children as $child)
                <div
                    class="flex items-center justify-between p-4 rounded-2xl bg-white border border-slate-100 hover:border-blue-200 transition-colors">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold relative overflow-hidden shrink-0">
                            @if($child->profile_picture)
                                <img src="{{ asset('storage/' . $child->profile_picture) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($child->first_name, 0, 1) }}
                            @endif
                            @php
                                $cEnroll = $program->enrollments->where('user_id', $child->id)->first();
                                $cSuspended = $cEnroll && $cEnroll->chat_status === 'suspended';
                            @endphp
                            @if($cSuspended)
                                <span
                                    class="absolute -top-1 -right-1 w-4 h-4 bg-amber-500 rounded-full border-2 border-white flex items-center justify-center shadow-sm z-10"
                                    title="Suspended">
                                    <i class="fas fa-pause text-[6px] text-white"></i>
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 leading-tight">{{ $child->full_name }}</p>
                            @if($cSuspended)
                                <span class="text-[9px] font-bold text-amber-600 uppercase tracking-wide">Suspended</span>
                            @else
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wide">Mentee</span>
                            @endif
                        </div>
                    </div>

                    <!-- Moderation & Reporting Actions -->
                    <div class="flex items-center gap-2">
                        @if(auth()->id() !== $child->id)
                            <button
                                onclick="openReportModal('App\\\\Models\\\\User', {{ $child->id }}, 'Report User', 'Flag this student for administrator review.')"
                                class="w-8 h-8 flex items-center justify-center text-slate-300 hover:text-rose-500 bg-slate-50 hover:bg-rose-50 rounded-xl transition-all"
                                title="Report Mentee">
                                <i class="fas fa-flag text-[10px]"></i>
                            </button>
                        @endif

                        @if(auth()->user()->hasRole('Admin') || auth()->id() === $program->mentor_id)
                            <button onclick="toggleSuspension({{ $child->id }}, '{{ $cSuspended ? 'active' : 'suspended' }}')"
                                class="w-8 h-8 flex items-center justify-center {{ $cSuspended ? 'text-emerald-500 bg-emerald-50 hover:bg-emerald-100' : 'text-amber-500 bg-amber-50 hover:bg-amber-100' }} rounded-xl transition-all shadow-sm"
                                title="{{ $cSuspended ? 'Reinstate' : 'Suspend' }}">
                                <i class="fas {{ $cSuspended ? 'fa-play' : 'fa-user-slash' }} text-[10px]"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }

    #chat-messages>div:last-child {
        padding-bottom: 0.5rem;
    }
</style>

<script>
    (function () {
        const programId = {{ $program->id }};
        const currentUserId = {{ auth()->id() }};
        const fetchUrlBase = "{{ route(auth()->user()->hasRole('Admin') ? 'admin.programs.chat.messages' : (auth()->user()->hasRole('Mentor') ? 'mentor.programs.chat.messages' : 'child.programs.chat.messages'), $program->id) }}";
        const sendUrl = "{{ route(auth()->user()->hasRole('Admin') ? 'admin.programs.chat.send' : (auth()->user()->hasRole('Mentor') ? 'mentor.programs.chat.send' : 'child.programs.chat.send'), $program->id) }}";

        let lastMessageId = 0;
        let isInitialLoad = true;
        const chatArea = document.getElementById('chat-messages');

        async function fetchMessages() {
            try {
                // Efficiency: Only fetch messages newer than our last received ID
                const response = await fetch(`${fetchUrlBase}?last_id=${lastMessageId}`);
                const messages = await response.json();

                messages.forEach(msg => {
                    const existing = document.getElementById(`msg-${msg.id}`);
                    if (existing) {
                        // Update reaction counts if message exists
                        const reactionContainer = existing.querySelector('.reaction-bar');
                        if (reactionContainer) {
                            reactionContainer.innerHTML = renderReactions(msg);
                        }
                        return;
                    }

                    const isMe = msg.user_id === currentUserId;
                    const messageElement = document.createElement('div');
                    messageElement.id = `msg-${msg.id}`;
                    messageElement.className = `flex flex-col ${isMe ? 'items-end' : 'items-start'} space-y-1 animate-fade-in mb-4`;

                    messageElement.innerHTML = `
                        <div class="flex items-center gap-2 mb-1 px-1">
                            <span class="text-[10px] font-bold ${msg.is_moderator ? 'text-[#0B4D73]' : 'text-slate-500'} uppercase tracking-tight">
                                ${msg.user_name} ${msg.is_moderator ? '<i class="fas fa-shield-alt ml-1"></i>' : ''}
                            </span>
                            <span class="text-[9px] text-slate-400 font-medium">${msg.created_at}</span>
                        </div>
                        <div class="relative group max-w-[85%]">
                            <div class="p-3.5 rounded-2xl text-[13px] leading-relaxed shadow-sm ${isMe ? 'bg-[#0B4D73] text-white rounded-tr-none' : 'bg-slate-100 text-slate-700 rounded-tl-none border border-slate-200/50'}">
                                ${msg.content}
                            </div>
                            
                            <!-- Reactions Bar -->
                            <div class="reaction-bar flex items-center gap-1 mt-1 ${isMe ? 'justify-end' : 'justify-start'}">
                                ${renderReactions(msg)}
                                ${!isMe ? `
                                    <button onclick="openReportModal('App\\\\Models\\\\Chat\\\\Message', ${msg.id}, 'Report Message', 'Flag this message for administrator review.')" 
                                        class="p-1 text-slate-300 hover:text-rose-400 transition-colors" title="Report message">
                                        <i class="fas fa-flag text-[9px]"></i>
                                    </button>
                                ` : ''}
                            </div>

                            ${msg.can_delete ? `
                                <button onclick="deleteMessage(${msg.id})" class="absolute ${isMe ? '-left-8' : '-right-8'} top-8 -translate-y-1/2 p-2 text-slate-200 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-all">
                                    <i class="fas fa-trash-alt text-[10px]"></i>
                                </button>
                            ` : ''}
                        </div>
                    `;

                    chatArea.appendChild(messageElement);
                    lastMessageId = Math.max(lastMessageId, msg.id);

                    if (isInitialLoad) {
                        chatArea.scrollTop = chatArea.scrollHeight;
                    }
                });

                if (!isInitialLoad) {
                    // Smooth scroll to bottom on new messages
                    chatArea.scrollTo({
                        top: chatArea.scrollHeight,
                        behavior: 'smooth'
                    });
                }
                isInitialLoad = false;

            } catch (error) {
                console.error('Connection issue:', error);
            }
        }

        function renderReactions(msg) {
            const types = [
                { id: 'like', icon: 'fa-thumbs-up', color: 'text-blue-500', activeBg: 'bg-blue-50' },
                { id: 'love', icon: 'fa-heart', color: 'text-rose-500', activeBg: 'bg-rose-50' },
                { id: 'dislike', icon: 'fa-thumbs-down', color: 'text-slate-500', activeBg: 'bg-slate-50' }
            ];

            return types.map(type => `
                <button onclick="toggleReaction(${msg.id}, '${type.id}')" 
                    class="flex items-center gap-1 px-2 py-1 rounded-full border border-slate-100 transition-all hover:scale-110 active:scale-95 ${msg.my_reaction === type.id ? type.activeBg + ' border-' + type.id + '-200 shadow-sm' : 'bg-white/50'}">
                    <i class="fas ${type.icon} text-[9px] ${msg.my_reaction === type.id ? type.color : 'text-slate-300'}"></i>
                    <span class="text-[9px] font-black ${msg.my_reaction === type.id ? 'text-slate-700' : 'text-slate-400'}">${msg.reactions[type.id] || ''}</span>
                </button>
            `).join('');
        }

        async function toggleReaction(messageId, type) {
            try {
                // Determine base URL based on role (hacky but consistent with current routing)
                const rolePrefix = window.location.pathname.includes('/child/') ? '/child' : '/mentor';
                const url = `${rolePrefix}/programs/${programId}/messages/${messageId}/react`;

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ type })
                });

                if (response.ok) {
                    fetchMessages(); // Refresh to show new counts
                }
            } catch (error) {
                console.error('Reaction error:', error);
            }
        }

        const chatForm = document.getElementById('chat-form');
        if (chatForm) {
            chatForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const input = document.getElementById('message-input');
                const button = document.getElementById('send-button');
                const content = input.value.trim();

                if (!content) return;
                button.disabled = true;

                try {
                    const response = await fetch(sendUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ content })
                    });

                    if (response.ok) {
                        input.value = '';
                        input.style.height = 'auto';
                        fetchMessages();
                    } else {
                        const data = await response.json();
                        alert(data.error || 'Connection dropped.');
                    }
                } catch (error) {
                    console.error('Send failed:', error);
                } finally {
                    button.disabled = false;
                }
            });

            document.getElementById('message-input').addEventListener('input', function () {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }

        // Expose functions to global scope since they are called from inline HTML
        window.deleteMessage = async function (messageId) {
            if (!confirm('Permanent delete?')) return;
            try {
                const rolePrefix = "{{ auth()->user()->hasRole('Admin') ? 'admin' : (auth()->user()->hasRole('Mentor') ? 'mentor' : 'child') }}";
                const response = await fetch(`/${rolePrefix}/chat/messages/${messageId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if (response.ok) {
                    const el = document.getElementById(`msg-${messageId}`);
                    if (el) el.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => el?.remove(), 300);
                }
            } catch (error) { console.error('Delete failed:', error); }
        };

        window.toggleSuspension = async function (userId, action) {
            if (!confirm(action === 'suspended' ? 'Restrict chat access?' : 'Restore chat access?')) return;
            try {
                const prefix = "{{ auth()->user()->hasRole('Admin') ? 'admin' : 'mentor' }}";
                const response = await fetch(`/` + prefix + `/programs/${programId}/chat/members/${userId}/toggle-suspension`, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                if (response.ok) location.reload();
            } catch (error) { console.error('Toggle failed:', error); }
        };

        fetchMessages();
        setInterval(fetchMessages, 12000); // Poll every 12s
    })();
</script>