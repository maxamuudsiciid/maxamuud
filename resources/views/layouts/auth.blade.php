<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') — BloodBank MS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(
           135deg,
          #ff6b6b 0%,
          #a855f7 35%,
          #2563eb 70%,
          #22c55e 100% );
            display: flex; align-items: center; justify-content: center;
        }
        .auth-container { width: 100%; max-width: 440px; padding: 20px; }
        .auth-brand {
            text-align: center; margin-bottom: 28px; color: #fff;
        }
        .auth-brand .brand-logo {
            width: 64px; height: 64px; background: rgba(255,255,255,0.2);
            border-radius: 18px; margin: 0 auto 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; backdrop-filter: blur(4px);
        }
        .auth-brand h1 { font-size: 22px; font-weight: 800; margin: 0; }
        .auth-brand p { font-size: 13px; margin: 4px 0 0; opacity: 0.8; }
        .auth-card {
            background: #fff;
            border-radius: 20px;
            padding: 32px 36px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.25);
        }
     .auth-card h2 {
    font-size: 20px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.auth-card .sub {
    font-size: 13px;
    color: #94a3b8;
    margin-bottom: 24px;
}
        .form-label { font-size: 12.5px; font-weight: 600; color: #374151; margin-bottom: 5px; }
        .form-control, .form-select {
            border-radius: 10px; border: 1.5px solid #e2e8f0;
            font-size: 13.5px; padding: 10px 14px; transition: all 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #dc2626; box-shadow: 0 0 0 3px rgba(220,38,38,0.1);
        }
        .input-group-text {
            background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px 0 0 10px;
            color: #94a3b8;
        }
        .form-control:focus,
.form-select:focus{
border-color:#8b5cf6;
box-shadow:0 0 0 4px rgba(139,92,246,.15);
}
        .btn-auth {
            background: linear-gradient(90deg,
           #6366f1,
           #ec4899,
           #f97316,
           #facc15 );


            color: #fff; border: none; border-radius: 10px;
            font-size: 14px; font-weight: 600; padding: 11px;
            width: 100%; transition: all 0.2s; letter-spacing: 0.3px;
        }
        .auth-brand .brand-logo{
width: 75px;
height: 75px;
background: linear-gradient(
135deg,
#ec4899,
#8b5cf6,
#3b82f6
);
border-radius: 22px;
}
        .auth-footer { text-align: center; margin-top: 20px; font-size: 13px; color: #64748b; }
        .auth-footer a { color: #dc2626; font-weight: 600; text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }
        .divider { display: flex; align-items: center; margin: 18px 0; }
        .divider::before, .divider::after { content:''; flex:1; border-top: 1px solid #e2e8f0; }
        .divider span { padding: 0 12px; font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; }
        .demo-cred {
            background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px;
            padding: 12px 16px; margin-bottom: 20px; font-size: 12.5px; color: #78350f;
        }
        .demo-cred strong { display: block; margin-bottom: 4px; }
        .demo-cred code { background: rgba(0,0,0,0.06); padding: 1px 6px; border-radius: 4px; }
    </style>
</head>
<body>
<div class="auth-container">
    @yield('content')
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>    