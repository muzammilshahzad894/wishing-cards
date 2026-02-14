<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- PAGE TITLE HERE -->
    <title>Admin Login - Wishing Cards</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('admin/css/auth.css') }}">
</head>

<body class="h-100">
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7 col-sm-9">
                    <div class="login-card">
                        <div class="login-header">
                            <div class="logo-container">
                                <i class="fas fa-birthday-cake"></i>
                            </div>
                            <h2 class="mb-2 fw-bold">Welcome Back</h2>
                            <p class="mb-0 opacity-75">Sign in to manage card designs</p>
                        </div>
                        
                        <div class="login-body">
                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    {{ $errors->first() }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('admin.login') }}" id="loginForm">
                                @csrf
                                
                                <div class="mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope text-muted"></i>
                                        </span>
                                        <div class="form-floating flex-grow-1">
                                            <input type="email" 
                                                   class="form-control with-icon @error('email') is-invalid @enderror" 
                                                   id="email" 
                                                   name="email" 
                                                   placeholder="name@example.com" 
                                                   value="{{ old('email') }}" 
                                                   required>
                                            <label for="email">Email Address</label>
                                        </div>
                                    </div>
                                    @error('email')
                                        <div class="text-danger mt-2">
                                            <small><i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}</small>
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="mb-4">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <div class="form-floating flex-grow-1">
                                            <input type="password" 
                                                   class="form-control with-icon @error('password') is-invalid @enderror" 
                                                   id="password" 
                                                   name="password" 
                                                   placeholder="Password" 
                                                   required>
                                            <label for="password">Password</label>
                                        </div>
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="text-danger mt-2">
                                            <small><i class="fas fa-exclamation-triangle me-1"></i>{{ $message }}</small>
                                        </div>
                                    @enderror
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Remember me
                                        </label>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-login btn-primary w-100 mb-3" id="loginBtn">
                                    <span class="spinner-border spinner-border-sm d-none me-2" role="status" id="loginSpinner"></span>
                                    <i class="fas fa-sign-in-alt me-2" id="loginIcon"></i>
                                    <span id="loginText">Sign In</span>
                                </button>
                                
                                <div class="text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        Your information is secure and encrypted
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('admin/js/auth.js') }}"></script>
</body>
</html>