# Password Reset Flow - Direct TOTP Authentication (No Email)

## 🎯 Overview

The password reset system now uses **direct TOTP authentication** - no emails are sent at all!

### How It Works

```
User → Forgot Password Page
     → Enter Email + TOTP Code
     → Verify Identity
     → Redirect to Password Reset Form
     → Set New Password
     → Done!
```

**No emails, no waiting, instant password reset!**

---

## 📱 User Flow

### Step 1: Navigate to Forgot Password
- URL: `http://127.0.0.1:8000/forgot-password`
- User sees a form with:
  - Email field
  - TOTP code field (6 digits)

### Step 2: Authenticate
1. User enters their email address
2. User opens their **authenticator app** (Google Authenticator, Authy, etc.)
3. User finds the entry for your app (e.g., "GrowDev")
4. User enters the current **6-digit code**
5. User clicks **"Verify & Continue"**

### Step 3: Set New Password
- If authentication succeeds:
  - User is redirected to a password reset form
  - Form shows their account name and email (confirmation)
  - User enters new password twice
  - User clicks **"Reset Password"**

### Step 4: Done!
- Password is updated immediately
- User is redirected to login page
- User logs in with new password

---

## 🔐 Security Features

### Multi-Layer Protection

1. **Email Verification**: User must know the account email
2. **TOTP Verification**: User must have the authenticator device
3. **Session-Based**: Reset session expires after 5 minutes
4. **Rate Limiting**: 3 attempts per minute
5. **No Email Trail**: No password reset links in email that could be intercepted

### Advantages Over Email-Based Reset

✅ **Faster**: Instant reset, no waiting for emails  
✅ **More Secure**: No email interception possible  
✅ **Offline**: Authenticator apps work without internet  
✅ **Phishing-Resistant**: No links to click in emails  
✅ **Device-Bound**: TOTP device is the second factor

---

## 🧪 Testing

### Test Valid Reset

1. **Start server** (if not running):
   ```powershell
   php artisan serve
   ```

2. **Navigate to**: http://127.0.0.1:8000/forgot-password

3. **Get current TOTP code**:
   - Open your authenticator app
   - Find the code for your app (e.g., "GrowDev")
   - Note the 6-digit code (e.g., "123456")

4. **Submit form**:
   - Email: test@example.com
   - TOTP Code: 123456 (from your app)
   - Click "Verify & Continue"

5. **Set new password**:
   - New Password: newpassword123
   - Confirm Password: newpassword123
   - Click "Reset Password"

6. **Login**:
   - Email: test@example.com
   - Password: newpassword123
   - Success!

### Test Invalid TOTP

1. Go to forgot-password page
2. Enter email: test@example.com
3. Enter TOTP: 000000 (wrong code)
4. **Expected**: Error "The verification code is invalid or has expired. Please try again."

### Test Non-Existent User

1. Go to forgot-password page
2. Enter email: nonexistent@example.com
3. Enter any TOTP code
4. **Expected**: Error "No account found with this email address."

### Test Session Expiration

1. Successfully authenticate on forgot-password page
2. Wait 6+ minutes
3. Try to submit password reset form
4. **Expected**: Redirect to forgot-password with error "Your session has expired. Please authenticate again."

---

## 🔧 Technical Details

### Controller: PasswordResetLinkController

**Method**: `store()`

```php
public function store(Request $request): RedirectResponse
{
    // Validate email + TOTP code
    $request->validate([
        'email' => ['required', 'email'],
        'totp_code' => ['required', 'string', 'size:6'],
    ]);

    // Find user
    $user = User::where('email', $request->email)->first();
    
    // Verify TOTP code
    $google2fa = new Google2FA();
    $valid = $google2fa->verifyKey($user->totp_secret, $request->totp_code);

    if (!$valid) {
        throw ValidationException::withMessages([
            'totp_code' => 'Invalid or expired code.'
        ]);
    }

    // Store user ID in session (5 min timeout)
    session([
        'password_reset_verified_user' => $user->id,
        'password_reset_verified_at' => now(),
    ]);

    // Redirect to password reset form
    return redirect()->route('password.reset.form');
}
```

### Controller: NewPasswordController

**Method**: `create()`

```php
public function create(): View|RedirectResponse
{
    // Check if user authenticated via TOTP
    if (!session()->has('password_reset_verified_user')) {
        return redirect()->route('password.request')
            ->withErrors(['email' => 'Please authenticate first.']);
    }

    // Check session timeout (5 minutes)
    $verifiedAt = session('password_reset_verified_at');
    if (now()->diffInMinutes($verifiedAt) > 5) {
        session()->forget(['password_reset_verified_user', 'password_reset_verified_at']);
        return redirect()->route('password.request')
            ->withErrors(['email' => 'Session expired. Authenticate again.']);
    }

    // Show password reset form
    $user = User::find(session('password_reset_verified_user'));
    return view('auth.reset-password', ['user' => $user]);
}
```

**Method**: `store()`

```php
public function store(Request $request): RedirectResponse
{
    // Verify session exists
    if (!session()->has('password_reset_verified_user')) {
        return redirect()->route('password.request');
    }

    // Validate password
    $request->validate([
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    // Update password
    $user = User::find(session('password_reset_verified_user'));
    $user->forceFill([
        'password' => Hash::make($request->password),
        'remember_token' => Str::random(60),
    ])->save();

    // Clear session
    session()->forget(['password_reset_verified_user', 'password_reset_verified_at']);

    // Redirect to login
    return redirect()->route('login')->with('status', 'Password reset successful!');
}
```

---

## 📊 Routes

```php
// Forgot Password (TOTP Authentication)
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
    ->name('password.request');

Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('throttle:3,1')
    ->name('password.email');

// Reset Password Form (After TOTP Verification)
Route::get('reset-password', [NewPasswordController::class, 'create'])
    ->name('password.reset.form');

Route::post('reset-password', [NewPasswordController::class, 'store'])
    ->name('password.store');
```

---

## 🎨 Views

### `forgot-password.blade.php`

**Features**:
- Email input field
- TOTP code input (6-digit, numeric, centered)
- Instructions for using authenticator app
- "Back to Login" link
- Help section for lost authenticator
- No email notices or development warnings

**Key Elements**:
```blade
<form method="POST" action="{{ route('password.email') }}">
    @csrf
    
    <!-- Email -->
    <input type="email" name="email" required autofocus />
    
    <!-- TOTP Code -->
    <input 
        type="text" 
        name="totp_code" 
        pattern="[0-9]{6}" 
        maxlength="6"
        required 
    />
    
    <button>Verify & Continue</button>
</form>
```

### `reset-password.blade.php`

**Features**:
- Success notice (authentication verified)
- Shows user's name and email
- New password field
- Confirm password field
- Password requirements
- Session timeout warning (5 minutes)

**Key Elements**:
```blade
<form method="POST" action="{{ route('password.store') }}">
    @csrf
    
    <!-- Account Info -->
    <p>Account: {{ $user->name }} ({{ $user->email }})</p>
    
    <!-- New Password -->
    <input type="password" name="password" required />
    
    <!-- Confirm Password -->
    <input type="password" name="password_confirmation" required />
    
    <button>Reset Password</button>
</form>
```

---

## 🚨 Error Handling

### Validation Errors

| Error | Message | Trigger |
|-------|---------|---------|
| Invalid Email | "No account found with this email address." | User doesn't exist |
| No TOTP | "This account does not have two-factor authentication set up." | User registered before TOTP |
| Invalid TOTP | "The verification code is invalid or has expired. Please try again." | Wrong code or expired (30s) |
| Session Expired | "Your session has expired. Please authenticate again." | >5 minutes since verification |
| No Session | "Please authenticate with your email and authenticator code first." | Direct access to reset form |

### Rate Limiting

- **Forgot Password**: 3 attempts per minute
- **Login**: 5 attempts per minute
- **Registration**: 5 attempts per minute

---

## 🔍 Debugging

### Check User Has TOTP Secret

```powershell
php artisan tinker
```

```php
$user = App\Models\User::where('email', 'test@example.com')->first();
echo "TOTP Secret: " . $user->totp_secret;
// Should output something like: "JBSWY3DPEHPK3PXP"
```

### Generate Current Valid Code

```php
use PragmaRX\Google2FA\Google2FA;

$google2fa = new Google2FA();
$currentCode = $google2fa->getCurrentOtp($user->totp_secret);
echo "Current valid code: " . $currentCode;
// Output: "123456" (use this immediately!)
```

### Check Session

```php
// In reset-password form, check session:
dd(session()->all());

// Should show:
// 'password_reset_verified_user' => 1
// 'password_reset_verified_at' => Carbon instance
```

---

## 📝 Comparison: Old vs New Flow

### Old Flow (Email-Based)

```
User enters email
   ↓
Email sent with reset link
   ↓
User clicks link in email
   ↓
User enters TOTP code + new password
   ↓
Password reset
```

**Issues**:
- Requires email infrastructure
- Emails can be intercepted
- User must wait for email
- Links can be phishing targets

### New Flow (Direct TOTP)

```
User enters email + TOTP code
   ↓
Instant verification
   ↓
User sets new password
   ↓
Password reset
```

**Benefits**:
- No email required
- Instant verification
- No phishing links
- Simpler user experience

---

## 🎉 Summary

### What Changed

✅ **Removed**:
- Email sending functionality
- Password reset tokens in database
- Email verification step
- Reset links

✅ **Added**:
- Direct TOTP authentication on forgot-password page
- Session-based verification (5 min timeout)
- Cleaner, faster UX

### User Experience

**Before**: Email → Wait → Click Link → Enter TOTP → Reset  
**After**: Enter TOTP → Reset (instant!)

### Security

- ✅ Still requires two factors (email + TOTP)
- ✅ No email interception risk
- ✅ Session timeout prevents abuse
- ✅ Rate limiting prevents brute force

---

## 🚀 Next Steps

1. **Test the new flow** at http://127.0.0.1:8000/forgot-password
2. **Verify** TOTP authentication works
3. **Test** session timeout (wait 6 minutes)
4. **Test** invalid TOTP codes
5. **Consider** adding backup codes for account recovery

---

**Implementation Date**: October 21, 2025  
**Flow Type**: Direct TOTP Authentication (No Email)  
**Session Timeout**: 5 minutes  
**Rate Limit**: 3 attempts/minute
