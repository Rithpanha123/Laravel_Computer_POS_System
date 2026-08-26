<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Computer POS System</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', 'Kantumruy Pro', sans-serif;
        }

        /* 3D Rotating Rings */
        @keyframes spin-clockwise {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }
        @keyframes spin-counter {
            0% { transform: translate(-50%, -50%) rotate(360deg); }
            100% { transform: translate(-50%, -50%) rotate(0deg); }
        }
        @keyframes pulse-ring {
            0%, 100% { opacity: 0.35; transform: translate(-50%, -50%) scale(0.96); }
            50% { opacity: 0.85; transform: translate(-50%, -50%) scale(1.04); }
        }
        @keyframes shimmer {
            100% { transform: translateX(100%); }
        }

        .cyber-ring-outer {
            animation: spin-clockwise 22s linear infinite;
        }
        .cyber-ring-inner {
            animation: spin-counter 16s linear infinite;
        }
        .cyber-ring-pulse {
            animation: pulse-ring 6s ease-in-out infinite;
        }

        /* Shimmer overlay */
        .shimmer-btn::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            transform: translateX(-100%);
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.35), transparent);
            animation: shimmer 2.8s infinite;
        }
    </style>
</head>
<body class="bg-[#050813] text-slate-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden selection:bg-cyan-500 selection:text-white">

    <!-- Interactive Animated Particle Canvas -->
    <canvas id="particleCanvas" class="fixed inset-0 pointer-events-none z-0"></canvas>

    <!-- Neon Cyber Rings Around the Card -->
    <div class="fixed top-1/2 left-1/2 w-[720px] h-[720px] rounded-full border border-cyan-500/20 cyber-ring-outer pointer-events-none z-0"></div>
    <div class="fixed top-1/2 left-1/2 w-[580px] h-[580px] rounded-full border border-dashed border-indigo-500/30 cyber-ring-inner pointer-events-none z-0"></div>
    <div class="fixed top-1/2 left-1/2 w-[460px] h-[460px] rounded-full bg-cyan-500/10 blur-[90px] cyber-ring-pulse pointer-events-none z-0"></div>

    <div class="w-full max-w-md relative z-10">
        
        <!-- Logo & Branding -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-cyan-500 via-indigo-600 to-blue-500 text-white text-2xl shadow-xl shadow-cyan-500/30 mb-4 ring-2 ring-cyan-400/30 transition-transform duration-300 hover:scale-110 hover:rotate-3">
                <i class="fa-solid fa-atom"></i>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white drop-shadow-[0_0_20px_rgba(6,182,212,0.4)]">
                Computer <span class="bg-gradient-to-r from-cyan-400 via-sky-300 to-indigo-400 bg-clip-text text-transparent">POS System</span>
            </h1>
            <p class="text-slate-400 text-sm mt-1.5">Sign in to your dashboard to manage sales</p>
        </div>

        <!-- Glassmorphism Login Card -->
        <div class="bg-slate-900/65 backdrop-blur-2xl rounded-3xl p-7 sm:p-9 shadow-2xl border border-slate-700/60 hover:border-cyan-500/40 transition-all duration-300 shadow-[0_0_50px_rgba(6,182,212,0.12)]">
            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Username or Email -->
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300 mb-2">
                        Username or Email
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 group-focus-within:text-cyan-400 group-focus-within:scale-110 transition-all duration-200">
                            <i class="fa-solid fa-user-astronaut text-sm"></i>
                        </span>
                        <input 
                            type="text" 
                            name="login" 
                            value="{{ old('login') }}" 
                            class="w-full pl-10 pr-4 py-3 bg-slate-800/50 border @error('login') border-rose-500 bg-rose-500/10 @else border-slate-700/80 hover:border-slate-500 @enderror rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-4 focus:ring-cyan-500/15 focus:bg-slate-800 transition duration-200" 
                            placeholder="admin or user@example.com" 
                            required 
                            autofocus
                        >
                    </div>
                    @error('login')
                        <p class="text-rose-400 text-xs font-medium mt-1.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-300">
                            Password
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-cyan-400 hover:text-cyan-300 transition-colors">
                                Forgot password?
                            </a>
                        @endif
                    </div>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400 group-focus-within:text-cyan-400 group-focus-within:scale-110 transition-all duration-200">
                            <i class="fa-solid fa-shield-halved text-sm"></i>
                        </span>
                        <input 
                            id="passwordInput"
                            type="password" 
                            name="password" 
                            class="w-full pl-10 pr-11 py-3 bg-slate-800/50 border @error('password') border-rose-500 bg-rose-500/10 @else border-slate-700/80 hover:border-slate-500 @enderror rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-400 focus:ring-4 focus:ring-cyan-500/15 focus:bg-slate-800 transition duration-200" 
                            placeholder="••••••••" 
                            required
                        >
                        <button 
                            type="button" 
                            onclick="togglePasswordVisibility()" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-cyan-400 focus:outline-none transition-colors"
                        >
                            <i id="toggleIcon" class="fa-regular fa-eye text-sm"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-rose-400 text-xs font-medium mt-1.5 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                        <input 
                            type="checkbox" 
                            name="remember" 
                            class="w-4 h-4 text-cyan-500 bg-slate-800 border-slate-700 rounded focus:ring-cyan-400 focus:ring-offset-slate-900 cursor-pointer"
                        >
                        <span class="text-xs text-slate-300 group-hover:text-white transition-colors">Remember me</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="shimmer-btn w-full relative overflow-hidden py-3.5 px-4 bg-gradient-to-r from-cyan-500 via-blue-600 to-indigo-600 hover:from-cyan-400 hover:via-blue-500 hover:to-indigo-500 text-white text-sm font-bold rounded-xl shadow-lg shadow-cyan-500/30 hover:shadow-cyan-400/40 transition-all duration-200 transform active:scale-[0.98] focus:outline-none focus:ring-4 focus:ring-cyan-500/20"
                    >
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            <span>Sign In to System</span>
                            <i class="fa-solid fa-arrow-right-to-bracket text-sm"></i>
                        </span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-slate-500 mt-8">
            &copy; 2026 Computer POS System. All rights reserved.
        </p>
    </div>

    <!-- Interactive Script for Password Toggle & Particle Mesh -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('passwordInput');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Particle Mesh Animation Script
        const canvas = document.getElementById('particleCanvas');
        const ctx = canvas.getContext('2d');
        let particles = [];
        const particleCount = 55;

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.vx = (Math.random() - 0.5) * 0.8;
                this.vy = (Math.random() - 0.5) * 0.8;
                this.radius = Math.random() * 2 + 1;
                this.color = Math.random() > 0.5 ? 'rgba(6, 182, 212, ' : 'rgba(99, 102, 241, ';
            }

            update() {
                this.x += this.vx;
                this.y += this.vy;

                if (this.x < 0 || this.x > canvas.width) this.vx *= -1;
                if (this.y < 0 || this.y > canvas.height) this.vy *= -1;
            }

            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
                ctx.fillStyle = this.color + '0.7)';
                ctx.fill();
            }
        }

        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }

        function animateParticles() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            for (let i = 0; i < particles.length; i++) {
                particles[i].update();
                particles[i].draw();

                for (let j = i + 1; j < particles.length; j++) {
                    const dx = particles[i].x - particles[j].x;
                    const dy = particles[i].y - particles[j].y;
                    const dist = Math.sqrt(dx * dx + dy * dy);

                    if (dist < 130) {
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(6, 182, 212, ${0.25 * (1 - dist / 130)})`;
                        ctx.lineWidth = 0.8;
                        ctx.moveTo(particles[i].x, particles[i].y);
                        ctx.lineTo(particles[j].x, particles[j].y);
                        ctx.stroke();
                    }
                }
            }
            requestAnimationFrame(animateParticles);
        }

        animateParticles();
    </script>
</body>
</html>