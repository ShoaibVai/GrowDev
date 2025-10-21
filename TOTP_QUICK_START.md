# TOTP Authentication System - Quick Start Guide

## 🚀 System Overview

Your Laravel application now includes **TOTP-based Two-Factor Authentication** for password resets. Users receive a unique authentication key during registration that they add to their authenticator app.

---

## ✅ What Changed

### Before (Old System)
- Registration → Auto redirect to login
- Password reset → Word-based verification codes (ALPHA-DELTA-STORM)

### After (New System)
- Registration → TOTP setup page with QR code → Login
- Password reset → 6-digit TOTP code from authenticator app

---

## 📱 Testing the New System

### Step 1: Register a New Account

1. **Start server** (if not running):
   ```powershell
   php artisan serve
   ```

2. **Navigate to**: http://127.0.0.1:8000/register

3. **Fill in registration form**:
   - Name: Test User
   - Email: test@example.com
   - Password: password123
   - Confirm Password: password123

4. **Submit form** - You'll be redirected to TOTP setup page

### Step 2: Set Up Authenticator App

**You'll see a page with**:
- ✅ Your unique authentication key (e.g., "JBSWY3DPEHPK3PXP")
- ✅ QR code
- ✅ Copy button
- ✅ Instructions

**Choose one option**:

#### Option A: Use QR Code (Recommended)
1. Download **Google Authenticator** or **Authy** on your phone
2. Open the app
3. Tap "+" or "Add account"
4. Scan the QR code on your screen
5. The app will show a 6-digit code that refreshes every 30 seconds

#### Option B: Manual Entry
1. Open your authenticator app
2. Choose "Enter a setup key" or "Manual entry"
3. Copy the authentication key from the setup page
4. Paste it into your authenticator app
5. Account name: GrowDev (or your app name)
6. The app will generate 6-digit codes

### Step 3: Save Your Key

⚠️ **CRITICAL**: Before clicking "Continue to Login"
- Copy the authentication key
- Save it in a password manager OR
- Take a screenshot of the QR code OR
- Write it down securely

**Why?** If you lose your phone/authenticator app, you'll need this key to recover your account!

### Step 4: Continue to Login

1. Click **"I've Saved My Key - Continue to Login"**
2. You'll be redirected to the login page
3. Log in with your email and password

---

## 🔐 Testing Password Reset

### Step 1: Request Password Reset

1. **Navigate to**: http://127.0.0.1:8000/login
2. Click **"Forgot your password?"**
3. Enter your email: test@example.com
4. Click **"Email Password Reset Link"**

### Step 2: Get Reset Link (Development Mode)

Since we're using the `log` mail driver, check the log file:

```powershell
Get-Content storage\logs\laravel.log -Tail 50
```

Look for:
```
[2025-10-21 10:00:00] local.INFO: Password Reset Request
{
    "email": "test@example.com",
    "reset_url": "http://127.0.0.1:8000/reset-password/abc123...",
    "message": "Please use your authenticator app to get the verification code."
}
```

### Step 3: Complete Password Reset

1. **Copy the reset URL** from the log
2. **Paste it in your browser**
3. **Open your authenticator app** on your phone
4. **Find the code** for "GrowDev" (it refreshes every 30 seconds)
5. **Enter**:
   - Email: test@example.com (should be pre-filled)
   - Authenticator Code: 123456 (current code from your app)
   - New Password: newpassword123
   - Confirm Password: newpassword123
6. **Click "Reset Password"**

### Step 4: Verify Success

1. You'll be redirected to login page
2. Log in with:
   - Email: test@example.com
   - Password: newpassword123 (your NEW password)

---

## 🧪 Testing Scenarios

### Test 1: Valid TOTP Code ✅
- Use current code from authenticator app
- **Expected**: Password reset successful

### Test 2: Invalid TOTP Code ❌
- Enter: 000000 or any random 6-digit number
- **Expected**: Error message "The verification code is invalid or has expired."

### Test 3: Expired TOTP Code ⏰
- Wait 30 seconds for code to change
- Enter the OLD code (before it changed)
- **Expected**: Error message "The verification code is invalid or has expired."

### Test 4: Account Without TOTP ⚠️
- For old accounts (before TOTP implementation)
- **Expected**: Error message "This account does not have two-factor authentication set up."

### Test 5: Wrong Email ❌
- Enter different email than the one used to request reset
- **Expected**: Error message "We could not find a user with that email address."

---

## 📊 Database Check

Verify TOTP secret was saved:

```powershell
php artisan tinker
```

Then run:
```php
$user = App\Models\User::where('email', 'test@example.com')->first();
echo "TOTP Secret: " . $user->totp_secret;
// Output: TOTP Secret: JBSWY3DPEHPK3PXP
```

---

## 🔍 Debugging

### Check Current Valid TOTP Code

```powershell
php artisan tinker
```

```php
use PragmaRX\Google2FA\Google2FA;
use App\Models\User;

$user = User::where('email', 'test@example.com')->first();
$google2fa = new Google2FA();
$currentCode = $google2fa->getCurrentOtp($user->totp_secret);

echo "Current valid code: " . $currentCode;
// Output: Current valid code: 123456
```

Use this code immediately in the password reset form!

### Verify TOTP Secret Format

```php
$user = User::find(1);
echo strlen($user->totp_secret); // Should be 16 or 32 characters
echo $user->totp_secret; // Should be alphanumeric (Base32)
```

### Check Reset Token in Database

```powershell
php artisan tinker
```

```php
DB::table('password_reset_tokens')->where('email', 'test@example.com')->first();
```

---

## 🛠️ Common Issues & Solutions

### Issue 1: "Account does not have two-factor authentication set up"
**Cause**: User registered before TOTP system was implemented
**Solution**: 
- Delete user and re-register OR
- Manually add TOTP secret:
  ```php
  $user = User::where('email', 'old@example.com')->first();
  $google2fa = new Google2FA();
  $user->totp_secret = $google2fa->generateSecretKey();
  $user->save();
  echo "New secret: " . $user->totp_secret;
  ```

### Issue 2: TOTP code always invalid
**Cause**: Device time not synchronized
**Solution**:
- Check your phone's time settings
- Enable "Automatic date & time"
- TOTP requires accurate time (within ±30 seconds)

### Issue 3: QR code doesn't load
**Cause**: No internet connection (using external QR service)
**Solution**: Use manual entry with the text key

### Issue 4: Lost authenticator app
**Cause**: Phone lost/stolen or app uninstalled
**Solution** (Development only):
```php
// Manually reset password in database
$user = User::where('email', 'test@example.com')->first();
$user->password = Hash::make('newpassword123');
$user->save();

// OR regenerate TOTP secret
$google2fa = new Google2FA();
$user->totp_secret = $google2fa->generateSecretKey();
$user->save();
echo "New TOTP secret: " . $user->totp_secret;
// User must add this new secret to their authenticator app
```

---

## 📱 Recommended Authenticator Apps

### Mobile Apps
- **Google Authenticator** (Free, iOS/Android)
- **Authy** (Free, iOS/Android, Desktop) - Has backup/sync
- **Microsoft Authenticator** (Free, iOS/Android)
- **Duo Mobile** (Free, iOS/Android)

### Desktop/Browser
- **Authy** (Windows, macOS, Linux)
- **1Password** (Paid, includes TOTP)
- **Bitwarden** (Free tier includes TOTP)

### Open Source
- **FreeOTP** (iOS/Android)
- **andOTP** (Android only)

---

## 🎯 Quick Testing Checklist

- [ ] Server running (`php artisan serve`)
- [ ] Navigate to http://127.0.0.1:8000/register
- [ ] Fill registration form and submit
- [ ] See TOTP setup page with QR code
- [ ] Scan QR code with authenticator app
- [ ] See 6-digit code in app (changes every 30s)
- [ ] Copy authentication key (save it!)
- [ ] Click "Continue to Login"
- [ ] Log in successfully
- [ ] Click "Forgot Password"
- [ ] Enter email and submit
- [ ] Check `storage/logs/laravel.log` for reset link
- [ ] Copy reset URL to browser
- [ ] Open authenticator app
- [ ] Enter current 6-digit TOTP code
- [ ] Enter new password
- [ ] Submit reset form
- [ ] See success message
- [ ] Log in with NEW password

---

## 🔐 Security Notes

### What's Protected
✅ Password reset requires email + TOTP device
✅ TOTP codes expire every 30 seconds
✅ Reset tokens expire after 60 minutes
✅ Tokens are hashed in database
✅ Rate limiting (3 attempts/min on password reset)

### Important Reminders
⚠️ **Save your TOTP secret** - You'll need it if you lose your phone
⚠️ **Use HTTPS in production** - Never use plain HTTP
⚠️ **TOTP secrets in database** - If database is compromised, TOTP can be extracted
⚠️ **No backup codes yet** - Consider implementing backup codes for production

---

## 📞 Support

### User Lost Authenticator App?
**Manual recovery process**:
1. User contacts support
2. Verify identity (government ID, etc.)
3. Admin manually regenerates TOTP secret:
   ```php
   $user = User::where('email', 'user@example.com')->first();
   $google2fa = new Google2FA();
   $newSecret = $google2fa->generateSecretKey();
   $user->totp_secret = $newSecret;
   $user->save();
   
   // Send new secret to user via secure channel
   echo "New TOTP Secret: " . $newSecret;
   $qrCodeUrl = $google2fa->getQRCodeUrl(
       config('app.name'),
       $user->email,
       $newSecret
   );
   echo "QR Code URL: https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrCodeUrl);
   ```

---

## 🎉 Success!

You now have a **production-ready TOTP authentication system** that:
- ✅ Protects against email compromise
- ✅ Uses industry-standard TOTP (RFC 6238)
- ✅ Works with all major authenticator apps
- ✅ Provides strong two-factor security
- ✅ Maintains user-friendly experience

**Next Steps**:
1. Test all scenarios above
2. Consider adding backup codes
3. Implement account recovery flow
4. Add TOTP to login (optional 2FA on every login)
5. Deploy to production with HTTPS
