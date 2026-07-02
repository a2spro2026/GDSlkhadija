<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Connexion — GROUPE DLIMI SERVICES</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Inter:wght@400;500;600&family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }
        h1, h2, h3, .font-poppins { font-family: 'Poppins', sans-serif; }

        .page-bg {
            background-image: url('{{ asset('images/back-reseau.png') }}');
            animation: parallax 25s ease-in-out infinite alternate;
        }
        @keyframes parallax {
            from { transform: scale(1.05) translateY(0); }
            to   { transform: scale(1.12) translateY(-12px); }
        }

        .overlay-gradient {
            background: linear-gradient(135deg, #020617 0%, #071A35 50%, #0F4C81 100%);
            opacity: 0.88;
        }

        .fade-in { animation: fadeIn 0.9s ease-out forwards; }
        .fade-in-up { animation: fadeInUp 0.9s ease-out forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.15s; }
        .delay-2 { animation-delay: 0.3s; }
        .delay-3 { animation-delay: 0.45s; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(96,165,250,0.3), 0 0 40px rgba(37,99,235,0.15); }
            50%       { box-shadow: 0 0 30px rgba(96,165,250,0.55), 0 0 60px rgba(37,99,235,0.25); }
        }

        @keyframes spinSlow {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        .logo-ring {
            animation: pulseGlow 3s ease-in-out infinite;
        }
        .logo-ring svg {
            animation: spinSlow 20s linear infinite;
        }

        .glass-feature {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.10);
            transition: all 0.35s ease;
        }
        .glass-feature:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(96, 165, 250, 0.35);
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(37, 99, 235, 0.2);
        }

        .login-card {
            width: 100%;
            max-width: 520px;
            border-radius: 35px;
            background: rgba(10, 20, 40, 0.65);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid rgba(96, 165, 250, 0.35);
            box-shadow:
                0 0 25px rgba(37, 99, 235, 0.50),
                0 0 50px rgba(37, 99, 235, 0.40),
                0 0 100px rgba(96, 165, 250, 0.25);
            transition: all 0.4s ease;
        }
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow:
                0 0 35px rgba(37, 99, 235, 0.60),
                0 0 70px rgba(37, 99, 235, 0.45),
                0 0 120px rgba(96, 165, 250, 0.30);
        }

        .login-input {
            height: 60px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.10);
            color: #ffffff;
            transition: border-color 0.25s, box-shadow 0.25s;
        }
        .login-input::placeholder { color: rgba(255, 255, 255, 0.45); }
        .login-input:focus {
            outline: none;
            border-color: rgba(96, 165, 250, 0.55);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2), 0 0 20px rgba(96, 165, 250, 0.15);
        }
        .login-input:-webkit-autofill,
        .login-input:-webkit-autofill:hover,
        .login-input:-webkit-autofill:focus {
            -webkit-text-fill-color: #fff;
            -webkit-box-shadow: 0 0 0 1000px rgba(15, 30, 60, 0.9) inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        .btn-connect {
            height: 60px;
            border-radius: 15px;
            background: linear-gradient(90deg, #2563EB, #3B82F6, #60A5FA);
            box-shadow: 0 0 20px rgba(37, 99, 235, 0.60);
            transition: all 0.35s ease;
        }
        .btn-connect:hover {
            transform: scale(1.03);
            box-shadow: 0 0 30px rgba(37, 99, 235, 0.75);
        }
        .btn-connect:active { transform: scale(1); }

        .badge-2fa {
            backdrop-filter: blur(12px);
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(96, 165, 250, 0.25);
        }

        #particles { position: fixed; inset: 0; z-index: 2; pointer-events: none; }

        .slogan-word {
            background: linear-gradient(90deg, #60A5FA, #fff, #60A5FA);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 4s linear infinite;
        }
        @keyframes shimmer {
            0%   { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        /* Typographie logo — style bleu cyan */
        .logo-graphic {
            filter:
                drop-shadow(0 4px 20px rgba(37, 99, 235, 0.5))
                drop-shadow(0 0 35px rgba(96, 165, 250, 0.35));
        }

        .brand-name {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 0.72rem;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.55);
            line-height: 1.3;
        }
        .brand-name .accent {
            background: linear-gradient(135deg, #60A5FA 0%, #38bdf8 50%, #2563EB 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        .brand-motto {
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
            font-size: 0.6rem;
            letter-spacing: 0.32em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.35);
            margin-top: 0.4rem;
        }
        .brand-motto .dot {
            color: #60A5FA;
            margin: 0 0.35em;
        }
    </style>
</head>
<body class="text-white">

    {{-- Arrière-plan --}}
    <div class="fixed inset-0 z-0">
        <div class="absolute inset-0 page-bg bg-cover bg-center"></div>
        <div class="absolute inset-0 overlay-gradient"></div>
    </div>
    <canvas id="particles" class="z-[2]"></canvas>

  <div class="relative z-10 min-h-screen flex flex-col lg:flex-row">

    {{-- ══════════════ GAUCHE 50% ══════════════ --}}
    <section class="lg:w-1/2 flex flex-col justify-center px-8 md:px-14 xl:px-20 py-12 lg:py-0 order-1">

      <div class="fade-in max-w-xl">

        {{-- Logo animé réseau --}}
        <div class="logo-ring w-20 h-20 rounded-full border border-[#60A5FA]/40 flex items-center justify-center mb-8 bg-white/5">
          <svg class="w-10 h-10 text-[#60A5FA]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"/>
          </svg>
        </div>

        <p class="text-[#60A5FA] text-sm font-semibold tracking-[0.35em] uppercase mb-2 font-poppins">GDS</p>
        <h1 class="font-poppins text-3xl md:text-4xl xl:text-5xl font-bold tracking-wide leading-snug mb-3 uppercase">
          Groupe <span class="text-[#60A5FA]">Dlimi</span> Services
        </h1>
        <p class="text-white/60 text-sm md:text-base font-medium mb-6">
          Installation Réseautique &amp; Gestion de Stock
        </p>

        <div class="space-y-1 mb-8 font-poppins">
          <p class="slogan-word text-2xl md:text-3xl font-bold tracking-[0.2em]">CONNECTER</p>
          <p class="slogan-word text-2xl md:text-3xl font-bold tracking-[0.2em]">INSTALLER</p>
          <p class="slogan-word text-2xl md:text-3xl font-bold tracking-[0.2em]">GÉRER</p>
        </div>

        <p class="text-white/70 text-sm md:text-[15px] leading-relaxed mb-10 max-w-lg">
          La plateforme intelligente pour gérer vos projets d'installation réseau,
          vos équipements, vos techniciens et votre stock en temps réel.
        </p>

        {{-- 4 cartes glassmorphism --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 fade-in-up delay-2">
          <div class="glass-feature rounded-2xl p-4 text-center">
            <i class="fa-solid fa-tower-broadcast text-[#60A5FA] text-xl mb-2"></i>
            <p class="text-xs font-semibold font-poppins">Réseaux</p>
          </div>
          <div class="glass-feature rounded-2xl p-4 text-center">
            <i class="fa-solid fa-shield-halved text-[#60A5FA] text-xl mb-2"></i>
            <p class="text-xs font-semibold font-poppins">Sécurité</p>
          </div>
          <div class="glass-feature rounded-2xl p-4 text-center">
            <i class="fa-solid fa-boxes-stacked text-[#60A5FA] text-xl mb-2"></i>
            <p class="text-xs font-semibold font-poppins">Stock</p>
          </div>
          <div class="glass-feature rounded-2xl p-4 text-center">
            <i class="fa-solid fa-chart-line text-[#60A5FA] text-xl mb-2"></i>
            <p class="text-xs font-semibold font-poppins">Performance</p>
          </div>
        </div>

        <p class="mt-10 text-white/30 text-xs">
          &copy; {{ date('Y') }} A2S — Créé par A2SPRO
        </p>
      </div>
    </section>

    {{-- ══════════════ DROITE 50% ══════════════ --}}
    <section class="lg:w-1/2 flex items-center justify-center px-6 md:px-10 py-12 lg:py-0 order-2 min-h-[60vh] lg:min-h-screen">

      <div class="login-card fade-in-up delay-3 px-8 md:px-10 py-10 w-full">

        {{-- En-tête --}}
        <div class="text-center mb-8">
          <img src="{{ asset('images/logo-gds.png') }}?v=4" alt="GROUPE DLIMI SERVICES"
               class="logo-graphic h-[130px] w-auto max-w-[300px] object-contain mx-auto mb-5">
          <h2 class="font-poppins text-2xl font-bold mb-1">Bienvenue !</h2>
          <p class="text-white/50 text-sm">Connectez-vous pour continuer</p>
        </div>

        @if ($errors->any())
          <div class="mb-6 p-4 rounded-2xl bg-red-500/15 border border-red-400/30 text-red-300 text-sm">
            @foreach ($errors->all() as $error)
              <p><i class="fa-solid fa-circle-exclamation mr-2"></i>{{ $error }}</p>
            @endforeach
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
          @csrf

          {{-- Nom d'utilisateur --}}
          <div>
            <label for="username" class="sr-only">Nom d'utilisateur</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/50 text-lg pointer-events-none">
                <i class="fa-solid fa-user"></i>
              </span>
              <input type="text" id="username" name="username" value="{{ old('username') }}"
                     placeholder="Nom d'utilisateur" required autofocus
                     class="login-input w-full pl-12 pr-4 text-sm">
            </div>
          </div>

          {{-- Mot de passe --}}
          <div>
            <label for="password" class="sr-only">Mot de passe</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-white/50 text-lg pointer-events-none">
                <i class="fa-solid fa-lock"></i>
              </span>
              <input type="password" id="password" name="password" value="{{ old('password') }}"
                     placeholder="Mot de passe" required autocomplete="current-password"
                     class="login-input w-full pl-12 pr-12 text-sm">
              <button type="button" id="togglePassword"
                      class="absolute right-4 top-1/2 -translate-y-1/2 text-white/50 hover:text-[#60A5FA] transition text-lg"
                      aria-label="Afficher le mot de passe">
                <i class="fa-solid fa-eye" id="eyeIcon"></i>
              </button>
            </div>
          </div>

          {{-- Options --}}
          <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 cursor-pointer text-white/70">
              <input type="checkbox" name="remember"
                     class="w-4 h-4 rounded border-white/20 bg-white/5 text-[#2563EB] focus:ring-[#60A5FA]/40">
              Se souvenir de moi
            </label>
            <a href="#" class="text-[#60A5FA] hover:text-white transition font-medium text-sm">
              Mot de passe oublié ?
            </a>
          </div>

          {{-- Bouton connexion --}}
          <button type="submit"
                  class="btn-connect w-full flex items-center justify-center gap-2 text-white font-semibold text-[15px] font-poppins">
            <i class="fa-solid fa-lock"></i>
            Se connecter
          </button>
        </form>

        {{-- Carte secondaire 2FA --}}
        <div class="badge-2fa mt-5 rounded-2xl px-4 py-3 flex items-center justify-center gap-2 text-sm text-white/60">
          <i class="fa-solid fa-shield text-[#60A5FA]"></i>
          Connexion sécurisée (2FA)
        </div>

        {{-- Pied du formulaire --}}
        <div class="mt-8 pt-6 border-t border-white/10 text-center space-y-2">
          <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-6 text-xs text-white/45">
            <span><i class="fa-regular fa-calendar text-[#60A5FA] mr-1.5"></i><span id="dateToday"></span></span>
            <span><i class="fa-regular fa-clock text-[#60A5FA] mr-1.5"></i><span id="timeNow" class="font-mono"></span></span>
          </div>
          <p class="text-[10px] text-white/25 tracking-widest uppercase font-poppins">
            GROUPE DLIMI SERVICES v2.0
          </p>
        </div>
      </div>
    </section>
  </div>

  <script>
    /* Afficher / masquer mot de passe */
    const pwd = document.getElementById('password');
    const toggle = document.getElementById('togglePassword');
    const eye = document.getElementById('eyeIcon');
    toggle.addEventListener('click', () => {
      const hidden = pwd.type === 'password';
      pwd.type = hidden ? 'text' : 'password';
      eye.className = hidden ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
    });

    /* Date & heure */
    function updateClock() {
      const now = new Date();
      document.getElementById('dateToday').textContent = now.toLocaleDateString('fr-FR', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
      });
      document.getElementById('timeNow').textContent = now.toLocaleTimeString('fr-FR');
    }
    updateClock();
    setInterval(updateClock, 1000);

    /* Particules réseau */
    (function () {
      const canvas = document.getElementById('particles');
      const ctx = canvas.getContext('2d');
      let w, h, nodes;

      function resize() {
        w = canvas.width = window.innerWidth;
        h = canvas.height = window.innerHeight;
        const count = Math.min(80, Math.floor(w * h / 22000) + 25);
        nodes = Array.from({ length: count }, () => ({
          x: Math.random() * w,
          y: Math.random() * h,
          vx: (Math.random() - 0.5) * 0.35,
          vy: (Math.random() - 0.5) * 0.35,
          r: Math.random() * 1.8 + 0.4,
        }));
      }

      function draw() {
        ctx.clearRect(0, 0, w, h);
        const maxDist = 150;
        for (let i = 0; i < nodes.length; i++) {
          const a = nodes[i];
          a.x += a.vx; a.y += a.vy;
          if (a.x < 0 || a.x > w) a.vx *= -1;
          if (a.y < 0 || a.y > h) a.vy *= -1;

          ctx.beginPath();
          ctx.arc(a.x, a.y, a.r, 0, Math.PI * 2);
          ctx.fillStyle = 'rgba(96, 165, 250, 0.7)';
          ctx.fill();

          for (let j = i + 1; j < nodes.length; j++) {
            const b = nodes[j];
            const dx = a.x - b.x, dy = a.y - b.y;
            const dist = Math.sqrt(dx * dx + dy * dy);
            if (dist < maxDist) {
              ctx.beginPath();
              ctx.moveTo(a.x, a.y);
              ctx.lineTo(b.x, b.y);
              ctx.strokeStyle = `rgba(96, 165, 250, ${0.12 * (1 - dist / maxDist)})`;
              ctx.lineWidth = 0.7;
              ctx.stroke();
            }
          }
        }
        requestAnimationFrame(draw);
      }

      resize();
      draw();
      window.addEventListener('resize', resize);
    })();
  </script>

</body>
</html>
