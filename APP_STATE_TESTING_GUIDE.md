# 🧪 FCM App State Testing Guide

## 📱 Test Scenarios

### 1️⃣ **App Closed Test**
**Goal**: Verify notifications appear when app is completely closed

**Steps:**
1. Close the app completely (swipe from recent apps)
2. Run: `php test_app_states.php app_closed`
3. **Expected**: Notification should appear in system tray
4. Tap notification → App should open to home screen

**What to Check:**
- ✅ Notification appears in system tray
- ✅ Notification sound plays
- ✅ Tapping opens the app
- ✅ Logcat shows: "FCM Message received"

---

### 2️⃣ **App Background Test**
**Goal**: Verify notifications appear when app is in background

**Steps:**
1. Open the app
2. Press home button (app runs in background)
3. Run: `php test_app_states.php app_background`
4. **Expected**: Notification should appear in system tray
5. Tap notification → App should come to foreground

**What to Check:**
- ✅ Notification appears in system tray
- ✅ App state preserved when returning
- ✅ Notification handled correctly

---

### 3️⃣ **App Foreground Test**
**Goal**: Verify silent handling when app is visible

**Steps:**
1. Keep the app open and visible on screen
2. Run: `php test_app_states.php app_foreground`
3. **Expected**: Silent handling or in-app banner

**What to Check:**
- ✅ No system tray notification (silent)
- ✅ Logcat shows: "FCM Message received"
- ✅ App handles data payload
- ✅ No disruption to user experience

---

## 🔧 Testing Commands

### Run Individual Tests:
```bash
# App closed test
php test_app_states.php app_closed

# App background test  
php test_app_states.php app_background

# App foreground test
php test_app_states.php app_foreground
```

### Check Device Tokens:
```bash
php check_notifications.php
```

### Send Custom Test:
```bash
php test_fcm_service.php
```

---

## 📊 Expected Behavior Matrix

| App State | Notification Display | Sound | Vibration | App Navigation |
|-----------|-------------------|-------|-----------|----------------|
| **Closed** | System Tray | ✅ | ✅ | Opens to Home |
| **Background** | System Tray | ✅ | ✅ | Returns to App |
| **Foreground** | Silent/In-App | ❌ | ❌ | No Navigation |

---

## 🔍 Debugging Tips

### Check Android Logcat:
```bash
adb logcat -s FCMService
adb logcat -s NotificationHandler
adb logcat -s MainActivity
```

### Look for These Logs:
- `"FCM Message received"`
- `"Handling notification click for type"`
- `"FCM Token sent to backend successfully"`

### Common Issues:
1. **No notification**: Check notification permissions
2. **No sound**: Check device sound settings
3. **App crashes**: Check notification icon exists
4. **Token expired**: Clear app data and re-login

---

## ✅ Success Criteria

All tests pass when:
- [ ] App closed → Notification in system tray ✅
- [ ] App background → Notification in system tray ✅  
- [ ] App foreground → Silent handling ✅
- [ ] All notifications open app correctly ✅
- [ ] No app crashes or ANRs ✅

---

## 🚨 Troubleshooting

### If notifications don't appear:
1. Check app notification permissions
2. Verify device token is registered
3. Check Laravel logs for errors
4. Restart the app completely

### If app crashes on notification:
1. Check notification icon resource
2. Verify MainActivity handles intent extras
3. Check for null pointer exceptions

### If token registration fails:
1. Check network connectivity
2. Verify Firebase authentication
3. Check Laravel backend logs

---

## 📱 Testing Checklist

Before testing:
- [ ] App is built and installed
- [ ] User is logged in
- [ ] Device token is registered
- [ ] Laravel server is running
- [ ] FCM Service Account is configured

After each test:
- [ ] Clear notification
- [ ] Reset app state
- [ ] Check logs for errors
- [ ] Verify expected behavior

---

**🎯 Goal: Ensure notifications work reliably in all app states!**
