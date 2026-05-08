<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - UniShop Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', sans-serif; }
        body { background: #f1f5f9; }
        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 50%, #1e1b4b 100%);
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        .auth-wrapper::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(67, 97, 238, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .auth-wrapper::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(124, 58, 237, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }
        .auth-card {
            width: 480px;
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            position: relative;
            z-index: 1;
        }
        .auth-card-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 32px 30px;
            text-align: center;
            color: #fff;
            position: relative;
        }
        .auth-card-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #4361ee, #7c3aed, #4361ee);
        }
        .auth-logo {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, #4361ee, #7c3aed);
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            box-shadow: 0 8px 24px rgba(67, 97, 238, 0.3);
        }
        .auth-card-header h3 {
            font-size: 1.35rem;
            font-weight: 800;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }
        .auth-card-header p {
            font-size: 0.82rem;
            opacity: 0.6;
            margin: 0;
        }
        .auth-card-body { padding: 28px 30px; }
        .auth-card .form-label {
            font-weight: 600;
            font-size: 0.82rem;
            color: #475569;
            margin-bottom: 6px;
        }
        .auth-card .form-control {
            border-radius: 10px;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.875rem;
            background: #f8fafc;
            transition: all 0.2s;
        }
        .auth-card .form-control:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            background: #fff;
        }
        .auth-card .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 1.5px solid #e2e8f0;
            border-right: none;
            background: #f8fafc;
            color: #94a3b8;
        }
        .auth-card .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }
        .auth-card .input-group:focus-within .input-group-text {
            border-color: #4361ee;
            color: #4361ee;
        }
        .auth-card .btn-primary {
            background: linear-gradient(135deg, #4361ee, #3a56d4);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }
        .auth-card .btn-primary:hover {
            background: linear-gradient(135deg, #3a56d4, #2d4bc4);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.35);
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card">
            <div class="auth-card-header">
                <div class="auth-logo">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <path d="M16 10a4 4 0 01-8 0"/>
                    </svg>
                </div>
                <h3>UniShop Manager</h3>
                <p>Create Your Shop Account</p>
            </div>
            <div class="auth-card-body">
                <form method="POST" action="{{ route('register.post') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="John Doe" value="{{ old('name') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Shop Name</label>
                            <input type="text" name="shop_name" class="form-control" placeholder="My Shop" value="{{ old('shop_name') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            </span>
                            <input type="email" name="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg>
                            </span>
                            <input type="tel" name="phone" class="form-control" placeholder="+880 1XXX-XXXXXX" value="{{ old('phone') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Min 8 chars" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 mb-3">Create Account</button>
                    <div class="text-center">
                        <p class="mb-0" style="font-size:0.85rem; color:#64748b;">Already have an account?
                            <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color:#4361ee;">Sign In</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
