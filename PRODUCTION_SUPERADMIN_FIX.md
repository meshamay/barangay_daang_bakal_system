# Fix Superadmin Login - Production Railway

## ✅ Development Status
The superadmin account works perfectly in development:
- Username: `superadmin` ✓
- Password: `SuperAdmin@2026` ✓
- User Type: `super admin` ✓
- Status: `approved` ✓

## ❌ Production Issue
The login fails in production (Railway), which means the production database has different credentials.

## 🔧 Solution for Railway Production

You MUST run this command directly on your Railway production container:

### Step 1: Access Railway Console
1. Go to: https://railway.app
2. Select your project: "Barangay Daang Bakal System"
3. Click the "Deploy" tab
4. Click "View Logs" → Look for the Console button
5. Or SSH into the container if you have access

### Step 2: Run PHP Tinker
```bash
php artisan tinker
```

### Step 3: Copy & Paste This Code (One Chunk)

```php
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// Step 1: Delete the old/broken superadmin if it exists
$old = User::where('username', 'superadmin')->first();
if ($old) {
    echo "Deleting old superadmin user...\n";
    $old->forceDelete();
}

// Step 2: Create fresh superadmin with correct credentials
$superadmin = User::create([
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

echo "✓ Superadmin created!\n";
echo "ID: {$superadmin->id}\n";
echo "Username: {$superadmin->username}\n";
echo "Status: {$superadmin->status}\n";

// Step 3: Verify it works
$test = Auth::guard('admin')->attempt([
    'username' => 'superadmin',
    'password' => 'SuperAdmin@2026'
]);

echo "Login test: " . ($test ? "✓ SUCCESS" : "✗ FAILED") . "\n";

exit;
```

### Step 4: Clear Cache
```bash
php artisan cache:clear
php artisan view:clear
```

### Step 5: Test Login
Visit: https://barangaydaangbakalsystem-production.up.railway.app/login

**Credentials:**
- Username: `superadmin`
- Password: `SuperAdmin@2026`

---

## 🚨 If Console Access is Not Available

Add these environment variables to your Railway dashboard instead:

1. Go to Railway Project Settings
2. Click "Variables"
3. Add:
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

4. **Deploy/Restart** your application
5. The AppServiceProvider will auto-create superadmin on startup

---

## 🔍 Troubleshooting

### Still Getting "Credentials do not match" Error?

**Check 1:** Verify the user exists
```bash
php artisan tinker
User::where('username', 'superadmin')->first()
```

**Check 2:** Verify password hash
```bash
use Illuminate\Support\Facades\Hash;
$user = User::where('username', 'superadmin')->first();
Hash::check('SuperAdmin@2026', $user->password)
```

**Check 3:** Clear all caches
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:cache --force
```

**Check 4:** Check session table exists
```bash
php artisan migrate
```

### Getting "SQLSTATE" Error?

Run migrations first:
```bash
php artisan migrate --force
```

### Timeout or Connection Error?

Make sure the database is running and accessible from the container.

---

## ✅ Success Indicators

After running the fix, you should see:
```
✓ Superadmin created!
ID: X
Username: superadmin
Status: approved
Login test: ✓ SUCCESS
```

Then you can log in successfully with:
- Username: `superadmin`
- Password: `SuperAdmin@2026`

---

## 📝 Key Points

- **Development**: ✅ Working perfectly with credentials above
- **Production**: ❌ Needs manual fix via Railway console or env vars
- **Password**: Always `SuperAdmin@2026`
- **User Type**: Must be `super admin` (with space)
- **Status**: Must be `approved`

---

**Last Updated:** February 19, 2026  
**Status:** Ready for Production Fix
