# ⚡ Quick Fix for Superadmin Login

## Problem
❌ Superadmin can't log in: `superadmin` / `SuperAdmin@2026`

## Fastest Fix (5 minutes)

### Step 1: Open Railway Console
- Go to Railway Dashboard → Select project
- Click the "Deploy" tab
- Click "View Logs" or "Console" button

### Step 2: Run Tinker
```bash
php artisan tinker
```

### Step 3: Paste This Code
```php
use Illuminate\Support\Facades\Hash;
use App\Models\User;
@php
// Delete existing
User::where('username', 'superadmin')->forceDelete();
// Recreate with correct credentials
User::create([
    'resident_id' => 'SA-00001',
    'first_name' => 'Super',
    'last_name' => 'Admin',
    'username' => 'superadmin',
    'email' => 'superadmin@daangbakal.gov',
    'password' => Hash::make('SuperAdmin@2026'),
    'plain_password' => 'SuperAdmin@2026',
    'user_type' => 'super admin',
    'role' => 'super admin',
    'status' => 'approved',
    'gender' => 'Male',
    'age' => 35,
    'civil_status' => 'Single',
    'birthdate' => '1991-01-01',
    'place_of_birth' => 'Manila',
    'citizenship' => 'Filipino',
    'contact_number' => '0000000000',
    'address' => 'Barangay Daang Bakal'
]);
echo "✓ Fixed!";
exit;
```

### Step 4: Clear Cache
```bash
php artisan cache:clear
```

### Step 5: Test Login
Visit: https://barangaydaangbakalsystem-production.up.railway.app/login
- Username: `superadmin`
- Password: `SuperAdmin@2026`

## Done! ✅

---

## Alternative: Environment Variables (Permanent)

1. Go to Railway Dashboard → "Variables" tab
2. Add:
   ```
   SUPER_ADMIN_USERNAME=superadmin
   SUPER_ADMIN_PASSWORD=SuperAdmin@2026
   SUPER_ADMIN_EMAIL=superadmin@daangbakal.gov
   SUPER_ADMIN_FIRST_NAME=Super
   SUPER_ADMIN_LAST_NAME=Admin
   ```
3. Redeploy application

---

## Questions?
See detailed docs:
- `SUPERADMIN_LOGIN_FIX.md` - Detailed steps
- `SUPERADMIN_FIX_SUMMARY.md` - Technical analysis
