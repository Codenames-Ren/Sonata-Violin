<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sonata Violin - Kursus Biola Profesional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#8B5CF6',
                        secondary: '#6366F1',
                        dark: '#0F172A',
                        accent: '#F8FAFC',
                    },
                    fontFamily: {
                        'display': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }

        .loading-screen {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s, visibility 0.5s;
        }

        .loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader {
            width: 80px;
            height: 80px;
            border: 4px solid rgba(139, 92, 246, 0.1);
            border-top: 4px solid #8B5CF6;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            color: white;
            font-size: 1.5rem;
            font-weight: 600;
            margin-top: 1.5rem;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        html {
            scroll-behavior: smooth;
        }

        .navbar-glass {
            background: rgba(15, 23, 42, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(139, 92, 246, 0.1);
        }

        .hero-carousel {
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .carousel-item {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            transition: opacity 1s ease-in-out;
            background-size: cover;
            background-position: center;
            visibility: hidden;
        }

        .carousel-item.active {
            opacity: 1;
            visibility: visible;
            
        }

        .carousel-item button {
            position: relative;
            z-index: 10;  
        }

        .carousel-item:not(.active) button {
            pointer-events: none;  
        }

        .carousel-item.active button {
            pointer-events: auto;  
        }

        .carousel-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(139, 92, 246, 0.3) 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .card-hover {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-hover:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(139, 92, 246, 0.5);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        .glow-button {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%);
            transition: all 0.3s ease;
        }

        .glow-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .glow-button:hover::before {
            width: 300px;
            height: 300px;
        }

        .glow-button:hover {
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.8);
            transform: translateY(-2px);
        }

        .reveal {
            opacity: 0;
            transform: translateY(50px);
            transition: all 0.8s ease-out;
        }

        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .parallax-section {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .modal-backdrop {
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.6);
        }

        .modal-content {
            animation: modalSlideIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @media (max-width: 768px) {
            .hero-carousel {
                height: 100vh; 
                min-height: 600px;
            }
            
            .hero-carousel .text-center {
                padding-top: 80px;  
                padding-bottom: 60px;
            }
            
            .hero-carousel h1 {
                font-size: 2.5rem !important;  
                line-height: 1.2 !important;
            }
            
            .hero-carousel p {
                font-size: 1rem !important;  
                margin-bottom: 1.5rem !important;
            }
            
            .hero-carousel button {
                padding: 0.75rem 2rem !important;  
                font-size: 0.875rem !important;
            }
            
            .hero-carousel .carousel-indicator {
                width: 8px;
                height: 8px;
            }
            
            #carouselPrev, #carouselNext {
                width: 40px !important;
                height: 40px !important;
                font-size: 1.25rem !important;
            }
            
            .hero-carousel > div:last-of-type {
                bottom: 80px !important;
            }
        }

        @media (max-width: 480px) {
            .hero-carousel h1 {
                font-size: 2rem !important;
            }
            
            .hero-carousel p {
                font-size: 0.875rem !important;
            }
        }

        @keyframes slide-in {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slide-out {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .animate-slide-in {
            animation: slide-in 0.5s ease-out;
        }

        #paket_id {
            color: white;
        }

        #paket_id option {
            background-color: #1e293b; 
            color: white;
            padding: 8px;
        }

        #paket_id option[value=""] {
            color: #9ca3af !important; 
            font-style: italic;
            background-color: #1e293b;
        }

        #paket_id option:not([value=""]) {
            color: white; 
        }

        #paket_id option:hover {
            background-color: #8B5CF6;
        }

        #paket_id option:checked {
            background: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%);
            color: white;
            font-weight: 600;
        }

                .navbar-glass a[href="#home"] {
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .navbar-glass a[href="#home"]:hover {
            transform: scale(1.05) translateY(-2px);
            filter: drop-shadow(0 4px 12px rgba(139, 92, 246, 0.5));
        }

        .nav-link {
            position: relative;
            display: inline-block;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #8B5CF6 0%, #6366F1 100%);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-link.text-primary::after {
            width: 100%;
        }

    </style>
</head>

<body class="bg-dark text-white antialiased">
    
<!-- Loading Screen -->
<div class="loading-screen" id="loadingScreen">
    <div class="text-center">
        <div class="loader mx-auto"></div>
        <img src="<?= base_url('image/loading.gif'); ?>" class="loading w-25 h-20 mx-auto">
        <div class="loading-text">SONATA VIOLIN</div>
        <p class="text-white/60 text-sm mt-2">Learn New Experience~</p>
    </div>
</div>

<!-- Navbar -->
<nav class="fixed top-0 left-0 w-full navbar-glass z-40 transition-all duration-300" id="navbar">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <a href="#home" class="text-2xl lg:text-3xl font-bold gradient-text tracking-tight">
                SONATA VIOLIN
            </a>
            
            <!-- Desktop Menu -->
            <ul class="hidden lg:flex space-x-12 text-sm font-medium tracking-wide">
                <li><a href="#home" class="nav-link relative py-2 hover:text-primary transition-all duration-300">HOME</a></li>
                <li><a href="#package" class="nav-link relative py-2 hover:text-primary transition-all duration-300">PACKAGES</a></li>
                <li><a href="#register" class="nav-link relative py-2 hover:text-primary transition-all duration-300">REGISTER</a></li>
                <li><a href="#about" class="nav-link relative py-2 hover:text-primary transition-all duration-300">ABOUT</a></li>
                <li><a href="#contact" class="nav-link relative py-2 hover:text-primary transition-all duration-300">CONTACT</a></li>
            </ul>
            
            <!-- Hamburger Menu -->
            <button id="menuToggle" class="lg:hidden text-3xl hover:text-primary transition-colors duration-300">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden lg:hidden bg-dark/95 backdrop-blur-xl border-t border-primary/20">
        <ul class="flex flex-col space-y-4 px-6 py-6 text-base font-medium">
            <li><a href="#home" class="block hover:text-primary transition-colors duration-300">HOME</a></li>
            <li><a href="#package" class="block hover:text-primary transition-colors duration-300">PACKAGES</a></li>
            <li><a href="#register" class="block hover:text-primary transition-colors duration-300">REGISTER</a></li>
            <li><a href="#about" class="block hover:text-primary transition-colors duration-300">ABOUT</a></li>
            <li><a href="#contact" class="block hover:text-primary transition-colors duration-300">CONTACT</a></li>
        </ul>
    </div>
</nav>

<!-- Hero Section with Carousel -->
<section id="home" class="hero-carousel">
    <!-- Carousel Items -->
    <div class="carousel-item active" style="background-image: url('https://images4.alphacoders.com/882/882716.jpg');">
        <div class="carousel-overlay"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center px-6 max-w-5xl">
                <h1 class="text-5xl lg:text-7xl font-black mb-6 leading-tight" data-carousel-text="1">
                    Nice to meet you,<br/>
                    <span class="gradient-text">I'm Your Guide!</span>
                </h1>
                <p class="text-xl lg:text-2xl text-white/80 mb-8 font-light" data-carousel-desc="1">
                    Dear Students, get ready to immerse yourself in dynamic melodies with Sonata Violin!
                </p>
                <button onclick="document.getElementById('about').scrollIntoView({ behavior: 'smooth' })" class="glow-button text-white px-10 py-4 rounded-full font-bold text-lg shadow-2xl relative overflow-hidden">
                    LEARN MORE
                    <i class="fas fa-arrow-right ml-3"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="carousel-item" style="background-image: url('https://images3.alphacoders.com/210/thumb-1920-210359.jpg');">
        <div class="carousel-overlay"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center px-6 max-w-5xl">
                <h1 class="text-5xl lg:text-7xl font-black mb-6 leading-tight" data-carousel-text="2">
                    Master Your<br/>
                    <span class="gradient-text">Musical Journey</span>
                </h1>
                <p class="text-xl lg:text-2xl text-white/80 mb-8 font-light" data-carousel-desc="2">
                    Professional violin courses designed for all skill levels - from beginner to advanced
                </p>
                <button onclick="document.getElementById('package').scrollIntoView({ behavior: 'smooth' })" class="glow-button text-white px-10 py-4 rounded-full font-bold text-lg shadow-2xl relative overflow-hidden">
                    EXPLORE PACKAGES
                    <i class="fas fa-music ml-3"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="carousel-item" style="background-image: url('https://images8.alphacoders.com/391/thumb-1920-391320.jpg');">
        <div class="carousel-overlay"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="text-center px-6 max-w-5xl">
                <h1 class="text-5xl lg:text-7xl font-black mb-6 leading-tight" data-carousel-text="3">
                    Transform Your<br/>
                    <span class="gradient-text">Passion into Skill</span>
                </h1>
                <p class="text-xl lg:text-2xl text-white/80 mb-8 font-light" data-carousel-desc="3">
                    Join hundreds of students who have discovered their musical potential with us
                </p>
                <button onclick="document.getElementById('register').scrollIntoView({ behavior: 'smooth' })" class="glow-button text-white px-10 py-4 rounded-full font-bold text-lg shadow-2xl relative overflow-hidden">
                    START TODAY
                    <i class="fas fa-play ml-3"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Carousel Indicators -->
    <div class="absolute bottom-12 left-1/2 transform -translate-x-1/2 flex space-x-3 z-10">
        <button class="carousel-indicator w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-all duration-300 active"></button>
        <button class="carousel-indicator w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-all duration-300"></button>
        <button class="carousel-indicator w-3 h-3 rounded-full bg-white/50 hover:bg-white transition-all duration-300"></button>
    </div>

    <!-- Carousel Navigation -->
    <button class="absolute left-6 lg:left-12 top-1/2 transform -translate-y-1/2 w-14 h-14 rounded-full bg-white/10 backdrop-blur-md hover:bg-white/20 transition-all duration-300 flex items-center justify-center text-2xl" id="carouselPrev">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button class="absolute right-6 lg:right-12 top-1/2 transform -translate-y-1/2 w-14 h-14 rounded-full bg-white/10 backdrop-blur-md hover:bg-white/20 transition-all duration-300 flex items-center justify-center text-2xl" id="carouselNext">
        <i class="fas fa-chevron-right"></i>
    </button>
</section>

<!-- Package Section -->
<section id="package" class="py-24 lg:py-32 bg-gradient-to-b from-dark to-slate-900 reveal">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="text-center mb-16">
            <h2 class="text-5xl lg:text-6xl font-black mb-6">
                Our <span class="gradient-text">Packages</span>
            </h2>
            <p class="text-xl text-white/70 max-w-2xl mx-auto">
                Choose the perfect package that suits your learning journey and musical goals
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <?php if (!empty($paket)): ?>
                <?php foreach ($paket as $index => $pk): ?>
                    <?php if ($pk['status'] === 'aktif'): ?>
                    
                    <!-- Package Card -->
                    <div class="card-hover bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-xl border border-primary/20 rounded-3xl p-8 relative overflow-hidden">
                        
                        <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-full blur-3xl"></div>
                        
                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center mb-6">
                                <i class="fas fa-<?= $pk['level'] === 'beginner' ? 'music' : ($pk['level'] === 'intermediate' ? 'star' : 'crown') ?> text-2xl"></i>
                            </div>
                            
                            <h3 class="text-2xl font-bold mb-3"><?= esc($pk['nama_paket']) ?></h3>
                            <p class="text-white/60 mb-4"><?= esc($pk['deskripsi']) ?></p>
                            
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-check-circle text-primary mr-3"></i>
                                    <span><?= esc($pk['jumlah_pertemuan']) ?> Sessions / Month</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-check-circle text-primary mr-3"></i>
                                    <span>Level: <?= ucfirst(esc($pk['level'])) ?></span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-check-circle text-primary mr-3"></i>
                                    <span>Duration: <?= esc($pk['durasi']) ?></span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <i class="fas fa-check-circle text-primary mr-3"></i>
                                    <span>Batch: <?= esc($pk['batch']) ?></span>
                                </div>
                            </div>
                            
                            <div class="border-t border-white/10 pt-6 mt-6">
                                <div class="text-4xl font-black gradient-text mb-4">
                                    Rp <?= number_format($pk['harga'], 0, ',', '.') ?>
                                </div>
                                <div class="text-sm text-white/60 mb-4">
                                    <i class="fas fa-calendar-alt mr-2"></i>
                                    Mulai: <?= date('d M Y', strtotime($pk['tanggal_mulai'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center text-white/70 py-12">
                    <i class="fas fa-info-circle text-4xl mb-4"></i>
                    <p>Belum ada paket yang tersedia saat ini.</p>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</section>

<!-- Register Section -->
<section id="register" class="py-24 lg:py-32 bg-gradient-to-b from-slate-900 to-dark relative overflow-hidden reveal">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0icmdiYSgxMzksOTIsMjQ2LDAuMSkiIHN0cm9rZS13aWR0aD0iMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')] opacity-20"></div>
    
    <div class="container mx-auto px-6 lg:px-12 relative z-10">
        <div class="max-w-6xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Side - Content -->
                <div class="space-y-8">
                    <div class="inline-block">
                        <span class="bg-primary/20 text-primary px-6 py-2 rounded-full text-sm font-bold tracking-wider">
                            START YOUR JOURNEY
                        </span>
                    </div>
                    
                    <h2 class="text-5xl lg:text-6xl font-black leading-tight">
                        Ready to Begin Your<br/>
                        <span class="gradient-text">Musical Adventure?</span>
                    </h2>
                    
                    <p class="text-xl text-white/70 leading-relaxed">
                        Join our community of passionate musicians and start your journey to mastering the violin. Our expert instructors are ready to guide you every step of the way.
                    </p>

                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/20 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-primary text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg mb-1">Flexible Schedule</h4>
                                <p class="text-white/60">Choose class times that fit your lifestyle</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/20 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-primary text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg mb-1">Expert Instructors</h4>
                                <p class="text-white/60">Learn from professional violinists</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/20 flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-check text-primary text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg mb-1">All Skill Levels</h4>
                                <p class="text-white/60">From beginner to advanced students</p>
                            </div>
                        </div>
                    </div>

                <button id="openRegisterModal" class="glow-button text-white px-12 py-5 rounded-full font-bold text-lg shadow-2xl relative overflow-hidden group">
                    <span class="relative z-10 flex items-center">
                        REGISTER NOW
                        <i class="fas fa-arrow-right ml-4 group-hover:translate-x-2 transition-transform duration-300"></i>
                    </span>
                </button>
                </div>

                <!-- Right Side - Image/Visual -->
                <div class="relative float-animation hidden lg:block">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary/30 to-secondary/30 rounded-3xl blur-3xl"></div>
                    <img src="https://images.alphacoders.com/950/thumb-1920-95011.jpg" alt="Register" class="relative z-10 rounded-3xl shadow-2xl border-2 border-primary/20">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-24 lg:py-32 bg-dark reveal">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-5xl lg:text-6xl font-black mb-8">
                About <span class="gradient-text">Sonata Violin</span>
            </h2>
            <p class="text-xl text-white/70 leading-relaxed mb-12">
                Sonata Violin adalah lembaga kursus biola profesional yang didedikasikan untuk membantu siswa dari semua level mengembangkan keterampilan musik mereka. Dengan pengajar berpengalaman dan kurikulum yang dirancang khusus, kami memastikan pengalaman belajar yang menyenangkan dan efektif.
            </p>

            <div class="grid md:grid-cols-3 gap-8 mt-16">
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center">
                        <i class="fas fa-users text-3xl"></i>
                    </div>
                    <h3 class="text-4xl font-black gradient-text mb-2">500+</h3>
                    <p class="text-white/60">Active Students</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center">
                        <i class="fas fa-award text-3xl"></i>
                    </div>
                    <h3 class="text-4xl font-black gradient-text mb-2">15+</h3>
                    <p class="text-white/60">Years Experience</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-primary to-secondary rounded-2xl flex items-center justify-center">
                        <i class="fas fa-trophy text-3xl"></i>
                    </div>
                    <h3 class="text-4xl font-black gradient-text mb-2">100+</h3>
                    <p class="text-white/60">Awards Won</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="py-24 lg:py-32 bg-gradient-to-b from-dark to-slate-900 reveal">
    <div class="container mx-auto px-6 lg:px-12">
        <div class="text-center mb-16">
            <h2 class="text-5xl lg:text-6xl font-black mb-6">
                Get In <span class="gradient-text">Touch</span>
            </h2>
            <p class="text-xl text-white/70 max-w-2xl mx-auto">
                Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
            </p>
        </div>

        <div class="max-w-5xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div class="space-y-8">
                    <div class="flex items-start space-x-6">
                        <div class="w-14 h-14 rounded-xl bg-primary/20 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-map-marker-alt text-primary text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-2">Location</h4>
                            <p class="text-white/60">Jl. Musik Indah No. 123<br/>Jakarta Selatan, Indonesia</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-6">
                        <div class="w-14 h-14 rounded-xl bg-primary/20 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-phone text-primary text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-2">Phone</h4>
                            <p class="text-white/60">+62 812-3456-7890<br/>+62 821-9876-5432</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-6">
                        <div class="w-14 h-14 rounded-xl bg-primary/20 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-envelope text-primary text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-2">Email</h4>
                            <p class="text-white/60">info@sonataviolin.com<br/>support@sonataviolin.com</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-6">
                        <div class="w-14 h-14 rounded-xl bg-primary/20 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-clock text-primary text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg mb-2">Working Hours</h4>
                            <p class="text-white/60">Monday - Friday: 9:00 - 21:00<br/>Saturday - Sunday: 10:00 - 18:00</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 backdrop-blur-xl border border-primary/20 rounded-3xl p-8">
                    <form class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium mb-2">Full Name</label>
                            <input type="text" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-primary transition-colors duration-300" placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Email Address</label>
                            <input type="email" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-primary transition-colors duration-300" placeholder="john@example.com">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Phone Number</label>
                            <input type="tel" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-primary transition-colors duration-300" placeholder="+62 812-3456-7890">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Message</label>
                            <textarea rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-primary transition-colors duration-300" placeholder="Your message here..."></textarea>
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-primary to-secondary py-4 rounded-xl font-bold hover:shadow-2xl hover:shadow-primary/50 transition-all duration-300">
                            SEND MESSAGE
                            <i class="fas fa-paper-plane ml-3"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-slate-950 border-t border-primary/10">
    <div class="container mx-auto px-6 lg:px-12 py-12">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <!-- Company Info -->
            <div>
                <h3 class="text-2xl font-bold gradient-text mb-4">SONATA VIOLIN</h3>
                <p class="text-white/60 mb-6">Empowering musicians to achieve their full potential through expert instruction and personalized learning experiences.</p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition-colors duration-300">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition-colors duration-300">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition-colors duration-300">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-primary transition-colors duration-300">
                        <i class="fab fa-twitter"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-lg font-bold mb-4">Quick Links</h4>
                <ul class="space-y-3 text-white/60">
                    <li><a href="#home" class="hover:text-primary transition-colors duration-300">Home</a></li>
                    <li><a href="#package" class="hover:text-primary transition-colors duration-300">Packages</a></li>
                    <li><a href="#register" class="hover:text-primary transition-colors duration-300">Register</a></li>
                    <li><a href="#about" class="hover:text-primary transition-colors duration-300">About Us</a></li>
                    <li><a href="#contact" class="hover:text-primary transition-colors duration-300">Contact</a></li>
                </ul>
            </div>

            <!-- Programs -->
            <div>
                <h4 class="text-lg font-bold mb-4">Programs</h4>
                <ul class="space-y-3 text-white/60">
                    <li><a href="#" class="hover:text-primary transition-colors duration-300">Beginner Course</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors duration-300">Intermediate Course</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors duration-300">Advanced Course</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors duration-300">Private Lessons</a></li>
                    <li><a href="#" class="hover:text-primary transition-colors duration-300">Group Classes</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div>
                <h4 class="text-lg font-bold mb-4">Newsletter</h4>
                <p class="text-white/60 mb-4">Subscribe to get updates on our latest courses and events.</p>
                <div class="flex">
                    <input type="email" placeholder="Your email" class="flex-1 bg-white/5 border border-white/10 rounded-l-xl px-4 py-3 focus:outline-none focus:border-primary transition-colors duration-300">
                    <button class="bg-gradient-to-r from-primary to-secondary px-6 rounded-r-xl hover:shadow-lg hover:shadow-primary/50 transition-all duration-300">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center text-white/60 text-sm">
            <p>&copy; 2025 Sonata Violin. All rights reserved.</p>
            <div class="flex space-x-6 mt-4 md:mt-0">
                <a href="/login" class="hover:text-primary transition-colors duration-300">Privacy Policy</a>
                <a href="#" class="hover:text-primary transition-colors duration-300">Terms of Service</a>
                <a href="#" class="hover:text-primary transition-colors duration-300">Cookie Policy</a>
            </div>
        </div>
    </div>
</footer>

<script>
    // LOADING SCREEN
    window.addEventListener('load', () => {
        const loadingScreen = document.getElementById('loadingScreen');
        setTimeout(() => {
            loadingScreen.style.opacity = '0';
            setTimeout(() => {
                loadingScreen.classList.add('hidden');
            }, 500);
        }, 1000);
    });

    // NAVBAR SCROLL EFFECT
    let lastScroll = 0;
    const navbar = document.getElementById('navbar');

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
            navbar.style.background = 'rgba(15, 23, 42, 0.95)';
            navbar.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.3)';
        } else {
            navbar.style.background = 'rgba(15, 23, 42, 0.85)';
            navbar.style.boxShadow = 'none';
        }
        
        lastScroll = currentScroll;
    });

    // MOBILE MENU TOGGLE
    const menuToggle = document.getElementById('menuToggle');
    const mobileMenu = document.getElementById('mobileMenu');

    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        
        const icon = menuToggle.querySelector('i');
        if (mobileMenu.classList.contains('hidden')) {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
        } else {
            icon.classList.remove('fa-bars');
            icon.classList.add('fa-times');
        }
    });

    document.querySelectorAll('#mobileMenu a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            menuToggle.querySelector('i').classList.remove('fa-times');
            menuToggle.querySelector('i').classList.add('fa-bars');
        });
    });

    // SMOOTH SCROLL FOR NAVIGATION LINKS
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const offsetTop = target.offsetTop - 80;
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });

    // HERO CAROUSEL
    const carouselItems = document.querySelectorAll('.carousel-item');
    const carouselIndicators = document.querySelectorAll('.carousel-indicator');
    const carouselPrev = document.getElementById('carouselPrev');
    const carouselNext = document.getElementById('carouselNext');

    let currentSlide = 0;
    let carouselInterval;
    let isTransitioning = false;

    function showSlide(index) {
        if (isTransitioning) return;
        
        isTransitioning = true;
        
        carouselItems.forEach((item, i) => {
            if (i === index) {
                item.style.zIndex = '2';
                item.style.opacity = '0';
                
                void item.offsetWidth;
                
                item.classList.add('active');
                item.style.opacity = '1';
                
                carouselIndicators[i].classList.add('active', 'bg-white');
                carouselIndicators[i].classList.remove('bg-white/50');
            } else {
                item.style.opacity = '0';
                item.classList.remove('active');
                carouselIndicators[i].classList.remove('active', 'bg-white');
                carouselIndicators[i].classList.add('bg-white/50');
                
                setTimeout(() => {
                    if (!item.classList.contains('active')) {
                        item.style.zIndex = '0';
                    }
                }, 1000);
            }
        });
        
        setTimeout(() => {
            isTransitioning = false;
        }, 1000);
    }

    function nextSlide() {
        if (isTransitioning) return;
        currentSlide = (currentSlide + 1) % carouselItems.length;
        showSlide(currentSlide);
    }

    function prevSlide() {
        if (isTransitioning) return;
        currentSlide = (currentSlide - 1 + carouselItems.length) % carouselItems.length;
        showSlide(currentSlide);
    }

    function startCarousel() {
        carouselInterval = setInterval(nextSlide, 5000);
    }

    function stopCarousel() {
        clearInterval(carouselInterval);
    }

    carouselNext.addEventListener('click', () => {
        stopCarousel();
        nextSlide();
        startCarousel();
    });

    carouselPrev.addEventListener('click', () => {
        stopCarousel();
        prevSlide();
        startCarousel();
    });

    carouselIndicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            if (isTransitioning) return;
            stopCarousel();
            currentSlide = index;
            showSlide(currentSlide);
            startCarousel();
        });
    });

    showSlide(0);
    startCarousel();

    document.querySelector('.hero-carousel').addEventListener('mouseenter', stopCarousel);
    document.querySelector('.hero-carousel').addEventListener('mouseleave', startCarousel);
    
    // SCROLL REVEAL ANIMATION
    const revealElements = document.querySelectorAll('.reveal');

    const revealOnScroll = () => {
        const windowHeight = window.innerHeight;
        
        revealElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const revealPoint = 150;
            
            if (elementTop < windowHeight - revealPoint) {
                element.classList.add('active');
            }
        });
    };

    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();

    // ACTIVE NAVIGATION LINK
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.nav-link');

    window.addEventListener('scroll', () => {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            
            if (window.pageYOffset >= sectionTop - 200) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('text-primary');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('text-primary');
            }
        });
    });

    // MULTI-STEP REGISTRATION MODAL
    const openRegisterModalBtn = document.getElementById('openRegisterModal');

    // Create and inject modal HTML into page
    const modalHTML = `
        <div id="registerModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-start justify-center p-4 z-50">
            <div id="modalBox" class="modal-content bg-gradient-to-br from-slate-800 to-slate-900 w-full max-w-3xl rounded-3xl shadow-2xl overflow-hidden transform transition-all duration-300 opacity-0 scale-95">
                
                <!-- HEADER -->
                <div class="bg-gradient-to-r from-primary to-secondary p-5 flex justify-between items-center rounded-t-3xl">
                    <h3 id="modalTitle" class="text-white text-xl font-bold">Student Registration</h3>
                    <button id="closeModal" class="text-white text-2xl hover:bg-white/20 rounded-lg w-8 h-8 flex items-center justify-center transition-all">
                        &times;
                    </button>
                </div>

                <!-- STEP INDICATOR -->
                <div class="flex justify-between px-6 py-4 bg-slate-900/50 border-b-2 border-primary/20 text-sm font-semibold">
                    <div class="wizard-step flex-1 text-center transition-all text-primary" data-step="1">
                        <span class="inline-block px-3 py-1 rounded-full bg-primary/20">1. Student Data</span>
                    </div>
                    <div class="wizard-step flex-1 text-center text-white/40 transition-all" data-step="2">
                        <span class="inline-block px-3 py-1 rounded-full">2. Course Package</span>
                    </div>
                    <div class="wizard-step flex-1 text-center text-white/40 transition-all" data-step="3">
                        <span class="inline-block px-3 py-1 rounded-full">3. Payment</span>
                    </div>
                    <div class="wizard-step flex-1 text-center text-white/40 transition-all" data-step="4">
                        <span class="inline-block px-3 py-1 rounded-full">4. Review</span>
                    </div>
                </div>

                <!-- FORM -->
                <form id="registerForm" method="POST" action="<?= base_url('daftar') ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="p-6 space-y-6 max-h-[45vh] overflow-y-auto">

                        <!-- STEP 1: DATA SISWA -->
                        <div id="step1" class="wizard-page">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="font-semibold text-white mb-2 block">Full Name *</label>
                                    <input id="nama" name="nama" required
                                        class="w-full bg-white/5 border-2 border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                        placeholder="Enter full name">
                                </div>

                                <div>
                                    <label class="font-semibold text-white mb-2 block">Date of Birth *</label>
                                    <input id="tgl_lahir" name="tgl_lahir" type="date" required
                                        class="w-full bg-white/5 border-2 border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                </div>

                                <div>
                                    <label class="font-semibold text-white mb-2 block">Email *</label>
                                    <input id="email" name="email" type="email" required
                                        class="w-full bg-white/5 border-2 border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                        placeholder="email@example.com">
                                </div>

                                <div>
                                    <label class="font-semibold text-white mb-2 block">Phone Number *</label>
                                    <input id="no_hp" name="no_hp" required
                                        class="w-full bg-white/5 border-2 border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                        placeholder="+62xxxxxxxxxx">
                                </div>
                            </div>

                            <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="md:col-span-2">
                                    <label class="font-semibold text-white mb-2 block">Address *</label>
                                    <textarea id="alamat" name="alamat" rows="3" required
                                            class="w-full bg-white/5 border-2 border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all"
                                            placeholder="Full address"></textarea>
                                </div>

                                <div>
                                    <label class="font-semibold text-white mb-2 block">Profile Photo</label>
                                    <div class="flex flex-col items-center">
                                        <img id="fotoPreview" src="<?= base_url('uploads/pendaftaran/default.png') ?>"
                                            class="w-24 h-24 rounded-lg object-cover border-2 border-primary/20 mb-2">
                                        <input id="fotoInput" name="foto_profil" type="file" accept="image/*"
                                            class="w-full bg-white/5 border-2 border-white/10 rounded-xl p-2 text-xs text-white file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-primary file:text-white hover:file:bg-primary/80">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 2: PAKET KURSUS -->
                        <div id="step2" class="wizard-page hidden space-y-4">
                            <div>
                                <label class="font-semibold text-white mb-2 block">Course Package *</label>
                                <select id="paket_id" name="paket_id" required
                                    class="w-full bg-white/5 border-2 border-white/10 rounded-xl px-4 py-2.5 text-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all">
                                <option value="" class="text-gray-400">-- Select Package --</option>
                                <?php if (!empty($paket)): foreach ($paket as $pk): ?>
                                    <?php if ($pk['status'] === 'aktif'): ?>
                                        <?php
                                            $durasiInt = preg_replace('/[^0-9]/', '', $pk['durasi']);
                                        ?>
                                        <option value="<?= $pk['id'] ?>"
                                                data-level="<?= ucfirst(esc($pk['level'] ?? '')) ?>"
                                                data-batch="<?= esc($pk['batch'] ?? '') ?>"
                                                data-mulai="<?= esc($pk['tanggal_mulai'] ?? '') ?>"
                                                data-selesai="<?= esc($pk['tanggal_selesai'] ?? '') ?>"
                                                data-durasi="<?= $durasiInt ?>"
                                                data-harga="<?= intval($pk['harga'] ?? 0) ?>"
                                                class="text-gray-900 bg-white">
                                            <?= esc($pk['nama_paket']) ?> 
                                            (<?= ucfirst($pk['level']) ?>)
                                            - Rp <?= number_format($pk['harga'], 0, ',', '.') ?>
                                        </option>
                                    <?php endif; ?>
                                <?php endforeach; endif; ?>
                            </select>

                                <p class="text-xs text-white/60 mt-1">* Date, level & batch will auto-fill after selecting package</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="font-semibold text-white mb-2 block">Level</label>
                                    <input id="level" type="text" readonly
                                        class="w-full bg-white/10 border-2 border-white/10 rounded-xl px-4 py-2.5 text-white/70 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="font-semibold text-white mb-2 block">Batch</label>
                                    <input id="batch" type="text" readonly
                                        class="w-full bg-white/10 border-2 border-white/10 rounded-xl px-4 py-2.5 text-white/70 cursor-not-allowed">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="font-semibold text-white mb-2 block">Start Date</label>
                                    <input id="tanggal_mulai" name="tanggal_mulai" type="date" readonly
                                        class="w-full bg-white/10 border-2 border-white/10 rounded-xl px-4 py-2.5 text-white/70 cursor-not-allowed">
                                </div>
                                <div>
                                    <label class="font-semibold text-white mb-2 block">End Date</label>
                                    <input id="tanggal_selesai" name="tanggal_selesai" type="date" readonly
                                        class="w-full bg-white/10 border-2 border-white/10 rounded-xl px-4 py-2.5 text-white/70 cursor-not-allowed">
                                </div>
                            </div>
                        </div>

                        <!-- STEP 3: PEMBAYARAN -->
                        <div id="step3" class="wizard-page hidden space-y-4">
                            <div>
                                <label class="font-semibold text-white mb-2 block">Payment Amount</label>
                                <input id="nominal" name="nominal" type="number" readonly
                                    class="w-full bg-white/10 border-2 border-white/10 rounded-xl px-4 py-2.5 text-white/70 cursor-not-allowed">
                                <p class="text-xs text-white/60 mt-1">* Amount is based on selected package</p>
                            </div>

                            <div>
                                <label class="font-semibold text-white mb-2 block">Upload Payment Proof *</label>
                                <div class="flex flex-col md:flex-row gap-4">
                                    <div class="flex flex-col items-center">
                                        <img id="buktiPreview" src="<?= base_url('uploads/bukti_pembayaran/default.png') ?>"
                                            class="w-32 h-32 rounded-lg object-cover border-2 border-primary/20 mb-2">
                                        <span class="text-xs text-white/60">Preview</span>
                                    </div>
                                    <div class="flex-1">
                                        <input id="buktiInput" name="bukti_transaksi" type="file" accept="image/*" required
                                            class="w-full bg-white/5 border-2 border-white/10 rounded-xl p-2.5 text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-primary file:text-white hover:file:bg-primary/80">
                                        <p class="text-xs text-white/60 mt-1">Format: .jpg, .png (Max 2MB)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- STEP 4: REVIEW -->
                        <div id="step4" class="wizard-page hidden">
                            <div class="bg-white/5 p-6 rounded-xl border-2 border-primary/20 space-y-4">
                                <h4 class="font-bold text-white mb-2">Data Summary</h4>
                                
                                <div class="space-y-2">
                                    <h5 class="font-semibold text-white/80">Student Data</h5>
                                    <div class="flex justify-between py-1 border-b border-white/10 text-sm">
                                        <span class="text-white/60">Name</span>
                                        <span id="rev_nama" class="font-semibold text-white"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-white/10 text-sm">
                                        <span class="text-white/60">Date of Birth</span>
                                        <span id="rev_tgl_lahir" class="font-semibold text-white"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-white/10 text-sm">
                                        <span class="text-white/60">Email</span>
                                        <span id="rev_email" class="font-semibold text-white"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-white/10 text-sm">
                                        <span class="text-white/60">Phone</span>
                                        <span id="rev_nohp" class="font-semibold text-white"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-white/10 text-sm">
                                        <span class="text-white/60">Address</span>
                                        <span id="rev_alamat" class="font-semibold text-white text-right"></span>
                                    </div>
                                    <div class="mt-3">
                                        <span class="text-xs text-white/60 block mb-2">Photo Preview</span>
                                        <img id="rev_foto_preview" src="<?= base_url('uploads/pendaftaran/default.png') ?>"
                                            class="w-24 h-24 rounded-lg object-cover border-2 border-primary/20">
                                    </div>
                                </div>

                                <div class="space-y-2 pt-3 border-t border-white/20">
                                    <h5 class="font-semibold text-white/80">Package & Schedule</h5>
                                    <div class="flex justify-between py-1 border-b border-white/10 text-sm">
                                        <span class="text-white/60">Package</span>
                                        <span id="rev_paket" class="font-semibold text-white"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-white/10 text-sm">
                                        <span class="text-white/60">Level</span>
                                        <span id="rev_level" class="font-semibold text-white"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-white/10 text-sm">
                                        <span class="text-white/60">Batch</span>
                                        <span id="rev_batch" class="font-semibold text-white"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-white/10 text-sm">
                                        <span class="text-white/60">Start Date</span>
                                        <span id="rev_tanggal_mulai" class="font-semibold text-white"></span>
                                    </div>
                                    <div class="flex justify-between py-1 border-b border-white/10 text-sm">
                                        <span class="text-white/60">End Date</span>
                                        <span id="rev_tanggal_selesai" class="font-semibold text-white"></span>
                                    </div>
                                </div>

                                <div class="space-y-2 pt-3 border-t border-white/20">
                                    <h5 class="font-semibold text-white/80">Payment</h5>
                                    <div class="flex justify-between py-1 border-b border-white/10 text-sm">
                                        <span class="text-white/60">Amount</span>
                                        <span id="rev_nominal" class="font-semibold text-white"></span>
                                    </div>
                                    <div class="mt-3">
                                        <span class="text-white/60 block mb-1 text-sm">Payment Proof:</span>
                                        <img id="rev_bukti" src="<?= base_url('uploads/bukti_pembayaran/default.png') ?>"
                                            class="w-40 h-40 rounded-lg object-cover border-2 border-primary/20 shadow-md">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- FOOTER BUTTONS -->
                    <div class="p-4 bg-slate-900/50 border-t-2 border-primary/20">
                        <!-- Mobile Layout: Stacked Vertical per Row -->
                        <div class="flex flex-col gap-2 md:hidden">
                            <button id="btnNextStepMobile" type="button"
                                class="w-full px-4 py-2 bg-gradient-to-r from-primary to-secondary border-2 border-white/20 text-white text-sm rounded-lg font-semibold hover:bg-white/20 transition-all">
                                Next <i class="fa fa-chevron-right ml-1"></i>
                            </button>
                            
                            <button id="btnPrevStepMobile" type="button"
                                class="w-full px-4 py-2 bg-white/10 border-2 border-white/20 text-white text-sm rounded-lg font-semibold hover:bg-white/20 transition-all">
                                <i class="fa fa-chevron-left mr-1"></i> Prev
                            </button>
                            
                            <button type="button" id="btnCancelModalMobile"
                                class="w-full px-4 py-2 bg-white/10 border-2 border-white/20 text-white text-sm rounded-lg font-semibold hover:bg-white/20 transition-all">
                                Batal
                            </button>
                            
                            <button id="btnSubmitMobile" type="submit"
                                class="hidden w-full px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm rounded-lg font-semibold shadow-md hover:shadow-lg transition-all">
                                <i class="fa fa-save mr-1"></i> Submit
                            </button>
                        </div>
                        
                        <!-- Desktop Layout: Original -->
                        <div class="hidden md:flex justify-between items-center">
                            <div class="flex gap-2">
                                <button type="button" id="btnCancelModal"
                                    class="px-5 py-2.5 bg-white/10 border-2 border-white/20 text-white rounded-lg font-semibold hover:bg-white/20 hover:scale-105 active:scale-95 transition-all duration-300">
                                    Cancel
                                </button>
                                <button id="btnPrevStep" type="button"
                                    class="px-5 py-2.5 bg-white/10 border-2 border-white/20 text-white rounded-lg font-semibold hover:bg-white/20 hover:scale-105 active:scale-95 transition-all duration-300">
                                    <i class="fa fa-chevron-left mr-1"></i>Previous
                                </button>
                            </div>

                            <div class="flex gap-2">
                                <button id="btnNextStep" type="button"
                                    class="px-5 py-2.5 bg-gradient-to-r from-primary to-secondary border-2 border-white/20 text-white rounded-lg font-semibold hover:bg-white/20 hover:scale-105 active:scale-95 transition-all duration-300">
                                    Next<i class="fa fa-chevron-right ml-1"></i>
                                </button>
                                
                                <button id="btnSubmit" type="submit"
                                    class="hidden px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-semibold shadow-md hover:shadow-lg hover:scale-105 active:scale-95 transition-all duration-300">
                                    <i class="fa fa-save mr-1"></i>Submit
                                </button>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
        `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);

    const registerModal = document.getElementById('registerModal');
    const modalBox = document.getElementById('modalBox');
    const closeModalBtn = document.getElementById('closeModal');
    const registerForm = document.getElementById('registerForm');

    // Desktop buttons
    const btnCancelModal = document.getElementById('btnCancelModal');
    const btnNextStep = document.getElementById('btnNextStep');
    const btnPrevStep = document.getElementById('btnPrevStep');
    const btnSubmit = document.getElementById('btnSubmit');

    // Mobile buttons
    const btnCancelModalMobile = document.getElementById('btnCancelModalMobile');
    const btnNextStepMobile = document.getElementById('btnNextStepMobile');
    const btnPrevStepMobile = document.getElementById('btnPrevStepMobile');
    const btnSubmitMobile = document.getElementById('btnSubmitMobile');

    let currentStep = 1;
    const totalSteps = 4;

    openRegisterModalBtn.addEventListener('click', () => {
        registerModal.classList.remove('hidden');
        registerModal.classList.add('flex');
        setTimeout(() => {
            modalBox.classList.remove('opacity-0', 'scale-95');
            modalBox.classList.add('opacity-100', 'scale-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    });

    // Close modal function
    function closeModal() {
        modalBox.classList.remove('opacity-100', 'scale-100');
        modalBox.classList.add('opacity-0', 'scale-95');
        setTimeout(() => {
            registerModal.classList.add('hidden');
            registerModal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            
            registerForm.reset();
            currentStep = 1;
            showStep(1);
            
            document.getElementById('fotoPreview').src = '<?= base_url("uploads/pendaftaran/default.png") ?>';
            document.getElementById('buktiPreview').src = '<?= base_url("uploads/bukti_pembayaran/default.png") ?>';
            document.getElementById('rev_foto_preview').src = '<?= base_url("uploads/pendaftaran/default.png") ?>';
            document.getElementById('rev_bukti').src = '<?= base_url("uploads/bukti_pembayaran/default.png") ?>';
        }, 300);
    }

    closeModalBtn.addEventListener('click', closeModal);
    btnCancelModal.addEventListener('click', closeModal);
    btnCancelModalMobile.addEventListener('click', closeModal);

    // Close on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !registerModal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Show specific step
    function showStep(step) {
        document.querySelectorAll('.wizard-page').forEach(page => {
            page.classList.add('hidden');
        });
        
        document.getElementById(`step${step}`).classList.remove('hidden');
        
        document.querySelectorAll('.wizard-step').forEach((indicator, index) => {
            const stepNum = index + 1;
            const span = indicator.querySelector('span');
            
            if (stepNum === step) {
                indicator.classList.remove('text-white/40');
                indicator.classList.add('text-primary');
                span.classList.add('bg-primary/20');
            } else {
                indicator.classList.remove('text-primary');
                indicator.classList.add('text-white/40');
                span.classList.remove('bg-primary/20');
            }
        });
        
        // Desktop buttons visibility
        btnPrevStep.classList.toggle('hidden', step === 1);
        btnNextStep.classList.toggle('hidden', step === totalSteps);
        btnSubmit.classList.toggle('hidden', step !== totalSteps);
        
        // Mobile buttons visibility
        btnPrevStepMobile.classList.toggle('hidden', step === 1);
        btnNextStepMobile.classList.toggle('hidden', step === totalSteps);
        btnSubmitMobile.classList.toggle('hidden', step !== totalSteps);
        
        currentStep = step;
    }

    btnNextStep.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            if (currentStep === 3) {
                updateReviewStep();
            }
            showStep(currentStep + 1);
        }
    });

    btnPrevStep.addEventListener('click', () => {
        showStep(currentStep - 1);
    });

    btnNextStepMobile.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            if (currentStep === 3) {
                updateReviewStep();
            }
            showStep(currentStep + 1);
        }
    });

    btnPrevStepMobile.addEventListener('click', () => {
        showStep(currentStep - 1);
    });

    // Validate current step
    function validateStep(step) {
        const currentPage = document.getElementById(`step${step}`);
        const requiredInputs = currentPage.querySelectorAll('[required]');
        
        let isValid = true;
        requiredInputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('border-red-500');
                isValid = false;
            } else {
                input.classList.remove('border-red-500');
            }
        });
        
        if (!isValid) {
            Swal.fire({
                icon: 'warning',
                title: 'Incomplete Form',
                text: 'Please fill in all required fields!',
                background: '#1e293b',
                color: '#ffffff',
                iconColor: '#f59e0b',
                confirmButtonColor: '#8B5CF6',
                confirmButtonText: 'OK',
                backdrop: `rgba(0,0,0,0.8)`,
                customClass: {
                    popup: 'rounded-3xl border-2 border-yellow-500/20',
                    confirmButton: 'rounded-xl px-8 py-3 font-bold'
                }
            });
        }
        
        return isValid;
    }

    // Update review step with all data
    function updateReviewStep() {
        document.getElementById('rev_nama').textContent = document.getElementById('nama').value;
        document.getElementById('rev_tgl_lahir').textContent = document.getElementById('tgl_lahir').value;
        document.getElementById('rev_email').textContent = document.getElementById('email').value;
        document.getElementById('rev_nohp').textContent = document.getElementById('no_hp').value;
        document.getElementById('rev_alamat').textContent = document.getElementById('alamat').value;
        
        const fotoPreview = document.getElementById('fotoPreview');
        document.getElementById('rev_foto_preview').src = fotoPreview.src;
        
        const paketSelect = document.getElementById('paket_id');
        const selectedOption = paketSelect.options[paketSelect.selectedIndex];
        
        document.getElementById('rev_paket').textContent = selectedOption.text.split(' (')[0] || '-';
        document.getElementById('rev_level').textContent = document.getElementById('level').value || '-';
        document.getElementById('rev_batch').textContent = document.getElementById('batch').value || '-';
        document.getElementById('rev_tanggal_mulai').textContent = document.getElementById('tanggal_mulai').value || '-';
        document.getElementById('rev_tanggal_selesai').textContent = document.getElementById('tanggal_selesai').value || '-';
        
        const nominal = document.getElementById('nominal').value;
        document.getElementById('rev_nominal').textContent = nominal ? `Rp ${parseInt(nominal).toLocaleString('id-ID')}` : '-';
        
        const buktiPreview = document.getElementById('buktiPreview');
        document.getElementById('rev_bukti').src = buktiPreview.src;
    }

    // Event listener untuk dropdown paket
    document.getElementById('paket_id').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        
        if (selected.value) {
            document.getElementById('level').value = selected.dataset.level || '';
            document.getElementById('batch').value = selected.dataset.batch || '';
            document.getElementById('nominal').value = selected.dataset.harga || '';
            document.getElementById('tanggal_mulai').value = selected.dataset.mulai || '';
            document.getElementById('tanggal_selesai').value = selected.dataset.selesai || '';
        } else {
            document.getElementById('level').value = '';
            document.getElementById('batch').value = '';
            document.getElementById('nominal').value = '';
            document.getElementById('tanggal_mulai').value = '';
            document.getElementById('tanggal_selesai').value = '';
        }
    });

    // Foto profil preview
    const fotoInput = document.getElementById('fotoInput');
    const fotoPreview = document.getElementById('fotoPreview');

    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'File size must be less than 2MB!',
                    background: '#1e293b',
                    color: '#ffffff',
                    iconColor: '#ef4444',
                    confirmButtonColor: '#8B5CF6',
                    confirmButtonText: 'OK',
                    backdrop: `rgba(0,0,0,0.8)`,
                    customClass: {
                        popup: 'rounded-3xl border-2 border-red-500/20',
                        confirmButton: 'rounded-xl px-8 py-3 font-bold'
                    }
                });
                    this.value = '';
                    return;
                }
                
                if (!file.type.match('image.*')) {
                    Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please upload an image file!',
                    background: '#1e293b',
                    color: '#ffffff',
                    iconColor: '#ef4444',
                    confirmButtonColor: '#8B5CF6',
                    confirmButtonText: 'OK',
                    backdrop: `rgba(0,0,0,0.8)`,
                    customClass: {
                        popup: 'rounded-3xl border-2 border-red-500/20',
                        confirmButton: 'rounded-xl px-8 py-3 font-bold'
                    }
                });
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    fotoPreview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Bukti pembayaran preview
    const buktiInput = document.getElementById('buktiInput');
    const buktiPreview = document.getElementById('buktiPreview');

    if (buktiInput) {
        buktiInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                     Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'File size must be less than 2MB!',
                    background: '#1e293b',
                    color: '#ffffff',
                    iconColor: '#ef4444',
                    confirmButtonColor: '#8B5CF6',
                    confirmButtonText: 'OK',
                    backdrop: `rgba(0,0,0,0.8)`,
                    customClass: {
                        popup: 'rounded-3xl border-2 border-red-500/20',
                        confirmButton: 'rounded-xl px-8 py-3 font-bold'
                    }
                });
                    this.value = '';
                    return;
                }
                
                if (!file.type.match('image.*')) {
                    Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please upload an image file!',
                    background: '#1e293b',
                    color: '#ffffff',
                    iconColor: '#ef4444',
                    confirmButtonColor: '#8B5CF6',
                    confirmButtonText: 'OK',
                    backdrop: `rgba(0,0,0,0.8)`,
                    customClass: {
                        popup: 'rounded-3xl border-2 border-red-500/20',
                        confirmButton: 'rounded-xl px-8 py-3 font-bold'
                    }
                });
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    buktiPreview.src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Form submission handler dengan delay
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('btnSubmit');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';
        
        const formData = new FormData(this);
        
        fetch('<?= base_url('daftar') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            return response.text();
        })
        .then(html => {
            closeModal();
            
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Pendaftaran berhasil! Data akan diverifikasi oleh admin.',
                    background: '#1e293b',
                    color: '#ffffff',
                    iconColor: '#10b981',
                    confirmButtonColor: '#8B5CF6',
                    confirmButtonText: 'OK',
                    backdrop: `rgba(0,0,0,0.8)`,
                    customClass: {
                        popup: 'rounded-3xl border-2 border-primary/20',
                        title: 'text-2xl font-bold',
                        htmlContainer: 'text-white/80',
                        confirmButton: 'rounded-xl px-8 py-3 font-bold'
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= base_url('/') ?>';
                    }
                });
            }, 400);
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan. Silakan coba lagi.',
                background: '#1e293b',
                color: '#ffffff',
                iconColor: '#ef4444',
                confirmButtonColor: '#8B5CF6',
                confirmButtonText: 'OK',
                backdrop: `rgba(0,0,0,0.8)`,
                customClass: {
                    popup: 'rounded-3xl border-2 border-red-500/20',
                    confirmButton: 'rounded-xl px-8 py-3 font-bold'
                }
            });
        });
    });

    showStep(1);
</script>