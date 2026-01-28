<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Login - School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1565C0;
            --secondary-color: #00897B;
            --accent-color: #FFB300;
            --dark-color: #212121;
            --light-bg: #F5F5F5;
            --success-color: #4CAF50;
            --warning-color: #FF9800;
            --danger-color: #D32F2F;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            min-height: 600px;
        }

        .login-left {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
        }

        .login-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .login-left-content {
            position: relative;
            z-index: 1;
        }

        .school-logo {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
        }

        .login-left h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .login-left p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 40px;
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .features-list li {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .features-list i {
            width: 24px;
            margin-right: 15px;
            font-size: 1.2rem;
        }

        .login-right {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-form h2 {
            font-size: 2rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 10px;
        }

        .login-form p {
            color: #666;
            margin-bottom: 40px;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(21, 101, 192, 0.1);
        }

        .form-floating label {
            color: #666;
        }

        .form-floating .form-control:focus ~ label {
            color: var(--primary-color);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 10px;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(21, 101, 192, 0.3);
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e0e0e0;
        }

        .divider span {
            background: white;
            padding: 0 20px;
            color: #666;
            position: relative;
        }

        .social-login {
            display: flex;
            gap: 15px;
        }

        .social-btn {
            flex: 1;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px;
            background: white;
            color: #666;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .social-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #ffebee;
            color: var(--danger-color);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            z-index: 10;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #ffffff;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .btn-login.loading .btn-text {
            display: none;
        }

        .btn-login.loading .loading-spinner {
            display: inline-block;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                max-width: 400px;
                min-height: auto;
            }

            .login-left {
                padding: 40px 30px;
            }

            .login-left h1 {
                font-size: 2rem;
            }

            .login-right {
                padding: 40px 30px;
            }

            .features-list {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="row g-0 h-100">
            <!-- Left Side - Branding -->
            <div class="col-lg-6">
                <div class="login-left h-100">
                    <div class="login-left-content">
                        <div class="school-logo">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h1>School Management System</h1>
                        <p>Empowering education through technology and innovation</p>
                        
                        <ul class="features-list">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Comprehensive Student Management</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Real-time Attendance Tracking</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Digital Grade & Mark Management</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Secure Role-Based Access</span>
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                <span>Mobile-Friendly Interface</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="col-lg-6">
                <div class="login-right">
                    <div class="login-form">
                        <h2>Welcome Back</h2>
                        <p>Sign in to access your dashboard</p>

                        <!-- Alert for errors -->
                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Error!</strong> {{ $errors->first() }}
                            </div>
                        @endif

                        <!-- Alert for success messages -->
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('status') }}
                            </div>
                        @endif

                        <form id="loginForm" method="POST" action="{{ route('login.store') }}">
                            @csrf
                            <input type="hidden" name="id_token" id="id_token">
                        
                            <div class="form-floating mb-3">
                                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                <label for="email">Email address</label>
                            </div>
                        
                            <div class="form-floating mb-4">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                <label for="password">Password</label>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>

                            <button type="submit" class="btn btn-login">
                                <span class="btn-text">Sign In</span>
                                <div class="loading-spinner"></div>
                            </button>
                        </form>

                        <div class="divider">
                            <span>Or continue with</span>
                        </div>

                        <div class="social-login">
                            <button class="btn social-btn">
                                <i class="fab fa-google me-2"></i>
                                Google
                            </button>
                            <button class="btn social-btn">
                                <i class="fab fa-microsoft me-2"></i>
                                Microsoft
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <p class="mb-0">
                                Don't have an account? 
                                <a href="#" class="text-decoration-none" style="color: var(--primary-color); font-weight: 600;">
                                    Contact Administrator
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-auth-compat.js"></script>

    <script>
        // Firebase configuration
        const firebaseConfig = {
            apiKey: "{{ env('FIREBASE_API_KEY', 'your-api-key-here') }}",
            authDomain: "{{ env('FIREBASE_AUTH_DOMAIN', 'your-project.firebaseapp.com') }}",
            projectId: "{{ env('FIREBASE_PROJECT_ID', 'your-project-id') }}",
            storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET', 'your-project.appspot.com') }}",
            messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID', '123456789012') }}",
            appId: "{{ env('FIREBASE_APP_ID', '1:123456789012:web:abcdef123456789012345678') }}"
        };

        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);
        const auth = firebase.auth();

        // Handle form submission
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const loginBtn = document.querySelector('.btn-login');
            const idTokenInput = document.getElementById('id_token');
            
            // Show loading state
            loginBtn.classList.add('loading');
            loginBtn.disabled = true;
            
            try {
                // Sign in with Firebase
                const userCredential = await auth.signInWithEmailAndPassword(email, password);
                
                // Get ID token
                const idToken = await userCredential.user.getIdToken();
                
                // Set token in hidden input
                idTokenInput.value = idToken;
                
                // Submit form to Laravel
                this.submit();
                
            } catch (error) {
                console.error('Firebase authentication error:', error);
                
                // Show error message
                let errorMessage = 'Authentication failed. Please try again.';
                
                switch(error.code) {
                    case 'auth/user-not-found':
                        errorMessage = 'User not found. Please check your email.';
                        break;
                    case 'auth/wrong-password':
                        errorMessage = 'Incorrect password. Please try again.';
                        break;
                    case 'auth/invalid-email':
                        errorMessage = 'Invalid email address.';
                        break;
                    case 'auth/user-disabled':
                        errorMessage = 'Account has been disabled.';
                        break;
                    case 'auth/too-many-requests':
                        errorMessage = 'Too many failed attempts. Please try again later.';
                        break;
                }
                
                // Show error alert
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                alertDiv.innerHTML = `
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${errorMessage}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                const form = document.getElementById('loginForm');
                form.parentNode.insertBefore(alertDiv, form);
                
                // Remove loading state
                loginBtn.classList.remove('loading');
                loginBtn.disabled = false;
                
                // Auto-hide alert after 5 seconds
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 5000);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        document.querySelector('form').addEventListener('submit', function(e) {
            const loginBtn = document.querySelector('.btn-login');
            loginBtn.classList.add('loading');
        });
    </script>
    <script>
        // Force refresh on page load to prevent caching issues
        if (performance.navigation.type === 2) {
            window.location.reload(true);
        }
        
        // Clear any stored authentication data
        localStorage.removeItem('auth_token');
        sessionStorage.clear();
        
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is somehow still authenticated and redirect if needed
            const currentPath = window.location.pathname;
            if (currentPath !== '/login' && currentPath !== '/') {
                console.log('Redirecting to login due to authentication state');
                window.location.href = '/login';
            }
        });
    </script>
</body>
</html>
