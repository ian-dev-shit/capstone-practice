<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SecuFreight - Secure Freight Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Light Theme */
            --bg-primary: #f0f2f5;
            --bg-secondary: #ffffff;
            --bg-card: rgba(255, 255, 255, 0.7);
            --bg-card-hover: rgba(255, 255, 255, 0.85);
            --text-primary: #1a1a2e;
            --text-secondary: #4b5563;
            --text-muted: #9ca3af;
            --border-color: rgba(0, 0, 0, 0.08);
            --shadow-color: rgba(0, 0, 0, 0.08);
            --shadow-hover: rgba(0, 0, 0, 0.15);
            --gradient-start: #6366f1;
            --gradient-end: #8b5cf6;
            --gradient-start-dark: #4f46e5;
            --gradient-end-dark: #7c3aed;
            --blob-opacity: 0.4;
            --blob-filter: blur(80px);
            --particle-color: rgba(0, 0, 0, 0.1);
            --glass-border: rgba(255, 255, 255, 0.3);
            --nav-bg: rgba(255, 255, 255, 0.8);
            --footer-bg: rgba(255, 255, 255, 0.6);
            --stat-bg: rgba(255, 255, 255, 0.6);
            --feature-icon-bg: rgba(99, 102, 241, 0.1);
        }

        [data-theme="dark"] {
            --bg-primary: #0a0a0f;
            --bg-secondary: #1a1a2e;
            --bg-card: rgba(255, 255, 255, 0.06);
            --bg-card-hover: rgba(255, 255, 255, 0.1);
            --text-primary: #ffffff;
            --text-secondary: rgba(255, 255, 255, 0.7);
            --text-muted: rgba(255, 255, 255, 0.4);
            --border-color: rgba(255, 255, 255, 0.08);
            --shadow-color: rgba(0, 0, 0, 0.4);
            --shadow-hover: rgba(0, 0, 0, 0.6);
            --gradient-start: #818cf8;
            --gradient-end: #a78bfa;
            --gradient-start-dark: #6366f1;
            --gradient-end-dark: #8b5cf6;
            --blob-opacity: 0.6;
            --blob-filter: blur(80px);
            --particle-color: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.1);
            --nav-bg: rgba(26, 26, 46, 0.8);
            --footer-bg: rgba(26, 26, 46, 0.6);
            --stat-bg: rgba(255, 255, 255, 0.05);
            --feature-icon-bg: rgba(99, 102, 241, 0.15);
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            background: var(--bg-primary);
            color: var(--text-primary);
            overflow-x: hidden;
            transition: background 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94), color 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
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
            animation: blobMorph 25s ease-in-out infinite;
            will-change: transform, border-radius, opacity;
            transition: opacity 0.6s ease, filter 0.6s ease;
        }

        [data-theme="light"] .blob-1 {
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25), rgba(139, 92, 246, 0.08));
        }
        [data-theme="light"] .blob-2 {
            background: radial-gradient(circle, rgba(236, 72, 153, 0.2), rgba(99, 102, 241, 0.08));
        }
        [data-theme="light"] .blob-3 {
            background: radial-gradient(circle, rgba(34, 211, 238, 0.15), rgba(99, 102, 241, 0.06));
        }
        [data-theme="light"] .blob-4 {
            background: radial-gradient(circle, rgba(251, 146, 60, 0.15), rgba(236, 72, 153, 0.08));
        }
        [data-theme="light"] .blob-5 {
            background: radial-gradient(circle, rgba(52, 211, 153, 0.15), rgba(34, 211, 238, 0.06));
        }

        [data-theme="dark"] .blob-1 {
            background: radial-gradient(circle, rgba(120, 50, 200, 0.5), rgba(30, 60, 200, 0.25));
        }
        [data-theme="dark"] .blob-2 {
            background: radial-gradient(circle, rgba(200, 50, 150, 0.4), rgba(100, 30, 200, 0.25));
        }
        [data-theme="dark"] .blob-3 {
            background: radial-gradient(circle, rgba(50, 150, 255, 0.25), rgba(200, 50, 200, 0.15));
        }
        [data-theme="dark"] .blob-4 {
            background: radial-gradient(circle, rgba(255, 100, 50, 0.25), rgba(200, 50, 150, 0.15));
        }
        [data-theme="dark"] .blob-5 {
            background: radial-gradient(circle, rgba(50, 200, 255, 0.25), rgba(50, 100, 255, 0.15));
        }

        .blob-1 {
            width: 700px;
            height: 700px;
            top: -250px;
            right: -200px;
            animation-delay: 0s;
        }

        .blob-2 {
            width: 550px;
            height: 550px;
            bottom: -200px;
            left: -150px;
            animation-delay: -6s;
        }

        .blob-3 {
            width: 450px;
            height: 450px;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            animation-delay: -12s;
        }

        .blob-4 {
            width: 350px;
            height: 350px;
            top: 15%;
            left: 5%;
            animation-delay: -18s;
        }

        .blob-5 {
            width: 400px;
            height: 400px;
            bottom: 15%;
            right: 5%;
            animation-delay: -8s;
        }

        @keyframes blobMorph {
            0%, 100% {
                border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
                transform: translate(0, 0) scale(1) rotate(0deg);
            }
            25% {
                border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
                transform: translate(40px, -40px) scale(1.1) rotate(5deg);
            }
            50% {
                border-radius: 70% 30% 50% 50% / 30% 50% 70% 60%;
                transform: translate(-30px, 30px) scale(0.9) rotate(-5deg);
            }
            75% {
                border-radius: 40% 60% 40% 60% / 60% 40% 60% 40%;
                transform: translate(20px, -20px) scale(1.05) rotate(3deg);
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
            animation: floatParticle 18s infinite linear;
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
            z-index: 1000;
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

        /* === NAVIGATION === */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
            padding: 16px 40px;
            background: var(--nav-bg);
            backdrop-filter: blur(20px) saturate(1.8);
            -webkit-backdrop-filter: blur(20px) saturate(1.8);
            border-bottom: 1px solid var(--border-color);
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar.scrolled {
            box-shadow: 0 4px 30px var(--shadow-color);
        }

        .navbar .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 800;
            color: var(--text-primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .navbar .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .navbar .logo span {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            gap: 32px;
            align-items: center;
            list-style: none;
        }

        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            transition: width 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--text-primary);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-nav {
            padding: 10px 24px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-nav-outline {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-nav-outline:hover {
            background: var(--bg-card);
            border-color: var(--gradient-start);
            transform: translateY(-2px);
        }

        .btn-nav-primary {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-nav-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }

        /* Mobile Menu */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 4px;
            background: none;
            border: none;
        }

        .hamburger span {
            width: 28px;
            height: 3px;
            background: var(--text-primary);
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* === HERO SECTION === */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 100px 40px 60px;
            position: relative;
            z-index: 1;
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .hero-text {
            position: relative;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: var(--bg-card);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 24px;
        }

        .hero-badge .dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        .hero h1 {
            font-size: 58px;
            font-weight: 900;
            line-height: 1.1;
            margin-bottom: 20px;
            letter-spacing: -1.5px;
        }

        .hero h1 .highlight {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero p {
            font-size: 18px;
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: 32px;
            max-width: 480px;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            padding: 16px 36px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.4);
        }

        .btn-hero-secondary {
            padding: 16px 36px;
            background: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            font-size: 16px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-hero-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px var(--shadow-hover);
            border-color: var(--gradient-start);
        }

        .hero-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-top: 48px;
            padding-top: 32px;
            border-top: 1px solid var(--border-color);
        }

        .hero-stat {
            display: flex;
            flex-direction: column;
        }

        .hero-stat .number {
            font-size: 28px;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.2;
        }

        .hero-stat .label {
            font-size: 14px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Hero Visual */
        .hero-visual {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero-graphic {
            width: 100%;
            max-width: 500px;
            aspect-ratio: 1;
            position: relative;
        }

        .floating-card {
            position: absolute;
            background: var(--bg-card);
            backdrop-filter: blur(20px) saturate(1.8);
            -webkit-backdrop-filter: blur(20px) saturate(1.8);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 20px 24px;
            box-shadow: 0 20px 60px var(--shadow-color);
            animation: floatCard 6s ease-in-out infinite;
            min-width: 160px;
        }

        .floating-card:nth-child(1) {
            top: 5%;
            right: 0;
            animation-delay: 0s;
        }

        .floating-card:nth-child(2) {
            bottom: 10%;
            left: 0;
            animation-delay: -2s;
        }

        .floating-card:nth-child(3) {
            top: 40%;
            left: 50%;
            transform: translateX(-50%);
            animation-delay: -4s;
        }

        @keyframes floatCard {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-15px) rotate(2deg); }
        }

        .floating-card .card-icon {
            font-size: 28px;
            margin-bottom: 8px;
        }

        .floating-card .card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .floating-card .card-sub {
            font-size: 12px;
            color: var(--text-muted);
        }

        .hero-center-icon {
            width: 200px;
            height: 200px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            color: white;
            box-shadow: 0 20px 60px rgba(99, 102, 241, 0.3);
            animation: pulseGlow 3s ease-in-out infinite;
            position: relative;
            z-index: 1;
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 20px 60px rgba(99, 102, 241, 0.3); }
            50% { box-shadow: 0 30px 80px rgba(99, 102, 241, 0.5); }
        }

        .hero-center-icon::before {
            content: '';
            position: absolute;
            inset: -20px;
            border-radius: 50%;
            border: 2px solid var(--border-color);
            animation: spin 20s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* === FEATURES SECTION === */
        .features {
            padding: 80px 40px;
            position: relative;
            z-index: 1;
        }

        .features-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-header .tag {
            display: inline-block;
            padding: 6px 16px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 50px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: 12px;
        }

        .section-header h2 {
            font-size: 40px;
            font-weight: 800;
            margin-bottom: 12px;
            letter-spacing: -1px;
        }

        .section-header p {
            font-size: 18px;
            color: var(--text-secondary);
            max-width: 500px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .feature-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px) saturate(1.8);
            -webkit-backdrop-filter: blur(20px) saturate(1.8);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 32px;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            position: relative;
            overflow: hidden;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 60px var(--shadow-hover);
            border-color: var(--gradient-start);
        }

        .feature-card .icon {
            width: 56px;
            height: 56px;
            background: var(--feature-icon-bg);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 16px;
            transition: all 0.3s ease;
        }

        .feature-card:hover .icon {
            transform: scale(1.1) rotate(-5deg);
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
        }

        .feature-card h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-primary);
        }

        .feature-card p {
            font-size: 15px;
            color: var(--text-secondary);
            line-height: 1.6;
        }

        /* === STATS SECTION === */
        .stats {
            padding: 60px 40px 80px;
            position: relative;
            z-index: 1;
        }

        .stats-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }

        .stat-card {
            background: var(--stat-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 32px;
            text-align: center;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px var(--shadow-hover);
        }

        .stat-card .number {
            font-size: 42px;
            font-weight: 900;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        .stat-card .label {
            font-size: 15px;
            color: var(--text-secondary);
            font-weight: 500;
            margin-top: 4px;
        }

        /* === CTA SECTION === */
        .cta {
            padding: 80px 40px 100px;
            position: relative;
            z-index: 1;
        }

        .cta-container {
            max-width: 900px;
            margin: 0 auto;
            background: var(--bg-card);
            backdrop-filter: blur(20px) saturate(1.8);
            -webkit-backdrop-filter: blur(20px) saturate(1.8);
            border: 1px solid var(--border-color);
            border-radius: 40px;
            padding: 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px var(--shadow-color);
        }

        .cta-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(
                circle at 30% 30%,
                rgba(99, 102, 241, 0.05) 0%,
                transparent 60%
            );
            pointer-events: none;
        }

        .cta h2 {
            font-size: 38px;
            font-weight: 800;
            margin-bottom: 12px;
            letter-spacing: -1px;
        }

        .cta p {
            font-size: 18px;
            color: var(--text-secondary);
            margin-bottom: 32px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* === FOOTER === */
        .footer {
            padding: 40px;
            position: relative;
            z-index: 1;
            background: var(--footer-bg);
            backdrop-filter: blur(10px);
            border-top: 1px solid var(--border-color);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
        }

        .footer-brand .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
            font-weight: 800;
            margin-bottom: 12px;
            color: var(--text-primary);
            text-decoration: none;
        }

        .footer-brand .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: white;
        }

        .footer-brand p {
            color: var(--text-secondary);
            font-size: 14px;
            line-height: 1.6;
            max-width: 300px;
        }

        .footer-col h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 16px;
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-col ul a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .footer-col ul a:hover {
            color: var(--gradient-start);
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 32px auto 0;
            padding-top: 24px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: var(--text-muted);
        }

        .footer-social {
            display: flex;
            gap: 16px;
        }

        .footer-social a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .footer-social a:hover {
            color: var(--gradient-start);
            transform: translateY(-2px);
        }

        /* === RESPONSIVE === */
        @media (max-width: 1024px) {
            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero p {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-actions {
                justify-content: center;
            }

            .hero-stats {
                justify-items: center;
            }

            .hero-graphic {
                max-width: 400px;
                margin: 0 auto;
            }

            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-container {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 12px 20px;
            }

            .nav-links {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                flex-direction: column;
                background: var(--bg-card);
                backdrop-filter: blur(20px);
                padding: 20px;
                gap: 16px;
                border-bottom: 1px solid var(--border-color);
            }

            .nav-links.active {
                display: flex;
            }

            .hamburger {
                display: flex;
            }

            .nav-actions {
                display: none;
            }

            .hero {
                padding: 80px 20px 40px;
            }

            .hero h1 {
                font-size: 36px;
            }

            .hero-graphic {
                max-width: 300px;
            }

            .floating-card {
                padding: 12px 16px;
                min-width: 120px;
            }

            .floating-card .card-icon {
                font-size: 20px;
            }

            .floating-card .card-title {
                font-size: 12px;
            }

            .hero-center-icon {
                width: 140px;
                height: 140px;
                font-size: 56px;
            }

            .features {
                padding: 60px 20px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .section-header h2 {
                font-size: 30px;
            }

            .stats {
                padding: 40px 20px 60px;
            }

            .stats-container {
                grid-template-columns: 1fr 1fr;
                gap: 16px;
            }

            .stat-card .number {
                font-size: 32px;
            }

            .cta {
                padding: 60px 20px 80px;
            }

            .cta-container {
                padding: 32px 24px;
                border-radius: 24px;
            }

            .cta h2 {
                font-size: 28px;
            }

            .footer {
                padding: 32px 20px;
            }

            .footer-container {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }

            .theme-toggle {
                top: 16px;
                right: 16px;
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 28px;
            }

            .hero-stats {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .hero-actions {
                flex-direction: column;
                align-items: center;
            }

            .btn-hero-primary,
            .btn-hero-secondary {
                width: 100%;
                justify-content: center;
            }
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

    <!-- === NAVIGATION === -->
    <nav class="navbar" id="navbar">
        <a href="#" class="logo">
            <div class="logo-icon">🚛</div>
            <span>SecuFreight</span>
        </a>
        
        <ul class="nav-links" id="navLinks">
            <li><a href="#features">Features</a></li>
            <li><a href="#stats">Stats</a></li>
            <li><a href="#cta">Get Started</a></li>
            <li><a href="register.php" class="btn-nav btn-nav-outline">Sign In</a></li>
            <li><a href="register.php" class="btn-nav btn-nav-primary">Get Started</a></li>
        </ul>

        <button class="hamburger" id="hamburger" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </nav>

    <!-- === HERO SECTION === -->
    <section class="hero" id="hero">
        <div class="hero-content">
            <div class="hero-text">
                <div class="hero-badge">
                    <span class="dot"></span>
                    Next-Gen Freight Management
                </div>
                <h1>
                    Secure, Smart<br>
                    <span class="highlight">Freight Management</span>
                </h1>
                <p>
                    SecuFreight revolutionizes how you manage logistics with 
                    cutting-edge security, real-time tracking, and intelligent 
                    automation — all in one powerful platform.
                </p>
                <div class="hero-actions">
                    <a href="register.php" class="btn-hero-primary">
                        Start Free Trial →
                    </a>
                    <a href="#features" class="btn-hero-secondary">
                        Learn More
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <span class="number">10K+</span>
                        <span class="label">Shipments Delivered</span>
                    </div>
                    <div class="hero-stat">
                        <span class="number">99.9%</span>
                        <span class="label">On-Time Delivery</span>
                    </div>
                    <div class="hero-stat">
                        <span class="number">4.9★</span>
                        <span class="label">User Rating</span>
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                <div class="hero-graphic">
                    <div class="hero-center-icon">🚛</div>
                    
                    <div class="floating-card" style="top: 0; right: -20px;">
                        <div class="card-icon">📦</div>
                        <div class="card-title">Real-Time Tracking</div>
                        <div class="card-sub">Live updates</div>
                    </div>
                    
                    <div class="floating-card" style="bottom: 0; left: -20px;">
                        <div class="card-icon">🔒</div>
                        <div class="card-title">Enterprise Security</div>
                        <div class="card-sub">End-to-end encryption</div>
                    </div>
                    
                    <div class="floating-card" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
                        <div class="card-icon">⚡</div>
                        <div class="card-title">Smart Automation</div>
                        <div class="card-sub">AI-powered logistics</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- === FEATURES SECTION === -->
    <section class="features" id="features">
        <div class="features-container">
            <div class="section-header">
                <span class="tag">✦ Features</span>
                <h2>Everything You Need for<br>Modern Freight Management</h2>
                <p>Powerful tools designed to streamline your logistics operations</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="icon">🔒</div>
                    <h3>Bank-Grade Security</h3>
                    <p>Enterprise-level encryption and security protocols to protect your sensitive freight data and transactions.</p>
                </div>

                <div class="feature-card">
                    <div class="icon">📍</div>
                    <h3>Real-Time Tracking</h3>
                    <p>Track every shipment in real-time with GPS integration and instant status updates across all devices.</p>
                </div>

                <div class="feature-card">
                    <div class="icon">🤖</div>
                    <h3>AI-Powered Automation</h3>
                    <p>Intelligent routing, predictive analytics, and automated workflows to optimize your logistics operations.</p>
                </div>

                <div class="feature-card">
                    <div class="icon">📊</div>
                    <h3>Advanced Analytics</h3>
                    <p>Comprehensive dashboards and reporting tools to gain actionable insights into your freight operations.</p>
                </div>

                <div class="feature-card">
                    <div class="icon">🤝</div>
                    <h3>Collaborative Platform</h3>
                    <p>Seamlessly connect with carriers, suppliers, and customers in one unified, secure platform.</p>
                </div>

                <div class="feature-card">
                    <div class="icon">📱</div>
                    <h3>Mobile Ready</h3>
                    <p>Access your freight management system anywhere, anytime with our responsive mobile-first design.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- === STATS SECTION === -->
    <section class="stats" id="stats">
        <div class="stats-container">
            <div class="stat-card">
                <div class="number">10K+</div>
                <div class="label">Shipments Managed</div>
            </div>
            <div class="stat-card">
                <div class="number">99.9%</div>
                <div class="label">Delivery Success Rate</div>
            </div>
            <div class="stat-card">
                <div class="number">500+</div>
                <div class="label">Enterprise Clients</div>
            </div>
            <div class="stat-card">
                <div class="number">4.9★</div>
                <div class="label">Average User Rating</div>
            </div>
        </div>
    </section>

    <!-- === CTA SECTION === -->
    <section class="cta" id="cta">
        <div class="cta-container">
            <h2>Ready to Transform Your<br>Freight Operations?</h2>
            <p>
                Join thousands of companies using SecuFreight to secure, 
                streamline, and scale their logistics operations.
            </p>
            <div class="cta-actions">
                <a href="register.php" class="btn-hero-primary">
                    Start Free Trial
                </a>
                <a href="#" class="btn-hero-secondary">
                    Schedule Demo
                </a>
            </div>
        </div>
    </section>

    <!-- === FOOTER === -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-brand">
                <a href="#" class="logo">
                    <div class="logo-icon">🚛</div>
                    SecuFreight
                </a>
                <p>
                    Secure, smart freight management for the modern 
                    logistics industry. Built with cutting-edge technology 
                    and enterprise-grade security.
                </p>
            </div>

            <div class="footer-col">
                <h4>Product</h4>
                <ul>
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Pricing</a></li>
                    <li><a href="#">Integrations</a></li>
                    <li><a href="#">Changelog</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Company</h4>
                <ul>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Support</h4>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">API Status</a></li>
                    <li><a href="#">Community</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <span>© 2026 SecuFreight. All rights reserved.</span>
            <div class="footer-social">
                <a href="#" aria-label="Twitter">🐦</a>
                <a href="#" aria-label="LinkedIn">💼</a>
                <a href="#" aria-label="GitHub">🐙</a>
                <a href="#" aria-label="YouTube">▶️</a>
            </div>
        </div>
    </footer>

    <script>
        // === THEME TOGGLE ===
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        
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
        }

        // === CREATE PARTICLES ===
        (function createParticles() {
            const container = document.getElementById('particles');
            const count = 35;
            
            for (let i = 0; i < count; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                const size = Math.random() * 3 + 2;
                const left = Math.random() * 100;
                const delay = Math.random() * 15;
                const duration = Math.random() * 12 + 10;
                
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = left + '%';
                particle.style.animationDelay = delay + 's';
                particle.style.animationDuration = duration + 's';
                particle.style.opacity = Math.random() * 0.3 + 0.1;
                
                container.appendChild(particle);
            }
        })();

        // === NAVBAR SCROLL EFFECT ===
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // === MOBILE MENU ===
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('navLinks');

        hamburger.addEventListener('click', function() {
            this.classList.toggle('active');
            navLinks.classList.toggle('active');
        });

        // Close menu on link click
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                hamburger.classList.remove('active');
                navLinks.classList.remove('active');
            });
        });

        // === PARALLAX EFFECT ===
        document.addEventListener('mousemove', function(e) {
            const x = (e.clientX / window.innerWidth - 0.5) * 20;
            const y = (e.clientY / window.innerHeight - 0.5) * 20;
            
            document.querySelectorAll('.blob').forEach((blob, index) => {
                const speed = 1 + index * 0.25;
                blob.style.transform = `translate(${x * speed}px, ${y * speed}px)`;
            });
        });

        // === SMOOTH SCROLL FOR ANCHOR LINKS ===
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // === COUNTER ANIMATION ===
        function animateCounters() {
            const statNumbers = document.querySelectorAll('.stat-card .number, .hero-stat .number');
            
            statNumbers.forEach(stat => {
                const text = stat.textContent;
                const number = parseFloat(text);
                if (isNaN(number)) return;
                
                const isPercentage = text.includes('%');
                const isK = text.includes('K');
                const hasStar = text.includes('★');
                
                let target = number;
                if (isK) target = number * 1000;
                if (isPercentage) target = number;
                if (hasStar) target = number;
                
                const duration = 2000;
                const startTime = Date.now();
                
                function updateCounter() {
                    const elapsed = Date.now() - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const eased = progress * (2 - progress);
                    
                    let current = eased * target;
                    let display = Math.floor(current);
                    
                    if (isK) {
                        display = Math.floor(current / 1000);
                        stat.textContent = display + 'K+';
                    } else if (isPercentage) {
                        stat.textContent = Math.round(current * 10) / 10 + '%';
                    } else if (hasStar) {
                        stat.textContent = (Math.round(current * 10) / 10).toFixed(1) + '★';
                    } else {
                        stat.textContent = display.toLocaleString() + '+';
                    }
                    
                    if (progress < 1) {
                        requestAnimationFrame(updateCounter);
                    } else {
                        // Final value
                        if (isK) {
                            stat.textContent = Math.floor(target / 1000) + 'K+';
                        } else if (isPercentage) {
                            stat.textContent = target + '%';
                        } else if (hasStar) {
                            stat.textContent = target + '★';
                        } else {
                            stat.textContent = target.toLocaleString() + '+';
                        }
                    }
                }
                
                // Start counter when element is in view
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            updateCounter();
                            observer.unobserve(entry.target);
                        }
                    });
                });
                
                observer.observe(stat);
            });
        }

        // Run counter animation after page load
        window.addEventListener('load', animateCounters);

        // === KEYBOARD SHORTCUT: Toggle theme ===
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.shiftKey && (e.key === 'D' || e.key === 'd')) {
                e.preventDefault();
                themeToggle.click();
            }
        });
    </script>
</body>
</html>