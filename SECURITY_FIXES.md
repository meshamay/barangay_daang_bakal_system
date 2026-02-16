# Security Fixes Implementation Guide

## Quick Fixes to Apply Immediately

### Fix 1: SQL Injection in DashboardController
**File:** `app/Http/Controllers/Admin/DashboardController.php`

Change from:
```php
$q->where('transaction_no', 'like', "%{$search}%")
```

To:
```php
$q->where('transaction_no', 'like', '%' . $search . '%')
```

---

### Fix 2: Remove Sensitive Data Logging
**File:** `app/Http/UserController.php` (Lines 45-46)

Change from:
```php
Log::info('Store Request Data:', $request->all());
Log::info('Store Request Files:', $request->allFiles());
```

To:
```php
Log::info('Store Request Data:', $request->except(['password', 'photo', 'front_id_photo', 'back_id_photo']));
// Remove file logging completely as it's unnecessary
```

---

### Fix 3: Fix Authorization Null Check
**File:** `app/Http/UserController.php` (Lines 19-27)

Change from:
```php
$this->middleware(function ($request, $next) {
    $user = Auth::user();

    if (!$user) {
        return $next($request);
    }

    if (!in_array($user->user_type, ['admin', 'super admin'])) {
        abort(403, 'Unauthorized action.');
    }

    return $next($request);
});
```

To:
```php
$this->middleware(function ($request, $next) {
    $user = Auth::user();

    if (!$user || !in_array($user->user_type, ['admin', 'super admin'])) {
        abort(403, 'Unauthorized action.');
    }

    return $next($request);
});
```

---

### Fix 4: Update Password Validation in AuthController
**File:** `app/Http/Controllers/AuthController.php`

If using basic password validation, upgrade to:
```php
'password' => [
    'required', 'string', 'min:10', 'confirmed',
    'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'
],
```

---

### Fix 5: Add Rate Limiting to Routes
**File:** `routes/web.php`

Change login route:
```php
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
```

To:
```php
Route::post('/login', [LoginController::class, 'login'])->name('login.post')->middleware('throttle:5,1');
```

---

### Fix 6: Disable Debug Mode
**File:** `.env`

Ensure:
```
APP_DEBUG=false
APP_ENV=production
```

---

### Fix 7: Change Default Credentials
**File:** `database/seeders/UserSeeder.php`

Update or remove the hardcoded password:
```php
// Option 1: Use environment variable
'password' => Hash::make(env('INITIAL_ADMIN_PASSWORD', 'ChangeMe@123')),

// Option 2: Generate random password
'password' => Hash::make(Str::random(16)),
```

Then document the generated password securely.

---

### Fix 8: Add HTTPS Enforcement
**File:** `app/Providers/AppServiceProvider.php`

Add to `boot()` method:
```php
public function boot(): void
{
    if ($this->app->environment('production')) {
        \Illuminate\Support\Facades\URL::forceScheme('https');
    }
}
```

---

### Fix 9: Add Security Headers Middleware
**Create new file:** `app/Http/Middleware/SecurityHeaders.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('Content-Security-Policy', "default-src 'self'");
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        return $response;
    }
}
```

Then register in `app/Http/Kernel.php`:
```php
protected $middleware = [
    // ... other middleware
    \App\Http\Middleware\SecurityHeaders::class,
];
```

---

### Fix 10: Set Session Timeout
**File:** `.env`

Add:
```
SESSION_LIFETIME=30
SESSION_EXPIRE_ON_CLOSE=true
```

---

### Fix 11: Secure File Upload Function
**File:** `app/Http/UserController.php`

Create a helper method:
```php
protected function secureStoreFile($file, $path)
{
    // Generate unique filename
    $filename = time() . '_' . Str::random(16) . '.' . $file->getClientOriginalExtension();
    
    // Verify MIME type
    $mimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file->getMimeType(), $mimeTypes)) {
        throw new \Exception('Invalid file type');
    }
    
    // Store with disk
    return $file->storeAs($path, $filename, 'public');
}
```

---

### Fix 12: Add API Rate Limiting
**File:** `routes/web.php`

For API endpoints:
```php
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/api/notifications', [NotificationController::class, 'index']);
    Route::patch('/api/notifications/{id}', [NotificationController::class, 'markAsRead']);
});
```

---

## Database Security Checklist

- [ ] All foreign keys have `onDelete('cascade')` or `onDelete('restrict')`
- [ ] Sensitive columns are not logged
- [ ] Database backups are encrypted
- [ ] Database user has limited privileges
- [ ] Connection uses SSL/TLS

---

## Environment Variables to Verify

```bash
# .env file must have these settings for production:
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:xxx (generated by `php artisan key:generate`)
DB_HOST=secure-db-host
DB_USERNAME=limited-user
DB_PASSWORD=strong-password
MAIL_ENCRYPTION=tls
SESSION_LIFETIME=30
```

---

## Commands to Run

```bash
# Check for vulnerable packages
composer audit

# Run Laravel security analyzer
composer audit --format=json

# Generate new APP_KEY
php artisan key:generate

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Run tests
php artisan test
```

---

## Pre-Production Checklist

- [ ] All CRITICAL issues fixed
- [ ] Debug mode disabled
- [ ] HTTPS enforced
- [ ] Rate limiting enabled
- [ ] Security headers added
- [ ] Audit logging enhanced
- [ ] File uploads secured
- [ ] Default credentials changed
- [ ] Environment variables configured
- [ ] Database backups automated
- [ ] WAF configured (if using)
- [ ] Penetration testing completed
- [ ] Security scan passed

---

**Priority Order for Implementation:**
1. Fix 2 - Remove sensitive logging (5 min)
2. Fix 6 - Disable debug mode (1 min)
3. Fix 7 - Change credentials (5 min)
4. Fix 1 - Fix SQL injection (10 min)
5. Fix 3 - Fix authorization (5 min)
6. Fix 5 - Add rate limiting (5 min)
7. Fix 8 - HTTPS enforcement (5 min)
8. Fix 9 - Security headers (15 min)
9. Fix 4 - Password validation (10 min)
10. Fix 10 - Session timeout (2 min)
11. Fix 11 - File uploads (20 min)
12. Fix 12 - API rate limiting (5 min)

**Total Estimated Time:** ~90 minutes
