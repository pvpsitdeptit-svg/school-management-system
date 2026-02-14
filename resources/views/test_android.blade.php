<!DOCTYPE html>
<html>
<head>
    <title>Week 6 Android App API Testing</title>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-auth-compat.js"></script>
</head>
<body>
    <h2>Week 6 Android App API Testing</h2>
    <p><strong>Project:</strong> your-project-id</p>
    
    <div id="login-section">
        <h3>1. Login (Student/Parent)</h3>
        <input type="email" id="email" placeholder="Email" size="30"><br><br>
        <input type="password" id="password" placeholder="Password" size="30"><br><br>
        <button onclick="login()">Login</button>
    </div>

    <div id="test-section" style="display:none;">
        <h3>2. Test Android App APIs</h3>
        <p><strong>Logged in as:</strong> <span id="user-info"></span></p>
        
        <h4>Test 1: Student Profile</h4>
        <button onclick="testStudentProfile()">Get Profile</button><br><br>
        
        <h4>Test 2: Attendance View</h4>
        <button onclick="testAttendance()">Get Attendance</button>
        <select id="month-selector">
            <option value="">All Months</option>
        </select><br><br>
        
        <h4>Test 3: Marks View</h4>
        <button onclick="testMarks()">Get Marks</button><br><br>
        
        <h4>Results:</h4>
        <pre id="results" style="background: #f5f5f5; padding: 10px; border-radius: 5px; max-height: 400px; overflow-y: auto;"></pre>
    </div>

    <script>
        // Firebase configuration
        const firebaseConfig = {
            apiKey: "your-api-key",
            authDomain: "your-project-id.firebaseapp.com",
            projectId: "your-project-id",
        };

        firebase.initializeApp(firebaseConfig);
        let currentUser = null;
        let availableMonths = [];

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
                
                // Load available months after login
                await loadAvailableMonths();
                
            } catch (error) {
                log(`❌ Login failed: ${error.message}`);
            }
        }

        async function loadAvailableMonths() {
            try {
                const response = await makeRequest('GET', '/api/student/attendance');
                if (response.status === 200 && response.data.available_months) {
                    availableMonths = response.data.available_months;
                    const selector = document.getElementById('month-selector');
                    
                    // Clear existing options except "All Months"
                    while (selector.children.length > 1) {
                        selector.removeChild(selector.lastChild);
                    }
                    
                    // Add month options
                    availableMonths.forEach(month => {
                        const option = document.createElement('option');
                        option.value = month;
                        option.textContent = formatMonth(month);
                        selector.appendChild(option);
                    });
                }
            } catch (error) {
                log(`⚠️ Could not load available months: ${error.message}`);
            }
        }

        function formatMonth(monthStr) {
            const [year, month] = monthStr.split('-');
            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            return `${monthNames[parseInt(month) - 1]} ${year}`;
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

        async function testStudentProfile() {
            log('\n=== Test 1: Student Profile ===');
            
            const response = await makeRequest('GET', '/api/student/profile');
            
            if (response.status === 200) {
                log('✅ PASS: Profile retrieved successfully');
                log(`👤 Student: ${response.data.student.name}`);
                log(`📧 Email: ${response.data.student.email}`);
                log(`🏫 Class: ${response.data.student.class.name} ${response.data.student.class.section}`);
                log(`🏢 School: ${response.data.student.school.name}`);
            } else {
                log('❌ FAIL: Cannot retrieve profile');
            }
        }

        async function testAttendance() {
            log('\n=== Test 2: Attendance View ===');
            
            const selectedMonth = document.getElementById('month-selector').value;
            const url = selectedMonth ? `/api/student/attendance?month=${selectedMonth}` : '/api/student/attendance';
            
            const response = await makeRequest('GET', url);
            
            if (response.status === 200) {
                log('✅ PASS: Attendance retrieved successfully');
                log(`📊 Total Days: ${response.data.statistics.total_days}`);
                log(`✅ Present Days: ${response.data.statistics.present_days}`);
                log(`❌ Absent Days: ${response.data.statistics.absent_days}`);
                log(`📈 Attendance %: ${response.data.statistics.attendance_percentage}%`);
                log(`📅 Available Months: ${response.data.available_months.join(', ')}`);
            } else {
                log('❌ FAIL: Cannot retrieve attendance');
            }
        }

        async function testMarks() {
            log('\n=== Test 3: Marks View ===');
            
            const response = await makeRequest('GET', '/api/student/marks');
            
            if (response.status === 200) {
                log('✅ PASS: Marks retrieved successfully');
                log(`📚 Total Exams: ${response.data.overall_statistics.total_exams}`);
                log(`📊 Overall Percentage: ${response.data.overall_statistics.overall_percentage}%`);
                
                response.data.exams.forEach((exam, index) => {
                    log(`\n📝 Exam ${index + 1}: ${exam.exam.name}`);
                    log(`📅 Date: ${exam.exam.exam_date}`);
                    log(`📊 Exam %: ${exam.exam_statistics.percentage}%`);
                    
                    exam.subjects.forEach(subject => {
                        log(`  📚 ${subject.subject.name}: ${subject.marks_obtained}/${subject.max_marks} (${subject.percentage}%)`);
                    });
                });
            } else {
                log('❌ FAIL: Cannot retrieve marks');
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
