<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'SaaS Budidaya' }}</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background: #f3f4f6; margin: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh; }
        .auth-card { width: 100%; max-width: 420px; background: #ffffff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.875rem; color: #374151; }
        input { width: 100%; padding: 0.625rem; border: 1px solid #d1d5db; border-radius: 6px; box-sizing: border-box; }
        button { width: 100%; padding: 0.75rem; background: #2563eb; color: white; font-weight: 600; border: none; border-radius: 6px; cursor: pointer; }
        button:hover { background: #1d4ed8; }
        .alert-error { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; font-size: 0.875rem; margin-bottom: 1rem; }
        .text-center { text-align: center; font-size: 0.875rem; margin-top: 1rem; }
        a { color: #2563eb; text-decoration: none; }
    </style>
</head>
<body>
    <div class="auth-card">
        {{ $slot }}
    </div>
</body>
</html>