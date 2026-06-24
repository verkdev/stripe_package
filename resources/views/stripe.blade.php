<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Checkout | Stripe Payment</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #635bff;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --success: #10b981;
            --surface: rgba(255, 255, 255, 0.7);
            --surface-hover: rgba(255, 255, 255, 0.9);
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border: rgba(255, 255, 255, 0.3);
            --shadow: 0 8px 32px rgba(99, 91, 255, 0.15);
            --shadow-lg: 0 25px 50px -12px rgba(99, 91, 255, 0.25);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 75%, #4facfe 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow-x: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Floating particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float 20s infinite;
        }

        .particle:nth-child(1) { left: 10%; animation-delay: 0s; animation-duration: 25s; width: 15px; height: 15px; }
        .particle:nth-child(2) { left: 20%; animation-delay: 2s; animation-duration: 20s; width: 8px; height: 8px; }
        .particle:nth-child(3) { left: 35%; animation-delay: 4s; animation-duration: 28s; width: 12px; height: 12px; }
        .particle:nth-child(4) { left: 50%; animation-delay: 0s; animation-duration: 22s; width: 18px; height: 18px; }
        .particle:nth-child(5) { left: 65%; animation-delay: 6s; animation-duration: 26s; width: 10px; height: 10px; }
        .particle:nth-child(6) { left: 80%; animation-delay: 3s; animation-duration: 24s; width: 14px; height: 14px; }
        .particle:nth-child(7) { left: 90%; animation-delay: 5s; animation-duration: 21s; width: 9px; height: 9px; }

        @keyframes float {
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

        .container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 480px;
            perspective: 1000px;
        }

        .card {
            background: var(--surface);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: var(--shadow-lg);
            transform-style: preserve-3d;
            transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1);
            animation: cardEntry 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards;
            opacity: 0;
        }

        @keyframes cardEntry {
            from {
                opacity: 0;
                transform: translateY(40px) rotateX(10deg);
            }
            to {
                opacity: 1;
                transform: translateY(0) rotateX(0);
            }
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px -12px rgba(99, 91, 255, 0.3);
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 10px 30px rgba(99, 91, 255, 0.3);
            animation: logoPulse 2s ease-in-out infinite;
        }

        @keyframes logoPulse {
            0%, 100% { transform: scale(1); box-shadow: 0 10px 30px rgba(99, 91, 255, 0.3); }
            50% { transform: scale(1.05); box-shadow: 0 15px 40px rgba(99, 91, 255, 0.4); }
        }

        .logo svg {
            width: 32px;
            height: 32px;
            fill: white;
        }

        .header h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 8px;
            letter-spacing: -0.02em;
        }

        .header p {
            color: var(--text-secondary);
            font-size: 15px;
            font-weight: 400;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 28px 0;
            gap: 16px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(99, 91, 255, 0.2), transparent);
        }

        .divider span {
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .product-card {
            background: linear-gradient(135deg, rgba(99, 91, 255, 0.05), rgba(79, 70, 229, 0.05));
            border: 1px solid rgba(99, 91, 255, 0.1);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s ease;
        }

        .product-card:hover {
            border-color: rgba(99, 91, 255, 0.3);
            transform: translateX(5px);
        }

        .product-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .product-icon svg {
            width: 24px;
            height: 24px;
            fill: white;
        }

        .product-info h3 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 4px;
        }

        .product-info p {
            font-size: 13px;
            color: var(--text-secondary);
        }

        .price-tag {
            margin-left: auto;
            text-align: right;
        }

        .price-tag .amount {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            line-height: 1;
        }

        .price-tag .currency {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .features {
            margin-bottom: 28px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            color: var(--text-secondary);
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .feature:hover {
            color: var(--text-primary);
            transform: translateX(5px);
        }

        .feature-icon {
            width: 20px;
            height: 20px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-icon svg {
            width: 12px;
            height: 12px;
            fill: var(--success);
        }

        .pay-button {
            width: 100%;
            padding: 16px 24px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: 14px;
            font-family: 'Inter', sans-serif;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 10px 30px rgba(99, 91, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .pay-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .pay-button:hover::before {
            left: 100%;
        }

        .pay-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 40px rgba(99, 91, 255, 0.4);
        }

        .pay-button:active {
            transform: translateY(0);
        }

        .pay-button svg {
            width: 20px;
            height: 20px;
            fill: currentColor;
        }

        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: 500;
        }

        .security-badge svg {
            width: 16px;
            height: 16px;
            fill: var(--success);
        }

        /* Loading state */
        .pay-button.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .pay-button.loading .btn-text {
            opacity: 0;
        }

        .pay-button.loading .btn-loader {
            position: absolute;
            width: 24px;
            height: 24px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Success state */
        .success-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.5s ease;
        }

        .success-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        .success-content {
            text-align: center;
            transform: scale(0.8);
            transition: transform 0.5s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .success-overlay.active .success-content {
            transform: scale(1);
        }

        .success-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--success), #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
            animation: successPop 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }

        @keyframes successPop {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        .success-circle svg {
            width: 40px;
            height: 40px;
            fill: white;
        }

        .success-content h2 {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 24px;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .success-content p {
            color: var(--text-secondary);
            font-size: 15px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .card {
                padding: 32px 24px;
                border-radius: 20px;
            }

            .header h1 {
                font-size: 24px;
            }
        }

        /* Ripple effect */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: scale(0);
            animation: rippleEffect 0.6s ease-out;
            pointer-events: none;
        }

        @keyframes rippleEffect {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Particles -->
    <div class="particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <div class="container">
        <div class="card">
            <div class="header">
                <div class="logo">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.975 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.545-2.354 1.545-1.875 0-4.965-.921-6.99-2.109l-.9 5.555C5.175 22.99 8.385 24 11.714 24c2.641 0 4.843-.624 6.328-1.813 1.664-1.305 2.525-3.236 2.525-5.732 0-4.128-2.524-5.851-6.591-7.305z"/>
                    </svg>
                </div>
                <h1>Secure Checkout</h1>
                <p>Complete your purchase securely with Stripe</p>
            </div>

            <div class="divider">
                <span>Order Summary</span>
            </div>

            <div class="product-card">
                <div class="product-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <div class="product-info">
                    <h3>Premium Plan</h3>
                    <p>Monthly subscription</p>
                </div>
                <div class="price-tag">
                    <div class="amount">$10</div>
                    <div class="currency">USD</div>
                </div>
            </div>

            <div class="features">
                <div class="feature">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </div>
                    <span>Instant access to all features</span>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </div>
                    <span>Secure SSL encrypted payment</span>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                        </svg>
                    </div>
                    <span>Cancel anytime, no commitments</span>
                </div>
            </div>

            <form method="POST" action="/stripe/checkout" id="paymentForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <button type="submit" class="pay-button" id="payButton">
                    <span class="btn-text">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                        </svg>
                        Pay $10.00
                    </span>
                    <div class="btn-loader" style="display: none;"></div>
                </button>
            </form>

            <div class="security-badge">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
                </svg>
                <span>256-bit SSL Secure Payment by Stripe</span>
            </div>
        </div>
    </div>

    <!-- Success Overlay -->
    <div class="success-overlay" id="successOverlay">
        <div class="success-content">
            <div class="success-circle">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                </svg>
            </div>
            <h2>Payment Successful!</h2>
            <p>Redirecting to your dashboard...</p>
        </div>
    </div>

    <script>
        // 3D Tilt Effect
        const card = document.querySelector('.card');
        const container = document.querySelector('.container');

        container.addEventListener('mousemove', (e) => {
            const rect = container.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const centerX = rect.width / 2;
            const centerY = rect.height / 2;

            const rotateX = (y - centerY) / 20;
            const rotateY = (centerX - x) / 20;

            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
        });

        container.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0)';
        });

        // Ripple Effect
        const payButton = document.getElementById('payButton');

        payButton.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';

            this.appendChild(ripple);

            setTimeout(() => ripple.remove(), 600);
        });

        // Form Submission
        const form = document.getElementById('paymentForm');
        const successOverlay = document.getElementById('successOverlay');
        const btnText = payButton.querySelector('.btn-text');
        const btnLoader = payButton.querySelector('.btn-loader');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Show loading state
            payButton.classList.add('loading');
            btnText.style.display = 'none';
            btnLoader.style.display = 'block';

            // Simulate processing (remove this in production and let form submit normally)
            setTimeout(() => {
                // Show success
                successOverlay.classList.add('active');

                // Actually submit the form after showing success
                setTimeout(() => {
                    form.submit();
                }, 2000);
            }, 1500);
        });

        // Add smooth entrance for features
        const features = document.querySelectorAll('.feature');
        features.forEach((feature, index) => {
            feature.style.opacity = '0';
            feature.style.transform = 'translateX(-20px)';
            setTimeout(() => {
                feature.style.transition = 'all 0.5s ease';
                feature.style.opacity = '1';
                feature.style.transform = 'translateX(0)';
            }, 600 + (index * 100));
        });
    </script>
</body>
</html>
