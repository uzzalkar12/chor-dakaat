<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'চোর-ডাকাত-পুলিশ-বাবু')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Tiro+Bangla&display=swap" rel="stylesheet">

    @vite(['resources/js/app.js'])

    <style>
        :root {
            --bg-dark:    #0d0f1a;
            --bg-card:    #161927;
            --bg-glass:   rgba(255,255,255,0.04);
            --border:     rgba(255,255,255,0.08);
            --accent-red: #e63946;
            --accent-gold:#f4a522;
            --accent-blue:#4ea8de;
            --accent-green:#57cc99;
            --text-main:  #e8eaf0;
            --text-muted: #7a7f99;
            --shadow:     0 8px 32px rgba(0,0,0,0.5);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Hind Siliguri', sans-serif;
            min-height: 100vh;
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(78,168,222,0.06) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(230,57,70,0.06) 0%, transparent 60%);
        }

        /* Navbar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border);
            background: rgba(13,15,26,0.9);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar-brand {
            font-family: 'Tiro Bangla', serif;
            font-size: 1.3rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--accent-gold), var(--accent-red));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.02em;
        }
        .navbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        .btn-logout {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-muted);
            padding: 0.4rem 0.9rem;
            border-radius: 6px;
            cursor: pointer;
            font-family: inherit;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .btn-logout:hover {
            border-color: var(--accent-red);
            color: var(--accent-red);
        }

        /* Main content */
        .main { padding: 2rem; max-width: 1100px; margin: 0 auto; }

        /* Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: var(--shadow);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-family: 'Hind Siliguri', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #e63946, #c1121f);
            color: white;
            box-shadow: 0 4px 15px rgba(230,57,70,0.3);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(230,57,70,0.45);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #4ea8de, #2d7eb5);
            color: white;
            box-shadow: 0 4px 15px rgba(78,168,222,0.3);
        }
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(78,168,222,0.45);
        }
        .btn-gold {
            background: linear-gradient(135deg, #f4a522, #d4850e);
            color: #1a1000;
            box-shadow: 0 4px 15px rgba(244,165,34,0.3);
        }
        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(244,165,34,0.45);
        }
        .btn-ghost {
            background: var(--bg-glass);
            border: 1px solid var(--border);
            color: var(--text-main);
        }
        .btn-ghost:hover { background: rgba(255,255,255,0.08); }
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
        }
        .btn-full { width: 100%; }

        /* Form elements */
        .form-group { margin-bottom: 1.2rem; }
        .form-label {
            display: block;
            margin-bottom: 0.4rem;
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-main);
            font-family: 'Hind Siliguri', sans-serif;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--accent-blue);
            background: rgba(78,168,222,0.05);
        }
        .form-input::placeholder { color: var(--text-muted); }

        /* Error messages */
        .alert-error {
            background: rgba(230,57,70,0.1);
            border: 1px solid rgba(230,57,70,0.3);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            color: #ff8a95;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        /* Toast notifications */
        #toast-container {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .toast {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            min-width: 260px;
            box-shadow: var(--shadow);
            animation: slideInRight 0.3s ease;
            font-size: 0.95rem;
        }
        .toast.success { border-left: 4px solid var(--accent-green); }
        .toast.error   { border-left: 4px solid var(--accent-red); }
        .toast.info    { border-left: 4px solid var(--accent-blue); }
        .toast.gold    { border-left: 4px solid var(--accent-gold); }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to   { transform: translateX(0); opacity: 1; }
        }

        /* Loader */
        .spinner {
            width: 24px; height: 24px;
            border: 3px solid var(--border);
            border-top-color: var(--accent-blue);
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
            display: inline-block;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Role badge colors */
        .role-chor   { color: var(--accent-red);   }
        .role-daakat { color: #c77dff;              }
        .role-police { color: var(--accent-blue);   }
        .role-babu   { color: var(--accent-gold);   }
    </style>

    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">🎮 চোর-ডাকাত-পুলিশ-বাবু</div>
        @auth
        <div class="navbar-user">
            <span>👤 {{ Auth::user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline">
                @csrf
                <button type="submit" class="btn-logout">লগআউট</button>
            </form>
        </div>
        @endauth
    </nav>

    <div class="main">
        @yield('content')
    </div>

    <div id="toast-container"></div>

    <script>
        // Global toast function
        function showToast(message, type = 'info', duration = 4000) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;
            container.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                toast.style.transition = 'all 0.3s';
                setTimeout(() => toast.remove(), 300);
            }, duration);
        }

        // CSRF for fetch
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        async function apiPost(url, data = {}) {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });
            return res.json();
        }

        async function apiGet(url) {
            const res = await fetch(url, {
                headers: { 'Accept': 'application/json' },
            });
            return res.json();
        }
    </script>

    @stack('scripts')
</body>
</html>
