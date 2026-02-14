<!DOCTYPE html>
<html>
<head>
    <title>Week 5 Admin & Academic Setup Testing</title>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.0.0/firebase-auth-compat.js"></script>
</head>
<body>
    <h2>Week 5 Admin & Academic Setup Testing</h2>
    <p><strong>Project:</strong> your-project-id</p>
    
    <div id="login-section">
        <h3>1. Login</h3>
        <input type="email" id="email" placeholder="Email" size="30"><br><br>
        <input type="password" id="password" placeholder="Password" size="30"><br><br>
        <button onclick="login()">Login</button>
        <button onclick="signup()">Sign Up</button>
    </div>

    <div id="test-section" style="display:none;">
        <h3>2. Test Admin Setup</h3>
        <p><strong>Logged in as:</strong> <span id="user-info"></span></p>
        
        <h4>Test 1: Admin Dashboard</h4>
        <button onclick="testAdminDashboard()">Test Admin Dashboard</button><br><br>
        
        <h4>Test 2: Class Management</h4>
        <button onclick="testCreateClass()">Create Class</button>
        <button onclick="testGetClasses()">Get Classes</button>
        <button onclick="testUpdateClass()">Update Class</button>
        <button onclick="testDeleteClass()">Delete Class</button><br><br>
        
        <h4>Test 3: Subject Management</h4>
        <button onclick="testCreateSubject()">Create Subject</button>
        <button onclick="testGetSubjects()">Get Subjects</button>
        <button onclick="testDeleteSubject()">Delete Subject</button><br><br>
        
        <h4>Test 4: Faculty Assignment</h4>
        <button onclick="testAssignFaculty()">Assign Faculty</button>
        <button onclick="testGetAssignments()">Get Assignments</button>
        <button onclick="testDeleteAssignment()">Delete Assignment</button><br><br>
        
        <h4>Test 5: Student Admission</h4>
        <button onclick="testAdmitStudent()">Admit Student</button>
        <button onclick="testGetStudents()">Get Students</button><br><br>
        
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
        let createdClassId = null;
        let createdSubjectId = null;
        let createdAssignmentId = null;
        let createdStudentId = null;

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

        async function testAdminDashboard() {
            log('\n=== Test 1: Admin Dashboard ===');
            
            const response = await makeRequest('GET', '/api/admin/dashboard');
            
            if (response.status === 200) {
                log('✅ PASS: Admin dashboard accessible');
                log(`📊 Total students: ${response.data.data.total_students}`);
                log(`📊 Total faculty: ${response.data.data.total_faculty}`);
                log(`📊 Total classes: ${response.data.data.total_classes}`);
                log(`📊 Total subjects: ${response.data.data.total_subjects}`);
            } else {
                log('❌ FAIL: Admin dashboard not accessible');
            }
        }

        async function testCreateClass() {
            log('\n=== Test 2: Create Class ===');
            
            const classData = {
                name: '12',
                section: 'B',
                status: 'active'
            };
            
            const response = await makeRequest('POST', '/api/admin/classes', classData);
            
            if (response.status === 201) {
                createdClassId = response.data.class.id;
                log('✅ PASS: Class created successfully');
                log(`📝 Created class ID: ${createdClassId}`);
            } else {
                log('❌ FAIL: Cannot create class');
            }
        }

        async function testGetClasses() {
            log('\n=== Test 3: Get Classes ===');
            
            const response = await makeRequest('GET', '/api/admin/classes');
            
            if (response.status === 200) {
                log('✅ PASS: Classes retrieved successfully');
                log(`📊 Total classes: ${response.data.total_classes}`);
            } else {
                log('❌ FAIL: Cannot retrieve classes');
            }
        }

        async function testUpdateClass() {
            log('\n=== Test 4: Update Class ===');
            
            if (!createdClassId) {
                log('❌ FAIL: No class ID available. Create class first.');
                return;
            }
            
            const updateData = {
                status: 'inactive'
            };
            
            const response = await makeRequest('PUT', `/api/admin/classes/${createdClassId}`, updateData);
            
            if (response.status === 200) {
                log('✅ PASS: Class updated successfully');
            } else {
                log('❌ FAIL: Cannot update class');
            }
        }

        async function testDeleteClass() {
            log('\n=== Test 5: Delete Class ===');
            
            if (!createdClassId) {
                log('❌ FAIL: No class ID available. Create class first.');
                return;
            }
            
            const response = await makeRequest('DELETE', `/api/admin/classes/${createdClassId}`);
            
            if (response.status === 200) {
                log('✅ PASS: Class deleted successfully');
                createdClassId = null;
            } else {
                log('❌ FAIL: Cannot delete class');
            }
        }

        async function testCreateSubject() {
            log('\n=== Test 6: Create Subject ===');
            
            const subjectData = {
                name: 'Physics',
                code: 'PHY'
            };
            
            const response = await makeRequest('POST', '/api/admin/subjects', subjectData);
            
            if (response.status === 201) {
                createdSubjectId = response.data.subject.id;
                log('✅ PASS: Subject created successfully');
                log(`📝 Created subject ID: ${createdSubjectId}`);
            } else {
                log('❌ FAIL: Cannot create subject');
            }
        }

        async function testGetSubjects() {
            log('\n=== Test 7: Get Subjects ===');
            
            const response = await makeRequest('GET', '/api/admin/subjects');
            
            if (response.status === 200) {
                log('✅ PASS: Subjects retrieved successfully');
                log(`📊 Total subjects: ${response.data.total_subjects}`);
            } else {
                log('❌ FAIL: Cannot retrieve subjects');
            }
        }

        async function testDeleteSubject() {
            log('\n=== Test 8: Delete Subject ===');
            
            if (!createdSubjectId) {
                log('❌ FAIL: No subject ID available. Create subject first.');
                return;
            }
            
            const response = await makeRequest('DELETE', `/api/admin/subjects/${createdSubjectId}`);
            
            if (response.status === 200) {
                log('✅ PASS: Subject deleted successfully');
                createdSubjectId = null;
            } else {
                log('❌ FAIL: Cannot delete subject');
            }
        }

        async function testAssignFaculty() {
            log('\n=== Test 9: Assign Faculty ===');
            
            // Use existing class and subject instead of creating new ones
            const assignmentData = {
                faculty_user_id: 4, // Assuming faculty user ID 4 exists
                class_id: 1, // Use existing class 10-A
                subject_id: 1 // Use existing subject Mathematics
            };
            
            const response = await makeRequest('POST', '/api/admin/faculty/assign', assignmentData);
            
            if (response.status === 201) {
                createdAssignmentId = response.data.assignment.id;
                log('✅ PASS: Faculty assigned successfully');
                log(`📝 Created assignment ID: ${createdAssignmentId}`);
            } else {
                log('❌ FAIL: Cannot assign faculty');
            }
        }

        async function testGetAssignments() {
            log('\n=== Test 10: Get Assignments ===');
            
            const response = await makeRequest('GET', '/api/admin/faculty/assignments');
            
            if (response.status === 200) {
                log('✅ PASS: Assignments retrieved successfully');
                log(`📊 Total assignments: ${response.data.total_assignments}`);
            } else {
                log('❌ FAIL: Cannot retrieve assignments');
            }
        }

        async function testDeleteAssignment() {
            log('\n=== Test 11: Delete Assignment ===');
            
            if (!createdAssignmentId) {
                log('❌ FAIL: No assignment ID available. Create assignment first.');
                return;
            }
            
            const response = await makeRequest('DELETE', `/api/admin/faculty/assignments/${createdAssignmentId}`);
            
            if (response.status === 200) {
                log('✅ PASS: Assignment deleted successfully');
                createdAssignmentId = null;
            } else {
                log('❌ FAIL: Cannot delete assignment');
            }
        }

        async function testAdmitStudent() {
            log('\n=== Test 12: Admit Student ===');
            
            // Use existing class instead of creating new one
            const studentData = {
                name: 'Test Student',
                email: 'teststudent' + Date.now() + '@demo.com',
                password: 'password',
                admission_no: 'ADM' + Date.now(),
                class_id: 1, // Use existing class 10-A
                status: 'active'
            };
            
            const response = await makeRequest('POST', '/api/admin/students', studentData);
            
            if (response.status === 201) {
                createdStudentId = response.data.student.id;
                log('✅ PASS: Student admitted successfully');
                log(`📝 Created student ID: ${createdStudentId}`);
            } else {
                log('❌ FAIL: Cannot admit student');
            }
        }

        async function testGetStudents() {
            log('\n=== Test 13: Get Students ===');
            
            const response = await makeRequest('GET', '/api/admin/students');
            
            if (response.status === 200) {
                log('✅ PASS: Students retrieved successfully');
                log(`📊 Total students: ${response.data.total_students}`);
            } else {
                log('❌ FAIL: Cannot retrieve students');
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
