@extends('layouts.dashboard')

@section('title', 'Manage Payments')

@section('content')
    <div class="space-y-8 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
                    Payment History
                </h1>
                <p class="font-medium text-slate-500">Track all program subscriptions and transactions.</p>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                Transaction ID</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Payer</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                Beneficiary</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Program
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Amount
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Status
                            </th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payments as $payment)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-mono text-xs font-bold text-slate-500">{{ $payment->transaction_id }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-100 text-[#0B4D73] flex items-center justify-center font-bold text-xs">
                                            {{ substr($payment->user->first_name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900">
                                                {{ $payment->user->full_name ?? 'Unknown' }}</p>
                                            <p class="text-[10px] font-medium text-slate-400">{{ $payment->user->email ?? '' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($payment->child)
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold text-slate-700">{{ $payment->child->full_name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Not Assigned</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-[#0B4D73]">
                                    @if($payment->program)
                                        {{ $payment->program->name }}
                                    @elseif($payment->description)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-lg bg-amber-50 text-amber-700 border border-amber-200">
                                            <i class="fas fa-crown text-amber-600"></i>
                                            {{ $payment->description }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 italic">Deleted Program</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono text-sm font-black text-slate-700">
                                    {{ $payment->currency }} {{ number_format($payment->amount) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($payment->status === 'success')
                                        <span
                                            class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-100">Success</span>
                                    @elseif($payment->status === 'pending')
                                        <span
                                            class="px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-amber-100">Pending</span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-red-100">{{ $payment->status }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-400">
                                    {{ $payment->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                    <i class="fas fa-receipt text-4xl mb-3 opacity-20"></i>
                                    <p class="text-sm font-bold uppercase tracking-widest">No payment records found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-slate-100">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
@endsection