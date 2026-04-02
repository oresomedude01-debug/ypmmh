@extends('layouts.dashboard')

@section('title', 'System Settings')

@section('styles')
    <style>
        .settings-tab {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .settings-tab.active {
            background: var(--primary-500);
            color: white;
            box-shadow: 0 10px 15px -3px rgba(11, 77, 115, 0.2);
        }

        .settings-tab:not(.active):hover {
            background: rgba(11, 77, 115, 0.05);
        }

        .logo-preview {
            transition: all 0.3s ease;
        }

        .logo-preview:hover {
            transform: scale(1.05);
        }

        .upload-zone {
            border: 2px dashed var(--border-color);
            transition: all 0.3s ease;
        }

        .upload-zone:hover {
            border-color: var(--primary-500);
            background: rgba(11, 77, 115, 0.02);
        }

        .upload-zone.dragover {
            border-color: var(--primary-500);
            background: rgba(11, 77, 115, 0.05);
        }
    </style>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black bg-gradient-to-r from-[#0B4D73] to-blue-500 bg-clip-text text-transparent">
                    System Configurations
                </h1>
                <p class="font-medium text-slate-500">Fine-tune your application's behavior and integrations</p>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Navigation -->
            <div class="lg:w-64 shrink-0">
                <div class="glass rounded-[2rem] p-4 border border-white space-y-2 sticky top-8">
                    <button onclick="switchTab('branding')" id="tab-branding"
                        class="settings-tab active w-full flex items-center gap-3 px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-[10px]">
                        <i class="fas fa-palette"></i>
                        Branding
                    </button>
                    <button onclick="switchTab('payment')" id="tab-payment"
                        class="settings-tab w-full flex items-center gap-3 px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-slate-500">
                        <i class="fas fa-credit-card"></i>
                        Payment Gateway
                    </button>
                    <button onclick="switchTab('premium')" id="tab-premium"
                        class="settings-tab w-full flex items-center gap-3 px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-slate-500">
                        <i class="fas fa-crown text-yellow-500"></i>
                        Premium Plans
                    </button>
                    <button onclick="switchTab('contact')" id="tab-contact"
                        class="settings-tab w-full flex items-center gap-3 px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-slate-500">
                        <i class="fas fa-address-book"></i>
                        Contact & Social
                    </button>
                    <button onclick="switchTab('roles')" id="tab-roles"
                        class="settings-tab w-full flex items-center gap-3 px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-slate-500">
                        <i class="fas fa-user-shield"></i>
                        Roles & Permissions
                    </button>
                    <button onclick="switchTab('general')" id="tab-general"
                        class="settings-tab w-full flex items-center gap-3 px-6 py-4 rounded-2xl font-black uppercase tracking-widest text-[10px] text-slate-500">
                        <i class="fas fa-sliders-h"></i>
                        General Settings
                    </button>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1">
                <!-- Branding Settings -->
                <div id="content-branding" class="tab-content">
                    <form action="{{ route('admin.settings.branding.update') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf
                        <div class="admin-card p-8 md:p-10">
                            <div class="flex items-center gap-4 mb-8">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center text-purple-600">
                                    <i class="fas fa-paint-brush text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900">App Branding</h3>
                                    <p class="text-sm text-slate-500">Customize your app's visual identity</p>
                                </div>
                            </div>

                            <!-- App Name & Tagline -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">App
                                        Name</label>
                                    <input type="text" name="app_name" value="{{ $settings['app_name'] ?? 'YPMMH' }}"
                                        placeholder="Your App Name" class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400">Tagline</label>
                                    <input type="text" name="app_tagline"
                                        value="{{ $settings['app_tagline'] ?? 'Young Productive Muslim Mentoring Hub' }}"
                                        placeholder="Your tagline" class="w-full">
                                </div>
                            </div>

                            <!-- Logo Upload -->
                            <div class="space-y-4 mb-8">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">App Logo /
                                    Icon</label>
                                <div class="flex flex-col md:flex-row gap-6 items-start">
                                    <!-- Current Logo Preview -->
                                    <div class="flex-shrink-0">
                                        <div
                                            class="logo-preview w-32 h-32 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden relative group">
                                            @if(isset($settings['app_logo']) && $settings['app_logo'])
                                                <img src="{{ asset('storage/' . $settings['app_logo']) }}" alt="App Logo"
                                                    class="w-full h-full object-contain p-2">
                                                <div
                                                    class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                    <button type="button"
                                                        onclick="if(confirm('Remove the current logo?')) document.getElementById('remove-logo-form').submit();"
                                                        class="w-10 h-10 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 transition-colors">
                                                        <i class="fas fa-trash text-sm"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <div class="text-center p-4">
                                                    <i class="fas fa-image text-3xl text-slate-300 mb-2"></i>
                                                    <p class="text-[10px] text-slate-400 font-bold">No logo</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Upload Zone -->
                                    <div class="flex-1">
                                        <div class="upload-zone rounded-2xl p-6 text-center" id="logoDropZone">
                                            <input type="file" name="app_logo" id="logoInput"
                                                accept="image/png,image/jpg,image/jpeg,image/svg+xml,image/webp"
                                                class="hidden">
                                            <div class="space-y-3">
                                                <div
                                                    class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center text-[#0B4D73] mx-auto">
                                                    <i class="fas fa-cloud-upload-alt text-xl"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-700">
                                                        <label for="logoInput"
                                                            class="text-[#0B4D73] cursor-pointer hover:underline">Click to
                                                            upload</label>
                                                        or drag and drop
                                                    </p>
                                                    <p class="text-[10px] text-slate-400 mt-1">PNG, JPG, SVG or WEBP (max
                                                        2MB)</p>
                                                </div>
                                            </div>
                                            <p id="logoFileName" class="text-xs text-green-600 font-bold mt-3 hidden"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Favicon Upload -->
                            <div class="space-y-4">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Favicon
                                    (Browser Tab Icon)</label>
                                <div class="flex flex-col md:flex-row gap-6 items-start">
                                    <!-- Current Favicon Preview -->
                                    <div class="flex-shrink-0">
                                        <div
                                            class="logo-preview w-20 h-20 rounded-xl bg-gradient-to-br from-slate-50 to-slate-100 border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden relative group">
                                            @if(isset($settings['app_favicon']) && $settings['app_favicon'])
                                                <img src="{{ asset('storage/' . $settings['app_favicon']) }}" alt="Favicon"
                                                    class="w-full h-full object-contain p-1">
                                                <div
                                                    class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                    <button type="button"
                                                        onclick="if(confirm('Remove the current favicon?')) document.getElementById('remove-favicon-form').submit();"
                                                        class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 transition-colors">
                                                        <i class="fas fa-trash text-xs"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <div class="text-center p-2">
                                                    <i class="fas fa-globe text-xl text-slate-300"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Upload Zone -->
                                    <div class="flex-1">
                                        <div class="upload-zone rounded-2xl p-6 text-center" id="faviconDropZone">
                                            <input type="file" name="app_favicon" id="faviconInput"
                                                accept="image/png,image/x-icon,image/svg+xml" class="hidden">
                                            <div class="space-y-3">
                                                <div
                                                    class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500 mx-auto">
                                                    <i class="fas fa-browser text-lg"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-slate-700">
                                                        <label for="faviconInput"
                                                            class="text-[#0B4D73] cursor-pointer hover:underline">Click to
                                                            upload</label>
                                                        or drag and drop
                                                    </p>
                                                    <p class="text-[10px] text-slate-400 mt-1">PNG, ICO or SVG (max 512KB,
                                                        32x32 recommended)</p>
                                                </div>
                                            </div>
                                            <p id="faviconFileName" class="text-xs text-green-600 font-bold mt-3 hidden">
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Box -->
                            <div
                                class="mt-10 p-6 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-3xl border border-blue-100 flex items-start gap-4">
                                <i class="fas fa-lightbulb text-amber-500 mt-1"></i>
                                <div class="text-xs text-slate-600 leading-relaxed">
                                    <strong>Pro Tip:</strong> Your logo will appear throughout the app including the
                                    navigation bar,
                                    login pages, welcome page, and email templates. The favicon appears in browser tabs.
                                    For best results, use a <span class="text-blue-600 font-bold">transparent PNG</span>
                                    with square dimensions.
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                class="px-8 py-4 bg-gradient-to-r from-[#0B4D73] to-cyan-700 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:shadow-xl hover:-translate-y-0.5 transition-all shadow-lg shadow-blue-900/10">
                                <i class="fas fa-save mr-2"></i>
                                Save Branding
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Hidden forms for removal -->
                <form id="remove-logo-form" action="{{ route('admin.settings.logo.remove') }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
                <form id="remove-favicon-form" action="{{ route('admin.settings.favicon.remove') }}" method="POST"
                    class="hidden">
                    @csrf
                    @method('DELETE')
                </form>

                <!-- Payment Gateway Form -->
                <div id="content-payment" class="tab-content hidden">
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="admin-card p-8 md:p-10">
                            <div class="flex items-center gap-4 mb-8">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-[#0B4D73]">
                                    <i class="fas fa-plug text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900">Paystack Integration</h3>
                                    <p class="text-sm text-slate-500">Configure your payment gateway credentials</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Public
                                        Key</label>
                                    <input type="text" name="paystack_public_key"
                                        value="{{ $settings['paystack_public_key'] ?? '' }}" placeholder="pk_live_..."
                                        class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Secret
                                        Key</label>
                                    <input type="password" name="paystack_secret_key"
                                        value="{{ $settings['paystack_secret_key'] ?? '' }}" placeholder="sk_live_..."
                                        class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Merchant
                                        Email</label>
                                    <input type="email" name="paystack_merchant_email"
                                        value="{{ $settings['paystack_merchant_email'] ?? '' }}"
                                        placeholder="billing@yourhub.com" class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Payment
                                        URL</label>
                                    <input type="url" name="paystack_payment_url"
                                        value="{{ $settings['paystack_payment_url'] ?? 'https://api.paystack.co' }}"
                                        class="w-full">
                                </div>
                            </div>

                            <div class="mt-10 p-6 bg-slate-50 rounded-3xl border border-slate-100 flex items-start gap-4">
                                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                                <div class="text-xs text-slate-600 leading-relaxed">
                                    <strong>Safety First:</strong> These keys are used to process live transactions. Ensure
                                    you never share your Secret Key with anyone. You can find these in your Paystack
                                    Dashboard under <span class="text-blue-600 font-bold italic">Settings > API Keys &
                                        Webhooks</span>.
                                </div>
                            </div>

                            <!-- Webhook URL -->
                            <div class="mt-6 p-6 bg-emerald-50 rounded-3xl border border-emerald-100">
                                <div class="flex items-start gap-4">
                                    <i class="fas fa-link text-emerald-500 mt-1"></i>
                                    <div class="flex-1">
                                        <p class="text-xs text-slate-600 mb-3">
                                            <strong>Webhook URL:</strong> Add this URL to your Paystack Dashboard under
                                            <span class="text-emerald-600 font-bold italic">Settings > API Keys & Webhooks >
                                                Webhook URL</span>
                                        </p>
                                        <div class="flex items-center gap-2">
                                            <code
                                                class="flex-1 px-4 py-3 bg-white rounded-xl text-xs font-mono text-slate-700 border border-slate-200 truncate">
                                                                    {{ url('/webhooks/paystack') }}
                                                                </code>
                                            <button type="button" onclick="copyWebhookUrl()"
                                                class="px-4 py-3 bg-emerald-500 text-white rounded-xl text-xs font-bold hover:bg-emerald-600 transition-colors">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                class="px-8 py-4 bg-[#0B4D73] text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-slate-900 transition-all shadow-xl shadow-blue-900/10">
                                Save Configurations
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Premium Settings Form -->
                <div id="content-premium" class="tab-content hidden">
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="admin-card p-8 md:p-10">
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-12 h-12 rounded-2xl bg-yellow-50 flex items-center justify-center text-yellow-600">
                                    <i class="fas fa-crown text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900">Premium Subscription Pricing</h3>
                                    <p class="text-sm text-slate-500">Setup billing cycles for core program access</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Currency</label>
                                    <select name="premium_currency" class="w-full">
                                        <option value="NGN" {{ ($settings['premium_currency'] ?? 'NGN') === 'NGN' ? 'selected' : '' }}>Nigerian Naira (₦)</option>
                                        <option value="USD" {{ ($settings['premium_currency'] ?? 'NGN') === 'USD' ? 'selected' : '' }}>US Dollar ($)</option>
                                        <option value="GBP" {{ ($settings['premium_currency'] ?? 'NGN') === 'GBP' ? 'selected' : '' }}>British Pound (£)</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Trial Duration (Days)</label>
                                    <input type="number" name="trial_duration_days" value="{{ $settings['trial_duration_days'] ?? '14' }}" placeholder="14" class="w-full">
                                </div>
                            </div>

                            <h4 class="text-sm font-bold text-slate-900 mb-4 border-b pb-2">Pricing Tiers</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Monthly Price</label>
                                    <input type="number" step="0.01" name="premium_price_monthly" value="{{ $settings['premium_price_monthly'] ?? '5000' }}" placeholder="5000" class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Termly (4 Months)</label>
                                    <input type="number" step="0.01" name="premium_price_termly" value="{{ $settings['premium_price_termly'] ?? '18000' }}" placeholder="18000" class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Annually</label>
                                    <input type="number" step="0.01" name="premium_price_annually" value="{{ $settings['premium_price_annually'] ?? '50000' }}" placeholder="50000" class="w-full">
                                </div>
                            </div>

                            <div class="mt-8 p-6 bg-yellow-50 rounded-3xl border border-yellow-200 flex items-start gap-4">
                                <i class="fas fa-info-circle text-yellow-600 mt-1"></i>
                                <div class="text-xs text-slate-700 leading-relaxed">
                                    <strong>How Auto-Billing Works:</strong> These prices dictate the checkout amount. Both Parents and Children (above 16) can subscribe to bypass the gate on "Rolling" auto-assigned programmes. Setup zero (0) to make it conditionally free.
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-8 py-4 bg-yellow-500 text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-yellow-600 transition-all shadow-xl shadow-yellow-500/20">
                                Save Subscriptions
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Contact & Social Settings -->
                <div id="content-contact" class="tab-content hidden">
                    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="admin-card p-8 md:p-10">
                            <div class="flex items-center gap-4 mb-8">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-600">
                                    <i class="fas fa-address-book text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900">Contact Information</h3>
                                    <p class="text-sm text-slate-500">Manage your organization's public contact details</p>
                                </div>
                            </div>

                            <!-- Basic Contact -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Contact
                                        Email</label>
                                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}"
                                        placeholder="hello@YPMMH.org" class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Phone
                                        Number</label>
                                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}"
                                        placeholder="+234 800 123 4567" class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">WhatsApp
                                        Number</label>
                                    <input type="text" name="contact_whatsapp"
                                        value="{{ $settings['contact_whatsapp'] ?? '' }}" placeholder="+234 800 123 4567"
                                        class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Physical
                                        Address</label>
                                    <input type="text" name="contact_address"
                                        value="{{ $settings['contact_address'] ?? '' }}" placeholder="Lagos, Nigeria"
                                        class="w-full">
                                </div>
                            </div>

                            <!-- Office Hours -->
                            <h4 class="text-sm font-bold text-slate-900 mb-4 border-b pb-2">Office Hours</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Mon -
                                        Fri</label>
                                    <input type="text" name="office_hours_weekdays"
                                        value="{{ $settings['office_hours_weekdays'] ?? '9AM - 5PM' }}" class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400">Saturday</label>
                                    <input type="text" name="office_hours_saturday"
                                        value="{{ $settings['office_hours_saturday'] ?? '10AM - 2PM' }}" class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-[10px] font-black uppercase tracking-widest text-slate-400">Sunday</label>
                                    <input type="text" name="office_hours_sunday"
                                        value="{{ $settings['office_hours_sunday'] ?? 'Closed' }}" class="w-full">
                                </div>
                            </div>

                            <!-- Social Media -->
                            <h4 class="text-sm font-bold text-slate-900 mb-4 border-b pb-2">Social Media Links</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Facebook
                                        URL</label>
                                    <input type="url" name="social_facebook"
                                        value="{{ $settings['social_facebook'] ?? '' }}"
                                        placeholder="https://facebook.com/..." class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Instagram
                                        URL</label>
                                    <input type="url" name="social_instagram"
                                        value="{{ $settings['social_instagram'] ?? '' }}"
                                        placeholder="https://instagram.com/..." class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Twitter
                                        (X) URL</label>
                                    <input type="url" name="social_twitter" value="{{ $settings['social_twitter'] ?? '' }}"
                                        placeholder="https://twitter.com/..." class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">YouTube
                                        URL</label>
                                    <input type="url" name="social_youtube" value="{{ $settings['social_youtube'] ?? '' }}"
                                        placeholder="https://youtube.com/..." class="w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">LinkedIn
                                        URL</label>
                                    <input type="url" name="social_linkedin"
                                        value="{{ $settings['social_linkedin'] ?? '' }}"
                                        placeholder="https://linkedin.com/..." class="w-full">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit"
                                class="px-8 py-4 bg-[#0B4D73] text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:bg-slate-900 transition-all shadow-xl shadow-blue-900/10">
                                Save Contact Info
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Roles & Permissions (Coming Soon) -->
                <div id="content-roles" class="tab-content hidden">
                    <div class="admin-card p-12 text-center space-y-6 relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/5 rounded-full -mr-32 -mt-32"></div>

                        <div
                            class="w-20 h-20 bg-amber-50 rounded-[2rem] flex items-center justify-center text-amber-500 text-3xl mx-auto shadow-lg shadow-amber-500/10">
                            <i class="fas fa-rocket"></i>
                        </div>

                        <div class="max-w-md mx-auto">
                            <h3 class="text-2xl font-black text-slate-900 mb-2">Coming Soon</h3>
                            <p class="text-slate-500 leading-relaxed">We are building a robust Role-Based Access Control
                                (RBAC) system. Soon you'll be able to create custom roles and define granular
                                permissions
                                for your staff and mentors.</p>
                        </div>

                        <div class="flex justify-center gap-2">
                            <span
                                class="px-3 py-1 bg-amber-100 text-amber-700 text-[9px] font-black uppercase rounded-full">Development
                                In Progress</span>
                        </div>
                    </div>
                </div>

                <!-- General Settings (Placeholders) -->
                <div id="content-general" class="tab-content hidden">
                    <div class="admin-card p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div
                                class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                <i class="fas fa-cog text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900">General Information</h3>
                                <p class="text-sm text-slate-500">Basic application identity and metadata</p>
                            </div>
                        </div>

                        <div class="space-y-6 opacity-40 select-none">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Application
                                    Name</label>
                                <input type="text" value="YPMMH Mentoring Hub" disabled class="bg-slate-50">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Support
                                    Email</label>
                                <input type="email" value="support@YPMMH.org" disabled class="bg-slate-50">
                            </div>
                            <div class="p-6 border-2 border-dashed border-slate-200 rounded-3xl text-center">
                                <p class="text-xs font-bold text-slate-400">More general settings will be unlocked in
                                    v2.0
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            // Toggle Buttons
            document.querySelectorAll('.settings-tab').forEach(btn => {
                btn.classList.remove('active');
                btn.classList.add('text-slate-500');
            });
            document.getElementById('tab-' + tabId).classList.add('active');
            document.getElementById('tab-' + tabId).classList.remove('text-slate-500');

            // Toggle Content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById('content-' + tabId).classList.remove('hidden');
        }

        // File upload handlers
        document.getElementById('logoInput').addEventListener('change', function (e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.getElementById('logoFileName').textContent = '✓ ' + fileName;
                document.getElementById('logoFileName').classList.remove('hidden');
            }
        });

        document.getElementById('faviconInput').addEventListener('change', function (e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                document.getElementById('faviconFileName').textContent = '✓ ' + fileName;
                document.getElementById('faviconFileName').classList.remove('hidden');
            }
        });

        // Drag and drop
        ['logoDropZone', 'faviconDropZone'].forEach(zoneId => {
            const zone = document.getElementById(zoneId);
            const inputId = zoneId === 'logoDropZone' ? 'logoInput' : 'faviconInput';

            zone.addEventListener('dragover', (e) => {
                e.preventDefault();
                zone.classList.add('dragover');
            });

            zone.addEventListener('dragleave', () => {
                zone.classList.remove('dragover');
            });

            zone.addEventListener('drop', (e) => {
                e.preventDefault();
                zone.classList.remove('dragover');
                const input = document.getElementById(inputId);
                input.files = e.dataTransfer.files;
                input.dispatchEvent(new Event('change'));
            });
        });

        // Copy Webhook URL
        function copyWebhookUrl() {
            const url = "{{ url('/webhooks/paystack') }}";
            navigator.clipboard.writeText(url).then(() => {
                showToast('Webhook URL copied to clipboard!', 'success');
            }).catch(() => {
                showToast('Failed to copy URL. Please copy manually.', 'error');
            });
        }
    </script>
@endsection