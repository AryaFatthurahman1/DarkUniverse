<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DarkUniverse - Luxury Dark Theme</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-primary: #050508;
            --bg-secondary: #0c0c10;
            --bg-card: rgba(20, 20, 25, 0.8);
            --border: rgba(255, 255, 255, 0.08);
            --border-bright: rgba(139, 92, 246, 0.4);
            --text-primary: #fafafa;
            --text-secondary: #a1a1aa;
            --text-muted: #52525b;
            --accent: #8b5cf6;
            --accent-light: #a78bfa;
            --gradient-1: linear-gradient(135deg, #8b5cf6 0%, #ec4899 50%, #f59e0b 100%);
            --gradient-2: linear-gradient(135deg, #06b6d4 0%, #8b5cf6 100%);
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }
        h1, h2, h3 { font-family: 'Space Grotesk', sans-serif; }
        .grid-pattern {
            background-size: 40px 40px;
            background-image: 
                linear-gradient(to right, rgba(139, 92, 246, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(139, 92, 246, 0.05) 1px, transparent 1px);
        }
        .orb-glow {
            position: absolute;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            filter: blur(120px);
            pointer-events: none;
            z-index: -1;
        }
        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 24px;
            transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .glass-card:hover {
            border-color: var(--border-bright);
            transform: translateY(-4px);
            box-shadow: 0 20px 60px rgba(139, 92, 246, 0.1);
        }
        .btn-primary {
            background: var(--gradient-1);
            padding: 16px 40px;
            border-radius: 100px;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            color: #050508;
        }
        .btn-primary:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 40px rgba(139, 92, 246, 0.3);
        }
        .section-padding {
            padding: 100px 0;
        }
        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; animation: none !important; }
        }
    </style>
</head>
<body class="antialiased">
    <!-- Background Effects -->
    <div class="orb-glow" style="top: -200px; left: -200px; background: radial-gradient(circle, rgba(139, 92, 246, 0.15), transparent 70%);"></div>
    <div class="orb-glow" style="bottom: -200px; right: -200px; background: radial-gradient(circle, rgba(236, 72, 153, 0.1), transparent 70%);"></div>
    <div class="grid-pattern fixed inset-0 z-0"></div>
    
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#050508]/80 backdrop-blur-xl border-b border-[rgba(255,255,255,0.05)]">
        <div class="max-w-7xl mx-auto px-8 py-5 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="text-xl font-bold tracking-tight">Dark<span class="text-purple-400">Universe</span></span>
            </div>
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-[#a1a1aa] hover:text-white transition-colors text-sm font-medium">Features</a>
                <a href="#about" class="text-[#a1a1aa] hover:text-white transition-colors text-sm font-medium">About</a>
                <a href="#contact" class="text-[#a1a1aa] hover:text-white transition-colors text-sm font-medium">Contact</a>
            </div>
            <a href="/" class="text-sm font-medium text-[#a1a1aa] hover:text-white transition-colors">
                <svg class="w-5 h-5 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Main Hub
            </a>
        </div>
    </nav>

    <main>
        <section class="section-padding flex items-center justify-center min-h-screen relative">
            <div class="text-center max-w-4xl mx-auto px-8">
                <div class="inline-block px-4 py-2 bg-white/5 border border-white/10 rounded-full text-sm font-medium mb-8">
                    Premium Dark Experience
                </div>
                <h1 class="text-6xl md:text-8xl font-bold mb-8 leading-tight">
                    Embrace the<span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-pink-500 to-amber-400"> Darkness</span>
                </h1>
                <p class="text-xl text-[#a1a1aa] mb-10 max-w-2xl mx-auto leading-relaxed">
                    A sophisticated dark theme design that combines elegance with modern aesthetics. Built for those who appreciate the subtle beauty of the night.
                </p>
                <div class="flex gap-4 justify-center">
                    <button class="btn-primary">Explore Theme</button>
                    <button class="px-8 py-4 border border-white/20 rounded-full text-sm font-medium hover:bg-white/5 transition-colors">View Gallery</button>
                </div>
            </div>
        </section>

        <section id="features" class="section-padding border-t border-white/5">
            <div class="max-w-7xl mx-auto px-8">
                <div class="text-center mb-20">
                    <span class="text-purple-400 text-sm font-medium tracking-widest uppercase mb-4 block">Features</span>
                    <h2 class="text-4xl md:text-5xl font-bold mb-4">Design with Purpose</h2>
                    <p class="text-[#a1a1aa] max-w-2xl mx-auto">Every element is crafted to create an immersive dark mode experience.</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="glass-card p-8">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 border border-purple-500/20 flex items-center justify-center mb-6">
                            <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Elegant Typography</h3>
                        <p class="text-[#a1a1aa] leading-relaxed">Carefully selected fonts that enhance readability and create a sophisticated visual hierarchy in dark environments.</p>
                    </div>
                    <div class="glass-card p-8">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 border border-purple-500/20 flex items-center justify-center mb-6">
                            <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Dynamic Glow</h3>
                        <p class="text-[#a1a1aa] leading-relaxed">Subtle gradients and orbs create depth without overwhelming the user experience.</p>
                    </div>
                    <div class="glass-card p-8">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 border border-purple-500/20 flex items-center justify-center mb-6">
                            <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-4">Smooth Interactions</h3>
                        <p class="text-[#a1a1aa] leading-relaxed">Thoughtful animations and transitions that feel natural and responsive.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-padding border-t border-white/5">
            <div class="max-w-7xl mx-auto px-8">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div>
                        <span class="text-purple-400 text-sm font-medium tracking-widest uppercase mb-4 block">Dark Gallery</span>
                        <h2 class="text-4xl md:text-5xl font-bold mb-6">A Collection of Beauty</h2>
                        <p class="text-[#a1a1aa] mb-8 leading-relaxed">Explore our curated selection of dark mode designs that push the boundaries of digital elegance. Each piece tells a story through color, form, and texture.</p>
                        <button class="btn-primary">View Collection</button>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="glass-card p-8 aspect-square flex items-center justify-center">
                            <svg class="w-16 h-16 text-purple-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="1.5"/></svg>
                        </div>
                        <div class="glass-card p-8 aspect-square flex items-center justify-center mt-8">
                            <svg class="w-16 h-16 text-pink-500/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="1.5"/></svg>
                        </div>
                        <div class="glass-card p-8 aspect-square flex items-center justify-center">
                            <svg class="w-16 h-16 text-amber-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon points="12,2 22,8.5 22,15.5 12,22 2,15.5 2,8.5" stroke-width="1.5"/></svg>
                        </div>
                        <div class="glass-card p-8 aspect-square flex items-center justify-center mt-8">
                            <svg class="w-16 h-16 text-teal-400/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke-width="1.5"/></svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="section-padding border-t border-white/5">
            <div class="text-center max-w-2xl mx-auto px-8">
                <span class="text-purple-400 text-sm font-medium tracking-widest uppercase mb-4 block">Get In Touch</span>
                <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to Experience?</h2>
                <p class="text-[#a1a1aa] mb-10 leading-relaxed">Join us in exploring the beauty of dark mode design. Let's create something extraordinary together.</p>
                <button class="btn-primary">Start Project</button>
                <p class="text-sm text-[#52525b] mt-8">© 2026 DarkUniverse. All rights reserved.</p>
            </div>
        </section>
    </main>
</body>
</html>