@extends('layouts.dashboard')

@section('title', 'Create Program')

@section('content')
  <div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Create New Program</h1>
        <p class="text-slate-600 text-sm">Set up a new youth development or educational program.</p>
      </div>
      <a href="{{ route('admin.programs.index') }}"
        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-colors shadow-sm">
        <i class="fas fa-arrow-left"></i>
        <span>Back to Programs</span>
      </a>
    </div>

    <form action="{{ route('admin.programs.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6"
      id="createProgramForm">
      @csrf

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Type & Mentor -->
        <div class="lg:col-span-1 space-y-6">
          <!-- Program Type -->
          <div class="glass rounded-2xl p-6">
            <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
              <i class="fas fa-layer-group text-blue-500"></i>
              <span>Program Type</span>
            </h3>
            <div class="space-y-3">
              <label class="relative block cursor-pointer group">
                <input type="radio" name="type" value="rolling" class="peer sr-only" {{ old('type', 'rolling') == 'rolling' ? 'checked' : '' }} onchange="toggleTypeFields()">
                <div
                  class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                      <i class="fas fa-sync-alt"></i>
                    </div>
                    <div>
                      <p class="font-bold text-slate-900">Rolling</p>
                      <p class="text-xs text-slate-500">Age-based / Ongoing</p>
                    </div>
                  </div>
                </div>
              </label>

              <label class="relative block cursor-pointer group">
                <input type="radio" name="type" value="scheduled" class="peer sr-only" {{ old('type') == 'scheduled' ? 'checked' : '' }} onchange="toggleTypeFields()">
                <div
                  class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-purple-500 peer-checked:bg-purple-50 transition-all">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center">
                      <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div>
                      <p class="font-bold text-slate-900">Scheduled</p>
                      <p class="text-xs text-slate-500">Cohort / Specific Dates</p>
                    </div>
                  </div>
                </div>
              </label>

              <label class="relative block cursor-pointer group">
                <input type="radio" name="type" value="journey" class="peer sr-only" {{ old('type') == 'journey' ? 'checked' : '' }} onchange="toggleTypeFields()">
                <div
                  class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-rose-500 peer-checked:bg-rose-50 transition-all">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center">
                      <i class="fas fa-route"></i>
                    </div>
                    <div>
                      <p class="font-bold text-slate-900">Journey</p>
                      <p class="text-xs text-slate-500">Subscription-based / Paced</p>
                    </div>
                  </div>
                </div>
              </label>

              <label class="relative block cursor-pointer group">
                <input type="radio" name="type" value="offline" class="peer sr-only" {{ old('type') == 'offline' ? 'checked' : '' }} onchange="toggleTypeFields()">
                <div
                  class="p-4 rounded-xl border-2 border-slate-100 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                      <i class="fas fa-building"></i>
                    </div>
                    <div>
                      <p class="font-bold text-slate-900">Offline</p>
                      <p class="text-xs text-slate-500">Face-to-face / Physical</p>
                    </div>
                  </div>
                </div>
              </label>
            </div>
          </div>

          <!-- Assignment -->
          <div class="glass rounded-2xl p-6">
            <h3 class="font-bold text-slate-900 mb-4 flex items-center gap-2">
              <i class="fas fa-user-tie text-emerald-500"></i>
              <span>Program Admin</span>
            </h3>
            <div class="space-y-4">
              <div>
                <label class="text-sm font-semibold text-slate-700 block mb-1">Lead Mentor</label>
                <select name="mentor_id"
                  class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all cursor-pointer">
                  <option value="">No mentor assigned</option>
                  @foreach($mentors as $mentor)
                    <option value="{{ $mentor['id'] }}" {{ old('mentor_id') == $mentor['id'] ? 'selected' : '' }}>
                      {{ $mentor['name'] }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div>
                <label class="text-sm font-semibold text-slate-700 block mb-1">Status</label>
                <select name="status"
                  class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all cursor-pointer">
                  <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                  <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                  <option value="archived" {{ old('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
              </div>

              <div class="pt-2">
                <label class="flex items-center gap-3 cursor-pointer group">
                  <div class="relative">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                      class="peer sr-only">
                    <div class="w-10 h-6 bg-slate-200 rounded-full peer peer-checked:bg-amber-400 transition-colors">
                    </div>
                    <div
                      class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-4">
                    </div>
                  </div>
                  <div>
                    <span class="text-sm font-bold text-slate-700">Feature in Catalog</span>
                    <p class="text-[10px] text-slate-500">Will appear in the main carousel</p>
                  </div>
                </label>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column: Details -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Basic Info -->
          <div class="glass rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
              <div class="w-10 h-10 rounded-full bg-blue-50 text-[#0B4D73] flex items-center justify-center">
                <i class="fas fa-info-circle"></i>
              </div>
              <div>
                <h3 class="font-bold text-slate-900">Program Information</h3>
                <p class="text-xs text-slate-500">The core details of your program</p>
              </div>
            </div>

            <div class="space-y-5">
              <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-700">Program Name <span
                    class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                  class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400"
                  placeholder="e.g., Youth Leadership 2024">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
              </div>

              <div class="space-y-1">
                <label class="text-sm font-semibold text-slate-700">Description</label>
                <textarea name="description" rows="5"
                  class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all placeholder:text-slate-400 resize-none"
                  placeholder="What is this program about?">{{ old('description') }}</textarea>
                @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
              </div>
            </div>
          </div>

          <!-- Type Specific Config -->
          <div class="glass rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
              <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center">
                <i class="fas fa-cog"></i>
              </div>
              <div>
                <h3 class="font-bold text-slate-900">Configuration</h3>
                <p class="text-xs text-slate-500" id="configHeading">Set up program requirements</p>
              </div>
            </div>

            <!-- Rolling Fields -->
            <div id="rollingFields" class="space-y-5">
              <div class="space-y-1 max-w-xs">
                <label class="text-sm font-semibold text-slate-700">Target Age (Years)</label>
                <div class="relative">
                  <input type="number" name="age_target" value="{{ old('age_target') }}"
                    class="w-full pl-4 pr-10 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all"
                    placeholder="e.g., 8">
                  <span
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs font-bold uppercase">Yrs</span>
                </div>
                <p class="text-[10px] text-slate-500 pl-1">Age groups: 8, 10, 12, etc.</p>
                @error('age_target') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
              </div>
            </div>

            <!-- Scheduled Fields -->
            <div id="scheduledFields" class="hidden space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1">
                  <label class="text-sm font-semibold text-slate-700">Minimum Age</label>
                  <input type="number" name="cohort_age_min" value="{{ old('cohort_age_min') }}"
                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all"
                    placeholder="e.g., 10">
                </div>
                <div class="space-y-1">
                  <label class="text-sm font-semibold text-slate-700">Maximum Age</label>
                  <input type="number" name="cohort_age_max" value="{{ old('cohort_age_max') }}"
                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all"
                    placeholder="e.g., 18">
                </div>
              </div>

              <div id="dateFields" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="space-y-1">
                  <label class="text-sm font-semibold text-slate-700">Start Date</label>
                  <input type="date" name="start_date" value="{{ old('start_date') }}"
                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all">
                </div>
                <div class="space-y-1">
                  <label class="text-sm font-semibold text-slate-700">End Date</label>
                  <input type="date" name="end_date" value="{{ old('end_date') }}"
                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all">
                </div>
              </div>

              <!-- Pricing & Media (Common for Scheduled and Journey) -->
              <div id="pricingMediaFields" class="pt-4 border-t border-slate-100">
                <h4 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Pricing & Media</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                  <div class="space-y-1">
                    <label class="text-sm font-semibold text-slate-700 flex items-center justify-between">
                      <span>Price (₦)</span>
                      <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_free" value="1" {{ old('is_free') ? 'checked' : '' }}
                          class="rounded border-slate-300 text-[#0B4D73] focus:ring-[#0B4D73]">
                        <span class="text-xs font-normal text-slate-500">Mark as Free</span>
                      </label>
                    </label>
                    <input type="number" name="price" step="0.01" value="{{ old('price') }}"
                      class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all"
                      placeholder="0.00">
                  </div>
                  <div class="space-y-1">
                    <label class="text-sm font-semibold text-slate-700">YouTube Jingle / Advert URL</label>
                    <input type="url" name="youtube_url" value="{{ old('youtube_url') }}"
                      class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all"
                      placeholder="https://youtube.com/watch?v=...">
                  </div>
                </div>
                <div class="space-y-1">
                  <label class="text-sm font-semibold text-slate-700">Program Thumbnail Image</label>
                  <input type="file" name="thumbnail" accept="image/*"
                    class="w-full px-4 py-2.5 bg-white/50 border border-slate-200 border-dashed rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0B4D73] focus:bg-white transition-all">
                  <p class="text-[10px] text-slate-500">Recommended: 16:9 aspect ratio, max 2MB.</p>
                </div>
              </div>

              @error('cohort_age_min') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
              @error('start_date') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
              @error('price') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
              @error('youtube_url') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
              @error('thumbnail') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
          </div>

          <!-- Submit -->
          <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.programs.index') }}"
              class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold hover:bg-slate-50 transition-colors">
              Cancel
            </a>
            <button type="submit"
              class="px-8 py-3 rounded-xl bg-[#0B4D73] text-white font-bold text-lg hover:bg-[#093e5d] transition-colors shadow-lg shadow-blue-900/20 flex items-center gap-2">
              <span>Save Program</span>
              <i class="fas fa-arrow-right text-sm"></i>
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>

  <script>
    function toggleTypeFields() {
      const type = document.querySelector('input[name="type"]:checked')?.value;
      const rollingFields = document.getElementById('rollingFields');
      const scheduledFields = document.getElementById('scheduledFields');
      const dateFields = document.getElementById('dateFields');
      const configHeading = document.getElementById('configHeading');

      if (type === 'scheduled' || type === 'offline') {
        rollingFields.classList.add('hidden');
        scheduledFields.classList.remove('hidden');
        dateFields.classList.remove('hidden');
        configHeading.textContent = type === 'offline' ? 'Set up offline program details' : 'Set up cohort dates and age range';
      } else if (type === 'journey') {
        rollingFields.classList.add('hidden');
        scheduledFields.classList.remove('hidden');
        dateFields.classList.add('hidden');
        configHeading.textContent = 'Set up subscription age range';
      } else {
        rollingFields.classList.remove('hidden');
        scheduledFields.classList.add('hidden');
        configHeading.textContent = 'Set up program age target';
      }
    }

    // Run on load to handle validation errors state
    document.addEventListener('DOMContentLoaded', toggleTypeFields);
  </script>
@endsection