<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Pet Shop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2c5aa0;
            --secondary-color: #f8a978;
            --accent-color: #74c69d;
            --text-dark: #2d3748;
            --text-light: #718096;
            --bg-gradient: linear-gradient(135deg, #1673ff 0%, #1673ff 100%);
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --card-hover-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
        }

        .hero-section {
            background: var(--bg-gradient);
            position: relative;
            overflow: hidden;
            min-height: 300px;
            display: flex;
            align-items: center;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="paw" patternUnits="userSpaceOnUse" width="20" height="20"><circle cx="5" cy="5" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="15" cy="5" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="12" r="1.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23paw)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            font-weight: 300;
            opacity: 0.95;
        }

        .content-section {
            padding: 80px 0;
            background: linear-gradient(to bottom, #f8f9ff, #ffffff);
        }

        .pet-image {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            border: 4px solid white;
            transition: all 0.3s ease;
        }

        .pet-image:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .enhanced-card {
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            border: none;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            position: relative;
        }

        .enhanced-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color), var(--secondary-color));
        }

        .enhanced-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--card-hover-shadow);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 24px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .story-icon {
            background: linear-gradient(135deg, #ff9a9e, #fecfef);
        }

        .mission-icon {
            background: linear-gradient(135deg, #a8edea, #fed6e3);
        }

        .values-icon {
            background: linear-gradient(135deg, #ffecd2, #fcb69f);
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-color), var(--secondary-color));
            border-radius: 2px;
        }

        .list-item {
            padding: 12px 0;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .list-item:hover {
            transform: translateX(10px);
            background: rgba(116, 198, 157, 0.05);
            border-radius: 8px;
            padding-left: 15px;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .list-icon {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 12px;
        }

        .cta-section {
            background: linear-gradient(135deg, rgba(44, 90, 160, 0.05), rgba(116, 198, 157, 0.05));
            border-radius: 25px;
            padding: 40px;
            text-align: center;
            margin-top: 60px;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '🐾';
            position: absolute;
            top: -20px;
            right: -20px;
            font-size: 100px;
            opacity: 0.1;
            transform: rotate(15deg);
        }

        .cta-button {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border: none;
            border-radius: 50px;
            padding: 15px 40px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(44, 90, 160, 0.3);
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
        }

        .floating-paw {
            position: absolute;
            font-size: 20px;
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        .floating-paw:nth-child(1) { top: 10%; left: 10%; animation-delay: 0s; }
        .floating-paw:nth-child(2) { top: 20%; right: 15%; animation-delay: 2s; }
        .floating-paw:nth-child(3) { bottom: 30%; left: 20%; animation-delay: 4s; }
        .floating-paw:nth-child(4) { bottom: 20%; right: 10%; animation-delay: 1s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .content-section {
                padding: 60px 0;
            }
            
            .cta-section {
                padding: 30px 20px;
                margin-top: 40px;
            }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <section class="hero-section">
        <div class="floating-elements">
            <div class="floating-paw">🐾</div>
            <div class="floating-paw">🐾</div>
            <div class="floating-paw">🐾</div>
            <div class="floating-paw">🐾</div>
        </div>
        <div class="container px-4 px-lg-5">
            <div class="hero-content text-center text-white">
                <h1 class="hero-title animate__animated animate__fadeInDown">About Us</h1>
                <p class="hero-subtitle animate__animated animate__fadeInUp animate__delay-1s">Discover Our Passion for Pets and Our Commitment to You</p>
            </div>
        </div>
    </section>

    <section class="content-section">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 align-items-center justify-content-center">
                <div class="col-md-10">
                    <div class="text-center mb-5 animate-on-scroll">
                        <img src="https://media.giphy.com/media/3oriO0OEd9QIDdllqo/giphy.gif" alt="Cute Pet" class="pet-image">
                    </div>

                    <div class="enhanced-card p-4 mb-5 animate-on-scroll">
                        <div class="card-body">
                            <div class="card-icon story-icon">
                                <i class="bi bi-book-heart"></i>
                            </div>
                            <h3 class="section-title text-center">Our Story</h3>
                            <p class="lead text-center mb-4" style="color: var(--text-dark);">
                                Welcome to our pet shop, where our journey began with a simple, profound love for animals. Established in 2022, we envisioned a place that goes beyond just selling pet supplies – a community hub dedicated to nurturing the bond between pets and their families.
                            </p>
                            <p class="text-center" style="color: var(--text-light);">
                                From humble beginnings, we've grown into a trusted destination for pet owners, driven by our commitment to providing premium products, compassionate services, and expert advice. We believe every pet deserves a life filled with joy, health, and love.
                            </p>
                        </div>
                    </div>

                    <div class="enhanced-card p-4 mb-5 animate-on-scroll">
                        <div class="card-body">
                            <div class="card-icon mission-icon">
                                <i class="bi bi-target"></i>
                            </div>
                            <h3 class="section-title text-center">Our Mission</h3>
                            <p class="lead text-center mb-4" style="color: var(--text-dark);">Our mission is to enrich the lives of pets and their owners by:</p>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="list-item">
                                        <span class="list-icon" style="background: var(--accent-color); color: white;"><i class="bi bi-check"></i></span>
                                        Providing a wide selection of premium pet food, eco-friendly toys, and essential accessories.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="list-item">
                                        <span class="list-icon" style="background: var(--secondary-color); color: white;"><i class="bi bi-check"></i></span>
                                        Promoting responsible pet ownership through accessible education and valuable resources.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="list-item">
                                        <span class="list-icon" style="background: var(--primary-color); color: white;"><i class="bi bi-check"></i></span>
                                        Supporting local animal shelters and rescue organizations through partnerships and awareness campaigns.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="list-item">
                                        <span class="list-icon" style="background: #e74c3c; color: white;"><i class="bi bi-check"></i></span>
                                        Offering exceptional customer service and expert guidance, ensuring a delightful experience for every visitor.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="enhanced-card p-4 mb-5 animate-on-scroll">
                        <div class="card-body">
                            <div class="card-icon values-icon">
                                <i class="bi bi-gem"></i>
                            </div>
                            <h3 class="section-title text-center">Our Values</h3>
                            <p class="lead text-center mb-4" style="color: var(--text-dark);">Integrity, compassion, and community are the pillars of our pet shop. We are committed to:</p>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="list-item">
                                        <span class="list-icon" style="background: #e91e63; color: white;"><i class="bi bi-heart-fill"></i></span>
                                        <strong>Care:</strong> Demonstrating genuine care for the well-being and happiness of all animals.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="list-item">
                                        <span class="list-icon" style="background: #ff9800; color: white;"><i class="bi bi-star-fill"></i></span>
                                        <strong>Quality:</strong> Sourcing and offering only the highest quality products that meet stringent safety and nutritional standards.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="list-item">
                                        <span class="list-icon" style="background: #4caf50; color: white;"><i class="bi bi-lightbulb-fill"></i></span>
                                        <strong>Knowledge:</strong> Continuously expanding our expertise and sharing valuable insights on pet health, behavior, and care.
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="list-item">
                                        <span class="list-icon" style="background: #2196f3; color: white;"><i class="bi bi-people-fill"></i></span>
                                        <strong>Community:</strong> Fostering a welcoming and supportive environment where pet owners can connect, learn, and thrive.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cta-section animate-on-scroll">
                        <h3 class="mb-3" style="color: var(--primary-color); font-weight: 700;">Come Visit Us!</h3>
                        <p class="lead mb-4" style="color: var(--text-dark);">
                            We invite you to explore our shop and discover everything we have to offer. Our friendly team is always here to help you find the perfect products for your furry, feathery, or scaly friends. We look forward to welcoming you to our pet shop family!
                        </p>
                        <a href="locations.php" class="btn cta-button text-white">
                            <i class="bi bi-geo-alt-fill me-2"></i> Find Our Store
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Scroll animation
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Add some interactive effects
        document.querySelectorAll('.enhanced-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-8px) scale(1.02)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Add ripple effect to CTA button
        document.querySelector('.cta-button').addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    </script>

    <style>
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.6);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</body>
</html>