<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CacheController as AdminCacheController;
use App\Http\Controllers\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Admin\ProgramContentController as AdminProgramContentController;
use App\Http\Controllers\Admin\CohortController as AdminCohortController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\AdminMailController;
use App\Http\Controllers\Admin\PremiumController as AdminPremiumController;
use App\Http\Controllers\Mentor\MentorDashboardController;
use App\Http\Controllers\Mentor\MentorProgramController;
use App\Http\Controllers\Mentor\LessonController as MentorLessonController;
use App\Http\Controllers\Parent\ParentDashboardController;
use App\Http\Controllers\Child\ChildDashboardController;
use App\Http\Controllers\PublicController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'welcome'])->name('home');

// PWA Offline Fallback Page
Route::get('/offline', function () {
    return view('offline');
})->name('offline');

Route::get('/sitemap.xml', function () {
    $content = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>' . url('/') . '</loc>
        <lastmod>' . date('Y-m-d') . '</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>' . route('about') . '</loc>
        <lastmod>' . date('Y-m-d') . '</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc>' . route('programs.explore') . '</loc>
        <lastmod>' . date('Y-m-d') . '</lastmod>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>' . route('gallery') . '</loc>
        <lastmod>' . date('Y-m-d') . '</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>' . route('blog') . '</loc>
        <lastmod>' . date('Y-m-d') . '</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc>' . route('login') . '</loc>
        <lastmod>' . date('Y-m-d') . '</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>' . route('register') . '</loc>
        <lastmod>' . date('Y-m-d') . '</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
</urlset>';
    return response($content, 200, [
        'Content-Type' => 'application/xml'
    ]);
});

Route::get('/programs/explore', [PublicController::class, 'explore'])->name('programs.explore');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/waitlist', [\App\Http\Controllers\WaitlistController::class, 'index'])->name('waitlist.index');
Route::post('/waitlist', [\App\Http\Controllers\WaitlistController::class, 'store'])->name('waitlist.store');

Route::get('/blog', [PublicController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [PublicController::class, 'blogShow'])->name('blog.show');

Route::get('/enroll', function () {
    return view('enroll');
})->name('enroll');

Route::get('/gallery', [PublicController::class, 'gallery'])->name('gallery');

Route::get('/coming-soon', function () {
    return view('coming-soon');
})->name('coming-soon');

// Smart role-based dashboard redirect
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('Admin')) {
        return redirect()->route('admin.dashboard');
    }

    if ($user->hasRole('Mentor')) {
        return redirect()->route('mentor.dashboard');
    }

    if ($user->hasRole('Parent')) {
        return redirect()->route('parent.dashboard');
    }

    if ($user->hasRole('Child')) {
        return redirect()->route('child.dashboard');
    }

    abort(403);
})->middleware(['auth', 'ensure_active'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/password/change', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'showChangeForm'])->name('password.change.notice');
    Route::post('/password/change', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'updatePassword'])->name('password.change.update');
});

Route::middleware(['auth', 'ensure_active', 'force_password_change'])->group(function () {

    // Shared Catalog Route
    Route::get('programs-catalog', [ParentDashboardController::class, 'programs'])->name('parent.programs.catalog');

    // Notifications Polling — only respond to XHR / JSON accept requests
    Route::get('/api/notifications/unread', function () {
        $request = app(\Illuminate\Http\Request::class);

        // Prevent direct browser navigation showing raw JSON
        if (!$request->wantsJson() && !$request->ajax()) {
            return redirect()->route('dashboard');
        }

        $user = auth()->user();
        $latest = $user->unreadNotifications->first();

        return response()->json([
            'unread_count' => $user->unreadNotifications->count(),
            'latest' => $latest ? [
                'id'      => $latest->id,
                'data'    => [
                    'message' => $latest->data['message'] ?? 'You have a new notification.',
                    'type'    => $latest->data['type']   ?? 'info',
                    'icon'    => $latest->data['icon']   ?? 'fas fa-bell',
                ],
                'created_at' => $latest->created_at->diffForHumans(),
            ] : null,
        ]);
    })->name('api.notifications.unread');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reports
    Route::post('/reports', [\App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');

    // Admin
    Route::middleware(['role:Admin'])->prefix('admin')->name('admin.')->group(function () {
        // Debug Mail Config
        Route::get('debug/mail-config', function () {
            $mailMailer = env('MAIL_MAILER') ?: config('mail.default');
            $mailFrom = config('mail.from.address') ?: env('MAIL_FROM_ADDRESS');
            $isConfigCached = \Illuminate\Support\Facades\File::exists(base_path('bootstrap/cache/config.php'));
            
            return response()->json([
                'env_MAIL_MAILER' => env('MAIL_MAILER') ?: 'NOT SET IN .env',
                'config_mail_default' => config('mail.default'),
                'resolved_mailer' => $mailMailer,
                'MAIL_HOST' => env('MAIL_HOST') ?: 'NOT SET',
                'MAIL_PORT' => env('MAIL_PORT') ?: 'NOT SET',
                'MAIL_USERNAME' => env('MAIL_USERNAME') ? substr(env('MAIL_USERNAME'), 0, 5) . '***' : 'NOT SET',
                'MAIL_FROM_ADDRESS' => $mailFrom ?: 'NOT SET',
                'MAIL_FROM_NAME' => config('mail.from.name') ?: env('MAIL_FROM_NAME') ?: 'NOT SET',
                'QUEUE_CONNECTION' => config('queue.default'),
                'config_cached' => $isConfigCached,
                'status' => $mailMailer === 'log' ? '❌ PROBLEM: Mail driver is LOG!' : ($mailMailer === 'smtp' ? '✓ Using SMTP' : '⚠️ Driver: ' . $mailMailer),
                'instructions' => [
                    'if_config_cached' => 'Run: php artisan config:clear && php artisan config:cache',
                    'test_mail' => 'Visit /admin/mail to send a test email',
                    'check_logs' => 'Check storage/logs/laravel.log for errors',
                ],
            ]);
        })->name('debug.mail');
        
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Cache Management
        Route::post('cache/clear', [AdminCacheController::class, 'clear'])->name('cache.clear');
        
        Route::resource('programs', AdminProgramController::class);
        Route::post('programs/{program}/contents', [AdminProgramContentController::class, 'store'])->name('programs.contents.store');
        Route::get('programs/{program}/contents/{content}/edit', [AdminProgramContentController::class, 'edit'])->name('programs.contents.edit');
        Route::patch('programs/{program}/contents/{content}', [AdminProgramContentController::class, 'update'])->name('programs.contents.update');
        Route::delete('programs/{program}/contents/{content}', [AdminProgramContentController::class, 'destroy'])->name('programs.contents.destroy');
        Route::delete('programs/{program}/children/{child}', [AdminProgramController::class, 'unassignChild'])->name('programs.children.unassign');

        // Blogs
        Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class);

        // Gallery
        Route::resource('gallery', \App\Http\Controllers\Admin\GalleryController::class);

        Route::resource('users', AdminUserController::class);
        Route::get('users/export/csv', [AdminUserController::class, 'export'])->name('users.export');
        Route::get('users/template/csv', [AdminUserController::class, 'downloadTemplate'])->name('users.template');
        Route::post('users/import/csv', [AdminUserController::class, 'import'])->name('users.import');
        Route::get('mentors', [AdminUserController::class, 'mentors'])->name('mentors.index');
        Route::get('mentors/{user}', [AdminUserController::class, 'mentorShow'])->name('mentors.show');
        Route::patch('mentors/{user}/assign-program', [AdminUserController::class, 'assignProgram'])->name('mentors.assign-program');
        Route::patch('mentors/{user}/unassign-program/{program}', [AdminUserController::class, 'unassignProgram'])->name('mentors.unassign-program');
        Route::get('parents', [AdminUserController::class, 'parents'])->name('parents.index');
        Route::get('children', [AdminUserController::class, 'children'])->name('children.index');
        Route::get('children/{user}', [AdminUserController::class, 'childShow'])->name('children.show');
        Route::post('children/{user}/enroll', [AdminUserController::class, 'enrollChild'])->name('children.enroll');
        Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'show'])->name('reports.show');
        Route::patch('reports/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'update'])->name('reports.update');
        Route::delete('reports/{report}', [\App\Http\Controllers\Admin\ReportController::class, 'destroy'])->name('reports.destroy');
        Route::get('payments', [\App\Http\Controllers\Admin\AdminPaymentController::class, 'index'])->name('payments.index');
        
        // Premium Subscriptions
        Route::get('premiums', [AdminPremiumController::class, 'index'])->name('premiums.index');
        Route::post('premiums/{user}/extend', [AdminPremiumController::class, 'extend'])->name('premiums.extend');
        Route::post('premiums/{user}/reactivate', [AdminPremiumController::class, 'reactivate'])->name('premiums.reactivate');
        Route::delete('premiums/{user}/cancel', [AdminPremiumController::class, 'cancel'])->name('premiums.cancel');
        
        Route::get('coming-soon', [AdminDashboardController::class, 'comingSoon'])->name('coming-soon');

        // Events
        Route::resource('events', \App\Http\Controllers\Admin\EventController::class);

        // Notifications
        Route::get('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::get('notifications/{id}/redirect', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsReadAndRedirect'])->name('notifications.redirect');
        Route::post('notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::delete('notifications/{id}', [\App\Http\Controllers\Admin\NotificationController::class, 'destroy'])->name('notifications.destroy');

        // Community Hub
        Route::get('communities', [\App\Http\Controllers\ProgramChatController::class, 'communityHub'])->name('communities.index');
        Route::get('communities/{program}', [\App\Http\Controllers\ProgramChatController::class, 'fullScreenChat'])->name('communities.show');

        // Program Community Chat
        Route::get('programs/{program}/chat/messages', [\App\Http\Controllers\ProgramChatController::class, 'fetchMessages'])->name('programs.chat.messages');
        Route::post('programs/{program}/chat/messages', [\App\Http\Controllers\ProgramChatController::class, 'sendMessage'])->middleware(\App\Http\Middleware\EnsureNotChatSuspended::class)->name('programs.chat.send');
        Route::delete('chat/messages/{message}', [\App\Http\Controllers\ProgramChatController::class, 'deleteMessage'])->name('chat.messages.destroy');
        Route::post('programs/{program}/chat/members/{user}/toggle-suspension', [\App\Http\Controllers\ProgramChatController::class, 'toggleSuspension'])->name('programs.chat.members.toggle-suspension');

        // Direct Mail to Students & Parents
        Route::get('mail', [AdminMailController::class, 'index'])->name('mail.index');
        Route::post('mail/send', [AdminMailController::class, 'send'])->name('mail.send');
        Route::get('mail/get-recipients', [AdminMailController::class, 'getRecipients'])->name('mail.get-recipients');

        // Settings
        Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings/branding', [\App\Http\Controllers\Admin\SettingsController::class, 'updateBranding'])->name('settings.branding.update');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        Route::delete('settings/logo', [\App\Http\Controllers\Admin\SettingsController::class, 'removeLogo'])->name('settings.logo.remove');
        Route::delete('settings/favicon', [\App\Http\Controllers\Admin\SettingsController::class, 'removeFavicon'])->name('settings.favicon.remove');
    });

    // Mentor
    Route::middleware(['role:Mentor'])->prefix('mentor')->name('mentor.')->group(function () {
        Route::get('dashboard', [MentorDashboardController::class, 'index'])->name('dashboard');
        Route::get('children/{child}', [MentorDashboardController::class, 'showChild'])->name('children.show');
        Route::post('observations/{child}', [MentorDashboardController::class, 'storeObservation'])->name('observations.store');

        // Blogs
        Route::resource('blogs', \App\Http\Controllers\Mentor\BlogController::class);

        Route::resource('lessons', MentorLessonController::class)->only(['create', 'store']);

        // Management
        Route::get('programs', [MentorProgramController::class, 'index'])->name('programs.index');
        Route::get('programs/{program}', [MentorProgramController::class, 'show'])->name('programs.show');

        // Community Hub
        Route::get('communities', [\App\Http\Controllers\ProgramChatController::class, 'communityHub'])->name('communities.index');
        Route::get('communities/{program}', [\App\Http\Controllers\ProgramChatController::class, 'fullScreenChat'])->name('communities.show');

        // Program Content Management
        Route::get('programs/{program}/contents/create', [MentorProgramController::class, 'createContent'])->name('programs.contents.create');
        Route::post('programs/{program}/contents', [MentorProgramController::class, 'storeContent'])->name('programs.contents.store');
        Route::get('programs/{program}/contents/{content}/edit', [MentorProgramController::class, 'editContent'])->name('programs.contents.edit');
        Route::patch('programs/{program}/contents/{content}', [MentorProgramController::class, 'updateContent'])->name('programs.contents.update');
        Route::delete('programs/{program}/contents/{content}', [MentorProgramController::class, 'destroyContent'])->name('programs.contents.destroy');

        // Program Community Chat
        Route::get('programs/{program}/chat/messages', [\App\Http\Controllers\ProgramChatController::class, 'fetchMessages'])->name('programs.chat.messages');
        Route::post('programs/{program}/chat/messages', [\App\Http\Controllers\ProgramChatController::class, 'sendMessage'])->middleware(\App\Http\Middleware\EnsureNotChatSuspended::class)->name('programs.chat.send');
        Route::delete('chat/messages/{message}', [\App\Http\Controllers\ProgramChatController::class, 'deleteMessage'])->name('chat.messages.destroy');
        Route::post('programs/{program}/chat/members/{user}/toggle-suspension', [\App\Http\Controllers\ProgramChatController::class, 'toggleSuspension'])->name('programs.chat.members.toggle-suspension');

        // Events
        Route::get('events', [\App\Http\Controllers\Mentor\EventController::class, 'index'])->name('events.index');
        Route::get('events/{event}', [\App\Http\Controllers\Mentor\EventController::class, 'show'])->name('events.show');

        // Notifications
        Route::get('notifications', [\App\Http\Controllers\Mentor\NotificationController::class, 'index'])->name('notifications.index');
        Route::post('notifications/{id}/read', [\App\Http\Controllers\Mentor\NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::get('notifications/{id}/redirect', [\App\Http\Controllers\Mentor\NotificationController::class, 'markAsReadAndRedirect'])->name('notifications.redirect');
        Route::post('notifications/read-all', [\App\Http\Controllers\Mentor\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
        Route::delete('notifications/{id}', [\App\Http\Controllers\Mentor\NotificationController::class, 'destroy'])->name('notifications.destroy');
    });

    // Parent
    Route::middleware(['role:Parent'])->prefix('parent')->name('parent.')->group(function () {
        Route::get('dashboard', [ParentDashboardController::class, 'index'])->name('dashboard');
        Route::get('children/create', [ParentDashboardController::class, 'createChild'])->name('children.create');
        Route::post('children', [ParentDashboardController::class, 'storeChild'])->name('children.store');
        Route::get('children/{child}', [ParentDashboardController::class, 'showChild'])->name('children.show');
        Route::get('children/{child}/edit', [ParentDashboardController::class, 'editChild'])->name('children.edit');
        Route::post('children/{child}/report', [ParentDashboardController::class, 'reportProfileIssue'])->name('children.report');
        Route::get('reports/{child}', [ParentDashboardController::class, 'downloadReport'])->name('reports.download');
        Route::get('observations', [ParentDashboardController::class, 'observations'])->name('observations');
        Route::get('events', [\App\Http\Controllers\Parent\ParentEventController::class, 'index'])->name('events.index');
        Route::get('notifications', [ParentDashboardController::class, 'notifications'])->name('notifications');
        Route::get('notifications/{id}/redirect', function ($id) {
            $user = auth()->user();
            $notification = $user->notifications()->where('id', $id)->first();
            if (!$notification) {
                return redirect()->route('parent.notifications');
            }
            $notification->markAsRead();
            $type = $notification->data['type'] ?? null;
            $url = match ($type) {
                'new_program_available', 'program_update', 'enrollment_request', 'lesson_completion' => route('parent.programs.catalog'),
                'blog_published', 'blog_post' => isset($notification->data['slug'])
                    ? route('blog.show', $notification->data['slug'])
                    : route('blog'),
                'birthday' => isset($notification->data['child_id'])
                    ? route('parent.children.show', $notification->data['child_id'])
                    : route('parent.notifications'),
                default    => route('parent.notifications'),
            };
            return redirect($url);
        })->name('notifications.redirect');
        Route::post('notifications/{id}/read', function ($id) {
            auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
            return back();
        })->name('notifications.read');
        Route::post('notifications/read-all', function () {
            auth()->user()->unreadNotifications->markAsRead();
            return back()->with('success', 'All notifications marked as read.');
        })->name('notifications.read-all');
        Route::post('enrollments/{enrollment}/toggle', [ParentDashboardController::class, 'toggleEnrollment'])->name('enrollments.toggle');
        Route::get('programs/{program}/pass/{child}', [ParentDashboardController::class, 'printPass'])->name('programs.pass');
    });

    // Child
    Route::middleware(['role:Child'])->prefix('child')->name('child.')->group(function () {
        Route::get('dashboard', [ChildDashboardController::class, 'index'])->name('dashboard');
        Route::get('my-profile', [ChildDashboardController::class, 'profile'])->name('profile');
        Route::get('lessons/{lesson}', [ChildDashboardController::class, 'showLesson'])->name('lessons.show');
        Route::post('reflections/{lesson}', [ChildDashboardController::class, 'submitReflection'])->name('reflections.submit');

        // Program Community Chat for Child
        Route::get('programs', [ChildDashboardController::class, 'programs'])->name('programs.index');
        Route::get('programs/{program}', [ChildDashboardController::class, 'showProgram'])->name('programs.show');
        Route::post('reflections/{lesson}', [ChildDashboardController::class, 'submitReflection'])->name('reflections.submit');
        Route::post('lessons/{lesson}/complete', [ChildDashboardController::class, 'completeLesson'])->name('lessons.complete');
        Route::get('programs/{program}/chat/messages', [\App\Http\Controllers\ProgramChatController::class, 'fetchMessages'])->name('programs.chat.messages');
        Route::post('programs/{program}/chat/messages', [\App\Http\Controllers\ProgramChatController::class, 'sendMessage'])->middleware(\App\Http\Middleware\EnsureNotChatSuspended::class)->name('programs.chat.send');
        Route::delete('chat/messages/{message}', [\App\Http\Controllers\ProgramChatController::class, 'deleteMessage'])->name('chat.messages.destroy');

        // Achievements
        Route::get('achievements', [ChildDashboardController::class, 'achievements'])->name('achievements');

        // Community Hub
        Route::get('communities', [ChildDashboardController::class, 'communities'])->name('communities.index');
        Route::get('communities/{program}', [\App\Http\Controllers\ProgramChatController::class, 'fullScreenChat'])->name('communities.show');

        // Events
        Route::get('events', [\App\Http\Controllers\Child\ChildEventController::class, 'index'])->name('events.index');
        Route::get('events/{event}', [\App\Http\Controllers\Child\ChildEventController::class, 'show'])->name('events.show');

        // Notifications
        Route::get('notifications', [ChildDashboardController::class, 'notifications'])->name('notifications.index');
        Route::get('notifications/{id}/redirect', function ($id) {
            $user = auth()->user();
            $notification = $user->notifications()->where('id', $id)->first();
            if (!$notification) {
                return redirect()->route('child.notifications.index');
            }
            $notification->markAsRead();
            $type = $notification->data['type'] ?? null;
            $url = match ($type) {
                'new_program_available', 'program_update' => isset($notification->data['program_id'])
                    ? route('child.programs.show', $notification->data['program_id'])
                    : route('child.programs.index'),
                'blog_published', 'blog_post' => isset($notification->data['slug'])
                    ? route('blog.show', $notification->data['slug'])
                    : route('blog'),
                default => route('child.notifications.index'),
            };
            return redirect($url);
        })->name('notifications.redirect');
        Route::post('notifications/{id}/read', function ($id) {
            auth()->user()->notifications()->where('id', $id)->first()?->markAsRead();
            return back();
        })->name('notifications.read');
        Route::post('notifications/read-all', function () {
            auth()->user()->unreadNotifications->markAsRead();
            return back();
        })->name('notifications.read-all');
        Route::delete('notifications/{id}', function ($id) {
            auth()->user()->notifications()->where('id', $id)->delete();
            return back();
        })->name('notifications.destroy');

        Route::post('programs/request', [ParentDashboardController::class, 'requestEnrollment'])->name('request');
    });
});

// Subscription Flow (public entry, auth required for most)
Route::post('subscription/initiate', [\App\Http\Controllers\SubscriptionController::class, 'initiate'])->name('subscription.initiate');

// Paystack Webhook (excluded from CSRF - see VerifyCsrfToken middleware)
Route::post('webhooks/paystack', [\App\Http\Controllers\SubscriptionController::class, 'handleWebhook'])->name('webhooks.paystack');

Route::middleware(['auth', 'ensure_active', 'force_password_change'])->group(function () {
    Route::get('subscription/resume', [\App\Http\Controllers\SubscriptionController::class, 'resumeAfterLogin'])->name('subscription.resume');
    Route::post('subscription/select-child', [\App\Http\Controllers\SubscriptionController::class, 'selectChild'])->name('subscription.select-child');
    Route::post('subscription/verify-payment', [\App\Http\Controllers\SubscriptionController::class, 'verifyPayment'])->name('subscription.verify-payment');
    Route::get('subscription/success/{payment}', [\App\Http\Controllers\SubscriptionController::class, 'success'])->name('subscription.success');
    Route::get('subscription/failed', [\App\Http\Controllers\SubscriptionController::class, 'failed'])->name('subscription.failed');

    // Premium Subscription Routes
    Route::get('premium/subscribe', [\App\Http\Controllers\PremiumSubscriptionController::class, 'index'])->name('premium.subscribe');
    Route::post('premium/checkout', [\App\Http\Controllers\PremiumSubscriptionController::class, 'checkout'])->name('premium.checkout');
    Route::get('premium/verify', [\App\Http\Controllers\PremiumSubscriptionController::class, 'verify'])->name('premium.verify');
    Route::get('premium/success', [\App\Http\Controllers\PremiumSubscriptionController::class, 'success'])->name('premium.success');
    Route::post('premium/toggle-auto-renewal', [\App\Http\Controllers\PremiumSubscriptionController::class, 'toggleAutoRenewal'])->name('premium.toggle-auto-renewal');
});

require __DIR__ . '/auth.php';
