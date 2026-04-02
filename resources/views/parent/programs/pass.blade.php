<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Entry Pass - {{ $program->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Outfit:wght@500;700;900&display=swap"
        rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }

            body {
                background: white;
                padding: 0;
            }

            .pass-card {
                box-shadow: none;
                border: 2px solid #e2e8f0;
            }
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
        }

        .font-outfit {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>

<body class="p-4 md:p-12 flex flex-col items-center">

    <div class="no-print mb-8 flex gap-4">
        <button onclick="window.print()"
            class="px-6 py-3 bg-[#0B4D73] text-white rounded-xl font-bold hover:bg-[#093e5d] transition-all flex items-center gap-2 shadow-lg">
            <i class="fas fa-print"></i> Print Your Pass
        </button>
        <a href="{{ route('parent.dashboard') }}"
            class="px-6 py-3 bg-white border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition-all">
            Back to Dashboard
        </a>
    </div>

    <!-- Pass Card -->
    <div
        class="pass-card w-full max-w-2xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border-4 border-[#0B4D73]/10 relative">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-[#0B4D73]/5 rounded-full -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-500/5 rounded-full -ml-10 -mb-10"></div>

        <!-- Header -->
        <div class="bg-[#0B4D73] p-8 md:p-12 text-center relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10">
                <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
                    <path d="M0 0 L100 0 L100 100 L0 100 Z" fill="none" stroke="white" stroke-width="0.5"
                        stroke-dasharray="2,2" />
                </svg>
            </div>
            <h1 class="font-outfit text-white text-xs font-black uppercase tracking-[0.3em] mb-4">Official Program Entry
                Pass</h1>
            <h2 class="font-outfit text-white text-3xl md:text-5xl font-black mb-2">{{ $program->name }}</h2>
            <div
                class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/10 rounded-full border border-white/20 backdrop-blur-sm">
                <span class="text-white text-[10px] font-bold uppercase tracking-widest">Offline Experience</span>
            </div>
        </div>

        <!-- Content -->
        <div class="p-8 md:p-12 grid grid-cols-1 md:grid-cols-2 gap-8 relative">
            <!-- Left: Child Info -->
            <div class="space-y-6">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#0B4D73] opacity-60 mb-1">Student
                        Name</p>
                    <p class="text-2xl font-black text-slate-800 font-outfit uppercase">{{ $child->full_name }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#0B4D73] opacity-60 mb-1">
                        Registration ID</p>
                    <p class="text-lg font-bold text-slate-700">
                        #YPMMH-{{ str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#0B4D73] opacity-60 mb-1">Assigned
                        Group/Age</p>
                    <p class="text-lg font-bold text-slate-700">{{ $child->age }} Years Old</p>
                </div>
            </div>

            <!-- Right: Program Info -->
            <div class="space-y-6">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#0B4D73] opacity-60 mb-1">Start
                        Date</p>
                    <p class="text-xl font-bold text-slate-800">
                        {{ $program->start_date ? \Carbon\Carbon::parse($program->start_date)->format('F d, Y') : 'TBC / Ongoing' }}
                    </p>
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-[#0B4D73] opacity-60 mb-1">Venue</p>
                    <p class="text-lg font-bold text-slate-700 italic">Please check your email for physical location
                        details.</p>
                </div>

                <!-- QR Placeholder -->
                <div class="pt-4 flex justify-end">
                    <div class="w-24 h-24 border-2 border-slate-100 rounded-2xl flex items-center justify-center p-2">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ route('admin.programs.show', $program->id) }}"
                            class="w-full h-full opacity-30 grayscale">
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer / Security -->
        <div class="bg-slate-50 p-6 border-t font-mono text-[9px] text-slate-400 flex justify-between items-center">
            <p>ISSUED BY YOUTH PERSONAL MENTORING HUB</p>
            <p>VERIFIED ON {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>

        <!-- Perforation Effect -->
        <div class="absolute top-[35%] -left-3 w-6 h-6 bg-slate-100 rounded-full border-2 border-slate-200"></div>
        <div class="absolute top-[35%] -right-3 w-6 h-6 bg-slate-100 rounded-full border-2 border-slate-200"></div>
    </div>

    <p class="mt-8 text-slate-400 text-xs text-center max-w-sm">
        Please present this pass at the program entry. This pass is unique to the student named above and verified by
        our system.
    </p>

    <!-- FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>

</html>