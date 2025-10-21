# TOTP-Based Authentication System

## Overview

The system has been redesigned to use **TOTP (Time-based One-Time Password)** authentication for enhanced security. After registration, users receive a unique authentication key that can be added to any authenticator app (Google Authenticator, Authy, Microsoft Authenticator, etc.).

## Key Features

### 1. **Registration with TOTP Setup**
- Upon registration, each user is assigned a unique TOTP secret key
- Users are shown a dedicated setup page with:
  - **Authentication Key** (text format for manual entry)
  - **QR Code** (for quick scanning with authenticator apps)
  - Copy-to-clipboard functionality
  - Clear instructions and warnings

### 2. **Password Reset Verification**
- Password reset now requires **two factors**:
  1. Email verification (reset link)
  2. TOTP code from authenticator app (6-digit code)
- This prevents unauthorized password resets even if someone gains access to the user's email

### 3. **Security Benefits**
- **Phishing Protection**: Even if users fall for phishing, attackers can't reset passwords without the TOTP device
- **Email Compromise Protection**: Compromised email accounts can't be used to reset passwords
- **Time-Limited Codes**: TOTP codes expire every 30 seconds
- **Offline Capability**: Users can generate codes without internet connection
- **Device-Based**: TOTP secret is stored on user's device, not transmitted over network

## User Flow

### Registration Process

```
1. User fills registration form (Name, Email, Password)
   ↓
2. Account created with TOTP secret generated
   ↓
3. User redirected to TOTP Setup Page
   ↓
4. User sees:
   - Unique authentication key (e.g., "JBSWY3DPEHPK3PXP")
   - QR code for scanning
   - Instructions to save the key
   ↓
5. User adds key to authenticator app:
   Option A: Scan QR code
   Option B: Manually enter key
   ↓
6. Authenticator app generates 6-digit codes
   ↓
7. User clicks "Continue to Login"
   ↓
8. User can now log in normally
```

### Password Reset Process

```
1. User clicks "Forgot Password" on login page
   ↓
2. User enters email address
   ↓
3. System checks if account has TOTP enabled
   ↓
4. Password reset link sent to email
   ↓
5. User clicks link in email
   ↓
6. Reset password page shows:
   - Email field (pre-filled)
   - Authenticator code field (6 digits)
   - New password fields
   ↓
7. User opens authenticator app
   ↓
8. User enters current 6-digit TOTP code
   ↓
9. User enters new password
   ↓
10. System verifies:
    - Valid reset token
    - Correct TOTP code
    - Token not expired (60 min limit)
    ↓
11. Password updated successfully
    ↓
12. User redirected to login with new password
```

## Technical Implementation

### Database Schema

**Migration**: `2025_10_21_040308_add_totp_secret_to_users_table.php`

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('totp_secret')->nullable()->after('password');
});
```

**Users Table Fields**:
- `id` - Primary key
- `name` - User's full name
- `email` - User's email (unique)
- `password` - Hashed password
- `totp_secret` - TOTP secret key (hidden from API responses)
- `remember_token` - Laravel remember token
- `created_at` - Timestamp
- `updated_at` - Timestamp

### Key Components

#### 1. **RegisteredUserController.php**
```php
public function store(Request $request): RedirectResponse
{
    // Generate TOTP secret
    $google2fa = new Google2FA();
    $totpSecret = $google2fa->generateSecretKey();

    // Create user with TOTP secret
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'totp_secret' => $totpSecret,
    ]);

    // Generate QR code URL
    $qrCodeUrl = $google2fa->getQRCodeUrl(
        config('app.name'),
        $user->email,
        $totpSecret
    );

    // Redirect to TOTP setup page
    return redirect()->route('register.totp-setup')
        ->with('totp_secret', $totpSecret)
        ->with('qr_code_url', $qrCodeUrl)
        ->with('user_email', $user->email);
}
```

#### 2. **TOTP Setup View** (`totp-setup.blade.php`)
Features:
- Displays TOTP secret in large, copyable format
- Shows QR code for scanning
- Copy-to-clipboard functionality with visual feedback
- Multiple warnings to save the key
- Instructions for various authenticator apps
- Prominent "Continue to Login" button

#### 3. **Password Reset with TOTP** (`NewPasswordController.php`)
```php
public function store(Request $request): RedirectResponse
{
    // Find user
    $user = User::where('email', $request->email)->first();

    // Verify TOTP code
    $google2fa = new Google2FA();
    $valid = $google2fa->verifyKey($user->totp_secret, $request->totp_code);

    if (!$valid) {
        return back()->withErrors([
            'totp_code' => 'The verification code is invalid or has expired.'
        ]);
    }

    // Verify reset token
    // Update password
    // ...
}
```

### Dependencies

**Composer Package**: `pragmarx/google2fa`

```json
{
    "require": {
        "pragmarx/google2fa": "^8.0"
    }
}
```

## Supported Authenticator Apps

Users can use any TOTP-compatible authenticator app:

1. **Google Authenticator** (iOS, Android)
2. **Authy** (iOS, Android, Desktop)
3. **Microsoft Authenticator** (iOS, Android)
4. **1Password** (with TOTP support)
5. **LastPass Authenticator**
6. **Duo Mobile**
7. **FreeOTP** (Open Source)
8. **andOTP** (Android, Open Source)

## Security Considerations

### Current Implementation

✅ **What's Secure**:
- TOTP secrets are unique per user
- Secrets are hidden from API responses (in `$hidden` array)
- TOTP codes expire every 30 seconds
- Password reset tokens expire after 60 minutes
- Tokens are hashed in database
- Rate limiting on login and password reset
- CSRF protection on all forms

⚠️ **Important Notes**:
- TOTP secrets are stored in plain text in database (standard practice)
- If database is compromised, TOTP secrets could be extracted
- Users must securely store their TOTP secret/backup codes
- No backup codes provided (users should screenshot QR code)

### Best Practices

1. **For Users**:
   - Save TOTP secret in password manager
   - Screenshot QR code and store securely
   - Use multiple authenticator apps as backup
   - Never share your TOTP secret

2. **For Developers**:
   - Keep database backups encrypted
   - Use HTTPS in production
   - Monitor for suspicious password reset attempts
   - Consider implementing backup codes
   - Add account recovery flow for lost TOTP devices

## Account Recovery

### If User Loses Access to Authenticator App

**Current Limitation**: No automated recovery process

**Recommended Solutions**:
1. **Manual Support**: Admin manually verifies user identity and regenerates TOTP secret
2. **Backup Codes**: Implement one-time backup codes during registration
3. **Alternative Verification**: SMS, security questions, or ID verification

**Future Enhancement Example**:
```php
// Generate backup codes during registration
$backupCodes = [];
for ($i = 0; $i < 10; $i++) {
    $backupCodes[] = Str::random(8);
}
$user->backup_codes = Hash::make(json_encode($backupCodes));
```

## Testing

### Test User Registration with TOTP

1. Navigate to `/register`
2. Fill in registration form
3. Verify redirect to `/register/totp-setup`
4. Confirm:
   - ✅ TOTP secret displayed
   - ✅ QR code shown
   - ✅ Copy button works
   - ✅ "Continue to Login" redirects to `/login`

### Test TOTP with Authenticator App

1. Download Google Authenticator or Authy
2. Scan QR code from setup page
3. Verify 6-digit code appears
4. Note: Code changes every 30 seconds

### Test Password Reset with TOTP

1. Go to `/forgot-password`
2. Enter email address
3. Check `storage/logs/laravel.log` for reset link
4. Click reset link
5. Open authenticator app
6. Enter current 6-digit code
7. Enter new password
8. Submit form
9. Verify:
   - ✅ Invalid TOTP code shows error
   - ✅ Valid TOTP code resets password
   - ✅ Can log in with new password

### Test TOTP Validation

```php
// Test valid TOTP code
$user = User::find(1);
$google2fa = new Google2FA();
$validCode = $google2fa->getCurrentOtp($user->totp_secret);
// Use $validCode in password reset form - should succeed

// Test invalid TOTP code
// Use "000000" or any random code - should fail
```

## Configuration

### QR Code Generation

The system uses an external service for QR code generation:
```php
$qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($otpauthUrl);
```

**Alternative**: Self-hosted QR code generation
```bash
composer require bacon/bacon-qr-code
```

### TOTP Settings (Default)

- **Algorithm**: SHA1 (Google2FA default)
- **Digits**: 6
- **Period**: 30 seconds
- **Window**: ±1 period (allows for time drift)

### Custom Configuration (Optional)

```php
$google2fa = new Google2FA();
$google2fa->setWindow(2); // Allow ±2 periods (60 seconds tolerance)
```

## Routes

```php
// Registration with TOTP
Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('register', [RegisteredUserController::class, 'store']);
Route::get('register/totp-setup', [RegisteredUserController::class, 'totpSetup'])->name('register.totp-setup');

// Password Reset with TOTP
Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
```

## Migration from Old System

If migrating from the old word-based verification system:

1. **Old users without TOTP**: Add migration to check for null `totp_secret`
2. **Force TOTP setup**: Redirect to TOTP setup on next login
3. **Grace period**: Allow password reset without TOTP for X days
4. **Communication**: Email users about new security requirement

```php
// Middleware to check TOTP setup
if (auth()->check() && !auth()->user()->totp_secret) {
    return redirect()->route('totp.setup.required');
}
```

## Troubleshooting

### Common Issues

**Issue**: QR code doesn't scan
- **Solution**: Manually enter the TOTP secret in authenticator app

**Issue**: TOTP code always invalid
- **Solution**: Check device time synchronization (TOTP requires accurate time)

**Issue**: "Account does not have two-factor authentication set up"
- **Solution**: User registered before TOTP implementation - need to re-register or admin adds TOTP secret

**Issue**: Lost authenticator app
- **Solution**: Contact support for manual account recovery

### Debug TOTP

```php
// Check if TOTP secret exists
$user = User::where('email', 'test@example.com')->first();
dd($user->totp_secret); // Should show secret key

// Generate current valid code
$google2fa = new Google2FA();
$currentCode = $google2fa->getCurrentOtp($user->totp_secret);
echo "Current valid code: " . $currentCode;

// Verify a code
$isValid = $google2fa->verifyKey($user->totp_secret, '123456');
var_dump($isValid); // true or false
```

## Future Enhancements

1. **Backup Codes**: Generate 10 one-time backup codes during registration
2. **TOTP on Login**: Optional 2FA for every login (not just password reset)
3. **SMS Fallback**: SMS-based recovery option
4. **WebAuthn**: Add hardware key support (YubiKey, etc.)
5. **Admin Panel**: View users with/without TOTP enabled
6. **Audit Logs**: Track TOTP verification attempts
7. **Self-Service Recovery**: Allow users to regenerate TOTP with email + security questions

## API Documentation

### Generate TOTP Secret
```php
use PragmaRX\Google2FA\Google2FA;

$google2fa = new Google2FA();
$secret = $google2fa->generateSecretKey();
// Returns: "JBSWY3DPEHPK3PXP" (32 characters)
```

### Generate QR Code URL
```php
$qrCodeUrl = $google2fa->getQRCodeUrl(
    $companyName,    // e.g., "GrowDev"
    $email,          // e.g., "user@example.com"
    $secret          // TOTP secret
);
// Returns: "otpauth://totp/GrowDev:user@example.com?secret=JBSWY3DPEHPK3PXP&issuer=GrowDev"
```

### Verify TOTP Code
```php
$valid = $google2fa->verifyKey($secret, $code);
// Returns: true if valid, false if invalid
```

### Get Current Code (for testing)
```php
$currentCode = $google2fa->getCurrentOtp($secret);
// Returns: "123456" (current 6-digit code)
```

## Conclusion

This TOTP-based authentication system provides enterprise-grade security for password resets while maintaining user-friendliness. Users can use familiar authenticator apps they may already have for other services, and the time-based codes provide strong protection against unauthorized access.

**Key Advantages**:
- Industry-standard TOTP (RFC 6238)
- Works with any TOTP app
- No server-side code changes required
- Offline code generation
- Strong security with minimal user friction
