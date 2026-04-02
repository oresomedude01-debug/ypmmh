@extends('layouts.dashboard')

@section('title', 'Manage Users')

@section('content')
  <div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
          User Directory
        </h1>
        <p class="font-medium" style="color: var(--text-secondary);">System-wide user management and bulk operations.</p>
      </div>
      <div class="flex flex-wrap gap-2">
        <!-- Export Button -->
        <a href="{{ route('admin.users.export') }}"
          class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-xl text-xs font-bold hover:brightness-110 transition-all shadow-md shadow-emerald-500/20">
          <i class="fas fa-file-export"></i>
          <span>Export</span>
        </a>

        <!-- Import Button -->
        <button onclick="document.getElementById('importModal').classList.remove('hidden')"
          class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-xl text-xs font-bold hover:brightness-110 transition-all shadow-md shadow-amber-500/20">
          <i class="fas fa-file-import"></i>
          <span>Import</span>
        </button>

        <!-- Add User Button -->
        <a href="{{ route('admin.users.create') }}"
          class="inline-flex items-center gap-2 px-4 py-2 bg-[#0B4D73] text-white rounded-xl text-xs font-bold hover:brightness-110 transition-all shadow-md shadow-[#0B4D73]/20">
          <i class="fas fa-plus"></i>
          <span>Add User</span>
        </a>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
      <!-- Total Users -->
      <div class="admin-card p-4 sm:p-6 relative overflow-hidden group">
        <div class="relative z-10">
          <div class="flex items-center justify-between mb-2 sm:mb-4">
            <div
              class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-[#0B4D73]/10 text-[#0B4D73] flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
              <i class="fas fa-users"></i>
            </div>
          </div>
          <p class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest mb-0.5 sm:mb-1 truncate"
            style="color: var(--text-secondary);">Total Directory</p>
          <h3 class="text-xl sm:text-3xl font-black" style="color: var(--text-primary);">{{ $stats['totalUsers'] ?? 0 }}
          </h3>
        </div>
        <div
          class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-24 sm:h-24 bg-[#0B4D73]/5 rounded-full group-hover:scale-125 transition-transform">
        </div>
      </div>

      <!-- Verified Users -->
      <div class="admin-card p-4 sm:p-6 relative overflow-hidden group border-l-4 border-l-emerald-500">
        <div class="relative z-10">
          <div class="flex items-center justify-between mb-2 sm:mb-4">
            <div
              class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
              <i class="fas fa-user-check"></i>
            </div>
          </div>
          <p class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest mb-0.5 sm:mb-1 truncate"
            style="color: var(--text-secondary);">Verified</p>
          <h3 class="text-xl sm:text-3xl font-black text-emerald-600">{{ $stats['verifiedCount'] ?? 0 }}</h3>
        </div>
        <div
          class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-24 sm:h-24 bg-emerald-500/5 rounded-full group-hover:scale-125 transition-transform">
        </div>
      </div>

      <!-- Pending Users -->
      <div class="admin-card p-4 sm:p-6 relative overflow-hidden group border-l-4 border-l-amber-500">
        <div class="relative z-10">
          <div class="flex items-center justify-between mb-2 sm:mb-4">
            <div
              class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
              <i class="fas fa-clock"></i>
            </div>
          </div>
          <p class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest mb-0.5 sm:mb-1 truncate"
            style="color: var(--text-secondary);">Pending</p>
          <h3 class="text-xl sm:text-3xl font-black text-amber-600">{{ $stats['pendingCount'] ?? 0 }}</h3>
        </div>
        <div
          class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-24 sm:h-24 bg-amber-500/5 rounded-full group-hover:scale-125 transition-transform">
        </div>
      </div>

      <!-- Admins -->
      <div class="admin-card p-4 sm:p-6 relative overflow-hidden group border-l-4 border-l-purple-500">
        <div class="relative z-10">
          <div class="flex items-center justify-between mb-2 sm:mb-4">
            <div
              class="w-8 h-8 sm:w-12 sm:h-12 rounded-lg sm:rounded-xl bg-purple-500/10 text-purple-500 flex items-center justify-center text-sm sm:text-xl shadow-sm group-hover:scale-110 transition-transform">
              <i class="fas fa-user-shield"></i>
            </div>
          </div>
          <p class="text-[8px] sm:text-[10px] font-black uppercase tracking-widest mb-0.5 sm:mb-1 truncate"
            style="color: var(--text-secondary);">Admins</p>
          <h3 class="text-xl sm:text-3xl font-black text-purple-600">{{ $stats['adminCount'] ?? 0 }}</h3>
        </div>
        <div
          class="absolute -right-4 -bottom-4 w-16 h-16 sm:w-24 sm:h-24 bg-purple-500/5 rounded-full group-hover:scale-125 transition-transform">
        </div>
      </div>
    </div>

    <!-- Filters & Search -->
    <div class="glass rounded-2xl p-4 sm:p-6 border shadow-sm" style="border-color: var(--border-color);">
      <form action="{{ route('admin.users.index') }}" method="GET"
        class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <!-- Search -->
        <div class="relative md:col-span-2">
          <label class="block text-xs font-black uppercase tracking-widest mb-2"
            style="color: var(--text-secondary);">Search Everything</label>
          <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}"
              class="w-full pl-10 pr-4 py-2.5 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all font-medium"
              style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);"
              placeholder="Search by name, email, ID...">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2" style="color: var(--text-secondary);"></i>
          </div>
        </div>

        <!-- Role Filter -->
        <div>
          <label class="block text-xs font-black uppercase tracking-widest mb-2"
            style="color: var(--text-secondary);">Role Type</label>
          <select name="role"
            class="w-full px-4 py-2.5 rounded-xl border focus:outline-none focus:ring-4 focus:ring-[#0B4D73]/10 transition-all cursor-pointer font-medium"
            style="background-color: var(--bg-primary); border-color: var(--border-color); color: var(--text-primary);">
            <option value="">All Roles</option>
            <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
            <option value="Mentor" {{ request('role') == 'Mentor' ? 'selected' : '' }}>Mentor</option>
            <option value="Parent" {{ request('role') == 'Parent' ? 'selected' : '' }}>Parent</option>
            <option value="Child" {{ request('role') == 'Child' ? 'selected' : '' }}>Child</option>
          </select>
        </div>

        <!-- Filter Buttons -->
        <div class="flex gap-2">
          <button type="submit"
            class="flex-1 bg-[#0B4D73] text-white rounded-xl py-2.5 font-bold hover:brightness-110 transition-all shadow-sm">
            Search
          </button>
          <a href="{{ route('admin.users.index') }}"
            class="px-4 py-2.5 border rounded-xl transition-all flex items-center justify-center"
            style="border-color: var(--border-color); color: var(--text-secondary);">
            <i class="fas fa-undo"></i>
          </a>
        </div>
      </form>
    </div>

    <!-- Users Content -->
    @if($users->count() > 0)
      <!-- Desktop Table View -->
      <div class="hidden lg:block admin-card overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr>
                <th class="admin-table-header rounded-tl-3xl">User Profile</th>
                <th class="admin-table-header">Role</th>
                <th class="admin-table-header">Status</th>
                <th class="admin-table-header">Joined</th>
                <th class="admin-table-header text-right rounded-tr-3xl">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y" style="border-color: var(--border-color);">
              @foreach($users as $user)
                <tr class="group hover:bg-opacity-5 transition-colors" style="background-color: transparent;">
                  <td class="px-6 py-4" style="background-color: transparent;">
                    <div class="flex items-center gap-3">
                      @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt=""
                          class="w-10 h-10 rounded-full object-cover border-2 shadow-sm"
                          style="border-color: var(--bg-secondary);">
                      @else
                        <div
                          class="w-10 h-10 rounded-full bg-[#0B4D73]/10 text-[#0B4D73] flex items-center justify-center font-black border-2 shadow-sm text-xs"
                          style="border-color: var(--bg-secondary);">
                          {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                        </div>
                      @endif
                      <div>
                        <p class="font-bold group-hover:text-[#0B4D73] transition-colors leading-tight"
                          style="color: var(--text-primary);">
                          {{ $user->first_name }} {{ $user->last_name }}
                        </p>
                        <p class="text-[10px] font-medium" style="color: var(--text-secondary);">{{ $user->email }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4" style="background-color: transparent;">
                    @php
                      $roleColors = [
                        'Admin' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                        'Mentor' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                        'Parent' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                        'Child' => 'bg-amber-500/10 text-amber-500 border-amber-500/20'
                      ];
                      $roleName = $user->roles->first()->name ?? 'No Role';
                    @endphp
                    <span
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase border {{ $roleColors[$roleName] ?? 'bg-slate-500/10 text-slate-500 border-slate-500/20' }}">
                      {{ $roleName }}
                    </span>
                  </td>
                  <td class="px-6 py-4" style="background-color: transparent;">
                    @if($user->email_verified_at)
                      <span
                        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-emerald-500/10 text-emerald-500">
                        <i class="fas fa-check-circle"></i> Verified
                      </span>
                    @else
                      <span
                        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-amber-500/10 text-amber-500">
                        <i class="fas fa-clock"></i> Pending
                      </span>
                    @endif
                  </td>
                  <td class="px-6 py-4 text-[11px] font-bold"
                    style="color: var(--text-secondary); background-color: transparent;">
                    {{ $user->created_at ? $user->created_at->format('M d, Y') : '—' }}
                  </td>
                  <td class="px-6 py-4 text-right" style="background-color: transparent;">
                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                      <a href="{{ route('admin.users.edit', $user) }}"
                        class="p-2 bg-blue-500/10 text-blue-500 rounded-lg hover:bg-blue-500/20 transition-all" title="Edit">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                        onsubmit="return confirm('Are you sure?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                          class="p-2 bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500/20 transition-all font-black"
                          title="Delete">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        @if($users->hasPages())
          <div class="px-6 py-4 border-t bg-opacity-5"
            style="border-color: var(--border-color); background-color: var(--text-primary);">
            {{ $users->withQueryString()->links() }}
          </div>
        @endif
      </div>

      <!-- Mobile/Tablet User List -->
      <div class="lg:hidden space-y-3">
        @foreach($users as $user)
          <div class="glass rounded-xl p-3 border flex items-center justify-between gap-3 group"
            style="border-color: var(--border-color);">
            <div class="flex items-center gap-3 overflow-hidden">
              <!-- Avatar -->
              <div class="relative shrink-0">
                @if($user->profile_picture)
                  <img src="{{ asset('storage/' . $user->profile_picture) }}" alt=""
                    class="w-10 h-10 rounded-full object-cover border shadow-sm" style="border-color: var(--bg-secondary);">
                @else
                  <div
                    class="w-10 h-10 rounded-full bg-[#0B4D73]/10 text-[#0B4D73] flex items-center justify-center font-black border shadow-sm text-xs"
                    style="border-color: var(--bg-secondary);">
                    {{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}
                  </div>
                @endif
                <!-- Verification/Role Status Dot -->
                <div
                  class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white flex items-center justify-center {{ $user->email_verified_at ? 'bg-emerald-500' : 'bg-amber-500' }}">
                  @if($user->email_verified_at)
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
                    {{ $user->first_name }} {{ $user->last_name }}
                  </h4>
                  @php $roleName = $user->roles->first()->name ?? 'N/A'; @endphp
                  <span
                    class="text-[8px] font-black uppercase px-1.5 py-0.5 rounded-md border {{ $roleColors[$roleName] ?? 'border-slate-500/10 text-slate-500' }}">
                    {{ $roleName }}
                  </span>
                </div>
                <p class="text-[10px] truncate font-medium opacity-70" style="color: var(--text-secondary);">
                  {{ $user->email }}
                </p>
                <p class="text-[9px] font-bold mt-0.5" style="color: var(--text-secondary);">
                  Joined: {{ $user->created_at ? $user->created_at->format('M d, Y') : '—' }}
                </p>
              </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-2 shrink-0">
              <a href="{{ route('admin.users.edit', $user) }}"
                class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-500 active:scale-95 transition-transform">
                <i class="fas fa-edit text-xs"></i>
              </a>
              <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                onsubmit="return confirm('Delete this user?');">
                @csrf
                @method('DELETE')
                <button type="submit"
                  class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 active:scale-95 transition-transform">
                  <i class="fas fa-trash-alt text-xs"></i>
                </button>
              </form>
            </div>
          </div>
        @endforeach

        @if($users->hasPages())
          <div class="pt-4 pb-8">
            {{ $users->withQueryString()->links() }}
          </div>
        @endif
      </div>
    @else
      <!-- Empty State -->
      <div class="glass rounded-3xl p-8 sm:p-16 text-center border" style="border-color: var(--border-color);">
        <div class="w-20 h-20 bg-opacity-5 rounded-full flex items-center justify-center mx-auto mb-6"
          style="background-color: var(--text-primary);">
          <i class="fas fa-users-slash text-4xl opacity-20" style="color: var(--text-primary);"></i>
        </div>
        <h3 class="text-xl font-black mb-2" style="color: var(--text-primary);">No Results Found</h3>
        <p class="max-w-sm mx-auto mb-8 font-medium" style="color: var(--text-secondary);">
          Your search criteria didn't return any matches. Try using different keywords or removing filters.
        </p>
        <div class="flex items-center justify-center gap-4">
          <a href="{{ route('admin.users.index') }}"
            class="px-6 py-2.5 border rounded-xl font-black uppercase tracking-widest text-xs transition-all"
            style="border-color: var(--border-color); color: var(--text-secondary);">
            Reset search
          </a>
        </div>
      </div>
    @endif
  </div>

  <!-- Import Modal -->
  <div id="importModal"
    class="hidden fixed inset-0 bg-opacity-80 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-all"
    style="background-color: var(--bg-primary);">
    <div class="glass border rounded-3xl shadow-2xl max-w-md w-full p-8 transform transition-all scale-100"
      style="border-color: var(--border-color);">
      <div class="text-center mb-6">
        <div
          class="w-16 h-16 bg-amber-500/10 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl border border-amber-500/20">
          <i class="fas fa-file-csv"></i>
        </div>
        <h3 class="text-2xl font-black" style="color: var(--text-primary);">Import Users</h3>
        <p class="mt-2 font-medium" style="color: var(--text-secondary);">Bulk create accounts via CSV upload.</p>
      </div>

      <form action="{{ route('admin.users.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div
          class="relative border-2 border-dashed rounded-2xl p-8 text-center hover:border-blue-500 transition-colors cursor-pointer group"
          style="border-color: var(--border-color);">
          <input type="file" name="csv_file" accept=".csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
            required>
          <div class="space-y-2 pointer-events-none">
            <i class="fas fa-cloud-upload-alt text-3xl opacity-20 group-hover:opacity-100 group-hover:text-blue-500 transition-all"
              style="color: var(--text-primary);"></i>
            <p class="text-sm font-black uppercase tracking-tighter" style="color: var(--text-primary);">Select CSV file
            </p>
            <p class="text-[10px] font-bold" style="color: var(--text-secondary);">Template required format</p>
          </div>
        </div>

        <div class="text-center">
          <a href="{{ route('admin.users.template') }}" class="text-xs font-black uppercase tracking-wide hover:underline"
            style="color: var(--primary-500);">
            Download official CSV template
          </a>
        </div>

        <div class="grid grid-cols-2 gap-3 mt-6">
          <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
            class="px-4 py-3 rounded-xl border font-bold transition-all text-sm"
            style="background-color: var(--bg-secondary); border-color: var(--border-color); color: var(--text-secondary);">
            Cancel
          </button>
          <button type="submit"
            class="px-4 py-3 rounded-xl bg-[#0B4D73] text-white font-bold hover:brightness-110 transition-all shadow-lg shadow-[#0B4D73]/20 text-sm">
            Upload & Import
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection