@extends('layouts.dashboard')

@section('title', 'Manage Mentors')

@section('content')
  <div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
          Mentors
        </h1>
        <p class="font-medium" style="color: var(--text-secondary);">Manage all mentors in the system</p>
      </div>
      <div>
        <!-- Add Mentor Button -->
        <a href="{{ route('admin.users.create') }}"
          class="inline-flex items-center gap-2 px-6 py-3 bg-[#0B4D73] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#0B4D73]/20 hover:scale-105 active:scale-95 transition-all">
          <i class="fas fa-plus"></i>
          <span>Add Mentor</span>
        </a>
      </div>
    </div>

    <!-- Back Link -->
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.users.index') }}"
        class="hover:underline font-black text-xs uppercase tracking-widest flex items-center gap-2"
        style="color: var(--primary-500);">
        <i class="fas fa-arrow-left text-[10px]"></i>
        <span>Back to Users</span>
      </a>
    </div>

    <!-- Filters Section -->
    <div class="glass rounded-2xl p-6 shadow-sm border" style="border-color: var(--border-color);">
      <form action="{{ route('admin.mentors.index') }}" method="GET"
        class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
          <label class="block text-xs font-black uppercase tracking-widest mb-2"
            style="color: var(--text-secondary);">Search Mentor</label>
          <div class="relative">
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
              class="w-full pl-10 pr-4 py-2.5 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all font-medium"
              style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
              placeholder="Search by name or email...">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2" style="color: var(--text-secondary);"></i>
          </div>
        </div>

        <div>
          <label class="block text-xs font-black uppercase tracking-widest mb-2"
            style="color: var(--text-secondary);">Status</label>
          <select name="status"
            class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all cursor-pointer font-medium"
            style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
            <option value="">All Statuses</option>
            <option value="verified" {{ (isset($filters['status']) && $filters['status'] == 'verified') ? 'selected' : '' }}>Verified</option>
            <option value="pending" {{ (isset($filters['status']) && $filters['status'] == 'pending') ? 'selected' : '' }}>
              Pending</option>
          </select>
        </div>

        <div class="flex gap-2">
          <button type="submit"
            class="flex-1 bg-[#0B4D73] text-white rounded-xl py-2.5 font-bold hover:brightness-110 transition-all shadow-sm">
            Apply Filter
          </button>
          <a href="{{ route('admin.mentors.index') }}"
            class="px-4 py-2.5 border rounded-xl flex items-center justify-center transition-all"
            style="border-color: var(--border-color); color: var(--text-secondary);" title="Reset Filters">
            <i class="fas fa-undo"></i>
          </a>
        </div>
      </form>
    </div>

    <!-- Mentors Content -->
    @if($mentors->count() > 0)
      <!-- Desktop Table View -->
      <div class="hidden lg:block admin-card overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr>
                <th class="admin-table-header rounded-tl-3xl">Mentor Profile</th>
                <th class="admin-table-header">Email Address</th>
                <th class="admin-table-header">Status</th>
                <th class="admin-table-header">Programs</th>
                <th class="admin-table-header text-right rounded-tr-3xl">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y" style="border-color: var(--border-color);">
              @foreach($mentors as $mentor)
                <tr class="group hover:bg-opacity-5 transition-colors" style="background-color: transparent;">
                  <td class="px-6 py-4" style="background-color: transparent;">
                    <div class="flex items-center gap-3">
                      @if($mentor->profile_picture)
                        <img src="{{ asset('storage/' . $mentor->profile_picture) }}" alt=""
                          class="w-10 h-10 rounded-full object-cover border-2 shadow-sm"
                          style="border-color: var(--bg-secondary);">
                      @else
                        <div
                          class="w-10 h-10 rounded-full bg-blue-500/10 text-blue-500 flex items-center justify-center font-black border-2 shadow-sm text-xs"
                          style="border-color: var(--bg-secondary);">
                          {{ substr($mentor->first_name, 0, 1) }}{{ substr($mentor->last_name, 0, 1) }}
                        </div>
                      @endif
                      <div>
                        <p class="font-bold group-hover:text-[#0B4D73] transition-colors" style="color: var(--text-primary);">
                          {{ $mentor->first_name }} {{ $mentor->last_name }}</p>
                        <p class="text-[10px]" style="color: var(--text-secondary);">Last seen
                          {{ $mentor->created_at->diffForHumans() }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-sm" style="color: var(--text-primary); background-color: transparent;">
                    {{ $mentor->email }}
                  </td>
                  <td class="px-6 py-4" style="background-color: transparent;">
                    @if($mentor->email_verified_at)
                      <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                        <i class="fas fa-check-circle"></i>
                        Verified
                      </span>
                    @else
                      <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-500/10 text-amber-500 border border-amber-500/20">
                        <i class="fas fa-clock"></i>
                        Pending
                      </span>
                    @endif
                  </td>
                  <td class="px-6 py-4" style="background-color: transparent;">
                    <div class="flex items-center gap-2">
                      <span class="text-sm font-black"
                        style="color: var(--text-primary);">{{ $mentor->programs_count }}</span>
                      <span class="text-[10px] font-bold uppercase" style="color: var(--text-secondary);">Programs</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-right" style="background-color: transparent;">
                    <div class="flex items-center justify-end gap-2 text-sm">
                      <a href="{{ route('admin.mentors.show', $mentor->id) }}"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#0B4D73] text-white rounded-lg text-xs font-bold hover:brightness-110 transition-all shadow-sm">
                        <i class="fas fa-eye text-[10px]"></i>
                        <span>Manage</span>
                      </a>
                      <a href="{{ route('admin.users.edit', $mentor->id) }}"
                        class="inline-flex items-center gap-1 px-3 py-1.5 border rounded-lg text-xs font-bold hover:bg-opacity-5 transition-all shadow-sm"
                        style="border-color: var(--border-color); color: var(--text-primary); background-color: var(--bg-secondary);">
                        <i class="fas fa-edit text-[10px]"></i>
                        <span>Edit</span>
                      </a>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @if($mentors->hasPages())
          <div class="px-6 py-4 border-t bg-opacity-5"
            style="border-color: var(--border-color); background-color: var(--text-primary);">
            {{ $mentors->withQueryString()->links() }}
          </div>
        @endif
      </div>

      <!-- Mobile/Tablet Grid View -->
      <div class="lg:hidden grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($mentors as $mentor)
          <div class="glass rounded-2xl p-5 shadow-sm border flex flex-col group" style="border-color: var(--border-color);">
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-3">
                @if($mentor->profile_picture)
                  <img src="{{ asset('storage/' . $mentor->profile_picture) }}" alt=""
                    class="w-12 h-12 rounded-full object-cover border-2 shadow-sm" style="border-color: var(--bg-secondary);">
                @else
                  <div
                    class="w-12 h-12 rounded-full bg-blue-500/10 text-blue-500 flex items-center justify-center font-black border-2 shadow-sm"
                    style="border-color: var(--bg-secondary);">
                    {{ substr($mentor->first_name, 0, 1) }}{{ substr($mentor->last_name, 0, 1) }}
                  </div>
                @endif
                <div>
                  <h4 class="font-black group-hover:text-[#0B4D73] transition-colors leading-tight"
                    style="color: var(--text-primary);">{{ $mentor->first_name }}
                    {{ $mentor->last_name }}
                  </h4>
                  <p class="text-[10px] uppercase font-bold tracking-tighter" style="color: var(--text-secondary);">Last seen
                    {{ $mentor->created_at->diffForHumans() }}</p>
                </div>
              </div>
            </div>

            <div class="space-y-4 mb-6">
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-tighter" style="color: var(--text-secondary);">Email</span>
                <span class="text-xs font-black truncate max-w-[150px]"
                  style="color: var(--text-primary);">{{ $mentor->email }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-tighter"
                  style="color: var(--text-secondary);">Programs</span>
                <span class="text-xs font-black" style="color: var(--text-primary);">{{ $mentor->programs_count }}
                  Active</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-tighter" style="color: var(--text-secondary);">Status</span>
                @if($mentor->email_verified_at)
                  <span
                    class="text-[10px] font-black uppercase text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded-full border border-emerald-500/20">Verified</span>
                @else
                  <span
                    class="text-[10px] font-black uppercase text-amber-500 bg-amber-500/10 px-2 py-0.5 rounded-full border border-amber-500/20">Pending</span>
                @endif
              </div>
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-tighter" style="color: var(--text-secondary);">Joined</span>
                <span class="text-[10px] font-black" style="color: var(--text-primary);">{{ $mentor->created_at->format('M d, Y') }}</span>
              </div>
            </div>

            <div class="mt-auto flex items-center gap-2 pt-4 border-t" style="border-color: var(--border-color);">
              <a href="{{ route('admin.mentors.show', $mentor->id) }}"
                class="flex-1 py-3 bg-[#0B4D73] text-white text-center rounded-xl text-xs font-black hover:brightness-110 transition-all shadow-md shadow-[#0B4D73]/20">
                MANAGE MENTOR
              </a>
            </div>
          </div>
        @endforeach
      </div>

      @if($mentors->hasPages())
        <div class="pt-4 pb-8">
          {{ $mentors->withQueryString()->links() }}
        </div>
      @endif
    @else
      <div class="glass rounded-3xl p-16 text-center border" style="border-color: var(--border-color);">
        <div class="w-20 h-20 bg-opacity-5 rounded-full flex items-center justify-center mx-auto mb-6"
          style="background-color: var(--text-primary);">
          <i class="fas fa-user-tie text-4xl opacity-20" style="color: var(--text-primary);"></i>
        </div>
        <h3 class="text-xl font-black mb-2" style="color: var(--text-primary);">No Mentors Found</h3>
        <p class="max-w-sm mx-auto mb-8 font-medium" style="color: var(--text-secondary);">
          Try adjusting your filters to find the mentor you are looking for.
        </p>
        <div class="flex items-center justify-center gap-4">
          <a href="{{ route('admin.mentors.index') }}" class="px-6 py-2.5 border rounded-xl font-bold transition-all"
            style="border-color: var(--border-color); color: var(--text-secondary);">
            Clear Filters
          </a>
          <a href="{{ route('admin.users.create') }}"
            class="px-6 py-2.5 bg-[#0B4D73] text-white rounded-xl font-bold shadow-lg shadow-[#0B4D73]/20 hover:scale-105 active:scale-95 transition-all">
            Add Mentor
          </a>
        </div>
      </div>
    @endif
  </div>
@endsection