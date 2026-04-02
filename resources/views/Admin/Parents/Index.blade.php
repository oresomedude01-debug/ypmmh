@extends('layouts.dashboard')

@section('title', 'Manage Parents')

@section('content')
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Parents / Guardians</h1>
        <p class="text-slate-600 text-sm">Manage all parents and guardians in the system</p>
      </div>
      <div>
        <!-- Add Parent Button -->
        <a href="{{ route('admin.users.create') }}"
          class="inline-flex items-center gap-2 px-6 py-3 bg-[#0B4D73] text-white rounded-xl text-sm font-semibold hover:bg-[#093e5d] transition-all shadow-md shadow-blue-900/10 hover:shadow-blue-900/20">
          <i class="fas fa-plus"></i>
          <span>Add Parent</span>
        </a>
      </div>
    </div>

    <!-- Back Link -->
    <div class="flex items-center gap-2">
      <a href="{{ route('admin.users.index') }}"
        class="text-[#0B4D73] hover:underline font-medium text-sm flex items-center gap-1">
        <i class="fas fa-arrow-left text-xs"></i>
        <span>Back to Users</span>
      </a>
    </div>

    <!-- Filters Section -->
    <div class="glass rounded-2xl p-6 shadow-sm border border-slate-100/50">
      <form action="{{ route('admin.parents.index') }}" method="GET"
        class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Search Parent</label>
          <div class="relative">
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
              class="w-full pl-10 pr-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:border-transparent transition-all"
              placeholder="Search by name or email...">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
          </div>
        </div>

        <div>
          <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
          <select name="status"
            class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:border-transparent transition-all cursor-pointer">
            <option value="">All Statuses</option>
            <option value="verified" {{ (isset($filters['status']) && $filters['status'] == 'verified') ? 'selected' : '' }}>Verified</option>
            <option value="pending" {{ (isset($filters['status']) && $filters['status'] == 'pending') ? 'selected' : '' }}>
              Pending</option>
          </select>
        </div>

        <div class="flex gap-2">
          <button type="submit"
            class="flex-1 bg-[#0B4D73] text-white rounded-xl py-2.5 font-semibold hover:bg-[#093e5d] transition-colors shadow-sm">
            Apply Filter
          </button>
          <a href="{{ route('admin.parents.index') }}"
            class="px-4 py-2.5 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors flex items-center justify-center"
            title="Reset Filters">
            <i class="fas fa-undo"></i>
          </a>
        </div>
      </form>
    </div>

    <!-- Parents Content -->
    @if($parents->count() > 0)
      <!-- Desktop Table View -->
      <div class="hidden lg:block admin-card overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr>
                <th class="admin-table-header rounded-tl-3xl">Parent Profile</th>
                <th class="admin-table-header">Email Address</th>
                <th class="admin-table-header">Status</th>
                <th class="admin-table-header">Linked Children</th>
                <th class="admin-table-header text-right rounded-tr-3xl">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              @foreach($parents as $parent)
                <tr class="hover:bg-slate-50/50 transition-colors group">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      @if($parent->profile_picture)
                        <img src="{{ asset('storage/' . $parent->profile_picture) }}" alt=""
                          class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                      @else
                        <div
                          class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold border-2 border-white shadow-sm text-xs">
                          {{ substr($parent->first_name, 0, 1) }}{{ substr($parent->last_name, 0, 1) }}
                        </div>
                      @endif
                      <div>
                        <p class="font-bold text-slate-900 group-hover:text-[#0B4D73] transition-colors">
                          {{ $parent->first_name }} {{ $parent->last_name }}</p>
                        <p class="text-[10px] text-slate-500 truncate max-w-[150px]">{{ $parent->phone_number ?? 'No phone' }}
                        </p>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-sm text-slate-600">
                    {{ $parent->email }}
                  </td>
                  <td class="px-6 py-4">
                    @if($parent->email_verified_at)
                      <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                        <i class="fas fa-check-circle"></i>
                        Verified
                      </span>
                    @else
                      <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-100">
                        <i class="fas fa-clock"></i>
                        Pending
                      </span>
                    @endif
                  </td>
                  <td class="px-6 py-4">
                    <span class="px-2.5 py-1 bg-slate-50 text-slate-600 rounded-lg text-xs font-bold border border-slate-100">
                      {{ $parent->children->count() }} {{ Str::plural('Child', $parent->children->count()) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.users.edit', $parent->id) }}"
                      class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-slate-600 text-xs font-bold hover:bg-slate-50 transition-colors shadow-sm">
                      <i class="fas fa-edit text-[10px]"></i>
                      <span>Edit</span>
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @if($parents->hasPages())
          <div class="px-6 py-4 border-t bg-slate-50 border-slate-200">
            {{ $parents->withQueryString()->links() }}
          </div>
        @endif
      </div>

      <!-- Mobile/Tablet Grid View -->
      <div class="lg:hidden grid grid-cols-1 sm:grid-cols-2 gap-4">
        @foreach($parents as $parent)
          <div class="glass rounded-2xl p-5 shadow-sm border border-slate-100/50 flex flex-col group">
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-3">
                @if($parent->profile_picture)
                  <img src="{{ asset('storage/' . $parent->profile_picture) }}" alt=""
                    class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                @else
                  <div
                    class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold border-2 border-white shadow-sm">
                    {{ substr($parent->first_name, 0, 1) }}{{ substr($parent->last_name, 0, 1) }}
                  </div>
                @endif
                <div>
                  <h4 class="font-bold text-slate-900 group-hover:text-[#0B4D73] transition-colors">{{ $parent->first_name }}
                    {{ $parent->last_name }}</h4>
                  <p class="text-[10px] text-slate-400">Parent / Guardian</p>
                </div>
              </div>
            </div>

            <div class="space-y-4 mb-6">
              <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-medium">Email</span>
                <span class="text-xs text-slate-800 font-semibold truncate max-w-[150px]">{{ $parent->email }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-medium">Phone</span>
                <span class="text-xs text-slate-800 font-semibold">{{ $parent->phone_number ?? 'N/A' }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-medium">Linked Children</span>
                <span class="text-xs font-bold text-[#0B4D73]">{{ $parent->children->count() }}</span>
              </div>
              <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-medium">Status</span>
                @if($parent->email_verified_at)
                  <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">Verified</span>
                @else
                  <span class="text-[10px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Pending</span>
                @endif
              </div>
              <div class="flex items-center justify-between">
                <span class="text-xs text-slate-400 font-medium">Joined</span>
                <span class="text-xs text-slate-800 font-semibold">{{ $parent->created_at->format('M d, Y') }}</span>
              </div>
            </div>

            <div class="mt-auto pt-4 border-t border-slate-100">
              <a href="{{ route('admin.users.edit', $parent->id) }}"
                class="block w-full py-2.5 bg-white border border-slate-200 text-slate-700 text-center rounded-xl text-xs font-bold hover:bg-slate-50 transition-colors">
                Edit Account Details
              </a>
            </div>
          </div>
        @endforeach
      </div>

      @if($parents->hasPages())
        <div class="pt-4 pb-8">
          {{ $parents->withQueryString()->links() }}
        </div>
      @endif
    @else
      <!-- Empty State -->
      <div class="glass rounded-2xl p-16 text-center border border-slate-100/50">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
          <i class="fas fa-user-friends text-4xl text-slate-300"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-900 mb-2">No Parents Found</h3>
        <p class="text-slate-500 max-w-sm mx-auto mb-8">
          {{ isset($filters) && array_filter($filters) ? 'Try adjusting your filters to find the parent you are looking for.' : 'There are no parents registered in the system yet.' }}
        </p>
        <div class="flex items-center justify-center gap-4">
          @if(isset($filters) && array_filter($filters))
            <a href="{{ route('admin.parents.index') }}"
              class="px-6 py-2.5 border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition-colors">
              Clear Filters
            </a>
          @endif
          <a href="{{ route('admin.users.create') }}"
            class="px-6 py-2.5 bg-[#0B4D73] text-white rounded-xl font-bold hover:bg-[#093e5d] transition-colors shadow-lg shadow-blue-900/10">
            Add Parent
          </a>
        </div>
      </div>
    @endif
  </div>
@endsection