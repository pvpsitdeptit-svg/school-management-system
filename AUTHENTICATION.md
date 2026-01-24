# Authentication & Access Control Documentation

## Overview
This system uses Firebase Authentication for user identity and Laravel for role-based access control across multiple schools.

## How Authentication Works

### 1. Firebase Integration
- **Firebase Project**: Single project handles all authentication
- **Supported Methods**: Email/Password (Phone optional)
- **Platforms**: Web app + Android app (com.ukv.studentmanagementsystem)

### 2. Token Verification Flow
```
Client → Firebase Login → ID Token → Laravel API → Verify Token → User Authenticated
```

1. User logs in via Firebase (Web/Android)
2. Firebase returns ID token
3. Client includes token in `Authorization: Bearer <token>` header
4. Laravel verifies token with Google's public keys
5. User is authenticated and role is checked

### 3. User Mapping
- **Existing User**: Found by `firebase_uid`
- **New User**: Created with `student` role, `pending` status
- **School Assignment**: Admin must assign new users to schools

## School Resolution

### Multi-Tenant Architecture
Every request automatically identifies the school:

1. **Subdomain**: `demo.localhost.com` → Demo High School
2. **Custom Domain**: `school.com` → Specific school
3. **Local Dev**: Falls back to first active school

### Data Isolation
- All queries automatically scoped by `school_id`
- Users can only access data from their school
- Cross-school access is impossible by design

## Role-Based Access Control

### User Roles
- `super_admin`: Can access all schools
- `admin`: Can manage their school only
- `faculty`: Can teach classes, manage attendance/marks
- `student`: Can view own data
- `parent`: Can view child's data

### Middleware Protection
```php
// Admin only
Route::middleware(['auth.firebase', 'admin'])->group(...);

// Faculty only  
Route::middleware(['auth.firebase', 'faculty'])->group(...);

// Students/Parents only
Route::middleware(['auth.firebase', 'student.or.parent'])->group(...);
```

## API Endpoints

### Public
- `GET /api/health` - System status

### Authentication Required
- `GET /api/me` - Current user info + permissions

### Role-Protected
- `GET /api/admin/dashboard` - Admin stats
- `GET /api/admin/users` - School user list
- `GET /api/faculty/dashboard` - Faculty stats
- `GET /api/student/profile` - Student/Parent profile

## Security Features

### Token Security
- Firebase ID tokens are cryptographically signed
- Tokens expire automatically (1 hour)
- Invalid/expired tokens are rejected

### Data Security
- All database queries scoped by `school_id`
- Role-based middleware prevents unauthorized access
- Users cannot access other schools' data

### Access Control
- 401 Unauthorized: Missing/invalid token
- 403 Forbidden: Insufficient role permissions
- Automatic school detection prevents cross-school access

## Setup Instructions

### Firebase Configuration
1. Create Firebase project at https://console.firebase.google.com
2. Enable Authentication → Email/Password
3. Add Web app configuration
4. Add Android app (package: com.ukv.studentmanagementsystem)
5. Add `FIREBASE_PROJECT_ID=your-project-id` to `.env`

### Client Integration
```javascript
// Firebase Login
const result = await signInWithEmailAndPassword(auth, email, password);
const token = await result.user.getIdToken();

// API Call
const response = await fetch('/api/me', {
  headers: {
    'Authorization': `Bearer ${token}`
  }
});
```

## Testing

### Security Tests
✅ Unauthenticated requests blocked (401)
✅ Wrong role access blocked (403)  
✅ Cross-school data access prevented
✅ Token validation working
✅ School resolution functional

### Manual Testing
1. Get Firebase ID token from authenticated client
2. Test endpoints with/without tokens
3. Verify role-based access restrictions
4. Confirm school data isolation
