<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Budidaya' }}</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background: #f9fafb; margin: 0; }
        header { background: #ffffff; border-bottom: 1px solid #e5e7eb; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: #4b5563; text-decoration: none; font-weight: 600; margin-right: 1.5rem; padding-bottom: 4px; }
        nav a:hover, nav a.active { color: #2563eb; border-bottom: 2px solid #2563eb; }
        main { padding: 2rem; max-width: 1200px; margin: 0 auto; }
        .btn-logout { background: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; font-weight: 600; }
    </style>
</head>
<body>
    <header>
        <div style="display: flex; align-items: center; gap: 2rem;">
            <strong style="font-size: 1.125rem; color: #111827;">
                {{-- {{ auth()->user()->tenant ? auth()->user()->tenant->name : 'SaaS Superadmin' }} --}}
                {{ auth()->user()?->tenant?->name ?? 'SaaS Platform' }}
            </strong>

            {{-- Navigasi Menu Stage 2 --}}
            @auth
            <nav>
                <a href="/dashboard" class="{{ request()->is('dashboard*') || request()->is('tenant/dashboard*') ? 'active' : '' }}">Dashboard</a>
                <a href="/tenant/ponds" class="{{ request()->is('tenant/ponds*') ? 'active' : '' }}">Data Kolam</a>
                <a href="/tenant/batches" class="{{ request()->is('tenant/batches*') ? 'active' : '' }}">Siklus Budidaya</a>
                <a href="/tenant/logs/feed" class="{{ request()->is('tenant/logs/feed*') ? 'active' : '' }}">Log Pakan</a>
                <a href="/tenant/logs/mortality" class="{{ request()->is('tenant/logs/mortality*') ? 'active' : '' }}">Log Kematian</a>
                <a href="/tenant/logs/sampling" class="{{ request()->is('tenant/logs/sampling*') ? 'active' : '' }}">Log Sampling</a>
                <a href="/tenant/logs/treatment" class="{{ request()->is('tenant/logs/treatment*') ? 'active' : '' }}">Log Treatment</a>
                <a href="/tenant/logs/water" class="{{ request()->is('tenant/logs/water*') ? 'active' : '' }}">Log Kualitas Air</a>
                <a href="/tenant/harvests" class="{{ request()->is('tenant/harvests*') ? 'active' : '' }}">Log Panen</a>
                <a href="/tenant/profile" class="{{ request()->is('tenant/profile*') ? 'active' : '' }}">Profil Usaha</a>
            </nav>
            @endauth
        </div>

        <div>
            {{-- <span style="font-size: 0.875rem; color: #4b5563;">
                {{ auth()->user()->name }} (<strong>{{ auth()->user()->getRoleNames()->first() }}</strong>)
            </span>
            <form action="/logout" method="POST" style="display: inline; margin-left: 1rem;">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form> --}}
            @auth
                <span style="font-size: 0.875rem; color: #4b5563;">
                    {{ auth()->user()->name }} (<strong>{{ auth()->user()->getRoleNames()->first() }}</strong>)
                </span>
                <form action="/logout" method="POST" style="display: inline; margin-left: 1rem;">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            @else
                <a href="/login" style="color: #2563eb; font-weight: bold; text-decoration: none; margin-right: 1rem;">Masuk</a>
                <a href="/register" style="background: #2563eb; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: bold;">Daftar</a>
            @endauth
        </div>
    </header>

    <main>
        {{ $slot }}
    </main>
</body>
</html>