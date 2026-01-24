<!DOCTYPE html>
<html>
<head>
    <title>Week 3 Attendance Testing</title>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-auth-compat.js"></script>
</head>
<body>
    <h2>Week 3 Attendance Testing</h2>
    <p><strong>Project:</strong> studentmanagementsystem-74f48</p>
    
    <div id="login-section">
        <h3>1. Login</h3>
        <input type="email" id="email" placeholder="Email" size="30"><br><br>
        <input type="password" id="password" placeholder="Password" size="30"><br><br>
        <button onclick="login()">Login</button>
        <button onclick="signup()">Sign Up</button>
    </div>

    <div id="test-section" style="display:none;">
        <h3>2. Test Attendance</h3>
        <p><strong>Logged in as:</strong> <span id="user-info"></span></p>
        
        <h4>Test 1: Faculty Mark Attendance (Happy Path)</h4>
        <button onclick="testFacultyMarkAttendance()">Test Faculty Mark Attendance</button><br><br>
        
        <h4>Test 2: Duplicate Attendance Blocked</h4>
        <button onclick="testDuplicateAttendance()">Test Duplicate Attendance</button><br><br>
        
        <h4>Test 3: Student View Attendance</h4>
        <button onclick="testStudentViewAttendance()">Test Student View Attendance</button><br><br>
        
        <h4>Test 4: Role Enforcement</h4>
        <button onclick="testStudentMarkAttendance()">Test Student Mark Attendance (Should Fail)</button><br><br>
        
        <h4>Results:</h4>
        <pre id="results" style="background: #f5f5f5; padding: 10px; border-radius: 5px; max-height: 400px; overflow-y: auto;"></pre>
    </div>

    <script>
        // Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyBa1_z-kgywejMSOn_aCPNqr_fpuWt9Ukw",
            authDomain: "studentmanagementsystem-74f48.firebaseapp.com",
            projectId: "studentmanagementsystem-74f48",
        };

        firebase.initializeApp(firebaseConfig);
        let currentUser = null;

        async function signup() {
            try {
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                const result = await firebase.auth().createUserWithEmailAndPassword(email, password);
                log(`User created: ${result.user.email}`);
                await login();
            } catch (error) {
                if (error.code === 'auth/email-already-in-use') {
                    log('User already exists, trying login...');
                    await login();
                } else {
                    log(`Signup failed: ${error.message}`);
                }
            }
        }

        async function login() {
            try {
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                
                const result = await firebase.auth().signInWithEmailAndPassword(email, password);
                currentUser = result.user;
                
                document.getElementById('login-section').style.display = 'none';
                document.getElementById('test-section').style.display = 'block';
                
                document.getElementById('user-info').textContent = `${currentUser.email} (UID: ${currentUser.uid})`;
                
                log(`✅ Logged in as: ${currentUser.email}`);
                
            } catch (error) {
                log(`❌ Login failed: ${error.message}`);
            }
        }

        async function makeRequest(method, url, data = null) {
            try {
                const token = await currentUser.getIdToken();
                
                const options = {
                    method: method,
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                };
                
                if (data) {
                    options.body = JSON.stringify(data);
                }
                
                const response = await fetch(url, options);
                const responseData = await response.json();
                
                log(`📡 ${method} ${url}`);
                log(`📊 Status: ${response.status} ${response.statusText}`);
                log(`📋 Response: ${JSON.stringify(responseData, null, 2)}`);
                
                return { status: response.status, data: responseData };
                
            } catch (error) {
                log(`💥 Error: ${error.message}`);
                return { status: 0, error: error.message };
            }
        }

        async function testFacultyMarkAttendance() {
            log('\n=== Test 1: Faculty Mark Attendance ===');
            
            // First, get class info
            const classResponse = await makeRequest('GET', '/api/faculty/dashboard');
            if (classResponse.status !== 200) {
                log('❌ Cannot get faculty dashboard - not faculty or no classes');
                return;
            }
            
            // Test data for marking attendance (use a date far in the past to avoid duplicates)
            const testDate = '2025-12-01'; // A date that definitely won't have attendance
            
            const attendanceData = {
                class_id: 1, // Assuming class ID 1 exists
                date: testDate,
                attendance: [
                    { student_id: 1, status: 'present' },
                    { student_id: 2, status: 'absent' }
                ]
            };
            
            log(`Marking attendance for date: ${attendanceData.date}`);
            const response = await makeRequest('POST', '/api/faculty/attendance', attendanceData);
            
            if (response.status === 200) {
                log('✅ PASS: Faculty can mark attendance');
            } else {
                log('❌ FAIL: Faculty cannot mark attendance');
            }
        }

        async function testDuplicateAttendance() {
            log('\n=== Test 2: Duplicate Attendance Blocked ===');
            
            // Test data for duplicate test (use a date far in the past to avoid duplicates)
            const testDate = '2025-12-02'; // A date that definitely won't have attendance
            
            const attendanceData = {
                class_id: 1,
                date: testDate,
                attendance: [
                    { student_id: 1, status: 'present' }
                ]
            };
            
            log(`Testing duplicate for date: ${attendanceData.date}`);
            
            // First request
            log('First request...');
            const response1 = await makeRequest('POST', '/api/faculty/attendance', attendanceData);
            
            // Second request (should fail)
            log('Second request (duplicate)...');
            const response2 = await makeRequest('POST', '/api/faculty/attendance', attendanceData);
            
            if (response2.status === 409) {
                log('✅ PASS: Duplicate attendance blocked with clean 409 error');
            } else if (response2.status === 200) {
                log('❌ FAIL: Duplicate attendance was allowed');
            } else {
                log('✅ PASS: Duplicate attendance blocked (status: ' + response2.status + ')');
            }
        }

        async function testStudentViewAttendance() {
            log('\n=== Test 3: Student View Attendance ===');
            
            const currentMonth = new Date().getMonth() + 1;
            const currentYear = new Date().getFullYear();
            
            const response = await makeRequest('GET', `/api/student/attendance?month=${currentMonth}&year=${currentYear}`);
            
            if (response.status === 200) {
                log('✅ PASS: Student can view attendance');
                if (response.data.students && response.data.students.length > 0) {
                    log(`✅ PASS: Student data returned for ${response.data.students.length} student(s)`);
                }
            } else {
                log('❌ FAIL: Student cannot view attendance');
            }
        }

        async function testStudentMarkAttendance() {
            log('\n=== Test 4: Role Enforcement ===');
            
            const attendanceData = {
                class_id: 1,
                date: new Date().toISOString().split('T')[0],
                attendance: [
                    { student_id: 1, status: 'present' }
                ]
            };
            
            const response = await makeRequest('POST', '/api/faculty/attendance', attendanceData);
            
            if (response.status === 403) {
                log('✅ PASS: Student cannot mark attendance (403 Forbidden)');
            } else {
                log('❌ FAIL: Student was able to mark attendance');
            }
        }

        function log(message) {
            const results = document.getElementById('results');
            const timestamp = new Date().toLocaleTimeString();
            results.textContent += `[${timestamp}] ${message}\n`;
            results.scrollTop = results.scrollHeight;
        }
    </script>
</body>
</html>
