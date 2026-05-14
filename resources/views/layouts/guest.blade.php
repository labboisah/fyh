<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Fatima Yahaya Hospital') }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Bootstrap Icons -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
        <!-- Poppins Font -->
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet">
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
        <style>
            :root {
                --primary-green: #27AE60;
                --primary-orange: #FF8C42;
                --light-green: #E8F8F5;
                --light-orange: #FFF5E6;
            }

            * {
                font-family: 'Poppins', sans-serif;
            }

            body {
                background-image: url('{{ asset("images/hero-bg.png") }}');
                background-attachment: fixed;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 40px 20px;
                position: relative;
                overflow-y: auto;
            }

            body::before {
                content: '';
                position: fixed;
                top: -50%;
                right: -10%;
                width: 500px;
                height: 500px;
                background: rgba(255, 255, 255, 0.05);
                border-radius: 50%;
                z-index: 0;
                pointer-events: none;
            }

            body::after {
                content: '';
                position: fixed;
                bottom: -30%;
                left: -5%;
                width: 400px;
                height: 400px;
                background: rgba(255, 140, 66, 0.1);
                border-radius: 50%;
                z-index: 0;
                pointer-events: none;
            }

            .auth-container {
                background: white;
                border-radius: 15px;
                box-shadow: 0 8px 32px rgba(39, 174, 96, 0.15);
                overflow: hidden;
                max-width: 450px;
                width: 100%;
                position: relative;
                z-index: 1;
            }

            .auth-header {
                background: linear-gradient(90deg, var(--primary-green) 0%, var(--primary-green) 70%, var(--primary-orange) 100%);
                padding: 2rem 1.5rem;
                text-align: center;
                color: white;
            }

            .hospital-logo {
                width: 80px;
                height: 80px;
                margin: 0 auto 1rem;
            }

            .auth-header h1 {
                font-size: 1.5rem;
                font-weight: 700;
                margin: 0 0 0.5rem 0;
            }

            .auth-header p {
                font-size: 0.9rem;
                margin: 0;
                opacity: 0.9;
            }

            .auth-body {
                padding: 2.5rem 2rem;
            }

            .form-label {
                font-weight: 600;
                color: #1a1a1a;
                font-size: 0.95rem;
                margin-bottom: 0.6rem;
            }

            .form-control, .form-control:focus {
                border-radius: 8px;
                border: 2px solid #e0e0e0;
                padding: 0.7rem 1rem;
                font-size: 0.95rem;
                transition: all 0.3s ease;
            }

            .form-control:focus {
                border-color: var(--primary-green);
                box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
            }

            .btn {
                border-radius: 8px;
                padding: 0.75rem 1.5rem;
                font-weight: 600;
                font-size: 0.95rem;
                transition: all 0.3s ease;
                border: none;
            }

            .btn-success {
                background: var(--primary-green);
                color: white;
            }

            .btn-success:hover {
                background: #1e8449;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
            }

            .btn-success:active {
                background: #1a6f3f;
                transform: translateY(0);
            }

            .form-check-input {
                border-radius: 4px;
                border: 2px solid #ccc;
                width: 1.2rem;
                height: 1.2rem;
                cursor: pointer;
            }

            .form-check-input:checked {
                background-color: var(--primary-green);
                border-color: var(--primary-green);
            }

            .form-check-input:focus {
                box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
                border-color: var(--primary-green);
            }

            .form-check-label {
                margin-left: 0.5rem;
                color: #666;
                cursor: pointer;
                font-size: 0.9rem;
            }

            .auth-links {
                text-align: center;
                margin-top: 1.5rem;
                padding-top: 1.5rem;
                border-top: 1px solid #e0e0e0;
            }

            .auth-links a {
                color: var(--primary-green);
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .auth-links a:hover {
                color: var(--primary-orange);
                text-decoration: underline;
            }

            .invalid-feedback {
                color: #dc3545;
                font-size: 0.85rem;
                margin-top: 0.3rem;
                display: block;
            }

            .home-link {
                display: inline-block;
                margin-top: 1rem;
                padding: 0.5rem 1rem;
                background: linear-gradient(90deg, var(--primary-green), var(--primary-orange));
                color: white;
                text-decoration: none;
                border-radius: 6px;
                font-weight: 600;
                font-size: 0.85rem;
                transition: all 0.3s ease;
            }

            .home-link:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(39, 174, 96, 0.3);
                color: white;
            }

            .form-row {
                display: flex;
                gap: 1rem;
                align-items: flex-end;
            }

            .form-row > div {
                flex: 1;
            }

            .button-group {
                display: flex;
                gap: 1rem;
                justify-content: flex-end;
                align-items: center;
                margin-top: 1.5rem;
            }

            @media (max-width: 576px) {
                body {
                    padding: 30px 15px;
                    align-items: flex-start;
                    justify-content: flex-start;
                    padding-top: 50px;
                }

                .auth-container {
                    border-radius: 12px;
                    margin: auto;
                    max-width: 100%;
                }

                .auth-header {
                    padding: 2rem 1.5rem;
                }

                .auth-body {
                    padding: 1.5rem;
                }

                .hospital-logo {
                    width: 70px;
                    height: 70px;
                }

                .button-group {
                    flex-direction: column;
                    gap: 0.75rem;
                }

                .button-group > * {
                    width: 100%;
                }

                .form-check-label {
                    font-size: 0.85rem;
                }

                .form-label {
                    font-size: 0.9rem;
                }
            }

            .page-loader {
                position: fixed;
                inset: 0;
                z-index: 2000;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(255, 255, 255, 0.96);
                transition: opacity 0.25s ease, visibility 0.25s ease;
                opacity: 1;
                visibility: visible;
            }

            .page-loader.hidden {
                opacity: 0;
                visibility: hidden;
                pointer-events: none;
            }

            .loader-box {
                text-align: center;
                padding: 1.5rem 2rem;
                border-radius: 1rem;
                background: #fff;
                box-shadow: 0 18px 60px rgba(0, 0, 0, 0.12);
            }

            .loader-logo {
                width: 80px;
                max-width: 100%;
                display: block;
                margin: 0 auto 1rem;
            }

            .page-loader .spinner-border {
                width: 3rem;
                height: 3rem;
            }
        </style>
    </head>
    <body>
        <div id="page-loader" class="page-loader">
            <div class="loader-box">
                <img src="{{ asset('images/logo.png') }}" alt="FAYHOS Logo" class="loader-logo">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-3">Loading page...</div>
            </div>
        </div>

        <div class="auth-container">
            
            <div class="auth-body">
                <div class="text text-center"><img src="{{asset('images/logo.png')}}" width="100" alt=""></div>
                
                {{ $slot }}
                
            </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            (function () {
                const pageLoader = document.getElementById('page-loader');

                const hidePageLoader = () => {
                    if (pageLoader) {
                        pageLoader.classList.add('hidden');
                    }
                };

                const disableElement = (element) => {
                    if (!element || element.dataset.loading === 'true') {
                        return;
                    }

                    element.dataset.loading = 'true';

                    if (element.tagName === 'A') {
                        element.style.pointerEvents = 'none';
                        element.setAttribute('aria-disabled', 'true');
                        element.classList.add('disabled');
                        return;
                    }

                    element.disabled = true;
                };

                const setButtonLoading = (button, label) => {
                    if (!button || button.dataset.loading === 'true') {
                        return;
                    }

                    disableElement(button);
                    button.dataset.originalHtml = button.innerHTML;
                    button.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        ${label}
                    `;
                };

                const isActionableLink = (link) => {
                    if (!link || !link.href) {
                        return false;
                    }

                    const href = link.getAttribute('href');
                    if (!href || href.startsWith('#') || href.startsWith('javascript:') || href === 'mailto:' || href === 'tel:') {
                        return false;
                    }

                    if (link.target && link.target !== '_self') {
                        return false;
                    }

                    if (link.hasAttribute('download')) {
                        return false;
                    }

                    return link.hostname === window.location.hostname;
                };

                document.addEventListener('DOMContentLoaded', function () {
                    hidePageLoader();

                    document.querySelectorAll('a').forEach(link => {
                        if (!isActionableLink(link)) {
                            return;
                        }

                        link.addEventListener('click', function () {
                            disableElement(link);
                        });
                    });

                    document.querySelectorAll('button, input[type="submit"], input[type="button"], input[type="reset"]').forEach(control => {
                        control.addEventListener('click', function (event) {
                            const button = event.currentTarget;

                            if (button.matches('[data-bs-toggle], [data-toggle], .dropdown-toggle')) {
                                return;
                            }

                            if (button.closest('form') && (button.type === 'submit' || button.getAttribute('type') === 'submit')) {
                                return;
                            }

                            setButtonLoading(button, button.dataset.loadingText || 'Loading...');
                        });
                    });

                    document.querySelectorAll('form').forEach(form => {
                        form.addEventListener('submit', function () {
                            const button = form.querySelector("button[type='submit'], input[type='submit']");
                            if (button) {
                                setButtonLoading(button, button.dataset.loadingText || 'Processing...');
                            }
                        });
                    });
                });
            })();
        </script>
    </body>
</html>
