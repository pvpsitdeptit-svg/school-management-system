<!DOCTYPE html>
<html>
<head>
    <title>Week 4 Exams & Marks Testing</title>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-auth-compat.js"></script>
</head>
<body>
    <h2>Week 4 Exams & Marks Testing</h2>
    <p><strong>Project:</strong> studentmanagementsystem-74f48</p>
    
    <div id="login-section">
        <h3>1. Login</h3>
        <input type="email" id="email" placeholder="Email" size="30"><br><br>
        <input type="password" id="password" placeholder="Password" size="30"><br><br>
        <button onclick="login()">Login</button>
        <button onclick="signup()">Sign Up</button>
    </div>

    <div id="test-section" style="display:none;">
        <h3>2. Test Exams & Marks</h3>
        <p><strong>Logged in as:</strong> <span id="user-info"></span></p>
        
        <h4>Test 1: Admin Create Exam (Admin Only)</h4>
        <button onclick="testAdminCreateExam()">Test Admin Create Exam</button><br><br>
        
        <h4>Test 2: Admin Add Subjects to Exam (Admin Only)</h4>
        <button onclick="testAdminAddSubjects()">Test Admin Add Subjects</button><br><br>
        
        <h4>Test 3: Faculty Upload Marks (Faculty Only)</h4>
        <button onclick="testFacultyUploadMarks()">Test Faculty Upload Marks</button><br><br>
        
        <h4>Test 4: Student View Marks (Student Only)</h4>
        <button onclick="testStudentViewMarks()">Test Student View Marks</button><br><br>
        
        <h4>Test 5: Publish Exam (Admin Only)</h4>
        <button onclick="testPublishExam()">Test Publish Exam</button><br><br>
        
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
        let createdExamId = null;
        let createdExamSubjectId = null;

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

        async function testAdminCreateExam() {
            log('\n=== Test 1: Admin Create Exam ===');
            
            const examData = {
                name: 'Mid Term Test',
                class_id: 1, // Assuming class ID 1 exists
                exam_date: '2026-01-25'
            };
            
            const response = await makeRequest('POST', '/api/admin/exams', examData);
            
            if (response.status === 201) {
                createdExamId = response.data.exam.id;
                log('✅ PASS: Admin can create exam');
                log(`📝 Created exam ID: ${createdExamId}`);
            } else {
                log('❌ FAIL: Admin cannot create exam');
            }
        }

        async function testAdminAddSubjects() {
            log('\n=== Test 2: Admin Add Subjects ===');
            
            if (!createdExamId) {
                log('❌ FAIL: No exam ID available. Create exam first.');
                return;
            }
            
            const subjectsData = {
                subjects: [
                    { subject_id: 1, max_marks: 100 }, // Assuming subject ID 1 exists
                    { subject_id: 2, max_marks: 50 }   // Assuming subject ID 2 exists
                ]
            };
            
            const response = await makeRequest('POST', `/api/admin/exams/${createdExamId}/subjects`, subjectsData);
            
            if (response.status === 200) {
                createdExamSubjectId = response.data.subjects[0].id;
                log('✅ PASS: Admin can add subjects to exam');
                log(`📝 Created exam subject ID: ${createdExamSubjectId}`);
            } else {
                log('❌ FAIL: Admin cannot add subjects to exam');
            }
        }

        async function testFacultyUploadMarks() {
            log('\n=== Test 3: Faculty Upload Marks ===');
            
            // Use the final exam ID (5) that was created for complete testing
            const examId = 5;
            
            // Get exam subjects for the exam
            const examSubjectsResponse = await makeRequest('GET', `/api/exam-subjects?exam_id=${examId}`);
            
            if (examSubjectsResponse.status !== 200 || !examSubjectsResponse.data.exam_subjects || examSubjectsResponse.data.exam_subjects.length === 0) {
                log('❌ FAIL: No exam subjects found.');
                return;
            }
            
            const examSubjectId = examSubjectsResponse.data.exam_subjects[0].id;
            const examStatus = examSubjectsResponse.data.exam_subjects[0].exam_status;
            
            log(`📝 Using exam ID: ${examId}`);
            log(`📝 Using exam subject ID: ${examSubjectId}`);
            log(`📝 Exam status: ${examStatus}`);
            
            const marksData = {
                exam_subject_id: examSubjectId,
                marks: [
                    { student_id: 1, marks_obtained: 85 },
                    { student_id: 2, marks_obtained: 72 }
                ]
            };
            
            const response = await makeRequest('POST', '/api/faculty/marks', marksData);
            
            if (response.status === 200) {
                log('✅ PASS: Faculty can upload marks');
            } else {
                log('❌ FAIL: Faculty cannot upload marks');
            }
        }

        async function testStudentViewMarks() {
            log('\n=== Test 4: Student View Marks ===');
            
            const response = await makeRequest('GET', '/api/student/marks');
            
            if (response.status === 200) {
                log('✅ PASS: Student can view marks');
                if (response.data.exams && response.data.exams.length > 0) {
                    log(`✅ PASS: Found ${response.data.exams.length} exam(s)`);
                } else {
                    log('ℹ️ INFO: No published exams found');
                }
            } else {
                log('❌ FAIL: Student cannot view marks');
            }
        }

        async function testPublishExam() {
            log('\n=== Test 5: Publish Exam ===');
            
            // Use the final exam ID (5) that faculty will upload marks to
            const examId = 5;
            
            const response = await makeRequest('POST', `/api/admin/exams/${examId}/publish`);
            
            if (response.status === 200) {
                log('✅ PASS: Admin can publish exam');
                log(`📝 Published exam ID: ${examId}`);
            } else {
                log('❌ FAIL: Admin cannot publish exam');
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
