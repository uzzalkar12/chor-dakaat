<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>চোর ডাকাত পুলিশ বাবু — বাংলাদেশের অনলাইন গেম</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Tiro+Bangla:ital@0;1&family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --ink:     #0a0b10;
            --surface: #10121c;
            --card:    #161927;
            --border:  rgba(255,255,255,0.07);
            --gold:    #f0a500;
            --gold2:   #ffc94d;
            --red:     #e63946;
            --blue:    #4ea8de;
            --green:   #57cc99;
            --purple:  #c77dff;
            --text:    #e4e6f0;
            --muted:   #686d8a;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            background: var(--ink);
            color: var(--text);
            font-family: 'Hind Siliguri', sans-serif;
            overflow-x: hidden;
        }

        /* ── NOISE GRAIN OVERLAY ── */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: 0.4;
        }

        /* ── NAV ── */
        nav {
            position: fixed; top: 0; left: 0; right: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.1rem 3rem;
            background: rgba(10,11,16,0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
        }
        .nav-brand {
            font-family: 'Tiro Bangla', serif;
            font-size: 1.15rem;
            font-weight: 700;
            background: linear-gradient(120deg, var(--gold), var(--red));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.01em;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
            list-style: none;
        }
        .nav-links a {
            color: var(--muted);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--text); }
        .btn-nav {
            padding: 0.5rem 1.3rem;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--gold), #d4850e);
            color: #1a0f00;
            font-weight: 700;
            font-size: 0.9rem;
            font-family: 'Hind Siliguri', sans-serif;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(240,165,0,0.3);
        }
        .btn-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(240,165,0,0.45);
        }
        @media(max-width:640px) {
            nav { padding: 1rem 1.5rem; }
            .nav-links { display: none; }
        }

        /* ── HERO ── */
        #hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 8rem 1.5rem 4rem;
            overflow: hidden;
        }

        /* Animated mesh background */
        .hero-bg {
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 50% 40%, rgba(240,165,0,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 20% 80%, rgba(230,57,70,0.07) 0%, transparent 55%),
                radial-gradient(ellipse 50% 60% at 80% 20%, rgba(78,168,222,0.06) 0%, transparent 55%);
            z-index: 0;
        }

        /* Floating card ornaments */
        .hero-orbs {
            position: absolute; inset: 0;
            pointer-events: none;
            z-index: 1;
        }
        .orb {
            position: absolute;
            border-radius: 16px;
            font-size: 2.5rem;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 4px;
            padding: 1rem;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(22,25,39,0.7);
            backdrop-filter: blur(8px);
            animation: floatOrb 6s ease-in-out infinite;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }
        .orb span { font-size: 0.65rem; color: var(--muted); font-family: 'Hind Siliguri', sans-serif; }
        .orb-1 { top: 18%; left: 6%;  animation-delay: 0s;   animation-duration: 7s; }
        .orb-2 { top: 12%; right: 8%; animation-delay: 1.5s; animation-duration: 6s; }
        .orb-3 { bottom: 22%; left: 4%;  animation-delay: 3s;  animation-duration: 8s; }
        .orb-4 { bottom: 18%; right: 6%; animation-delay: 0.8s; animation-duration: 6.5s; }
        @keyframes floatOrb {
            0%,100% { transform: translateY(0px) rotate(-2deg); }
            50%      { transform: translateY(-18px) rotate(2deg); }
        }
        @media(max-width:768px) { .orb { display: none; } }

        .hero-content { position: relative; z-index: 2; max-width: 760px; }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 1rem;
            border-radius: 50px;
            background: rgba(240,165,0,0.1);
            border: 1px solid rgba(240,165,0,0.25);
            color: var(--gold);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            animation: fadeUp 0.6s ease both;
        }

        .hero-title {
            font-family: 'Tiro Bangla', serif;
            font-size: clamp(2.8rem, 7vw, 5.5rem);
            line-height: 1.15;
            margin-bottom: 1.5rem;
            animation: fadeUp 0.6s 0.1s ease both;
        }
        .hero-title .line1 { display: block; color: var(--text); }
        .hero-title .line2 {
            display: block;
            background: linear-gradient(120deg, var(--gold), var(--red), var(--purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200%;
            animation: gradShift 4s ease infinite;
        }
        @keyframes gradShift {
            0%,100% { background-position: 0% 50%; }
            50%      { background-position: 100% 50%; }
        }

        .hero-sub {
            font-size: 1.1rem;
            color: var(--muted);
            max-width: 500px;
            margin: 0 auto 2.5rem;
            line-height: 1.8;
            animation: fadeUp 0.6s 0.2s ease both;
        }

        .hero-btns {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeUp 0.6s 0.3s ease both;
        }
        .btn-hero-primary {
            padding: 0.85rem 2.2rem;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--gold), #d4850e);
            color: #1a0f00;
            font-weight: 700;
            font-size: 1.05rem;
            font-family: 'Hind Siliguri', sans-serif;
            border: none;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 6px 24px rgba(240,165,0,0.4);
            transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-hero-primary:hover { transform: translateY(-3px); box-shadow: 0 10px 30px rgba(240,165,0,0.5); }
        .btn-hero-ghost {
            padding: 0.85rem 2.2rem;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.04);
            color: var(--text);
            font-weight: 600;
            font-size: 1.05rem;
            font-family: 'Hind Siliguri', sans-serif;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 0.5rem;
        }
        .btn-hero-ghost:hover { background: rgba(255,255,255,0.09); border-color: rgba(255,255,255,0.2); transform: translateY(-3px); }

        .hero-stats {
            display: flex;
            gap: 2.5rem;
            justify-content: center;
            margin-top: 4rem;
            animation: fadeUp 0.6s 0.4s ease both;
        }
        .stat-item { text-align: center; }
        .stat-num {
            font-family: 'Tiro Bangla', serif;
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold);
            display: block;
        }
        .stat-label { font-size: 0.8rem; color: var(--muted); }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── ROLES SECTION ── */
        #roles {
            padding: 7rem 1.5rem;
            position: relative;
        }
        .section-tag {
            display: inline-block;
            color: var(--gold);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 0.75rem;
        }
        .section-title {
            font-family: 'Tiro Bangla', serif;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            margin-bottom: 1rem;
            color: var(--text);
        }
        .section-sub { color: var(--muted); max-width: 480px; line-height: 1.8; }

        .roles-layout {
            max-width: 1100px;
            margin: 4rem auto 0;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.2rem;
        }
        @media(max-width:900px) { .roles-layout { grid-template-columns: repeat(2,1fr); } }
        @media(max-width:500px) { .roles-layout { grid-template-columns: 1fr 1fr; gap: 0.75rem; } }

        .role-card {
            border-radius: 20px;
            padding: 2rem 1.5rem;
            text-align: center;
            border: 1px solid var(--border);
            background: var(--card);
            position: relative;
            overflow: hidden;
            transition: transform 0.25s, box-shadow 0.25s;
            cursor: default;
        }
        .role-card::before {
            content: '';
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .role-card:hover { transform: translateY(-8px); }
        .role-card:hover::before { opacity: 1; }

        .role-card.chor::before    { background: radial-gradient(ellipse at 50% 0%, rgba(230,57,70,0.12) 0%, transparent 70%); }
        .role-card.daakat::before  { background: radial-gradient(ellipse at 50% 0%, rgba(199,125,255,0.12) 0%, transparent 70%); }
        .role-card.police::before  { background: radial-gradient(ellipse at 50% 0%, rgba(78,168,222,0.12) 0%, transparent 70%); }
        .role-card.babu::before    { background: radial-gradient(ellipse at 50% 0%, rgba(240,165,0,0.12) 0%, transparent 70%); }

        .role-card:hover { box-shadow: 0 20px 60px rgba(0,0,0,0.4); }
        .role-card.chor:hover   { border-color: rgba(230,57,70,0.3); }
        .role-card.daakat:hover { border-color: rgba(199,125,255,0.3); }
        .role-card.police:hover { border-color: rgba(78,168,222,0.3); }
        .role-card.babu:hover   { border-color: rgba(240,165,0,0.3); }

        .role-emoji { font-size: 3.2rem; display: block; margin-bottom: 1rem; }
        .role-name  {
            font-family: 'Tiro Bangla', serif;
            font-size: 1.4rem;
            font-weight: 700;
            display: block;
            margin-bottom: 0.4rem;
        }
        .chor   .role-name { color: var(--red); }
        .daakat .role-name { color: var(--purple); }
        .police .role-name { color: var(--blue); }
        .babu   .role-name { color: var(--gold); }

        .role-pts {
            font-size: 1.6rem;
            font-weight: 700;
            display: block;
            margin-bottom: 0.6rem;
        }
        .chor   .role-pts { color: var(--red); }
        .daakat .role-pts { color: var(--purple); }
        .police .role-pts { color: var(--blue); }
        .babu   .role-pts { color: var(--gold); }

        .role-desc-text { font-size: 0.85rem; color: var(--muted); line-height: 1.7; }

        /* ── HOW TO PLAY ── */
        #howto {
            padding: 7rem 1.5rem;
            background: linear-gradient(180deg, transparent, rgba(240,165,0,0.03), transparent);
        }
        .howto-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }
        @media(max-width:768px) { .howto-inner { grid-template-columns: 1fr; gap: 3rem; } }

        .steps { margin-top: 2.5rem; display: flex; flex-direction: column; gap: 1.5rem; }
        .step {
            display: flex;
            gap: 1.2rem;
            align-items: flex-start;
        }
        .step-num {
            width: 36px; height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--gold), #d4850e);
            color: #1a0f00;
            font-weight: 800;
            font-size: 0.9rem;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .step-text h4 { font-size: 1rem; font-weight: 600; margin-bottom: 0.3rem; }
        .step-text p  { font-size: 0.88rem; color: var(--muted); line-height: 1.7; }

        /* Rules card on right */
        .rules-visual {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }
        .rules-visual h3 {
            font-family: 'Tiro Bangla', serif;
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            color: var(--gold);
            display: flex; align-items: center; gap: 0.5rem;
        }
        .rule-row {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
            padding: 0.9rem 0;
            border-bottom: 1px solid var(--border);
        }
        .rule-row:last-child { border-bottom: none; }
        .rule-icon { font-size: 1.4rem; flex-shrink: 0; margin-top: 2px; }
        .rule-body  { font-size: 0.88rem; line-height: 1.75; color: var(--muted); }
        .rule-body strong { color: var(--text); }

        /* ── FEATURES ── */
        #features {
            padding: 7rem 1.5rem;
        }
        .features-grid {
            max-width: 1100px;
            margin: 4rem auto 0;
            display: grid;
            grid-template-columns: repeat(3,1fr);
            gap: 1.5rem;
        }
        @media(max-width:900px) { .features-grid { grid-template-columns: 1fr 1fr; } }
        @media(max-width:560px) { .features-grid { grid-template-columns: 1fr; } }

        .feat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 2rem;
            transition: transform 0.2s, border-color 0.2s;
        }
        .feat-card:hover { transform: translateY(-5px); border-color: rgba(240,165,0,0.2); }
        .feat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            background: rgba(240,165,0,0.1);
            border: 1px solid rgba(240,165,0,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 1.2rem;
        }
        .feat-card h3 { font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem; }
        .feat-card p  { font-size: 0.87rem; color: var(--muted); line-height: 1.75; }

        /* ── CONTACT ── */
        #contact {
            padding: 7rem 1.5rem;
            background: linear-gradient(180deg, transparent, rgba(78,168,222,0.03), transparent);
        }
        .contact-inner {
            max-width: 700px;
            margin: 0 auto;
            text-align: center;
        }
        .contact-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 3rem 2.5rem;
            margin-top: 3rem;
        }
        .contact-links {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        .contact-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.6rem;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.04);
            color: var(--text);
            font-family: 'Hind Siliguri', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }
        .contact-btn:hover { background: rgba(255,255,255,0.09); border-color: rgba(255,255,255,0.2); transform: translateY(-2px); }

        /* Contact form */
        .form-row { margin-bottom: 1rem; text-align: left; }
        .form-row label { display: block; font-size: 0.85rem; color: var(--muted); margin-bottom: 0.4rem; }
        .form-row input,
        .form-row textarea {
            width: 100%;
            padding: 0.75rem 1rem;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: 'Hind Siliguri', sans-serif;
            font-size: 0.95rem;
            transition: border-color 0.2s;
            resize: none;
        }
        .form-row input:focus, .form-row textarea:focus {
            outline: none;
            border-color: var(--blue);
            background: rgba(78,168,222,0.05);
        }
        .form-row input::placeholder, .form-row textarea::placeholder { color: var(--muted); }
        .btn-submit {
            width: 100%;
            padding: 0.85rem;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--blue), #2d7eb5);
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            font-family: 'Hind Siliguri', sans-serif;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 16px rgba(78,168,222,0.3);
            margin-top: 0.5rem;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(78,168,222,0.45); }

        /* ── CTA BANNER ── */
        #cta {
            padding: 6rem 1.5rem;
        }
        .cta-box {
            max-width: 700px;
            margin: 0 auto;
            text-align: center;
            background: linear-gradient(135deg, rgba(240,165,0,0.08), rgba(230,57,70,0.08));
            border: 1px solid rgba(240,165,0,0.2);
            border-radius: 28px;
            padding: 4rem 3rem;
            position: relative;
            overflow: hidden;
        }
        .cta-box::before {
            content: '';
            position: absolute;
            top: -60px; left: 50%;
            transform: translateX(-50%);
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(240,165,0,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .cta-box h2 {
            font-family: 'Tiro Bangla', serif;
            font-size: clamp(1.8rem, 4vw, 2.6rem);
            margin-bottom: 1rem;
            position: relative;
        }
        .cta-box p  { color: var(--muted); margin-bottom: 2rem; font-size: 1rem; line-height: 1.8; position: relative; }
        .cta-btns { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; position: relative; }

        /* ── FOOTER ── */
        footer {
            border-top: 1px solid var(--border);
            padding: 3rem 3rem 2rem;
        }
        .footer-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
        }
        @media(max-width:768px) {
            .footer-inner { grid-template-columns: 1fr 1fr; gap: 2rem; }
        }
        @media(max-width:480px) {
            .footer-inner { grid-template-columns: 1fr; }
            footer { padding: 2rem 1.5rem; }
        }

        .footer-brand .brand-name {
            font-family: 'Tiro Bangla', serif;
            font-size: 1.2rem;
            background: linear-gradient(120deg, var(--gold), var(--red));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: block;
            margin-bottom: 0.75rem;
        }
        .footer-brand p { font-size: 0.85rem; color: var(--muted); line-height: 1.7; }

        .footer-col h4 { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); margin-bottom: 1rem; }
        .footer-col ul  { list-style: none; display: flex; flex-direction: column; gap: 0.6rem; }
        .footer-col a   { color: var(--muted); text-decoration: none; font-size: 0.88rem; transition: color 0.2s; }
        .footer-col a:hover { color: var(--text); }

        .footer-bottom {
            max-width: 1100px;
            margin: 2.5rem auto 0;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.82rem;
            color: var(--muted);
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .footer-roles { display: flex; gap: 0.5rem; }
        .footer-roles span { font-size: 1.2rem; }

        /* ── SECTION CENTER HELPER ── */
        .section-center { text-align: center; margin: 0 auto; }

        /* ── SCROLL REVEAL ── */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>

    <!-- NAV -->
    <nav>
        <div class="nav-brand">🎮 চোর-ডাকাত-পুলিশ-বাবু</div>
        <ul class="nav-links">
            <li><a href="#hero">হোম</a></li>
            <li><a href="#roles">রোলসমূহ</a></li>
            <li><a href="#howto">কীভাবে খেলবেন</a></li>
            <li><a href="#features">ফিচার</a></li>
            <li><a href="#contact">যোগাযোগ</a></li>
        </ul>
        <a href="{{ route('login') }}" class="btn-nav">🚀 খেলুন</a>
    </nav>

    <!-- ═══════════════════════════ HERO ═══════════════════════════ -->
    <section id="hero">
        <div class="hero-bg"></div>
        <div class="hero-orbs">
            <div class="orb orb-1">🦹<span>চোর</span></div>
            <div class="orb orb-2">💀<span>ডাকাত</span></div>
            <div class="orb orb-3">👮<span>পুলিশ</span></div>
            <div class="orb orb-4">🎩<span>বাবু</span></div>
        </div>
        <div class="hero-content">
            <div class="hero-eyebrow">🇧🇩 বাংলাদেশের ঐতিহ্যবাহী গেম</div>
            <h1 class="hero-title">
                <span class="line1">চোর ডাকাত পুলিশ বাবু</span>
                <span class="line2">এখন অনলাইনে!</span>
            </h1>
            <p class="hero-sub">
                ৪ বন্ধু মিলে খেলুন রিয়েলটাইমে। পুলিশ হলে ধরুন, চোর বা ডাকাত হলে পালান — বাবু সবসময় নিরাপদ!
            </p>
            <div class="hero-btns">
                <a href="{{ route('register') }}" class="btn-hero-primary">🎮 এখনই শুরু করুন</a>
                <a href="{{ route('login') }}" class="btn-hero-ghost">🔑 লগইন করুন</a>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-num">৪</span>
                    <span class="stat-label">খেলোয়াড়</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">রিয়েলটাইম</span>
                    <span class="stat-label">WebSocket</span>
                </div>
                <div class="stat-item">
                    <span class="stat-num">বিনামূল্যে</span>
                    <span class="stat-label">সম্পূর্ণ ফ্রি</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════ ROLES ═══════════════════════════ -->
    <section id="roles">
        <div class="section-center reveal">
            <span class="section-tag">চরিত্রসমূহ</span>
            <h2 class="section-title">৪টি রোল, ৪ রকম ভাগ্য</h2>
            <p class="section-sub">প্রতিটি রাউন্ডে র‍্যান্ডমলি একটি রোল পাবেন। কে কী পাবে — কেউ জানে না!</p>
        </div>
        <div class="roles-layout reveal">
            <div class="role-card chor">
                <span class="role-emoji">🦹</span>
                <span class="role-name">চোর</span>
                <span class="role-pts">৪০ পয়েন্ট</span>
                <p class="role-desc-text">পুলিশের চোখ ফাঁকি দিয়ে পালান। ধরা না পড়লে ৪০ পয়েন্ট আপনার!</p>
            </div>
            <div class="role-card daakat">
                <span class="role-emoji">💀</span>
                <span class="role-name">ডাকাত</span>
                <span class="role-pts">৬০ পয়েন্ট</span>
                <p class="role-desc-text">চোরের চেয়ে বড় অপরাধী। পুলিশ আপনাকে খুঁজছে — সাবধান!</p>
            </div>
            <div class="role-card police">
                <span class="role-emoji">👮</span>
                <span class="role-name">পুলিশ</span>
                <span class="role-pts">৮০ পয়েন্ট</span>
                <p class="role-desc-text">সঠিক লোককে চিহ্নিত করুন। ভুল করলে পয়েন্ট শূন্য!</p>
            </div>
            <div class="role-card babu">
                <span class="role-emoji">🎩</span>
                <span class="role-name">বাবু</span>
                <span class="role-pts">১০০ পয়েন্ট</span>
                <p class="role-desc-text">সবচেয়ে ভালো রোল! নিশ্চিন্তে বসে থাকুন — সর্বদা ১০০ পয়েন্ট!</p>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════ HOW TO PLAY ═══════════════════════════ -->
    <section id="howto">
        <div class="howto-inner">
            <div class="reveal">
                <span class="section-tag">নির্দেশিকা</span>
                <h2 class="section-title">কীভাবে খেলবেন?</h2>
                <p class="section-sub">মাত্র কয়েকটি ধাপে শুরু করুন।</p>
                <div class="steps">
                    <div class="step">
                        <div class="step-num">১</div>
                        <div class="step-text">
                            <h4>রুম তৈরি করুন</h4>
                            <p>হোস্ট একটি গেম রুম তৈরি করেন এবং ৬ অক্ষরের কোড শেয়ার করেন।</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-num">২</div>
                        <div class="step-text">
                            <h4>৪ জন যোগ দিন</h4>
                            <p>বন্ধুরা কোড দিয়ে রুমে প্রবেশ করেন। ৪ জন হলেই গেম শুরু হয়।</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-num">৩</div>
                        <div class="step-text">
                            <h4>রোল পান</h4>
                            <p>প্রতিটি রাউন্ডে র‍্যান্ডমলি চোর, ডাকাত, পুলিশ বা বাবু রোল পাবেন।</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-num">৪</div>
                        <div class="step-text">
                            <h4>পয়েন্ট জমান, জিতুন!</h4>
                            <p>সব রাউন্ড শেষে সর্বোচ্চ পয়েন্টধারী বিজয়ী। স্কোর লাইভ আপডেট হয়।</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rules-visual reveal">
                <h3>📜 স্কোর নিয়ম</h3>
                <div class="rule-row">
                    <div class="rule-icon">🔁</div>
                    <div class="rule-body">
                        <strong>বিজোড় রাউন্ড (১,৩,৫...)</strong> — পুলিশ <strong>চোরকে</strong> খোঁজে।
                    </div>
                </div>
                <div class="rule-row">
                    <div class="rule-icon">🔁</div>
                    <div class="rule-body">
                        <strong>জোড় রাউন্ড (২,৪,৬...)</strong> — পুলিশ <strong>ডাকাতকে</strong> খোঁজে।
                    </div>
                </div>
                <div class="rule-row">
                    <div class="rule-icon">✅</div>
                    <div class="rule-body">
                        পুলিশ <strong>সঠিক ব্যক্তি</strong> ধরলে → পুলিশ পায় <strong style="color:var(--blue)">৮০</strong>, ধরা পড়া ব্যক্তি পায় <strong style="color:var(--red)">০</strong>।
                    </div>
                </div>
                <div class="rule-row">
                    <div class="rule-icon">❌</div>
                    <div class="rule-body">
                        পুলিশ <strong>ভুল করলে</strong> → পুলিশ পায় <strong style="color:var(--red)">০</strong>, লক্ষ্য ব্যক্তি তার <strong>পূর্ণ পয়েন্ট</strong> পায়।
                    </div>
                </div>
                <div class="rule-row">
                    <div class="rule-icon">🎩</div>
                    <div class="rule-body">
                        <strong>বাবু সর্বদা ১০০ পয়েন্ট</strong> পান — কোনো ঝুঁকি নেই!
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════ FEATURES ═══════════════════════════ -->
    <section id="features">
        <div class="section-center reveal">
            <span class="section-tag">প্রযুক্তি</span>
            <h2 class="section-title">কেন এই গেম খেলবেন?</h2>
            <p class="section-sub">আধুনিক প্রযুক্তি দিয়ে বানানো — মসৃণ, দ্রুত, এবং মজাদার।</p>
        </div>
        <div class="features-grid">
            <div class="feat-card reveal">
                <div class="feat-icon">⚡</div>
                <h3>রিয়েলটাইম WebSocket</h3>
                <p>Laravel Reverb দিয়ে তৈরি — প্রতিটি মুভ তাৎক্ষণিকভাবে সবার কাছে পৌঁছায়।</p>
            </div>
            <div class="feat-card reveal">
                <div class="feat-icon">🔒</div>
                <h3>সুরক্ষিত রুম সিস্টেম</h3>
                <p>৬ অক্ষরের ইউনিক কোড — শুধু আপনার বন্ধুরাই যোগ দিতে পারবে।</p>
            </div>
            <div class="feat-card reveal">
                <div class="feat-icon">📱</div>
                <h3>মোবাইল ফ্রেন্ডলি</h3>
                <p>ফোন, ট্যাবলেট, ডেস্কটপ — যেকোনো ডিভাইসে সমানভাবে কাজ করে।</p>
            </div>
            <div class="feat-card reveal">
                <div class="feat-icon">🎲</div>
                <h3>র‍্যান্ডম রোল বিতরণ</h3>
                <p>প্রতিটি রাউন্ডে সম্পূর্ণ র‍্যান্ডম — কেউ আগে থেকে জানতে পারবে না।</p>
            </div>
            <div class="feat-card reveal">
                <div class="feat-icon">🏆</div>
                <h3>লাইভ স্কোরবোর্ড</h3>
                <p>প্রতিটি রাউন্ড শেষে স্কোর আপডেট হয়। সবার সামনে ফলাফল প্রকাশ পায়।</p>
            </div>
            <div class="feat-card reveal">
                <div class="feat-icon">🌐</div>
                <h3>বাংলা ভাষায়</h3>
                <p>সম্পূর্ণ বাংলা ইন্টারফেস — বাংলাদেশের সব মানুষের জন্য তৈরি।</p>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════ CONTACT ═══════════════════════════ -->
    <section id="contact">
        <div class="contact-inner">
            <div class="reveal section-center">
                <span class="section-tag">যোগাযোগ</span>
                <h2 class="section-title">কিছু জানতে চান?</h2>
                <p class="section-sub" style="margin:0 auto">প্রশ্ন, পরামর্শ বা বাগ রিপোর্ট — আমাদের জানান।</p>
            </div>
            <div class="contact-card reveal">
                <div class="form-row">
                    <label>আপনার নাম</label>
                    <input type="text" placeholder="নাম লিখুন">
                </div>
                <div class="form-row">
                    <label>ইমেইল</label>
                    <input type="email" placeholder="email@example.com">
                </div>
                <div class="form-row">
                    <label>বার্তা</label>
                    <textarea rows="4" placeholder="আপনার বার্তা লিখুন..."></textarea>
                </div>
                <button class="btn-submit" onclick="this.textContent='✅ পাঠানো হয়েছে!';this.disabled=true">
                    📨 বার্তা পাঠান
                </button>
                <div class="contact-links">
                    <a href="mailto:hello@chordakaat.com" class="contact-btn">📧 ইমেইল</a>
                    <a href="#" class="contact-btn">💬 Facebook</a>
                    <a href="#" class="contact-btn">🐦 Twitter</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════ CTA ═══════════════════════════ -->
    <section id="cta">
        <div class="cta-box reveal">
            <h2>এখনই খেলা শুরু করুন!</h2>
            <p>বন্ধুদের ডাকুন, রুম তৈরি করুন এবং বাংলাদেশের সবচেয়ে মজার গেম উপভোগ করুন।</p>
            <div class="cta-btns">
                <a href="{{ route('register') }}" class="btn-hero-primary">🎮 ফ্রি রেজিস্ট্রেশন</a>
                <a href="{{ route('login') }}" class="btn-hero-ghost">🔑 লগইন</a>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════ FOOTER ═══════════════════════════ -->
    <footer>
        <div class="footer-inner">
            <div class="footer-brand">
                <span class="brand-name">🎮 চোর-ডাকাত-পুলিশ-বাবু</span>
                <p>বাংলাদেশের ঐতিহ্যবাহী কার্ড গেম এখন ডিজিটাল রূপে। ৪ বন্ধু মিলে যেকোনো জায়গা থেকে খেলুন।</p>
            </div>
            <div class="footer-col">
                <h4>গেম</h4>
                <ul>
                    <li><a href="{{ route('register') }}">নতুন গেম</a></li>
                    <li><a href="{{ route('login') }}">লগইন</a></li>
                    <li><a href="#howto">নিয়মকানুন</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>রোলসমূহ</h4>
                <ul>
                    <li><a href="#roles">🦹 চোর (৪০)</a></li>
                    <li><a href="#roles">💀 ডাকাত (৬০)</a></li>
                    <li><a href="#roles">👮 পুলিশ (৮০)</a></li>
                    <li><a href="#roles">🎩 বাবু (১০০)</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>আরও</h4>
                <ul>
                    <li><a href="#features">ফিচারসমূহ</a></li>
                    <li><a href="#contact">যোগাযোগ</a></li>
                    <li><a href="#">গোপনীয়তা নীতি</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <span>© {{ date('Y') }} চোর-ডাকাত-পুলিশ-বাবু — সর্বস্বত্ব সংরক্ষিত</span>
            <div class="footer-roles">
                <span title="চোর">🦹</span>
                <span title="ডাকাত">💀</span>
                <span title="পুলিশ">👮</span>
                <span title="বাবু">🎩</span>
            </div>
        </div>
    </footer>

    <script>
    // Scroll reveal
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                // stagger children if grid
                e.target.querySelectorAll('.role-card, .feat-card, .step').forEach((el, i) => {
                    el.style.transitionDelay = `${i * 0.08}s`;
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                });
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.reveal').forEach(el => {
        // Pre-hide grid children for stagger
        el.querySelectorAll('.role-card, .feat-card, .step').forEach(child => {
            child.style.opacity = '0';
            child.style.transform = 'translateY(20px)';
            child.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        });
        observer.observe(el);
    });

    // Smooth active nav link highlight
    const sections = document.querySelectorAll('section[id]');
    const navLinks  = document.querySelectorAll('.nav-links a');
    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(s => {
            if (window.scrollY >= s.offsetTop - 120) current = s.id;
        });
        navLinks.forEach(a => {
            a.style.color = a.getAttribute('href') === `#${current}`
                ? 'var(--gold)' : '';
        });
    }, { passive: true });
    </script>
</body>
</html>
