# Fix Superadmin Login Issue on Railway Production

## Problem
The superadmin account cannot log in with credentials: `username: superadmin`, `password: SuperAdmin@2026`

## Root Cause
The production environment variables on Railway are likely not set with `SUPER_ADMIN_USERNAME` and `SUPER_ADMIN_PASSWORD`, so the AppServiceProvider auto-provisioning logic either:
1. Never created the superadmin user, OR
2. Created it with different credentials

## Solution

You need to run this command on Railway to fix the superadmin account:

```bash
php artisan tinker
```

Then paste and execute this code:

```php
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Try to find and delete existing superadmin if credentials don't match
$existingSuperAdmin = User::where('username', 'superadmin')->first();
if ($existingSuperAdmin) {
    $passwordMatch = Hash::check('SuperAdmin@2026', $existingSuperAdmin->password);
    if (!$passwordMatch) {
        echo "Existing superadmin password doesn't match. Deleting and recreating...\n";
        $existingSuperAdmin->forceDelete();
    } else {
        echo "Existing superadmin has correct password. Checking status...\n";
        if ($existingSuperAdmin->status !== 'approved') {
            echo "Updating status to 'approved'...\n";
            $existingSuperAdmin->update(['status' => 'approved']);
        }
        exit("Superadmin account is correct!\n");
    }
}

// Create/recreate the superadmin account
$newSuperAdmin = User::create([
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
    'address' => 'Barangay Daang Bakal',
]);

echo "✓ Superadmin account created/fixed successfully!\n";
echo "Username: superadmin\n";
echo "Password: SuperAdmin@2026\n";
echo "Status: approved\n";
exit;
```

## Steps to Execute

1. **SSH into your Railway container** or use Railway's console
2. **Run the tinker command**: `php artisan tinker`
3. **Paste the code above** and execute it
4. **Clear application cache**: `php artisan cache:clear`
5. **Try logging in** to https://barangaydaangbakalsystem-production.up.railway.app/login with:
   - Username: `superadmin`
   - Password: `SuperAdmin@2026`

## Alternative: Update Environment Variables on Railway

If the above doesn't work, add these environment variables to your Railway dashboard:

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

Then deploy/restart your application, which will trigger the AppServiceProvider to auto-provision the superadmin.

## Verification

After fixing, verify the superadmin login works by running:

```php
use Illuminate\Support\Facades\Auth;
use App\Models\User;

$credentials = ['username' => 'superadmin', 'password' => 'SuperAdmin@2026'];
$attempt = Auth::guard('admin')->attempt($credentials);
echo $attempt ? "✓ Login successful!" : "✗ Login failed!";
exit;
```

## Files Modified

- `.env.production.example` - Added SUPER_ADMIN_* variables
- `.env.example` - Added SUPER_ADMIN_* variables  
- `app/Providers/AppServiceProvider.php` - Already has auto-provisioning logic

No code changes needed - just environment configuration!
