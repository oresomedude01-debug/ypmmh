@extends('layouts.dashboard')

@section('title', 'Manage Mentees')

@section('content')
  <div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
          Mentees
        </h1>
        <p class="font-medium" style="color: var(--text-secondary);">Manage all mentees in the system</p>
      </div>
      <div>
        <!-- Add Student Button -->
        <a href="{{ route('admin.users.create') }}"
          class="inline-flex items-center gap-2 px-6 py-3 bg-[#0B4D73] text-white rounded-xl text-sm font-bold shadow-lg shadow-[#0B4D73]/20 hover:scale-105 active:scale-95 transition-all">
          <i class="fas fa-plus"></i>
          <span>Add Student</span>
        </a>
      </div>
    </div>

    <!-- Back Link & Global Breadcrumb -->
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
      <form action="{{ route('admin.children.index') }}" method="GET"
        class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
          <label class="block text-xs font-black uppercase tracking-widest mb-2"
            style="color: var(--text-secondary);">Search Student</label>
          <div class="relative">
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
              class="w-full pl-10 pr-4 py-2.5 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all font-medium"
              style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
              placeholder="Name, email or ID...">
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
          <a href="{{ route('admin.children.index') }}"
            class="px-4 py-2.5 border rounded-xl flex items-center justify-center transition-all"
            style="border-color: var(--border-color); color: var(--text-secondary);" title="Reset Filters">
            <i class="fas fa-undo"></i>
          </a>
        </div>
      </form>
    </div>

    <!-- Children Content -->
    @if($children->count() > 0)
      <!-- Desktop Table View -->
      <div class="hidden lg:block admin-card overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr>
                <th class="admin-table-header rounded-tl-3xl">Student Profile</th>
                <th class="admin-table-header">Age / Gender</th>
                <th class="admin-table-header">Student ID</th>
                <th class="admin-table-header">Parent/Guardian</th>
                <th class="admin-table-header">Verification</th>
                <th class="admin-table-header text-right rounded-tr-3xl">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y" style="border-color: var(--border-color);">
              @foreach($children as $child)
                <tr class="group hover:bg-opacity-5 transition-colors" style="background-color: transparent;">
                  <td class="px-6 py-4" style="background-color: transparent;">
                    <div class="flex items-center gap-3">
                      @if($child->profile_picture)
                        <img src="{{ asset('storage/' . $child->profile_picture) }}" alt=""
                          class="w-10 h-10 rounded-full object-cover border-2 shadow-sm"
                          style="border-color: var(--bg-secondary);">
                      @else
                        <div
                          class="w-10 h-10 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center font-black border-2 shadow-sm text-xs"
                          style="border-color: var(--bg-secondary);">
                          {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                        </div>
                      @endif
                      <div>
                        <p class="font-bold group-hover:text-[#0B4D73] transition-colors" style="color: var(--text-primary);">
                          {{ $child->first_name }} {{ $child->last_name }}
                        </p>
                        <p class="text-[10px]" style="color: var(--text-secondary);">{{ $child->email }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4" style="background-color: transparent;">
                    <div class="flex flex-col">
                      <span class="text-xs font-bold capitalize"
                        style="color: var(--text-primary);">{{ $child->gender ?? 'N/A' }}</span>
                      <span class="text-[10px]"
                        style="color: var(--text-secondary);">{{ $child->age ? $child->age . ' yrs' : 'N/A' }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-4" style="background-color: transparent;">
                    <span class="px-2 py-1 rounded-lg text-xs font-mono font-black"
                      style="background-color: var(--bg-primary); color: var(--text-primary); border: 1px solid var(--border-color);">
                      {{ $child->unique_number ?? 'N/A' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm" style="background-color: transparent;">
                    @if($child->parent)
                      <div>
                        <p class="font-bold" style="color: var(--text-primary);">{{ $child->parent->first_name }}
                          {{ $child->parent->last_name }}
                        </p>
                        <p class="text-[10px]" style="color: var(--text-secondary);">{{ $child->parent->email }}</p>
                      </div>
                    @else
                      <span class="italic text-xs" style="color: var(--text-secondary);">Not assigned</span>
                    @endif
                  </td>
                  <td class="px-6 py-4" style="background-color: transparent;">
                    @if($child->email_verified_at)
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
                  <td class="px-6 py-4 text-right" style="background-color: transparent;">
                    <div class="flex items-center justify-end gap-2 text-sm">
                      <a href="{{ route('admin.children.show', $child->id) }}"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#0B4D73] text-white rounded-lg text-xs font-bold hover:brightness-110 transition-all shadow-sm">
                        <i class="fas fa-eye text-[10px]"></i>
                        <span>Manage</span>
                      </a>
                      <a href="{{ route('admin.users.edit', $child->id) }}"
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
        @if($children->hasPages())
          <div class="px-6 py-4 border-t bg-opacity-5"
            style="border-color: var(--border-color); background-color: var(--text-primary);">
            {{ $children->withQueryString()->links() }}
          </div>
        @endif
      </div>

      <!-- Mobile/Tablet List View -->
      <div class="lg:hidden space-y-3">
        @foreach($children as $child)
          <div class="glass rounded-xl p-3 border flex items-center justify-between gap-3 group"
            style="border-color: var(--border-color);">
            <div class="flex items-center gap-3 overflow-hidden">
              <!-- Avatar -->
              <div class="relative shrink-0">
                @if($child->profile_picture)
                  <img src="{{ asset('storage/' . $child->profile_picture) }}" alt=""
                    class="w-10 h-10 rounded-full object-cover border shadow-sm" style="border-color: var(--bg-secondary);">
                @else
                  <div
                    class="w-10 h-10 rounded-full bg-amber-500/10 text-amber-500 flex items-center justify-center font-black border shadow-sm text-xs"
                    style="border-color: var(--bg-secondary);">
                    {{ substr($child->first_name, 0, 1) }}{{ substr($child->last_name, 0, 1) }}
                  </div>
                @endif
                <!-- Verification Status Dot -->
                <div
                  class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white flex items-center justify-center {{ $child->email_verified_at ? 'bg-emerald-500' : 'bg-amber-500' }}">
                  @if($child->email_verified_at)
                    <i class="fas fa-check text-[6px] text-white"></i>
                  @else
                    <i class="fas fa-clock text-[6px] text-white"></i>
                  @endif
                </div>
              </div>

              <!-- Info -->
              <div class="min-w-0">
                <div class="flex items-center gap-2">
                  <h4 class="font-bold text-sm truncate" style="color: var(--text-primary);">
                    {{ $child->first_name }} {{ $child->last_name }}
                  </h4>
                </div>
                <p class="text-[10px] truncate font-mono opacity-70" style="color: var(--text-secondary);">
                  {{ $child->unique_number ?? 'NO-ID' }} &bull; {{ $child->parent ? $child->parent->first_name . ' ' . $child->parent->last_name : 'No guardian' }}
                </p>
                <p class="text-[9px] font-bold mt-0.5" style="color: var(--text-secondary);">
                  Joined: {{ $child->created_at->format('M d, Y') }}
                </p>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 shrink-0">
              <a href="{{ route('admin.children.show', $child->id) }}"
                class="w-8 h-8 flex items-center justify-center rounded-lg bg-[#0B4D73]/10 text-[#0B4D73] active:scale-95 transition-transform" title="Manage">
                <i class="fas fa-eye text-xs"></i>
              </a>
              <a href="{{ route('admin.users.edit', $child->id) }}"
                class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-500 active:scale-95 transition-transform" title="Edit">
                <i class="fas fa-edit text-xs"></i>
              </a>
            </div>
          </div>
        @endforeach
      </div>

      @if($children->hasPages())
        <div class="pt-4 pb-8">
          {{ $children->withQueryString()->links() }}
        </div>
      @endif
    @else
      <!-- Empty State -->
      <div class="glass rounded-3xl p-16 text-center border" style="border-color: var(--border-color);">
        <div class="w-20 h-20 bg-opacity-5 rounded-full flex items-center justify-center mx-auto mb-6"
          style="background-color: var(--text-primary);">
          <i class="fas fa-child text-4xl opacity-20" style="color: var(--text-primary);"></i>
        </div>
        <h3 class="text-xl font-black mb-2" style="color: var(--text-primary);">No Students Found</h3>
        <p class="max-w-sm mx-auto mb-8 font-medium" style="color: var(--text-secondary);">
          {{ isset($filters) && array_filter($filters) ? 'Try adjusting your filters to find the student you are looking for.' : 'There are no students registered in the system yet.' }}
        </p>
        <div class="flex items-center justify-center gap-4">
          @if(isset($filters) && array_filter($filters))
            <a href="{{ route('admin.children.index') }}" class="px-6 py-2.5 border rounded-xl font-bold transition-all"
              style="border-color: var(--border-color); color: var(--text-secondary);">
              Clear Filters
            </a>
          @endif
          <a href="{{ route('admin.users.create') }}"
            class="px-6 py-2.5 bg-[#0B4D73] text-white rounded-xl font-bold shadow-lg shadow-[#0B4D73]/20 hover:scale-105 active:scale-95 transition-all">
            Add Student
          </a>
        </div>
      </div>
    @endif
  </div>
@endsection