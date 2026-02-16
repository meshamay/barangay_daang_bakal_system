# Security Audit Report
**Barangay Document Management System**
**Date: February 10, 2026**

---

## Executive Summary
This Laravel application has **MODERATE** security concerns. Several critical and high-priority vulnerabilities were identified that require immediate attention before production deployment.

---

## 🔴 CRITICAL ISSUES

### 1. **SQL Injection Vulnerability in DashboardController** 
**File:** [app/Http/Controllers/Admin/DashboardController.php](app/Http/Controllers/Admin/DashboardController.php#L59)  
**Severity:** 🔴 CRITICAL

**Issue:**
```php
$q->where('transaction_no', 'like', "%{$search}%")
  ->orWhereHas('user', function($q2) use ($search) {
      $q2->where('first_name', 'like', "%{$search}%")
         ->orWhere('last_name', 'like', "%{$search}%");
  });
```

While Laravel Eloquent provides some protection, the direct string interpolation in LIKE clauses can be vulnerable to SQL injection attacks.

**Fix:**
```php
$q->where('transaction_no', 'like', '%' . $search . '%')
  ->orWhereHas('user', function($q2) use ($search) {
      $q2->where('first_name', 'like', '%' . $search . '%')
         ->orWhere('last_name', 'like', '%' . $search . '%');
  });
```

---

### 2. **Debug Mode Enabled in Production**
**File:** [config/app.php](config/app.php#L37)

**Issue:**
```php
'debug' => (bool) env('APP_DEBUG', false),
```

If `APP_DEBUG=true` is set in the `.env` file, sensitive information (database credentials, file paths, user data) will be exposed in error pages.

**Fix:**
- Ensure `.env` file sets `APP_DEBUG=false` in production
- Never commit `.env` file to version control
- Create `.env.example` without sensitive values

---

### 3. **Hardcoded Database Credentials Risk**
**File:** [database/seeders/UserSeeder.php](database/seeders/UserSeeder.php#L37)

**Issue:**
```php
'password' => Hash::make('Super@123'),
'username' => 'superadmin',
```

Hardcoded default credentials are a major security risk.

**Fix:**
- Remove seeder credentials or use random passwords
- Change default credentials immediately after installation
- Use environment variables for default credentials

---

### 4. **Missing CSRF Protection Validation**
**File:** [resources/views/user/complaints/index.blade.php](resources/views/user/complaints/index.blade.php#L171)

**Issue:**
The complaint form includes `@csrf` but needs verification that middleware is properly configured.

**Recommendation:**
- Verify `VerifyCsrfToken` middleware is registered globally
- Test CSRF token validation

---

## 🟠 HIGH PRIORITY ISSUES

### 5. **Insufficient Authorization Check**
**File:** [app/Http/UserController.php](app/Http/UserController.php#L13-L30)

**Issue:**
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

The check allows requests to pass if user is null, which could be a vulnerability.

**Fix:**
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

### 6. **Logging Sensitive Data**
**File:** [app/Http/UserController.php](app/Http/UserController.php#L45-L46)

**Issue:**
```php
Log::info('Store Request Data:', $request->all());
Log::info('Store Request Files:', $request->allFiles());
```

This logs all request data including passwords, files, and personal information.

**Fix:**
```php
Log::info('Store Request Data:', $request->except(['password', 'photo', 'front_id_photo', 'back_id_photo']));
```

---

### 7. **Weak Password Requirements for Admin Creation**
**File:** [app/Http/Controllers/AuthController.php](app/Http/Controllers/AuthController.php#L32)

**Issue:**
```php
'password' => 'required|string|min:8|confirmed',
```

This lacks complexity requirements for admin passwords.

**Fix:**
```php
'password' => [
    'required', 'string', 'min:10', 'confirmed',
    'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/'
],
```

---

### 8. **Missing Input Sanitization in Complaint Form**
**File:** [app/Http/Controllers/User/ComplaintController.php](app/Http/Controllers/User/ComplaintController.php#L39)

**Issue:**
```php
'description'       => 'required|string|max:255',    
'specifyInput'      => 'nullable|string|max:255',
```

No sanitization for XSS attacks. Blade's `{{ }}` provides escaping, but additional validation is recommended.

**Recommendation:**
- Use `trim()` and sanitize inputs
- Validate against whitelist where possible

---

### 9. **File Upload Security**
**File:** [app/Http/UserController.php](app/Http/UserController.php#L62-L65)

**Issue:**
```php
'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
'front_id_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
'back_id_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
```

While MIME type validation exists, filename validation is missing.

**Fix:**
- Rename uploaded files to prevent directory traversal
- Store outside public folder
- Verify file contents match extension
- Add antivirus scanning for sensitive documents

---

## 🟡 MEDIUM PRIORITY ISSUES

### 10. **Exposed Sensitive Data in HTML Attributes**
**File:** [resources/views/admin/documents/index.blade.php](resources/views/admin/documents/index.blade.php#L221)

**Issue:**
```php
data-fname="{{ $request->resident->first_name ?? '' }}"
data-mname="{{ $request->resident->middle_name ?? '' }}"
data-dob="{{ $request->resident?->birthdate?->format('F d, Y') ?? '' }}"
```

Personal data is exposed in HTML attributes and could be accessed via JavaScript.

**Recommendation:**
- Move sensitive data to API endpoints
- Use JSON responses instead of HTML data attributes
- Implement proper API authentication

---

### 11. **Missing Rate Limiting on Authentication**
**File:** [app/Http/Controllers/Auth/LoginController.php](app/Http/Controllers/Auth/LoginController.php#L27)

**Issue:**
No rate limiting on login attempts to prevent brute force attacks.

**Fix:**
```php
Route::middleware('throttle:5,1')->post('/login', [LoginController::class, 'login']);
```

---

### 12. **No HTTPS Enforcement**
**File:** [config/app.php](config/app.php)

**Issue:**
No HTTPS enforcement in configuration.

**Fix:**
Add to `App\Providers\AppServiceProvider.php`:
```php
if ($this->app->environment('production')) {
    \Illuminate\Support\Facades\URL::forceScheme('https');
}
```

---

### 13. **Audit Log Insufficient for Security**
**File:** [app/Models/AuditLog.php](app/Models/AuditLog.php)

**Issue:**
Basic audit logging without detailed action tracking, IP addresses, or timestamps for modifications.

**Recommendation:**
- Log IP addresses
- Log detailed action descriptions
- Log data changes (old vs new values)
- Monitor for suspicious patterns

---

## 🟢 LOW PRIORITY ISSUES

### 14. **No API Rate Limiting**
**File:** [routes/web.php](routes/web.php#L107)

**Issue:**
API endpoints lack rate limiting.

**Fix:**
```php
Route::middleware('throttle:60,1')->group(function () {
    Route::get('/api/notifications', ...);
});
```

---

### 15. **Missing Security Headers**
**Issue:**
No custom HTTP security headers configured.

**Fix:** Add to `App\Http\Middleware\VerifyCsrfToken.php`:
```php
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
```

---

### 16. **No Session Timeout**
**Issue:**
Sessions have no automatic timeout configuration.

**Fix:** Update `.env`:
```
SESSION_LIFETIME=30
SESSION_EXPIRE_ON_CLOSE=true
```

---

## Summary Table

| Issue | Severity | Component | Status |
|-------|----------|-----------|--------|
| SQL Injection in Dashboard | 🔴 CRITICAL | DashboardController | ⚠️ Needs Fix |
| Debug Mode Enabled | 🔴 CRITICAL | .env Configuration | ⚠️ Needs Fix |
| Hardcoded Credentials | 🔴 CRITICAL | UserSeeder | ⚠️ Needs Fix |
| Null Check Authorization | 🟠 HIGH | UserController | ⚠️ Needs Fix |
| Logging Sensitive Data | 🟠 HIGH | UserController | ⚠️ Needs Fix |
| Weak Admin Passwords | 🟠 HIGH | AuthController | ⚠️ Needs Fix |
| File Upload Security | 🟠 HIGH | UserController | ⚠️ Needs Fix |
| Missing Rate Limiting | 🟡 MEDIUM | LoginController | ⚠️ Needs Fix |
| No HTTPS Enforcement | 🟡 MEDIUM | AppServiceProvider | ⚠️ Needs Fix |
| Data in HTML Attributes | 🟡 MEDIUM | admin/documents/index | ⚠️ Needs Fix |
| Insufficient Audit Logs | 🟡 MEDIUM | AuditLog | ⚠️ Needs Fix |
| API Rate Limiting | 🟢 LOW | API Routes | ⚠️ Needs Fix |
| Missing Security Headers | 🟢 LOW | Middleware | ⚠️ Needs Fix |
| No Session Timeout | 🟢 LOW | Configuration | ⚠️ Needs Fix |

---

## Recommendations

### Immediate Actions (Before Production):
1. ✅ Fix SQL injection vulnerability
2. ✅ Disable debug mode
3. ✅ Change hardcoded credentials
4. ✅ Fix authorization checks
5. ✅ Remove sensitive data logging

### Short-term (Week 1-2):
6. ✅ Add rate limiting
7. ✅ Enforce HTTPS
8. ✅ Improve audit logging
9. ✅ Secure file uploads
10. ✅ Add security headers

### Long-term (Monthly):
11. ✅ Implement API versioning
12. ✅ Add penetration testing
13. ✅ Monitor dependencies for vulnerabilities
14. ✅ Implement Web Application Firewall (WAF)

---

## Dependency Check

**composer.json** shows:
- Laravel 12.0 ✅ (Latest stable)
- Dependencies are up-to-date

**Run:** `composer audit` to check for known vulnerabilities in packages.

---

## Testing Recommendations

- Run `php artisan tinker` to test SQL injection scenarios
- Use OWASP ZAP or Burp Suite for penetration testing
- Implement unit tests for authorization
- Add integration tests for file upload security

---

**Report Generated By:** Security Audit Assistant  
**Framework:** Laravel 12.0  
**PHP Version:** 8.2+  
**Status:** ⚠️ NOT PRODUCTION READY
