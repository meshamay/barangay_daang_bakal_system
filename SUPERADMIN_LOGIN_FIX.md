# SUPERADMIN LOGIN FIX - QUICK START

## 🔴 Current Issue
Superadmin cannot log in at production URL: https://barangaydaangbakalsystem-production.up.railway.app/login
- Credentials: `superadmin` / `SuperAdmin@2026`
- Error: "The provided credentials do not match our records."

## ✅ Solution (Choose One)

### OPTION 1: Fix via Railway Console (Fastest)

1. **Open Railway Dashboard** → Select your project → Go to "Deploy" or "Console" tab
2. **Click "Console"** button to access the production container terminal
3. **Run this command**:
   ```bash
   php artisan tinker
   ```
4. **Paste this code** and press Enter:
   ```php
   use Illuminate\Support\Facades\Hash;
   use App\Models\User;
   $user = User::where('username', 'superadmin')->forceDelete();
   User::create(['resident_id' => 'SA-00001', 'first_name' => 'Super', 'last_name' => 'Admin', 'username' => 'superadmin', 'email' => 'superadmin@daangbakal.gov', 'password' => Hash::make('SuperAdmin@2026'), 'plain_password' => 'SuperAdmin@2026', 'user_type' => 'super admin', 'role' => 'super admin', 'status' => 'approved', 'gender' => 'Male', 'age' => 35, 'civil_status' => 'Single', 'birthdate' => '1991-01-01', 'place_of_birth' => 'Manila', 'citizenship' => 'Filipino', 'contact_number' => '0000000000', 'address' => 'Barangay Daang Bakal']);
   echo "✓ Fixed!\n";
   exit;
   ```
5. **Clear cache**:
   ```bash
   php artisan cache:clear
   ```
6. **Test login** at https://barangaydaangbakalsystem-production.up.railway.app/login

---

### OPTION 2: Fix via Environment Variables (Recommended)

1. **Go to Railway Dashboard** → Select project → "Variables" tab
2. **Add these environment variables**:
   ```
   SUPER_ADMIN_USERNAME=superadmin
   SUPER_ADMIN_PASSWORD=SuperAdmin@2026
   SUPER_ADMIN_EMAIL=superadmin@daangbakal.gov
   SUPER_ADMIN_FIRST_NAME=Super
   SUPER_ADMIN_LAST_NAME=Admin
   SUPER_ADMIN_GENDER=Male
   SUPER_ADMIN_AGE=35
   SUPER_ADMIN_CIVIL_STATUS=Single
   SUPER_ADMIN_BIRTHDATE=1991-01-01
   SUPER_ADMIN_BIRTHPLACE=Manila
   SUPER_ADMIN_CITIZENSHIP=Filipino
   SUPER_ADMIN_CONTACT=0000000000
   SUPER_ADMIN_ADDRESS=Barangay Daang Bakal
   ```
3. **Deploy/Restart** your application
4. **Test login** at https://barangaydaangbakalsystem-production.up.railway.app/login

---

## 🧪 Verify the Fix

After applying the fix, test the login:

**Test in Railway Console**:
```php
php artisan tinker
use Illuminate\Support\Facades\Auth;
Auth::guard('admin')->attempt(['username' => 'superadmin', 'password' => 'SuperAdmin@2026']) ? echo "✓ Success" : echo "✗ Failed";
exit;
```

**Test in Browser**:
- Navigate to: https://barangaydaangbakalsystem-production.up.railway.app/login
- Username: `superadmin`
- Password: `SuperAdmin@2026`
- Should redirect to admin dashboard

---

## 📋 What Changed

| File | Change |
|------|--------|
| `.env.production.example` | Added SUPER_ADMIN_* variables documentation |
| `.env.example` | Added SUPER_ADMIN_* variables documentation |
| `app/Providers/AppServiceProvider.php` | Already has auto-provisioning logic (no change needed) |

---

## 🔍 Technical Details

**Root Cause**: The production environment on Railway was missing the `SUPER_ADMIN_USERNAME` and `SUPER_ADMIN_PASSWORD` environment variables. The AppServiceProvider has logic to auto-create superadmin from these env vars, but they were never set in production.

**How AppServiceProvider Works** (in `app/Providers/AppServiceProvider.php`):
1. Checks if `SUPER_ADMIN_USERNAME` and `SUPER_ADMIN_PASSWORD` env vars are set
2. If set AND superadmin doesn't exist → Creates superadmin with provided credentials
3. If superadmin already exists → Does nothing (won't update if creds change)

**Why Manual Fix is Needed**: 
- If superadmin already exists in production with wrong credentials, re-deploying won't fix it
- Need to manually delete and recreate OR update env vars before deployment

---

## ❓ Questions?

**Q: What if I don't want to use tinker?**
A: Use Option 2 (Environment Variables) instead. It's safer and more permanent.

**Q: What if the superadmin still can't login after this?**
A: 
1. Check if status is "approved": `php artisan tinker` → `User::where('username', 'superadmin')->first()->status`
2. Clear all caches: `php artisan cache:clear && php artisan view:clear`
3. Restart application in Railway dashboard

**Q: What's the plain_password field for?**
A: It stores the plain password for reference. In production, only the `password` field (hashed) is used for authentication.

---

## 📞 Need Help?

If the fix doesn't work:
1. Check Railway logs for errors
2. Verify database is accessible
3. Ensure APP_KEY is set correctly (should match between envs)
4. Contact administrator with error messages from logs
