# Manual Testing Guide

## 1. Get Firebase ID Token

### Using Browser Console:
1. Open your Firebase web app
2. Login with any user
3. Open browser console (F12)
4. Run: `await firebase.auth().currentUser.getIdToken()`
5. Copy the returned token

### Using Firebase SDK:
```javascript
import { getAuth, signInWithEmailAndPassword } from "firebase/auth";

const auth = getAuth();
const result = await signInWithEmailAndPassword(auth, "admin@sms.com", "password");
const token = await result.user.getIdToken();
console.log(token);
```

## 2. Test API Endpoints

### Public Endpoint (Should Work)
```bash
curl http://localhost:8000/api/health
```

### Authenticated Endpoints (Replace YOUR_TOKEN_HERE)

#### Test User Profile
```bash
curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     http://localhost:8000/api/me
```

#### Test Admin Dashboard (Should work for admin@sms.com)
```bash
curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     http://localhost:8000/api/admin/dashboard
```

#### Test Faculty Dashboard (Should return 403 for admin)
```bash
curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     http://localhost:8000/api/faculty/dashboard
```

#### Test Student Profile (Should return 403 for admin)
```bash
curl -H "Authorization: Bearer YOUR_TOKEN_HERE" \
     http://localhost:8000/api/student/profile
```

## 3. Expected Results

### admin@sms.com (super_admin role)
- ✅ /api/health → 200 OK
- ✅ /api/me → 200 OK with user info
- ✅ /api/admin/dashboard → 200 OK with admin data
- ❌ /api/faculty/dashboard → 403 Forbidden
- ❌ /api/student/profile → 403 Forbidden

### Invalid/No Token
- ✅ /api/health → 200 OK
- ❌ /api/me → 401 Unauthorized
- ❌ All protected endpoints → 401 Unauthorized

### Invalid Token
```bash
curl -H "Authorization: Bearer invalid_token" \
     http://localhost:8000/api/me
# Expected: 401 Unauthorized
```

## 4. Cross-School Testing

Create a user in Test Academy and try to access Demo High School data:
- Should only see data from their own school
- School detection happens automatically via middleware
