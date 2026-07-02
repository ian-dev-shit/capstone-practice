<?php
// Isama ang configuration para sa API URL
include 'config/api_config.php';
$error = '';
$success = '';

// I-check kung pinindot na ang login button POST request
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if(empty($username) || empty($password)){
        $error = "All fields are required";
    }else{
        // 1. I-prepare ang payload data bilang JSON string
        $payload = json_encode([
            'username' => $username,
            'password' => $password
        ]);

        // 2. I-setup ang cURL request papunta sa Flask `/login` endpoint
        $url = API_BASE_URL . '/login';
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);
   
        // 3. I-execute ang cURL at kunin ang response at status code
        $response = curl_exec($ch);
        $HttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // 4. I-decode ang naging sagot ng Flask backend
        $data = json_decode($response, true);

        if($HttpCode === 200 && isset($data['user'])){
            $success = "Welcome back, " . htmlspecialchars($data['user']['username']) . "! 🎉";
            // You can redirect here if needed
            // header('Location: dashboard.php');
            // exit();
        } else {
            $error = "Login failed. Please check your credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Light Theme Colors */
            --bg-primary: #f0f2f5;
            --bg-secondary: #ffffff;
            --bg-card: rgba(255, 255, 255, 0.7);
            --bg-card-hover: rgba(255, 255, 255, 0.85);
            --text-primary: #1a1a2e;
            --text-secondary: #6b7280;
            --text-muted: #9ca3af;
            --border-color: rgba(0, 0, 0, 0.08);
            --border-hover: rgba(0, 0, 0, 0.15);
            --shadow-color: rgba(0, 0, 0, 0.08);
            --shadow-hover: rgba(0, 0, 0, 0.15);
            --input-bg: rgba(0, 0, 0, 0.03);
            --input-border: rgba(0, 0, 0, 0.1);
            --input-focus: rgba(99, 102, 241, 0.3);
            --glass-border: rgba(255, 255, 255, 0.3);
            --gradient-start: #6366f1;
            --gradient-end: #8b5cf6;
            --blob-opacity: 0.4;
            --blob-filter: blur(80px);
            --particle-color: rgba(0, 0, 0, 0.15);
            --alert-bg-error: rgba(239, 68, 68, 0.08);
            --alert-text-error: #dc2626;
            --alert-border-error: rgba(239, 68, 68, 0.15);
            --alert-bg-success: rgba(34, 197, 94, 0.08);
            --alert-text-success: #16a34a;
            --alert-border-success: rgba(34, 197, 94, 0.15);
        }

        [data-theme="dark"] {
            --bg-primary: #0a0a0f;
            --bg-secondary: #1a1a2e;
            --bg-card: rgba(255, 255, 255, 0.06);
            --bg-card-hover: rgba(255, 255, 255, 0.1);
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.6);
            --text-muted: rgba(255, 255, 255, 0.3);
            --border-color: rgba(255, 255, 255, 0.08);
            --border-hover: rgba(255, 255, 255, 0.15);
            --shadow-color: rgba(0, 0, 0, 0.4);
            --shadow-hover: rgba(0, 0, 0, 0.6);
            --input-bg: rgba(255, 255, 255, 0.05);
            --input-border: rgba(255, 255, 255, 0.1);
            --input-focus: rgba(99, 102, 241, 0.4);
            --glass-border: rgba(255, 255, 255, 0.1);
            --gradient-start: #818cf8;
            --gradient-end: #a78bfa;
            --blob-opacity: 0.6;
            --blob-filter: blur(80px);
            --particle-color: rgba(255, 255, 255, 0.15);
            --alert-bg-error: rgba(239, 68, 68, 0.15);
            --alert-text-error: #fca5a5;
            --alert-border-error: rgba(239, 68, 68, 0.2);
            --alert-bg-success: rgba(34, 197, 94, 0.15);
            --alert-text-success: #86efac;
            --alert-border-success: rgba(34, 197, 94, 0.2);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: var(--bg-primary);
            overflow: hidden;
            position: relative;
            transition: background 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        /* === LIQUID GLASS BACKGROUND === */
        .liquid-glass-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: var(--bg-primary);
            transition: background 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        /* === MORPHING LIQUID BLOBS === */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: var(--blob-filter);
            opacity: var(--blob-opacity);
            animation: blobMorph 20s ease-in-out infinite;
            will-change: transform, border-radius, opacity;
            transition: opacity 0.6s ease, filter 0.6s ease;
        }

        [data-theme="light"] .blob-1 {
            background: radial-gradient(circle, rgba(99, 102, 241, 0.3), rgba(139, 92, 246, 0.1));
        }
        [data-theme="light"] .blob-2 {
            background: radial-gradient(circle, rgba(236, 72, 153, 0.25), rgba(99, 102, 241, 0.1));
        }
        [data-theme="light"] .blob-3 {
            background: radial-gradient(circle, rgba(34, 211, 238, 0.2), rgba(99, 102, 241, 0.08));
        }
        [data-theme="light"] .blob-4 {
            background: radial-gradient(circle, rgba(251, 146, 60, 0.2), rgba(236, 72, 153, 0.1));
        }
        [data-theme="light"] .blob-5 {
            background: radial-gradient(circle, rgba(52, 211, 153, 0.2), rgba(34, 211, 238, 0.08));
        }

        [data-theme="dark"] .blob-1 {
            background: radial-gradient(circle, rgba(120, 50, 200, 0.6), rgba(30, 60, 200, 0.3));
        }
        [data-theme="dark"] .blob-2 {
            background: radial-gradient(circle, rgba(200, 50, 150, 0.5), rgba(100, 30, 200, 0.3));
        }
        [data-theme="dark"] .blob-3 {
            background: radial-gradient(circle, rgba(50, 150, 255, 0.3), rgba(200, 50, 200, 0.2));
        }
        [data-theme="dark"] .blob-4 {
            background: radial-gradient(circle, rgba(255, 100, 50, 0.3), rgba(200, 50, 150, 0.2));
        }
        [data-theme="dark"] .blob-5 {
            background: radial-gradient(circle, rgba(50, 200, 255, 0.3), rgba(50, 100, 255, 0.2));
        }

        .blob-1 {
            width: 600px;
            height: 600px;
            top: -200px;
            right: -200px;
            animation-delay: 0s;
        }

        .blob-2 {
            width: 500px;
            height: 500px;
            bottom: -150px;
            left: -150px;
            animation-delay: -5s;
        }

        .blob-3 {
            width: 400px;
            height: 400px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -10s;
        }

        .blob-4 {
            width: 300px;
            height: 300px;
            top: 20%;
            left: 10%;
            animation-delay: -15s;
        }

        .blob-5 {
            width: 350px;
            height: 350px;
            bottom: 20%;
            right: 10%;
            animation-delay: -7s;
        }

        @keyframes blobMorph {
            0%, 100% {
                border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
                transform: translate(0, 0) scale(1) rotate(0deg);
            }
            25% {
                border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
                transform: translate(30px, -30px) scale(1.1) rotate(5deg);
            }
            50% {
                border-radius: 70% 30% 50% 50% / 30% 50% 70% 60%;
                transform: translate(-20px, 20px) scale(0.9) rotate(-5deg);
            }
            75% {
                border-radius: 40% 60% 40% 60% / 60% 40% 60% 40%;
                transform: translate(15px, -15px) scale(1.05) rotate(3deg);
            }
        }

        /* === FLOATING PARTICLES === */
        .particles {
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--particle-color);
            border-radius: 50%;
            animation: floatParticle 15s infinite linear;
            transition: background 0.6s ease;
        }

        @keyframes floatParticle {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(720deg);
                opacity: 0;
            }
        }

        /* === THEME TOGGLE === */
        .theme-toggle {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 100;
            background: var(--bg-card);
            backdrop-filter: blur(20px) saturate(1.8);
            -webkit-backdrop-filter: blur(20px) saturate(1.8);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 20px;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow: 0 4px 20px var(--shadow-color);
            color: var(--text-primary);
        }

        .theme-toggle:hover {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 8px 32px var(--shadow-hover);
            border-color: var(--border-hover);
        }

        .theme-toggle:active {
            transform: scale(0.9);
        }

        /* === LOGIN CONTAINER === */
        .login-container {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
        }

        /* === LIQUID GLASS CARD === */
        .login-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px) saturate(1.8);
            -webkit-backdrop-filter: blur(20px) saturate(1.8);
            border-radius: 32px;
            padding: 48px 40px;
            border: 1px solid var(--border-color);
            box-shadow: 
                0 25px 80px var(--shadow-color),
                inset 0 1px 0 var(--glass-border),
                inset 0 -1px 0 rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
            transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        [data-theme="light"] .login-card {
            background: rgba(255, 255, 255, 0.7);
        }

        [data-theme="dark"] .login-card {
            background: rgba(255, 255, 255, 0.06);
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(
                circle at 30% 30%,
                rgba(255, 255, 255, 0.1) 0%,
                transparent 60%
            );
            pointer-events: none;
            animation: shimmer 10s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(10%, 5%) rotate(5deg); }
            66% { transform: translate(-5%, 10%) rotate(-3deg); }
        }

        .login-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 32px;
            padding: 1px;
            background: linear-gradient(
                135deg,
                rgba(255, 255, 255, 0.3),
                rgba(255, 255, 255, 0.05),
                rgba(255, 255, 255, 0.3)
            );
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        .login-card:hover {
            transform: translateY(-5px);
            background: var(--bg-card-hover);
            box-shadow: 
                0 35px 100px var(--shadow-hover),
                inset 0 1px 0 var(--glass-border),
                inset 0 -1px 0 rgba(255, 255, 255, 0.05);
        }

        .login-header {
            text-align: center;
            margin-bottom: 36px;
            position: relative;
            z-index: 1;
        }

        .login-header .icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 36px;
            color: white;
            box-shadow: 0 8px 32px rgba(99, 102, 241, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .login-header .icon::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(
                circle at 50% 50%,
                rgba(255, 255, 255, 0.2) 0%,
                transparent 60%
            );
            animation: shimmer 6s ease-in-out infinite;
        }

        .login-header h1 {
            font-size: 30px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            transition: color 0.4s ease;
            letter-spacing: -0.5px;
        }

        .login-header p {
            color: var(--text-secondary);
            font-size: 15px;
            font-weight: 400;
            transition: color 0.4s ease;
        }

        /* === ALERTS === */
        .alert {
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            position: relative;
            z-index: 1;
            transition: all 0.4s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .alert-error {
            background: var(--alert-bg-error);
            color: var(--alert-text-error);
            border-color: var(--alert-border-error);
        }

        .alert-success {
            background: var(--alert-bg-success);
            color: var(--alert-text-success);
            border-color: var(--alert-border-success);
        }

        /* === FORM === */
        .form-group {
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: color 0.4s ease;
        }

        .form-group .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .form-group .input-icon {
            position: absolute;
            left: 16px;
            color: var(--text-muted);
            font-size: 18px;
            pointer-events: none;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .form-group input {
            width: 100%;
            padding: 16px 16px 16px 48px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 14px;
            font-size: 15px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            color: var(--text-primary);
            backdrop-filter: blur(5px);
        }

        .form-group input::placeholder {
            color: var(--text-muted);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--gradient-start);
            background: var(--bg-card);
            box-shadow: 0 0 0 4px var(--input-focus);
            transform: scale(1.01);
        }

        .form-group .input-wrapper:focus-within .input-icon {
            color: var(--gradient-start);
        }

        /* === FORM OPTIONS === */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 14px;
            position: relative;
            z-index: 1;
        }

        .form-options label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: color 0.3s;
        }

        .form-options label:hover {
            color: var(--text-primary);
        }

        .form-options input[type="checkbox"] {
            width: 17px;
            height: 17px;
            accent-color: var(--gradient-start);
            cursor: pointer;
            border-radius: 4px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            transition: all 0.3s ease;
        }

        .form-options a {
            color: var(--gradient-start);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .form-options a:hover {
            color: var(--gradient-end);
            text-shadow: 0 0 20px var(--input-focus);
        }

        /* === BUTTON === */
        .btn-login {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(99, 102, 241, 0.3);
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                transparent, 
                rgba(255, 255, 255, 0.2), 
                transparent
            );
            transition: left 0.6s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 12px 48px rgba(99, 102, 241, 0.4);
        }

        .btn-login:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-login.loading {
            opacity: 0.8;
            cursor: not-allowed;
            transform: scale(0.98);
        }

        .spinner {
            display: none;
            width: 22px;
            height: 22px;
            border: 3px solid rgba(255, 255, 255, 0.2);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-login.loading .spinner {
            display: inline-block;
        }

        /* === SIGNUP LINK === */
        .signup-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: var(--text-secondary);
            position: relative;
            z-index: 1;
            transition: color 0.4s ease;
        }

        .signup-link a {
            color: var(--gradient-start);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .signup-link a:hover {
            color: var(--gradient-end);
        }

        /* === RESPONSIVE === */
        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
                border-radius: 24px;
            }

            .login-header h1 {
                font-size: 24px;
            }

            .login-header .icon {
                width: 64px;
                height: 64px;
                font-size: 28px;
            }

            .form-options {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .theme-toggle {
                top: 16px;
                right: 16px;
                width: 40px;
                height: 40px;
                font-size: 18px;
            }

            .blob-1 {
                width: 300px;
                height: 300px;
                top: -100px;
                right: -100px;
            }

            .blob-2 {
                width: 250px;
                height: 250px;
                bottom: -80px;
                left: -80px;
            }

            .blob-3 {
                width: 200px;
                height: 200px;
            }
        }

        @media (max-width: 380px) {
            .login-card {
                padding: 24px 16px;
            }
        }

        /* === TRANSITION STYLES === */
        .theme-transition {
            transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
    </style>
</head>
<body>
    <!-- === LIQUID GLASS BACKGROUND === -->
    <div class="liquid-glass-bg"></div>

    <!-- === MORPHING BLOBS === -->
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="blob blob-4"></div>
    <div class="blob blob-5"></div>

    <!-- === THEME TOGGLE === -->
    <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
        🌙
    </button>

    <!-- === LOGIN FORM === -->
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="icon">✦</div>
                <h1>Welcome Back</h1>
                <p>Sign in to continue your journey</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error">
                    <span>⚠️</span>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <span>✅</span>
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" id="loginForm">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            placeholder="Enter your username" 
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Enter your password"
                            required
                        >
                    </div>
                </div>

                <div class="form-options">
                    <label>
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    <a href="#">Forgot password?</a>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <span class="spinner"></span>
                    Sign In →
                </button>
            </form>

            <div class="signup-link">
                Don't have an account? <a href="register.php">Create one now</a>
            </div>
        </div>
    </div>

    <script>
        // === THEME TOGGLE ===
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', savedTheme);
        updateToggleIcon(savedTheme);

        themeToggle.addEventListener('click', function() {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateToggleIcon(newTheme);
            
            // Add a subtle animation
            this.style.transform = 'scale(0.8) rotate(180deg)';
            setTimeout(() => {
                this.style.transform = 'scale(1) rotate(0deg)';
            }, 300);
        });

        function updateToggleIcon(theme) {
            themeToggle.textContent = theme === 'light' ? '🌙' : '☀️';
            themeToggle.setAttribute('aria-label', theme === 'light' ? 'Switch to dark mode' : 'Switch to light mode');
        }

        // === CREATE PARTICLES ===
        (function createParticles() {
            const container = document.getElementById('particles');
            const count = 30;
            
            for (let i = 0; i < count; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                const size = Math.random() * 3 + 2;
                const left = Math.random() * 100;
                const delay = Math.random() * 15;
                const duration = Math.random() * 10 + 10;
                
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = left + '%';
                particle.style.animationDelay = delay + 's';
                particle.style.animationDuration = duration + 's';
                particle.style.opacity = Math.random() * 0.3 + 0.1;
                
                container.appendChild(particle);
            }
        })();

        // === LOADING STATE ===
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.innerHTML = '<span class="spinner"></span> Signing in...';
        });

        // === AUTO-DISMISS ALERTS ===
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.transition = 'all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px) scale(0.95)';
                setTimeout(() => alert.remove(), 600);
            });
        }, 6000);

        // === INPUT FOCUS ANIMATION ===
        document.querySelectorAll('.form-group input').forEach(input => {
            const wrapper = input.closest('.input-wrapper');
            
            input.addEventListener('focus', function() {
                wrapper.style.transform = 'scale(1.02)';
                wrapper.style.transition = 'transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
            });
            
            input.addEventListener('blur', function() {
                wrapper.style.transform = 'scale(1)';
            });
        });

        // === PARALLAX EFFECT FOR BLOBS ===
        document.addEventListener('mousemove', function(e) {
            const x = (e.clientX / window.innerWidth - 0.5) * 20;
            const y = (e.clientY / window.innerHeight - 0.5) * 20;
            
            document.querySelectorAll('.blob').forEach((blob, index) => {
                const speed = 1 + index * 0.3;
                blob.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
            });
        });

        // === KEYBOARD SHORTCUT: Toggle theme with Ctrl+Shift+D ===
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && (e.key === 'D' || e.key === 'd')) {
                e.preventDefault();
                themeToggle.click();
            }
        });
    </script>
</body>
</html>