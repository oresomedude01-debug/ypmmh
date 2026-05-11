@extends('layouts.dashboard')

@section('title', 'Programs')

  @section('styles')
    <style>
      .analytics-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border-color);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: var(--card-shadow, 0 4px 6px -1px rgba(0, 0, 0, 0.1));
      }

      .analytics-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px var(--shadow-color);
        border-color: var(--primary-500);
      }

      /* Themed utility overrides */
      [data-theme="dark"] .stat-icon-bg {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
      }
    </style>
  @endsection

  @section('content')
    <div class="space-y-8 animate-fade-in">
      <!-- Header Section -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
            Programmes
          </h1>
          <p class="font-medium" style="color: var(--text-secondary);">Manage all youth programmes and curriculums</p>
        </div>
        <div class="flex items-center gap-3">
          <!-- View Toggle Toggle -->
          <div class="hidden lg:flex items-center bg-slate-100 rounded-xl p-1 border border-slate-200">
            <button onclick="setViewMode('table')" id="table-view-btn"
              class="px-3 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 text-xs font-bold">
              <i class="fas fa-list"></i>
              <span>Table</span>
            </button>
            <button onclick="setViewMode('grid')" id="grid-view-btn"
              class="px-3 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 text-xs font-bold">
              <i class="fas fa-th-large"></i>
              <span>Grid</span>
            </button>
          </div>

          <a href="{{ route('admin.programs.create') }}" class="btn btn-primary shadow-lg shadow-[#0B4D73]/20">
            <i class="fas fa-plus"></i>
            <span>Create Programme</span>
          </a>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
        <!-- Total Programmes -->
        <div class="admin-card p-4 sm:p-6 relative overflow-hidden group">
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
              <div
                class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
                <i class="fas fa-book-open"></i>
              </div>
            </div>
            <p class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest mb-0.5 sm:mb-1 truncate" style="color: var(--text-secondary);">
              Total Programmes</p>
            <h3 class="text-xl sm:text-3xl font-black" style="color: var(--text-primary);">{{ $stats['totalPrograms'] ?? 0 }}</h3>
          </div>
          <div
            class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-24 sm:h-24 bg-blue-500/5 rounded-full group-hover:scale-125 transition-transform">
          </div>
        </div>

        <!-- Active Programmes -->
        <div class="admin-card p-4 sm:p-6 relative overflow-hidden group border-l-4 border-l-emerald-500">
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
              <div
                class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
                <i class="fas fa-check-circle"></i>
              </div>
            </div>
            <p class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest mb-0.5 sm:mb-1 truncate" style="color: var(--text-secondary);">
              Active</p>
            <h3 class="text-xl sm:text-3xl font-black text-emerald-600">{{ $stats['activePrograms'] ?? 0 }}</h3>
          </div>
          <div
            class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-24 sm:h-24 bg-emerald-500/5 rounded-full group-hover:scale-125 transition-transform">
          </div>
        </div>

        <!-- Total Students -->
        <div class="admin-card p-4 sm:p-6 relative overflow-hidden group border-l-4 border-l-purple-500">
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
              <div
                class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
                <i class="fas fa-users"></i>
              </div>
            </div>
            <p class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest mb-0.5 sm:mb-1 truncate" style="color: var(--text-secondary);">
              Total Students</p>
            <h3 class="text-xl sm:text-3xl font-black text-purple-600">{{ $stats['totalStudents'] ?? 0 }}</h3>
          </div>
          <div
            class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-24 sm:h-24 bg-purple-500/5 rounded-full group-hover:scale-125 transition-transform">
          </div>
        </div>

        <!-- Featured Programmes -->
        <div class="admin-card p-4 sm:p-6 relative overflow-hidden group border-l-4 border-l-amber-500">
          <div class="relative z-10">
            <div class="flex items-center justify-between mb-2 sm:mb-4">
              <div
                class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
                <i class="fas fa-star"></i>
              </div>
            </div>
            <p class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest mb-0.5 sm:mb-1 truncate" style="color: var(--text-secondary);">
              Featured</p>
            <h3 class="text-xl sm:text-3xl font-black text-amber-600">{{ $stats['featuredPrograms'] ?? 0 }}</h3>
          </div>
          <div
            class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-24 sm:h-24 bg-amber-500/5 rounded-full group-hover:scale-125 transition-transform">
          </div>
        </div>
      </div>

      <!-- Filters Section -->
      <div class="glass rounded-2xl p-6 border shadow-sm" style="border-color: var(--border-color);">
        <form action="{{ route('admin.programs.index') }}" method="GET"
          class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
          <div>
            <label class="block text-xs font-black uppercase tracking-widest mb-2"
              style="color: var(--text-secondary);">Programme Type</label>
            <select name="type"
              class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all cursor-pointer"
              style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
              <option value="">All Types</option>
              <option value="rolling" {{ request('type') == 'rolling' ? 'selected' : '' }}>Rolling</option>
              <option value="scheduled" {{ request('type') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
              <option value="journey" {{ request('type') == 'journey' ? 'selected' : '' }}>Journey</option>
              <option value="offline" {{ request('type') == 'offline' ? 'selected' : '' }}>Offline</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-black uppercase tracking-widest mb-2"
              style="color: var(--text-secondary);">Status</label>
            <select name="status"
              class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all cursor-pointer"
              style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
              <option value="">All Statuses</option>
              <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
              <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
              <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
            </select>
          </div>

          <div>
            <label class="block text-xs font-black uppercase tracking-widest mb-2"
              style="color: var(--text-secondary);">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name..."
              class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all"
              style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
          </div>

          <div class="flex gap-2">
            <button type="submit"
              class="flex-1 bg-[#0B4D73] text-white rounded-xl py-2.5 font-bold hover:brightness-110 transition-all shadow-sm">
              Filter
            </button>
            <a href="{{ route('admin.programs.index') }}"
              class="px-4 py-2.5 border rounded-xl transition-colors flex items-center justify-center"
              style="border-color: var(--border-color); color: var(--text-secondary);" title="Reset Filters">
              <i class="fas fa-undo"></i>
            </a>
          </div>
        </form>
      </div>

      <!-- Programs Grid View -->
      <div id="grid-view" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($programs as $p)
          <div
            class="glass rounded-3xl overflow-hidden border border-slate-100/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group flex flex-col">
            <!-- Header -->
            <div class="p-6 border-b border-slate-100 flex-1">
              <div class="flex items-start justify-between mb-4">
                <div
                  class="w-14 h-14 rounded-2xl bg-gradient-to-br from-[#0B4D73] to-blue-600 text-white flex items-center justify-center text-2xl shadow-lg shadow-blue-900/20">
                  <i
                    class="fas {{ $p->type === 'rolling' ? 'fa-sync-alt' : ($p->type === 'journey' ? 'fa-map-marked-alt' : 'fa-calendar-check') }}"></i>
                </div>
                <div class="flex flex-col items-end gap-2">
                  @php
                    $statusColors = [
                      'active' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                      'draft' => 'bg-amber-100 text-amber-700 border-amber-200',
                      'archived' => 'bg-slate-100 text-slate-700 border-slate-200'
                    ];
                  @endphp
                  <span
                    class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $statusColors[$p->status] ?? 'bg-slate-100 text-slate-700' }}">
                    {{ $p->status }}
                  </span>
                  @if($p->is_featured)
                    <span class="flex items-center gap-1 text-amber-500 text-[10px] font-black uppercase">
                      <i class="fas fa-star"></i> Featured
                    </span>
                  @endif
                </div>
              </div>

              <h3
                class="text-xl font-bold text-slate-900 mb-2 leading-tight group-hover:text-[#0B4D73] transition-colors line-clamp-1">
                {{ $p->name }}
              </h3>

              <div class="flex flex-wrap gap-2 mb-4">
                <span
                  class="px-2 py-1 rounded-md bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest">
                  {{ ucfirst($p->type) }}
                </span>
                <span class="px-2 py-1 rounded-md bg-blue-50 text-[#0B4D73] text-[9px] font-black uppercase tracking-widest">
                  @if($p->type === 'rolling')
                    Age {{ $p->age_target }}
                  @else
                    {{ $p->cohort_age_min }}–{{ $p->cohort_age_max }} Yrs
                  @endif
                </span>
              </div>

              @if($p->mentor)
                <div class="flex items-center gap-2 mt-4 p-2 bg-slate-50 rounded-xl border border-slate-100">
                  <div
                    class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-black text-xs">
                    {{ substr($p->mentor->first_name, 0, 1) }}
                  </div>
                  <div>
                    <p class="text-[9px] font-bold text-slate-400 uppercase leading-none">Mentor</p>
                    <p class="text-xs font-bold text-slate-700">{{ $p->mentor->name }}</p>
                  </div>
                </div>
              @endif
            </div>

            <!-- Footer Stats -->
            <div class="px-6 py-4 bg-slate-50/50 grid grid-cols-2 gap-4 text-center border-t border-slate-100">
              <div>
                <p class="text-xl font-black text-[#0B4D73]">{{ $p->enrollments_count }}</p>
                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-500">Students</p>
              </div>
              <div>
                <p class="text-xl font-black text-[#0B4D73]">{{ $p->contents_count ?? 0 }}</p>
                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-500">Contents</p>
              </div>
            </div>

            <!-- Actions -->
            <div class="p-4 border-t border-slate-100 grid grid-cols-2 gap-3">
              <a href="{{ route('admin.programs.show', $p->id) }}"
                class="px-4 py-2.5 bg-[#0B4D73] text-white font-bold rounded-xl hover:brightness-110 transition-all text-center text-xs shadow-md">
                <i class="fas fa-eye mr-2"></i>Manage
              </a>
              <div class="flex gap-2">
                <a href="{{ route('admin.programs.edit', $p->id) }}"
                  class="flex-1 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all text-center text-xs shadow-sm">
                  <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('admin.programs.destroy', $p->id) }}" method="POST" class="flex-1"
                  onsubmit="return confirm('Delete this program?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                    class="w-full px-4 py-2.5 bg-white border border-red-100 text-red-500 font-bold rounded-xl hover:bg-red-50 transition-all text-center text-xs shadow-sm">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </form>
              </div>
            </div>
          </div>
        @empty
          <div class="col-span-full glass rounded-3xl p-24 text-center border-2 border-dashed border-slate-200">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
              <i class="fas fa-book-open text-4xl text-slate-300"></i>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">No Programmes Found</h3>
            <p class="text-slate-500">Try adjusting your filters or create a new programme.</p>
          </div>
        @endforelse
      </div>

      <!-- Programs Table View -->
      <div id="table-view" class="hidden">
        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto glass rounded-3xl border border-slate-100/50">
          <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-slate-100">
              <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Programme</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Type</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Status</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest">Mentor</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest text-center">Stats</th>
              <th class="px-6 py-4 text-[10px] font-black uppercase text-slate-400 tracking-widest text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-50">
            @forelse($programs as $p)
              <tr class="hover:bg-slate-50/50 transition-colors group">
                <td class="px-6 py-4">
                  <div class="flex items-center gap-4">
                    <div
                      class="w-10 h-10 rounded-xl bg-slate-100 text-[#0B4D73] flex items-center justify-center text-lg group-hover:bg-[#0B4D73] group-hover:text-white transition-all">
                      <i
                        class="fas {{ $p->type === 'rolling' ? 'fa-sync-alt' : ($p->type === 'journey' ? 'fa-map-marked-alt' : 'fa-calendar-check') }}"></i>
                    </div>
                    <div>
                      <p class="font-bold text-slate-900 leading-none mb-1 group-hover:text-[#0B4D73]">{{ $p->name }}</p>
                      <p class="text-[10px] text-slate-400 font-medium">SYS-{{ str_pad($p->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <span
                    class="px-2 py-1 rounded-md bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-widest">
                    {{ $p->type }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  @php
                    $statusClasses = [
                      'active' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                      'draft' => 'bg-amber-50 text-amber-600 border-amber-100',
                      'archived' => 'bg-slate-50 text-slate-400 border-slate-100'
                    ];
                  @endphp
                  <span
                    class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statusClasses[$p->status] ?? 'bg-slate-50 text-slate-400 border-slate-100' }}">
                    {{ $p->status }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  @if($p->mentor)
                    <div class="flex items-center gap-2">
                      <div
                        class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-black">
                        {{ substr($p->mentor->first_name, 0, 1) }}
                      </div>
                      <span class="text-xs font-bold text-slate-700">{{ $p->mentor->full_name }}</span>
                    </div>
                  @else
                    <span class="text-[10px] font-bold text-slate-300 italic">Unassigned</span>
                  @endif
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-center gap-4">
                    <div class="text-center">
                      <p class="text-xs font-black text-slate-900 leading-tight">{{ $p->enrollments_count }}</p>
                      <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">Students</p>
                    </div>
                    <div class="text-center border-l border-slate-100 pl-4">
                      <p class="text-xs font-black text-slate-900 leading-tight">{{ $p->contents_count ?? 0 }}</p>
                      <p class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">Contents</p>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.programs.show', $p->id) }}"
                      class="p-2 text-slate-400 hover:text-[#0B4D73] hover:bg-blue-50 rounded-lg transition-all"
                      title="Manage">
                      <i class="fas fa-eye text-sm"></i>
                    </a>
                    <a href="{{ route('admin.programs.edit', $p->id) }}"
                      class="p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 rounded-lg transition-all"
                      title="Edit">
                      <i class="fas fa-edit text-sm"></i>
                    </a>
                    <form action="{{ route('admin.programs.destroy', $p->id) }}" method="POST" class="inline-block"
                      onsubmit="return confirm('Delete program?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                        class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                        <i class="fas fa-trash-alt text-sm"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-medium">No programmes found matching
                  filters.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Mobile/Tablet List View -->
      <div class="lg:hidden space-y-3">
        @forelse($programs as $p)
          <div class="admin-card p-4 flex flex-col gap-3 rounded-2xl glass border border-slate-100/50 relative overflow-hidden group">
            <div class="flex items-start justify-between">
              <div class="flex items-center gap-3">
                <!-- Icon -->
                <div
                  class="w-10 h-10 shrink-0 rounded-xl bg-slate-100 text-[#0B4D73] flex items-center justify-center text-lg shadow-sm border border-slate-200/50">
                  <i class="fas {{ $p->type === 'rolling' ? 'fa-sync-alt' : ($p->type === 'journey' ? 'fa-map-marked-alt' : 'fa-calendar-check') }}"></i>
                </div>
                <!-- Info -->
                <div class="min-w-0">
                  <div class="flex items-center gap-2">
                    <h4 class="font-bold text-sm text-slate-900 truncate tracking-tight">
                      {{ $p->name }}
                    </h4>
                  </div>
                  <!-- Type Badge -->
                  <span class="inline-block mt-0.5 px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[8px] font-black uppercase tracking-widest border border-slate-200">
                    {{ $p->type }}
                  </span>
                </div>
              </div>
            </div>
            
            <!-- Footer/Actions -->
            <div class="flex items-center justify-between pt-3 border-t border-slate-100/50">
              <div class="flex items-center gap-2">
                <!-- Status -->
                @php
                  $statusClasses = [
                    'active' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                    'draft' => 'bg-amber-50 text-amber-600 border-amber-100',
                    'archived' => 'bg-slate-50 text-slate-400 border-slate-100'
                  ];
                @endphp
                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statusClasses[$p->status] ?? 'bg-slate-50 text-slate-400 border-slate-100' }}">
                  {{ $p->status }}
                </span>
                
                <!-- Students Count -->
                <div class="flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                  <i class="fas fa-users text-[#0B4D73]"></i>
                  {{ $p->enrollments_count }}
                </div>

                <!-- Contents Count -->
                <div class="flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-50 px-2 py-1 rounded-md border border-slate-100">
                  <i class="fas fa-book text-[#0B4D73]"></i>
                  {{ $p->contents_count ?? 0 }}
                </div>
              </div>

              <!-- Actions -->
              <div class="flex items-center gap-1 shrink-0">
                <a href="{{ route('admin.programs.show', $p->id) }}"
                  class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-[#0B4D73] transition-colors border border-slate-100" title="Manage">
                  <i class="fas fa-eye text-xs"></i>
                </a>
                <a href="{{ route('admin.programs.edit', $p->id) }}"
                  class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-500 transition-colors border border-blue-100" title="Edit">
                  <i class="fas fa-edit text-xs"></i>
                </a>
                <form action="{{ route('admin.programs.destroy', $p->id) }}" method="POST"
                  onsubmit="return confirm('Delete program?');" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 transition-colors border border-red-100" title="Delete">
                    <i class="fas fa-trash-alt text-xs"></i>
                  </button>
                </form>
              </div>
            </div>
          </div>
        @empty
          <div class="glass rounded-3xl p-8 sm:p-16 text-center border-2 border-dashed border-slate-200">
            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-book-open text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-lg font-black text-slate-900 mb-1">No Programmes Found</h3>
            <p class="text-xs font-medium text-slate-500">Try adjusting your filters.</p>
          </div>
        @endforelse
      </div>

      <!-- Pagination (Shared across both views) -->
      @if($programs->hasPages())
        <div class="pt-6 pb-2 border-t mt-6" style="border-color: var(--border-color);">
          {{ $programs->withQueryString()->links() }}
        </div>
      @endif

    </div>

    <script>
      function setViewMode(mode) {
        const gridView = document.getElementById('grid-view');
        const tableView = document.getElementById('table-view');
        const gridBtn = document.getElementById('grid-view-btn');
        const tableBtn = document.getElementById('table-view-btn');

        if (mode === 'grid') {
          gridView.classList.remove('hidden');
          tableView.classList.add('hidden');
          gridBtn.classList.add('bg-white', 'text-[#0B4D73]', 'shadow-sm');
          gridBtn.classList.remove('text-slate-500');
          tableBtn.classList.remove('bg-white', 'text-[#0B4D73]', 'shadow-sm');
          tableBtn.classList.add('text-slate-500');
        } else {
          gridView.classList.add('hidden');
          tableView.classList.remove('hidden');
          tableBtn.classList.add('bg-white', 'text-[#0B4D73]', 'shadow-sm');
          tableBtn.classList.remove('text-slate-500');
          gridBtn.classList.remove('bg-white', 'text-[#0B4D73]', 'shadow-sm');
          gridBtn.classList.add('text-slate-500');
        }
        localStorage.setItem('programs_view', mode);
      }

      // Initialize view mode
      document.addEventListener('DOMContentLoaded', () => {
        const savedMode = localStorage.getItem('programs_view') || 'table';
        setViewMode(savedMode);
      });
    </script>
@endsection