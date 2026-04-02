# 🎨 YPMMH Admin Dashboard - Glassmorphism UI

A production-ready Laravel Blade admin dashboard template with modern glassmorphism design, built with **NO NPM, NO Vite, NO Webpack** - only CDN assets and vanilla JavaScript.

![Dashboard Preview](https://img.shields.io/badge/Laravel-Blade-red?style=for-the-badge&logo=laravel)
![No NPM](https://img.shields.io/badge/NO-NPM-success?style=for-the-badge)
![Vanilla JS](https://img.shields.io/badge/Vanilla-JavaScript-yellow?style=for-the-badge&logo=javascript)

## ✨ Features

### 🎯 Core Features
- ✅ **Pure Laravel Blade** templates
- ✅ **NO NPM dependencies** - all assets via CDN
- ✅ **Glassmorphism UI** with frosted glass effects
- ✅ **Dark/Light theme toggle** (localStorage persistence)
- ✅ **Fully responsive** (mobile, tablet, desktop)
- ✅ **Vanilla JavaScript** only (no frameworks)

### 🎨 UI Components
- ✅ Fixed glass sidebar with collapsible menu sections
- ✅ Top navigation with search, notifications, user dropdown
- ✅ Statistics cards with gradients
- ✅ Interactive data table (search, sort, pagination)
- ✅ Modal system
- ✅ Toast notifications
- ✅ Skeleton loaders
- ✅ Badge components
- ✅ Progress bars

### ♿ Accessibility
- ✅ ARIA labels and roles
- ✅ Keyboard navigation (Alt+S for sidebar, Alt+T for theme)
- ✅ Focus management
- ✅ Screen reader support

### 🎭 Animations
- ✅ Page transitions
- ✅ Smooth hover effects
- ✅ Slide-in animations
- ✅ Micro-interactions

## 📁 File Structure

```
my_YPMMH/
├── app/
│   └── Http/
│       └── Controllers/
│           └── DashboardController.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── dashboard.blade.php      # Main layout with all CSS/JS
│       ├── partials/
│       │   ├── sidebar.blade.php        # Collapsible sidebar
│       │   ├── navbar.blade.php         # Top navigation
│       │   └── footer.blade.php         # Footer
│       └── pages/
│           └── dashboard.blade.php      # Dashboard page
└── routes/
    └── web.php                          # All routes
```

## 🚀 Installation

### 1. Prerequisites
- PHP 8.1+
- Composer
- Laravel 10+
- Web server (Apache/Nginx/XAMPP)

### 2. Setup Steps

```bash
# 1. Navigate to your Laravel project
cd c:\xampp\htdocs\my_YPMMH

# 2. Install Laravel dependencies (if not already done)
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Start the development server
php artisan serve
```

### 3. Access the Dashboard

Open your browser and navigate to:
```
http://localhost:8000/dashboard
```

## 🎨 Customization

### Change Primary Color

Edit `resources/views/layouts/dashboard.blade.php` and modify the CSS variables:

```css
:root {
    /* Change these values */
    --primary-hue: 260;        /* 0-360 (color wheel) */
    --primary-sat: 80%;        /* 0-100% (saturation) */
    --primary-light: 60%;      /* 0-100% (lightness) */
}
```

**Color Examples:**
- **Purple (default):** `hue: 260`
- **Blue:** `hue: 210`
- **Green:** `hue: 140`
- **Orange:** `hue: 30`
- **Pink:** `hue: 330`

### Customize for Your Madrasah Brand

1. **Update Logo:**
   - Edit `resources/views/partials/sidebar.blade.php`
   - Replace the icon in the logo section

2. **Change Brand Name:**
   ```blade
   <h1 class="text-xl font-bold">Your Madrasah Name</h1>
   ```

3. **Update Footer:**
   - Edit `resources/views/partials/footer.blade.php`

### Add New Menu Items

Edit `resources/views/partials/sidebar.blade.php`:

```blade
<a href="/your-route" 
   class="menu-item flex items-center gap-3 px-4 py-3 rounded-lg mb-2 glass-hover"
   style="color: var(--text-primary);">
    <i class="fas fa-your-icon w-5"></i>
    <span class="font-medium">Your Menu Item</span>
</a>
```

## 🎯 Usage Guide

### Creating New Pages

1. **Create a new Blade file:**
   ```bash
   # Create in resources/views/pages/
   touch resources/views/pages/your-page.blade.php
   ```

2. **Use the dashboard layout:**
   ```blade
   @extends('layouts.dashboard')

   @section('title', 'Your Page Title')

   @section('content')
       <!-- Your content here -->
   @endsection
   ```

3. **Add route in `routes/web.php`:**
   ```php
   Route::get('/your-route', function () {
       return view('pages.your-page');
   })->name('your.route');
   ```

### Using Components

#### Toast Notifications
```javascript
// Success
showToast('Operation successful!', 'success');

// Error
showToast('Something went wrong!', 'error');

// Warning
showToast('Please check your input!', 'warning');

// Info
showToast('Here is some information', 'info');
```

#### Modal
```html
<!-- Modal Trigger -->
<button onclick="openModal('myModal')">Open Modal</button>

<!-- Modal Structure -->
<div id="myModal" class="modal-overlay fixed inset-0 z-[9999] items-center justify-center hidden">
    <div class="glass rounded-2xl max-w-2xl w-full mx-4 p-8">
        <h2>Modal Title</h2>
        <button onclick="closeModal('myModal')">Close</button>
    </div>
</div>
```

#### Badges
```html
<span class="badge badge-success">Active</span>
<span class="badge badge-warning">Pending</span>
<span class="badge badge-danger">Inactive</span>
<span class="badge badge-info">New</span>
```

#### Buttons
```html
<button class="btn btn-primary">Primary Button</button>
<button class="btn btn-secondary">Secondary Button</button>
```

## ⌨️ Keyboard Shortcuts

- **Alt + S** - Toggle sidebar
- **Alt + T** - Toggle theme (dark/light)
- **ESC** - Close open modals

## 🎨 Theme System

The dashboard includes a built-in dark/light theme toggle:

- **Default:** Light theme
- **Toggle:** Click the moon/sun icon in the navbar
- **Persistence:** Theme preference saved in localStorage
- **Auto-apply:** Theme loads automatically on page refresh

## 📊 Data Table Features

The dashboard includes a fully functional data table with:

1. **Search:** Real-time filtering
2. **Sort:** Click column headers to sort
3. **Pagination:** Navigate through pages
4. **Actions:** View, edit, delete buttons

### Customize Table Data

Edit `resources/views/pages/dashboard.blade.php` and modify the table rows in the `<tbody>` section.

## 🔧 Advanced Customization

### Add Submenu Items

```blade
<div class="mb-2">
    <button onclick="toggleSubmenu('mySubmenu')" 
            class="menu-item flex items-center justify-between w-full px-4 py-3 rounded-lg glass-hover">
        <div class="flex items-center gap-3">
            <i class="fas fa-icon w-5"></i>
            <span class="font-medium">Menu Item</span>
        </div>
        <i class="fas fa-chevron-down text-xs transition-transform" id="mySubmenuIcon"></i>
    </button>
    <div id="mySubmenu" class="ml-8 mt-2 hidden">
        <a href="/sub-item" class="block px-4 py-2 rounded-lg mb-1 glass-hover">
            Sub Item
        </a>
    </div>
</div>
```

### Custom Animations

Add custom animations in the `<style>` section of `dashboard.blade.php`:

```css
@keyframes yourAnimation {
    from { /* start state */ }
    to { /* end state */ }
}

.your-class {
    animation: yourAnimation 0.5s ease-out;
}
```

## 🌐 CDN Assets Used

- **Tailwind CSS:** `https://cdn.tailwindcss.com`
- **Font Awesome:** `https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css`
- **Google Fonts (Inter):** `https://fonts.googleapis.com/css2?family=Inter`
- **UI Avatars:** `https://ui-avatars.com/api/` (for demo avatars)

## 📱 Responsive Breakpoints

- **Mobile:** < 768px
- **Tablet:** 768px - 1024px
- **Desktop:** > 1024px

## 🎯 Browser Support

- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ✅ Safari (latest)
- ✅ Edge (latest)

**Note:** Glassmorphism effects require modern browsers with `backdrop-filter` support.

## 🐛 Troubleshooting

### Sidebar not showing
- Check that `sidebar.blade.php` is in `resources/views/partials/`
- Ensure the `@include('partials.sidebar')` is in the layout

### Theme toggle not working
- Check browser console for JavaScript errors
- Ensure localStorage is enabled in your browser

### Styles not applying
- Clear browser cache
- Check that CDN links are accessible
- Verify internet connection

## 📝 License

This template is open-source and free to use for your madrasah or educational institution.

## 🤝 Contributing

Feel free to customize and extend this template for your needs!

## 📧 Support

For issues or questions, please check the code comments or Laravel documentation.

---

**Built with ❤️ for YPMMH Madrasah**

*No NPM, No Build Tools, Just Pure Laravel Blade Magic!* ✨
