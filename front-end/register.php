<?php
// Isama ang configuration para sa API URL
include 'config/api_config.php';
$error = '';
$success = '';

// I-check kung pinindot na ang register button POST request
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');
    $terms = isset($_POST['terms']) ? true : false;

    // Validation
    if(empty($username) || empty($email) || empty($password) || empty($confirm_password)){
        $error = "All fields are required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters long";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } elseif (!$terms) {
        $error = "You must agree to the Terms and Conditions";
    } else {
        // 1. I-prepare ang payload data bilang JSON string
        $payload = json_encode([
            'username' => $username,
            'email' => $email,
            'password' => $password
        ]);

        // 2. I-setup ang cURL request papunta sa Flask `/register` endpoint
        $url = API_BASE_URL . '/register';
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

        if($HttpCode === 201 && isset($data['user'])){
            $success = "Account created successfully! Welcome, " . htmlspecialchars($data['user']['username']) . "! 🎉";
            // You can redirect to login page after successful registration
            // header('Location: login.php');
            // exit();
        } elseif($HttpCode === 400 && isset($data['message'])) {
            $error = htmlspecialchars($data['message']);
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Register</title>
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
            --password-strength-weak: #ef4444;
            --password-strength-medium: #f59e0b;
            --password-strength-strong: #22c55e;
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

        /* === REGISTER CONTAINER === */
        .register-container {
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 1;
        }

        /* === LIQUID GLASS CARD === */
        .register-card {
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

        [data-theme="light"] .register-card {
            background: rgba(255, 255, 255, 0.7);
        }

        [data-theme="dark"] .register-card {
            background: rgba(255, 255, 255, 0.06);
        }

        .register-card::before {
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

        .register-card::after {
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

        .register-card:hover {
            transform: translateY(-5px);
            background: var(--bg-card-hover);
            box-shadow: 
                0 35px 100px var(--shadow-hover),
                inset 0 1px 0 var(--glass-border),
                inset 0 -1px 0 rgba(255, 255, 255, 0.05);
        }

        .register-header {
            text-align: center;
            margin-bottom: 32px;
            position: relative;
            z-index: 1;
        }

        .register-header .icon {
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

        .register-header .icon::before {
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

        .register-header h1 {
            font-size: 30px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            transition: color 0.4s ease;
            letter-spacing: -0.5px;
        }

        .register-header p {
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
            margin-bottom: 18px;
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

        /* === PASSWORD STRENGTH INDICATOR === */
        .password-strength {
            margin-top: 8px;
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .password-strength .bar {
            flex: 1;
            height: 4px;
            background: var(--input-border);
            border-radius: 4px;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .password-strength .bar.active {
            background: var(--password-strength-weak);
        }

        .password-strength .bar.active.medium {
            background: var(--password-strength-medium);
        }

        .password-strength .bar.active.strong {
            background: var(--password-strength-strong);
        }

        .password-strength .text {
            font-size: 11px;
            font-weight: 500;
            color: var(--text-muted);
            min-width: 50px;
            text-align: right;
            transition: color 0.3s ease;
        }

        .password-strength .text.weak {
            color: var(--password-strength-weak);
        }

        .password-strength .text.medium {
            color: var(--password-strength-medium);
        }

        .password-strength .text.strong {
            color: var(--password-strength-strong);
        }

        /* === TERMS CHECKBOX === */
        .form-group-checkbox {
            margin: 20px 0;
            position: relative;
            z-index: 1;
        }

        .form-group-checkbox label {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 14px;
            color: var(--text-secondary);
            cursor: pointer;
            transition: color 0.3s ease;
            line-height: 1.5;
        }

        .form-group-checkbox label:hover {
            color: var(--text-primary);
        }

        .form-group-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            min-width: 18px;
            margin-top: 1px;
            accent-color: var(--gradient-start);
            cursor: pointer;
            border-radius: 4px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            transition: all 0.3s ease;
        }

        .form-group-checkbox a {
            color: var(--gradient-start);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .form-group-checkbox a:hover {
            color: var(--gradient-end);
        }

        /* === BUTTON === */
        .btn-register {
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

        .btn-register::before {
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

        .btn-register:hover::before {
            left: 100%;
        }

        .btn-register:hover {
            transform: translateY(-3px) scale(1.01);
            box-shadow: 0 12px 48px rgba(99, 102, 241, 0.4);
        }

        .btn-register:active {
            transform: translateY(0) scale(0.98);
        }

        .btn-register.loading {
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

        .btn-register.loading .spinner {
            display: inline-block;
        }

        /* === LOGIN LINK === */
        .login-link {
            text-align: center;
            margin-top: 24px;
            font-size: 14px;
            color: var(--text-secondary);
            position: relative;
            z-index: 1;
            transition: color 0.4s ease;
        }

        .login-link a {
            color: var(--gradient-start);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
        }

        .login-link a:hover {
            color: var(--gradient-end);
        }

        /* === RESPONSIVE === */
        @media (max-width: 480px) {
            .register-card {
                padding: 32px 24px;
                border-radius: 24px;
            }

            .register-header h1 {
                font-size: 24px;
            }

            .register-header .icon {
                width: 64px;
                height: 64px;
                font-size: 28px;
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

            .form-group input {
                padding: 14px 14px 14px 44px;
                font-size: 14px;
            }
        }

        @media (max-width: 380px) {
            .register-card {
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

    <!-- === FLOATING PARTICLES === -->
    <div class="particles" id="particles"></div>

    <!-- === THEME TOGGLE === -->
    <button class="theme-toggle" id="themeToggle" aria-label="Toggle theme">
        🌙
    </button>

    <!-- === REGISTER FORM === -->
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="icon">✨</div>
                <h1>Create Account</h1>
                <p>Join us and start your journey</p>
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

            <form method="POST" action="register.php" id="registerForm" novalidate>
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            placeholder="Choose a username" 
                            value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                            required
                            minlength="3"
                            maxlength="30"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <span class="input-icon">📧</span>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="Enter your email address" 
                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
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
                            placeholder="Create a strong password"
                            required
                            minlength="6"
                        >
                    </div>
                    <div class="password-strength" id="passwordStrength">
                        <div class="bar" data-index="0"></div>
                        <div class="bar" data-index="1"></div>
                        <div class="bar" data-index="2"></div>
                        <div class="bar" data-index="3"></div>
                        <span class="text">Weak</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <div class="input-wrapper">
                        <span class="input-icon">✓</span>
                        <input 
                            type="password" 
                            id="confirm_password" 
                            name="confirm_password" 
                            placeholder="Confirm your password"
                            required
                        >
                    </div>
                </div>

                <div class="form-group-checkbox">
                    <label>
                        <input type="checkbox" name="terms" id="terms" <?php echo isset($_POST['terms']) ? 'checked' : ''; ?>>
                        I agree to the <a href="#" target="_blank">Terms of Service</a> and <a href="#" target="_blank">Privacy Policy</a>
                    </label>
                </div>

                <button type="submit" class="btn-register" id="registerBtn">
                    <span class="spinner"></span>
                    Create Account →
                </button>
            </form>

            <div class="login-link">
                Already have an account? <a href="login.php">Sign in</a>
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
            
            this.style.transform = 'scale(0.8) rotate(180deg)';
            setTimeout(() => {
                this.style.transform = 'scale(1) rotate(0deg)';
            }, 300);
        });

        function updateToggleIcon(theme) {
            themeToggle.textContent = theme === 'light' ? '🌙' : '☀️';
            themeToggle.setAttribute('aria-label', theme === 'light' ? 'Switch to dark mode' : 'Switch to light mode');
        }


        // === PASSWORD STRENGTH INDICATOR ===
        const passwordInput = document.getElementById('password');
        const strengthBars = document.querySelectorAll('.password-strength .bar');
        const strengthText = document.querySelector('.password-strength .text');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = calculatePasswordStrength(password);
            
            // Update bars
            strengthBars.forEach((bar, index) => {
                bar.className = 'bar';
                if (index < strength.score) {
                    bar.classList.add('active');
                    if (strength.score === 4) {
                        bar.classList.add('strong');
                    } else if (strength.score >= 2) {
                        bar.classList.add('medium');
                    }
                }
            });
            
            // Update text
            strengthText.textContent = strength.label;
            strengthText.className = 'text';
            if (strength.score === 0) {
                strengthText.classList.add('weak');
            } else if (strength.score <= 2) {
                strengthText.classList.add('medium');
            } else {
                strengthText.classList.add('strong');
            }
        });

        function calculatePasswordStrength(password) {
            let score = 0;
            
            if (password.length === 0) return { score: 0, label: 'Weak' };
            
            // Length check
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            
            // Character variety checks
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^a-zA-Z0-9]/.test(password)) score++;
            
            // Normalize score to 0-3 range
            score = Math.min(3, Math.floor(score / 2));
            
            const labels = ['Weak', 'Weak', 'Medium', 'Strong'];
            return { score: score, label: labels[score] };
        }

        // === PASSWORD MATCH VALIDATION ===
        const confirmInput = document.getElementById('confirm_password');
        const form = document.getElementById('registerForm');

        confirmInput.addEventListener('input', function() {
            const password = passwordInput.value;
            const confirm = this.value;
            
            if (confirm.length > 0 && password !== confirm) {
                this.style.borderColor = 'var(--alert-text-error)';
                this.style.boxShadow = '0 0 0 4px var(--alert-bg-error)';
            } else if (confirm.length > 0) {
                this.style.borderColor = 'var(--alert-text-success)';
                this.style.boxShadow = '0 0 0 4px var(--alert-bg-success)';
            } else {
                this.style.borderColor = 'var(--input-border)';
                this.style.boxShadow = 'none';
            }
        });

        // === FORM SUBMISSION WITH VALIDATION ===
        form.addEventListener('submit', function(e) {
            const btn = document.getElementById('registerBtn');
            
            // Client-side validation
            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = passwordInput.value;
            const confirm = confirmInput.value;
            const terms = document.getElementById('terms').checked;
            
            let hasError = false;
            
            if (username.length < 3) {
                showError('Username must be at least 3 characters');
                hasError = true;
            } else if (!email || !email.includes('@')) {
                showError('Please enter a valid email address');
                hasError = true;
            } else if (password.length < 6) {
                showError('Password must be at least 6 characters');
                hasError = true;
            } else if (password !== confirm) {
                showError('Passwords do not match');
                hasError = true;
            } else if (!terms) {
                showError('You must agree to the Terms and Conditions');
                hasError = true;
            }
            
            if (hasError) {
                e.preventDefault();
                return;
            }
            
            // Show loading state
            btn.classList.add('loading');
            btn.innerHTML = '<span class="spinner"></span> Creating account...';
        });

        function showError(message) {
            // Remove existing error alerts
            const existingAlerts = document.querySelectorAll('.alert-error');
            existingAlerts.forEach(alert => alert.remove());
            
            // Create new error alert
            const alert = document.createElement('div');
            alert.className = 'alert alert-error';
            alert.innerHTML = `<span>⚠️</span> ${message}`;
            
            // Insert after header
            const header = document.querySelector('.register-header');
            header.parentNode.insertBefore(alert, header.nextSibling);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                alert.style.transition = 'all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94)';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px) scale(0.95)';
                setTimeout(() => alert.remove(), 600);
            }, 5000);
        }

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