<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración - Cafetería</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #8B4513;
            --secondary-color: #D2691E;
            --accent-color: #F4A460;
            --coffee-brown: #6F4E37;
            --cream-color: #FFF8DC;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 8px 15px rgba(0, 0, 0, 0.2);
            --border-radius: 12px;
            --transition: all 0.3s ease;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            position: relative;
            overflow: hidden;
        }
        
        .background-carousel {
            position: fixed;
            top: 0;
            left: 0;
            width: 300%;
            height: 100%;
            z-index: -1;
            display: flex;
            animation: slideHorizontal 15s infinite linear;
        }
        
        @keyframes slideHorizontal {
            0% { transform: translateX(0); }
            33.33% { transform: translateX(-33.33%); }
            66.66% { transform: translateX(-66.66%); }
            100% { transform: translateX(0); }
        }
        
        .background-slide {
            width: 33.33%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            flex-shrink: 0;
        }
        
        .background-slide:nth-child(1) {
            background-image: url('/images/coffee-photo-1.svg');
        }
        
        .background-slide:nth-child(2) {
            background-image: url('/images/coffee-photo-2.svg');
        }
        
        .background-slide:nth-child(3) {
            background-image: url('/images/coffee-photo-3.svg');
        }
        
        .background-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }
        .login-container {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-hover);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
            transition: var(--transition);
        }
        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        .login-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--coffee-brown) 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-header h2 {
            margin: 0;
            font-weight: 300;
        }
        .login-header i {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .login-body {
            padding: 2rem;
        }
        .form-control {
            border: none;
            border-bottom: 2px solid #e9ecef;
            border-radius: 0;
            padding: 0.75rem 0;
            background: transparent;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #667eea;
            background: transparent;
        }
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--coffee-brown) 100%);
            border: none;
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: var(--transition);
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
            background: linear-gradient(135deg, var(--coffee-brown) 0%, var(--primary-color) 100%);
        }
        .alert {
            border: none;
            border-radius: 10px;
        }
        .test-credentials {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            font-size: 0.85rem;
        }
        .test-credentials h6 {
            color: #667eea;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Background Carousel -->
    <div class="background-carousel">
        <div class="background-slide"></div>
        <div class="background-slide"></div>
        <div class="background-slide"></div>
    </div>
    <div class="background-overlay"></div>
    
    <div class="login-container">
        <div class="login-header">
            <i class="fas fa-coffee"></i>
            <h2>Panel de Administración</h2>
            <p class="mb-0">Cafetería</p>
        </div>
        <div class="login-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0">
                            <i class="fas fa-envelope text-muted"></i>
                        </span>
                        <input type="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               name="email" 
                               placeholder="Correo electrónico"
                               value="{{ old('email') }}"
                               required>
                    </div>
                    @error('email')
                        <div class="text-danger small mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-0">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               name="password" 
                               placeholder="Contraseña"
                               required>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">
                        Recordarme
                    </label>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        Iniciar Sesión
                    </button>
                </div>
            </form>

            <div class="test-credentials">
                <h6><i class="fas fa-info-circle me-1"></i> Credenciales de Prueba:</h6>
                <div class="row">
                    <div class="col-6">
                        <strong>Administrador:</strong><br>
                        admin@cafeteria.com<br>
                        <code>admin123</code>
                    </div>
                    <div class="col-6">
                        <strong>Gerente:</strong><br>
                        manager@cafeteria.com<br>
                        <code>manager123</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    

</body>
</html>