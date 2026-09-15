<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Superadmin Panel - SaaS Budidaya' }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: system-ui, -apple-system, sans-serif; background: #f8fafc; margin: 0; color: #1e293b; display: flex; flex-direction: column; min-height: 100vh; }
        .app-wrapper { display: flex; flex: 1; margin-top: 60px; }
        .app-content { flex: 1; margin-left: 240px; padding: 2rem; min-height: calc(100vh - 120px); }
    </style>
</head>
<body>

    {{-- 1. Header Component --}}
    <x-layouts.header />

    <div class="app-wrapper">
        {{-- 2. Sidebar Component --}}
        <x-layouts.sidebar />

        {{-- 3. Main Content --}}
        <main class="app-content">
            {{ $slot }}
        </main>
    </div>

    {{-- 4. Footer Component --}}
    <x-layouts.footer />

</body>
</html>