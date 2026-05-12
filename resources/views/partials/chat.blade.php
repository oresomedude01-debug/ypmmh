@php
    $isFullScreen = $fullScreen ?? false;
    $user = auth()->user();
    $rolePrefix = $user->hasRole('Admin') ? 'admin' : ($user->hasRole('Mentor') ? 'mentor' : 'child');

    $fetchUrl  = route("{$rolePrefix}.programs.chat.messages", $program->id);
    $sendUrl   = route("{$rolePrefix}.programs.chat.send",     $program->id);
    // Base for reactions: append /{messageId}/react in JS
    $reactBase = route("{$rolePrefix}.programs.chat.messages.react", [$program->id, 0]);
    $reactBase = rtrim(preg_replace('#/0/react$#', '', $reactBase), '/'); // strip the placeholder

    $deleteBase = route("{$rolePrefix}.chat.messages.destroy", 0);
    $deleteBase = rtrim(preg_replace('#/0$#', '', $deleteBase), '/');

    $userEnrollment = $program->enrollments()->where('user_id', auth()->id())->first();
    $isSuspended    = $userEnrollment && $userEnrollment->chat_status === 'suspended';
@endphp

<div class="flex flex-col glass {{ !$isFullScreen ? 'rounded-3xl border border-slate-100 shadow-sm min-h-[600px] max-h-[800px]' : '' }} overflow-hidden chat-container"
    id="program-chat-{{ $program->id }}">

    {{-- Chat Header (non-fullscreen only) --}}
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

    {{-- Messages Area --}}
    <div id="chat-messages"
        class="flex-1 overflow-y-auto p-4 md:p-6 space-y-4 bg-white/50 custom-scrollbar flex flex-col">
        <div class="flex items-center justify-center h-full text-slate-400 italic text-sm py-20" id="chat-loading">
            <div class="text-center">
                <i class="fas fa-circle-notch fa-spin mb-2 text-2xl"></i>
                <p>Connecting to community...</p>
            </div>
        </div>
    </div>

    {{-- Message Input --}}
    <div class="px-4 pt-3 md:px-6 md:pt-4 bg-slate-50/50 border-t border-slate-100 shrink-0"
        style="{{ $isFullScreen ? 'padding-bottom: max(0.75rem, env(safe-area-inset-bottom, 0.75rem));' : 'padding-bottom: 0.75rem;' }}">

        @if($isSuspended)
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-amber-500"></i>
                <p class="text-sm text-amber-700 font-medium">Your chat privileges have been suspended by a moderator. You can still read messages.</p>
            </div>
        @else
            <form id="chat-form" class="relative group" autocomplete="off">
                <textarea id="message-input" rows="1"
                    class="w-full pl-5 pr-14 py-3.5 bg-white border border-slate-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] transition-all resize-none shadow-sm text-sm"
                    placeholder="Type a message…" maxlength="1000"></textarea>
                <button type="submit"
                    class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-[#0B4D73] text-white rounded-xl flex items-center justify-center hover:bg-[#093e5d] transition-all shadow-md active:scale-95 disabled:opacity-40"
                    id="send-button" aria-label="Send">
                    <i class="fas fa-paper-plane text-xs"></i>
                </button>
            </form>
            <p class="text-[9px] text-slate-400 text-right mt-1 pr-1"><span id="char-count">0</span>/1000</p>
        @endif
    </div>
</div>

{{-- Members Modal --}}
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
            @if($program->mentor)
                <div class="flex items-center justify-between p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-[#0B4D73] text-white flex items-center justify-center font-bold ring-4 ring-white shadow-sm">
                            {{ substr($program->mentor->first_name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 leading-tight">{{ $program->mentor->full_name }}</p>
                            <span class="text-[10px] font-bold text-[#0B4D73] uppercase tracking-tighter">Lead Mentor (Moderator)</span>
                        </div>
                    </div>
                </div>
            @endif
            <div class="h-px bg-slate-100 mx-2"></div>
            @foreach($program->children as $child)
                @php
                    $cEnroll    = $program->enrollments->where('user_id', $child->id)->first();
                    $cSuspended = $cEnroll && $cEnroll->chat_status === 'suspended';
                @endphp
                <div class="flex items-center justify-between p-4 rounded-2xl bg-white border border-slate-100 hover:border-blue-200 transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center font-bold relative overflow-hidden shrink-0">
                            @if($child->profile_picture)
                                <img src="{{ asset('storage/' . $child->profile_picture) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($child->first_name, 0, 1) }}
                            @endif
                            @if($cSuspended)
                                <span class="absolute -top-1 -right-1 w-4 h-4 bg-amber-500 rounded-full border-2 border-white flex items-center justify-center shadow-sm z-10" title="Suspended">
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
                    <div class="flex items-center gap-2">
                        @if(auth()->id() !== $child->id)
                            <button onclick="openReportModal('App\\\\Models\\\\User', {{ $child->id }}, 'Report User', 'Flag this student for administrator review.')"
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
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    #chat-messages > div:last-child { padding-bottom: 0.5rem; }
    .msg-bubble { animation: msgIn 0.2s cubic-bezier(0.16,1,0.3,1) both; }
    @keyframes msgIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
</style>

<script>
(function () {
    // ─── Config (Blade-resolved, never hardcoded) ───────────────────────────
    const programId   = {{ $program->id }};
    const myUserId    = {{ auth()->id() }};
    const csrfToken   = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    const fetchUrl    = @json($fetchUrl);
    const sendUrl     = @json($sendUrl);
    const reactBase   = @json($reactBase);   // /child/programs/3/chat/messages
    const deleteBase  = @json($deleteBase);  // /child/chat/messages

    // ─── State ─────────────────────────────────────────────────────────────
    let lastId      = 0;
    let initialDone = false;
    let pollTimer   = null;
    const chatArea  = document.getElementById('chat-messages');

    // Register current chat ID globally to suppress duplicate push notifications
    window.currentChatProgramId = programId;

    // ─── Fetch & Render ─────────────────────────────────────────────────────
    async function fetchMessages() {
        try {
            const res  = await fetch(`${fetchUrl}?last_id=${lastId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) return;
            const msgs = await res.json();

            // Remove loading spinner on first successful fetch
            if (!initialDone) {
                const loader = document.getElementById('chat-loading');
                if (loader) loader.remove();
                initialDone = true;
            }

            let hasNew = false;
            msgs.forEach(msg => {
                if (document.getElementById(`msg-${msg.id}`)) {
                    // Update reaction bar only
                    const bar = document.querySelector(`#msg-${msg.id} .reaction-bar`);
                    if (bar) bar.innerHTML = renderReactions(msg);
                    return;
                }
                appendMessage(msg);
                lastId  = Math.max(lastId, msg.id);
                hasNew  = true;
            });

            if (hasNew) {
                chatArea.scrollTo({ top: chatArea.scrollHeight, behavior: 'smooth' });
            } else if (!initialDone || msgs.length === 0) {
                chatArea.scrollTop = chatArea.scrollHeight;
            }
        } catch (e) {
            // Network error — silently retry next poll
        }
    }

    function appendMessage(msg) {
        const isMe  = msg.user_id === myUserId;
        const el    = document.createElement('div');
        el.id       = `msg-${msg.id}`;
        el.className = `flex flex-col ${isMe ? 'items-end' : 'items-start'} msg-bubble mb-3`;
        el.innerHTML = `
            <div class="flex items-center gap-2 mb-1 px-1">
                <span class="text-[10px] font-bold ${msg.is_moderator ? 'text-[#0B4D73]' : 'text-slate-500'} uppercase tracking-tight">
                    ${escHtml(msg.user_name)} ${msg.is_moderator ? '<i class="fas fa-shield-alt ml-0.5 text-[8px]"></i>' : ''}
                </span>
                <span class="text-[9px] text-slate-400 font-medium">${msg.created_at}</span>
            </div>
            <div class="relative group max-w-[85%]">
                <div class="p-3.5 rounded-2xl text-[13px] leading-relaxed shadow-sm
                    ${isMe ? 'bg-[#0B4D73] text-white rounded-tr-none' : 'bg-slate-100 text-slate-700 rounded-tl-none border border-slate-200/50'}">
                    ${escHtml(msg.content)}
                </div>
                <div class="reaction-bar flex items-center gap-1 mt-1 flex-wrap ${isMe ? 'justify-end' : 'justify-start'}">
                    ${renderReactions(msg)}
                    ${!isMe ? `
                        <button onclick="openReportModal('App\\\\\\\\Models\\\\\\\\Chat\\\\\\\\Message', ${msg.id}, 'Report Message', 'Flag this message.')"
                            class="p-1 text-slate-300 hover:text-rose-400 transition-colors" title="Report">
                            <i class="fas fa-flag text-[9px]"></i>
                        </button>` : ''}
                </div>
                ${msg.can_delete ? `
                    <button onclick="deleteMessage(${msg.id})"
                        class="absolute ${isMe ? '-left-8' : '-right-8'} top-2 p-2 text-slate-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-all">
                        <i class="fas fa-trash-alt text-[10px]"></i>
                    </button>` : ''}
            </div>`;
        chatArea.appendChild(el);
    }

    function renderReactions(msg) {
        const types = [
            { id: 'like',    icon: 'fa-thumbs-up',   color: 'text-blue-500',  bg: 'bg-blue-50 border-blue-200'  },
            { id: 'love',    icon: 'fa-heart',        color: 'text-rose-500',  bg: 'bg-rose-50 border-rose-200'  },
            { id: 'dislike', icon: 'fa-thumbs-down',  color: 'text-slate-500', bg: 'bg-slate-100 border-slate-200' },
        ];
        return types.map(t => {
            const active = msg.my_reaction === t.id;
            const count  = msg.reactions[t.id] || '';
            return `<button onclick="toggleReaction(${msg.id},'${t.id}')"
                class="flex items-center gap-1 px-2 py-1 rounded-full border transition-all hover:scale-110 active:scale-95
                    ${active ? t.bg + ' shadow-sm' : 'bg-white/50 border-slate-100'}">
                <i class="fas ${t.icon} text-[9px] ${active ? t.color : 'text-slate-300'}"></i>
                <span class="text-[9px] font-black ${active ? 'text-slate-700' : 'text-slate-400'}">${count}</span>
            </button>`;
        }).join('');
    }

    function escHtml(s) {
        return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // ─── Send Message ───────────────────────────────────────────────────────
    const chatForm = document.getElementById('chat-form');
    if (chatForm) {
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const input  = document.getElementById('message-input');
            const btn    = document.getElementById('send-button');
            const content = input.value.trim();
            if (!content) return;

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs"></i>';
            input.value  = '';
            input.style.height = 'auto';
            document.getElementById('char-count').textContent = '0';

            try {
                const res = await fetch(sendUrl, {
                    method:  'POST',
                    headers: {
                        'Content-Type':  'application/json',
                        'X-CSRF-TOKEN':  csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ content }),
                });

                if (res.ok) {
                    await fetchMessages(); // Pull new message immediately
                    triggerAbsentUserPush(content); // SW push for absent members
                } else {
                    const data = await res.json().catch(() => ({}));
                    input.value = content; // Restore so user doesn't lose text
                    showChatError(data.error || data.message || 'Could not send. Please try again.');
                }
            } catch (err) {
                input.value = content;
                showChatError('Connection issue. Check your network.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane text-xs"></i>';
                input.focus();
            }
        });

        // Auto-resize + char count
        const input = document.getElementById('message-input');
        input.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            document.getElementById('char-count').textContent = this.value.length;
        });

        // Send on Enter (Shift+Enter = new line)
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit', { cancelable: true }));
            }
        });
    }

    // ─── Toggle Reaction ────────────────────────────────────────────────────
    window.toggleReaction = async function (messageId, type) {
        try {
            const res = await fetch(`${reactBase}/${messageId}/react`, {
                method:  'POST',
                headers: {
                    'Content-Type':  'application/json',
                    'X-CSRF-TOKEN':  csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ type }),
            });
            if (res.ok) fetchMessages();
        } catch (e) { /* silent */ }
    };

    // ─── Delete Message ─────────────────────────────────────────────────────
    window.deleteMessage = async function (messageId) {
        if (!confirm('Permanently delete this message?')) return;
        try {
            const res = await fetch(`${deleteBase}/${messageId}`, {
                method:  'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken },
            });
            if (res.ok) {
                const el = document.getElementById(`msg-${messageId}`);
                if (el) { el.style.opacity = '0'; setTimeout(() => el.remove(), 250); }
            }
        } catch (e) { /* silent */ }
    };

    // ─── Toggle Suspension ──────────────────────────────────────────────────
    window.toggleSuspension = async function (userId, action) {
        if (!confirm(action === 'suspended' ? 'Restrict this user\'s chat access?' : 'Restore chat access?')) return;
        try {
            const prefix = @json($rolePrefix);
            const res = await fetch(`/${prefix}/programs/${programId}/chat/members/${userId}/toggle-suspension`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
            });
            if (res.ok) location.reload();
        } catch (e) { /* silent */ }
    };

    // ─── Error Toast ────────────────────────────────────────────────────────
    function showChatError(msg) {
        if (typeof showToast === 'function') {
            showToast(msg, 'error');
        } else {
            alert(msg);
        }
    }

    // ─── SW Push for Absent Members ─────────────────────────────────────────
    // When a message is sent, show a local push notification to this user if
    // they navigate away from the chat (Page Visibility + SW registration).
    // For OTHER members, the DB notification system picks it up and the
    // notification bell poll triggers the SW push in the navbar's poll cycle.
    function triggerAbsentUserPush(content) {
        if (!('serviceWorker' in navigator) || !('Notification' in window)) return;
        if (Notification.permission !== 'granted') return;

        // Store latest message for the SW to use when tab is hidden
        localStorage.setItem('chat_latest_msg_' + programId, JSON.stringify({
            sender: @json($user->first_name),
            content: content.substring(0, 80),
            program: @json($program->name),
            url: window.location.href,
            ts: Date.now(),
        }));
    }

    // ─── Polling with Page Visibility API ──────────────────────────────────
    // Poll every 3s when tab is active, pause when hidden (saves bandwidth)
    function startPolling() {
        if (pollTimer) return;
        pollTimer = setInterval(fetchMessages, 3000);
    }
    function stopPolling() {
        if (pollTimer) { clearInterval(pollTimer); pollTimer = null; }
    }

    document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'visible') {
            fetchMessages();   // Immediate catch-up
            startPolling();
        } else {
            stopPolling();
        }
    });

    // Initial load + start polling
    fetchMessages();
    startPolling();

    // Expose fetchMessages for re-init (AJAX navigation)
    window.fetchMessages = fetchMessages;
})();
</script>