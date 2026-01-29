<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="paroisse, connexion, église catholique" />
    <meta name="author" content="Paroisse" />
    <meta name="robots" content="index, follow" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Connexion au système de gestion de paroisse" />
    <meta name="format-detection" content="telephone=no">
    <title>Connexion - Gestion de Paroisse</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('tpl/images/favicon.png') }}">

    <!-- Styles du template -->
    <link rel="stylesheet" href="{{ asset('tpl/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('tpl/css/style.css') }}">

    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Styles personnalisés -->
    <style>
        :root {
            {!! \App\Helpers\ParoisseConfig::getCssVariables() !!}
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #003366 50%, #001f3d 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Effet de particules animées en arrière-plan */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255, 215, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255, 215, 0, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(255, 255, 255, 0.05) 0%, transparent 50%);
            animation: float 20s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .authincation {
            position: relative;
            z-index: 1;
        }

        .authincation-content {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            overflow: hidden;
            position: relative;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .authincation-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--secondary) 50%, var(--primary) 100%);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .auth-form {
            padding: 60px 55px;
        }

        .logo-container {
            margin-bottom: 35px;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .logo-container img {
            max-width: 200px;
            height: auto;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
            transition: transform 0.3s ease;
        }

        .logo-container img:hover {
            transform: scale(1.05);
        }

        .logo-container h3 {
            color: var(--primary);
            font-weight: 700;
            font-size: 1.5rem;
            margin-top: 15px;
            letter-spacing: -0.5px;
        }

        .auth-form h4 {
            color: #2d3748;
            font-weight: 600;
            font-size: 1.5rem;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            position: relative;
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            font-size: 0.9rem;
            letter-spacing: 0.3px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.1rem;
            transition: color 0.3s ease;
            z-index: 2;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px 14px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
            color: #2d3748;
        }

        .form-control.is-invalid {
            border-color: #dc143c;
            background: #fff5f5;
            box-shadow: 0 0 0 4px rgba(220, 20, 60, 0.08);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(0, 51, 102, 0.1);
            transform: translateY(-1px);
        }

        .form-control:focus + i,
        .form-control:not(:placeholder-shown) + i {
            color: var(--primary);
        }

        .form-control::placeholder {
            color: #a0aec0;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a0aec0;
            cursor: pointer;
            font-size: 1.1rem;
            padding: 5px;
            transition: color 0.3s ease;
            z-index: 2;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        .password-toggle:hover {
            color: var(--primary);
            background: rgba(0, 51, 102, 0.06);
        }

        /* Laisse la place au bouton œil à droite */
        .form-control.password-input {
            padding-right: 56px;
        }

        .input-wrapper.has-error i {
            color: #dc143c;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            border: 2px solid #cbd5e0;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background-color: var(--primary);
            border-color: var(--primary);
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(0, 51, 102, 0.1);
        }

        .form-check-label {
            color: #4a5568;
            font-size: 0.9rem;
            cursor: pointer;
            user-select: none;
        }

        .forgot-password-link {
            color: var(--primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .forgot-password-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s ease;
        }

        .forgot-password-link:hover {
            color: var(--primary-hover);
        }

        .forgot-password-link:hover::after {
            width: 100%;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            color: white;
            padding: 14px 32px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 51, 102, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 51, 102, 0.4);
            background: linear-gradient(135deg, var(--primary-hover) 0%, var(--primary) 100%);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary:focus {
            outline: none;
            box-shadow: 0 0 0 4px rgba(0, 51, 102, 0.2);
        }

        .alert {
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 20px;
            border: none;
            border-left: 4px solid;
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background: #fee;
            border-left-color: #dc143c;
            color: #721c24;
        }

        .alert-success {
            background: #f0fff4;
            border-left-color: #2d5016;
            color: #1f3a0f;
        }

        .alert-info {
            background: rgba(74, 144, 226, 0.12);
            border-left-color: #4a90e2;
            color: #1f4f7a;
        }

        .alert-warning {
            background: rgba(255, 140, 0, 0.12);
            border-left-color: #ff8c00;
            color: #7a3d00;
        }

        .alert ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .form-error-summary {
            font-size: 0.9rem;
        }

        .invalid-feedback {
            color: #dc143c;
            font-size: 0.82rem;
            margin-top: 6px;
        }

        .copyright {
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            color: #718096;
            font-size: 0.85rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-form {
                padding: 40px 30px;
            }

            .logo-container img {
                max-width: 140px;
            }

            .auth-form h4 {
                font-size: 1.3rem;
            }

            .form-control {
                padding: 12px 16px 12px 44px;
            }
        }

        @media (max-width: 480px) {
            .auth-form {
                padding: 30px 20px;
            }

            .logo-container h3 {
                font-size: 1.2rem;
            }
        }

        /* Animation de chargement pour le bouton */
        .btn-primary.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-primary.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<body class="vh-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-70 align-items-center">
                <div class="col-md-12 col-lg-6 col-xl-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <div class="text-center mb-4 logo-container">
                                        <img src="{{ asset(\App\Helpers\ParoisseConfig::get(null, 'logo_path', '/images/logo-paroisse.svg')) }}" alt="Logo Paroisse">
                                        <h3 class="mt-2 mb-0">
                                            Paroisse <br>
                                            {{ \App\Helpers\ParoisseConfig::get(null, 'nom_paroisse', 'Gestion de Paroisse') }}
                                        </h3>
                                    </div>
                                    <h4>Connectez-vous à votre compte</h4>

                                    @if (\App\Helpers\FlashAlert::has())
                                        @php($flash = \App\Helpers\FlashAlert::get())
                                        @php($flashType = $flash['type'] ?? 'info')
                                        <div class="alert {{ $flashType === 'success' ? 'alert-success' : ($flashType === 'warning' ? 'alert-warning' : ($flashType === 'info' ? 'alert-info' : 'alert-danger')) }}">
                                            {{ $flash['message'] ?? '' }}
                                        </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger form-error-summary">
                                            Merci de corriger les erreurs indiquées ci-dessous.
                                        </div>
                                    @endif

                                    <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                                        @csrf

                                        <div class="form-group">
                                            <label for="login"><strong>Email ou Identifiant</strong></label>
                                            <div class="input-wrapper @error('login') has-error @enderror">
                                                <input
                                                    type="text"
                                                    class="form-control @error('login') is-invalid @enderror"
                                                    id="login"
                                                    name="login"
                                                    value="{{ old('login') }}"
                                                    placeholder="Entrez votre email ou identifiant"
                                                    required
                                                    autocomplete="username"
                                                    autofocus
                                                >
                                                <i class="fas fa-user"></i>
                                            </div>
                                            @error('login')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="password"><strong>Mot de passe</strong></label>
                                            <div class="input-wrapper @error('password') has-error @enderror">
                                                <input
                                                    type="password"
                                                    class="form-control password-input @error('password') is-invalid @enderror"
                                                    id="password"
                                                    name="password"
                                                    placeholder="Entrez votre mot de passe"
                                                    required
                                                    autocomplete="current-password"
                                                >
                                                <i class="fas fa-lock"></i>
                                                <button type="button" class="password-toggle" id="togglePassword" aria-label="Afficher/Masquer le mot de passe">
                                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input"
                                                    id="remember_me"
                                                    name="remember"
                                                    {{ old('remember') ? 'checked' : '' }}
                                                >
                                                <label class="form-check-label" for="remember_me">
                                                    Se souvenir de moi
                                                </label>
                                            </div>
                                            <a href="#" class="forgot-password-link">
                                                Mot de passe oublié ?
                                            </a>
                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                                <span id="btnText">Se connecter</span>
                                            </button>
                                        </div>
                                    </form>

                                    <div class="copyright">
                                        <p class="mb-0">© {{ date('Y') }} - Gestion de Paroisse. Tous droits réservés.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('tpl/vendor/global/global.min.js') }}"></script>
    <script src="{{ asset('tpl/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('tpl/js/custom.min.js') }}"></script>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword')?.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Form submission loading state
        document.getElementById('loginForm')?.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');

            submitBtn.classList.add('loading');
            btnText.textContent = 'Connexion en cours...';
        });

        // Auto-focus on email field if empty
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            if (emailInput && !emailInput.value) {
                emailInput.focus();
            }
        });

        // Add smooth transitions on input focus
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>
