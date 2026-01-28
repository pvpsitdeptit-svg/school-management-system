# 🔥 FIREBASE TOKEN-BASED AUTHENTICATION FLOW

## 🎯 **CORRECT AUTHENTICATION FLOW**

### **Step 1: User Login (Frontend)**
```javascript
// User enters email/password
// Firebase Authentication
const userCredential = await auth.signInWithEmailAndPassword(email, password);

// Get Firebase ID Token
const idToken = await userCredential.user.getIdToken();

// Send token to Laravel
form.submit(); // Contains id_token in hidden field
```

### **Step 2: Laravel Verification (Backend)**
```php
// LoginController.php
public function login(Request $request)
{
    // Verify Firebase ID Token
    $firebaseUser = $this->verifyFirebaseToken($request->id_token);
    
    // Find user by Firebase UID
    $user = User::where('firebase_uid', $firebaseUser['localId'])->first();
    
    // Check role and redirect
    if ($user->role === 'super_admin') {
        return redirect('/super-admin/dashboard');
    }
}
```

### **Step 3: Role-Based Redirect**
```php
private function redirectBasedOnRole($user)
{
    switch ($user->role) {
        case 'super_admin':
            return redirect('/super-admin/dashboard');
        case 'school_admin':
        case 'faculty':
        case 'student':
            return redirect('/dashboard');
    }
}
```

## 🚀 **SUPER ADMIN FLOW EXAMPLE**

```
1. Super Admin enters email/password
   ↓
2. Firebase authenticates user
   ↓
3. Firebase returns ID token
   ↓
4. Token sent to Laravel
   ↓
5. Laravel verifies token with Firebase
   ↓
6. Laravel finds user by firebase_uid
   ↓
7. Role = super_admin
   ↓
8. Redirect → /super-admin/dashboard
   ↓
🎉 Done!
```

## 🔧 **TECHNICAL IMPLEMENTATION**

### **Firebase Token Verification**:
```php
private function verifyFirebaseToken($idToken)
{
    $response = Http::post(
        "https://identitytoolkit.googleapis.com/v1/accounts:lookup?key={$apiKey}",
        ['idToken' => $idToken]
    );
    
    return $response->successful() ? $data['users'][0] : null;
}
```

### **User Lookup**:
```php
// Find user by Firebase UID (not email)
$user = User::where('firebase_uid', $firebaseUser['localId'])->first();
```

### **Security**:
- ✅ Firebase tokens are short-lived (1 hour)
- ✅ Tokens are verified with Firebase API
- ✅ No passwords stored in Laravel database
- ✅ Firebase UID is primary identifier

## 📊 **USER TABLE STRUCTURE**

```sql
users table:
┌─────┬─────────┬─────────────────┬──────────────┬─────────────┐
│ id  │ name    │ email           │ firebase_uid │ role        │
├─────┼─────────┼─────────────────┼──────────────┼─────────────┤
│ 1   │ Admin   │ admin@sms.com   │ NULL         │ super_admin │
│ 12  │ John    │ john@school.com │ abc123def    │ school_admin│
│ 13  │ Jane    │ jane@school.com │ xyz789uvw    │ faculty     │
└─────┴─────────┴─────────────────┴──────────────┴─────────────┘
```

**Note**: Super admins may have NULL firebase_uid (legacy), but all other users MUST have firebase_uid.

## 🎯 **LOGIN PROCESS**

### **Frontend (JavaScript)**:
1. User enters credentials
2. Firebase authenticates
3. Get ID token
4. Submit form with token

### **Backend (Laravel)**:
1. Receive ID token
2. Verify with Firebase
3. Extract Firebase UID
4. Find user by UID
5. Check role
6. Create Laravel session
7. Redirect based on role

## 🛡️ **SECURITY BENEFITS**

✅ **No Password Storage**: No passwords in Laravel database
✅ **Token-Based**: Firebase tokens are secure and short-lived
✅ **Firebase Authority**: Firebase is the single source of truth
✅ **Role Separation**: Laravel handles roles/permissions only
✅ **Session Management**: Laravel sessions for web interface

## 🔄 **SCHOOL CREATION FLOW**

When Super Admin creates a new school:

```php
1. Create Firebase user first
$firebaseResult = $this->firebaseService->createUser($email, $password, $name);

2. Get Firebase UID
$firebaseUid = $firebaseResult['data']['localId'];

3. Create Laravel user with Firebase UID
User::create([
    'name' => $name,
    'email' => $email,
    'firebase_uid' => $firebaseUid, // Required!
    'role' => 'school_admin',
    'school_id' => $school->id,
    'password' => null, // Not used
]);
```

## 🚨 **IMPORTANT NOTES**

### **Every User MUST Have**:
- ✅ Firebase account
- ✅ Firebase UID in database (except legacy super admins)
- ✅ Valid email in Firebase

### **Authentication Flow**:
- ✅ Frontend: Firebase SDK
- ✅ Backend: Firebase token verification
- ✅ Database: Firebase UID lookup
- ✅ Session: Laravel auth system

### **No Laravel Passwords**:
- ❌ No password hashing in Laravel
- ❌ No Auth::attempt() with passwords
- ✅ Only Firebase token verification

## 🎉 **COMPLETE FLOW**

```
User Login
    ↓
Firebase Authentication
    ↓
Get ID Token
    ↓
Send to Laravel
    ↓
Verify Token
    ↓
Find User by Firebase UID
    ↓
Check Role
    ↓
Create Laravel Session
    ↓
Redirect to Dashboard
    ↓
🎉 AUTHENTICATED!
```

---

**🔥 This is the correct and secure Firebase token-based authentication flow!**

**All users authenticate via Firebase, Laravel verifies tokens, and redirects based on roles!**
