<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Cinema Management')</title>
     <!-- Favicon -->
     <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
     <link rel="shortcut icon" type="image/png" href="{{ asset('images/Logo.png') }}">
     <meta name="user-profile-image" content="{{ asset('images/Logo.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Responsive Design Framework -->
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --body-bg: #f1f5f9;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 1rem;
            overflow-x: hidden;
        }

        .login-container {
            max-width: 400px;
            margin: 0 auto;
            width: 100%;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        /* Mobile responsive adjustments */
        @media (min-width: 576px) {
            body {
                padding: 2rem;
            }

            .brand-logo {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }

            .login-container {
                max-width: 450px;
            }
        }

        @media (min-width: 768px) {
            .login-container {
                max-width: 500px;
            }
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: var(--card-shadow);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-control {
            border: 1px solid #e2e8f0;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            border-radius: 10px;
            background-color: #f8fafc;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background-color: white;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.3s ease;
            width: 100%;
            font-size: 0.9rem;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }

        /* Mobile responsive form adjustments */
        @media (min-width: 576px) {
            .card-body {
                padding: 2rem;
            }

            .form-control {
                font-size: 0.95rem;
            }

            .form-label {
                font-size: 0.9rem;
            }

            .btn-primary {
                font-size: 1rem;
            }
        }

        /* Input group responsive */
        .input-group {
            margin-bottom: 1rem;
        }

        .input-group-text {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px 0 0 10px;
            color: #64748b;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        /* Alert responsive */
        .alert {
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        @media (min-width: 576px) {
            .alert {
                font-size: 0.9rem;
            }
        }
        
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .invalid-feedback {
            font-size: 0.85rem;
        }
        
        .alert {
            border-radius: 10px;
            font-size: 0.9rem;
        }
    </style>
    @yield('styles')
</head>
<body>
    @yield('content')
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html> 