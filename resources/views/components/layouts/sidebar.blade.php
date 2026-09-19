<aside style="width: 240px; background: #1e293b; color: #cbd5e1; min-height: calc(100vh - 60px); padding: 1.5rem 1rem; position: fixed; top: 60px; bottom: 0; left: 0; overflow-y: auto;">
    <style>
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 9px 12px; color: #94a3b8; text-decoration: none; border-radius: 6px; margin-bottom: 3px; font-weight: 500; font-size: 0.88rem; transition: all 0.2s; }
        .nav-item:hover, .nav-item.active { background: #2563eb; color: white; }
        .nav-section { font-size: 0.72rem; text-transform: uppercase; color: #64748b; font-weight: bold; margin: 1.25rem 0 0.4rem 8px; letter-spacing: 0.5px; }
    </style>

    {{-- ========================================== --}}
    {{-- 1. SIDEBAR PROVIDER / SUPERADMIN PANEL      --}}
    {{-- ========================================== --}}
    
    {{-- @if(auth()->check() && auth()->user()->hasRole('superadmin')) --}}
    @hasrole('superadmin')
        <div class="nav-section" style="margin-top: 0;">Superadmin Core</div>
            <a href="/admin/dashboard" class="nav-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                <span>📊</span> Dashboard
            </a>

            <div class="nav-section">Tenant Management</div>
            <a href="/admin/tenants" class="nav-item {{ request()->is('admin/tenants*') ? 'active' : '' }}">
                <span>🏢</span> Client
            </a>

            <div class="nav-section">System & Security</div>
            <a href="/admin/roles" class="nav-item {{ request()->is('admin/roles*') ? 'active' : '' }}">
                <span>🔐</span> Roles & Permissions
            </a>

            <div class="nav-section">Master Data</div>
            <a href="{{ route('admin.pond-types.index') }}" class="nav-item {{ request()->routeIs('admin.pond-types.*') ? 'active' : '' }}">
                <span>🏗️</span> Tipe Kolam
            </a>
            <a href="{{ route('admin.fish-species.index') }}" class="nav-item {{ request()->routeIs('admin.fish-species.*') ? 'active' : '' }}">
                <span>🐟</span> Spesies Ikan
            </a>
            <a href="{{ route('admin.feed-types.index') }}" class="nav-item {{ request()->routeIs('admin.feed-types.*') ? 'active' : '' }}">
                <span>🌾</span> Jenis Pakan
            </a>
            <a href="{{ route('admin.cashflow-categories.index') }}" class="nav-item {{ request()->routeIs('admin.cashflow-categories.*') ? 'active' : '' }}">
                <span>💰</span> Kategori Keuangan
            </a>
    @endhasrole

    {{-- @elseif(auth()->check() && auth()->user()->hasRole('tenant_superadmin')) --}}

        {{-- ========================================== --}}
        {{-- 2. SIDEBAR CLIENT / WORKSPACE TENANT        --}}
        {{-- ========================================== --}}
    @hasrole('tenant_superadmin')
        <div class="nav-section" style="margin-top: 0;">Workspace Overview</div>
            <a href="/tenant/dashboard" class="nav-item {{ request()->is('tenant/dashboard') ? 'active' : '' }}">
                <span>📊</span> Dashboard
            </a>
    @endhasrole
    @hasanyrole(['tenant_superadmin', 'tenant_staf_kolam'])
        <div class="nav-section">Operasional Kolam</div>
            <a href="/tenant/ponds" class="nav-item {{ request()->is('tenant/ponds*') ? 'active' : '' }}">
                <span>🏊‍♂️</span> Kolam
            </a>
            <a href="/tenant/batches" class="nav-item {{ request()->is('tenant/batches*') ? 'active' : '' }}">
                <span>🔄</span> Siklus
            </a>
            <a href="/tenant/harvests" class="nav-item {{ request()->is('tenant/harvests*') ? 'active' : '' }}">
                <span>🌾</span> Panen
            </a>

            <div class="nav-section">Harian</div>
            <a href="/tenant/logs/feed" class="nav-item {{ request()->is('tenant/logs/feed*') ? 'active' : '' }}">
                <span>🐟</span> Pakan
            </a>
            <a href="/tenant/logs/water" class="nav-item {{ request()->is('tenant/logs/water*') ? 'active' : '' }}">
                <span>💧</span> Kualitas Air
            </a>
            <a href="/tenant/logs/sampling" class="nav-item {{ request()->is('tenant/logs/sampling*') ? 'active' : '' }}">
                <span>📏</span> Sampling
            </a>
            <a href="/tenant/logs/mortality" class="nav-item {{ request()->is('tenant/logs/mortality*') ? 'active' : '' }}">
                <span>⚠️</span> Kematian
            </a>
            <a href="/tenant/logs/treatment" class="nav-item {{ request()->is('tenant/logs/treatment*') ? 'active' : '' }}">
                <span>💊</span> Treatment & Obat
            </a>
    @endhasanyrole

    {{-- @elseif(auth()->check() && auth()->user()->hasRole('tenant_superadmin'|'tenant_staf_kolam')) --}}
    @hasanyrole(['tenant_superadmin', 'tenant_staf_keuangan'])
        <div class="nav-section">Kontak & Keuangan</div>
            <a href="/tenant/finance" class="nav-item {{ request()->is('tenant/finance*') ? 'active' : '' }}">
                <span>💰</span> Keuangan
            </a>
            <a href="/tenant/contacts" class="nav-item {{ request()->is('tenant/contacts*') ? 'active' : '' }}">
                <span>📇</span> Kontak
            </a>
    @endhasanyrole

    @hasrole('tenant_superadmin')
            <div class="nav-section">Pengaturan</div>
            <a href="/tenant/profile" class="nav-item {{ request()->is('tenant/profile*') ? 'active' : '' }}">
                <span>⚙️</span> Profil
            </a>
            <a href="/tenant/employees" class="nav-item {{ request()->is('tenant/employees*') ? 'active' : '' }}">
                <span>👥</span> Karyawan
            </a>
    @endhasanyrole

    {{-- @endif --}}

</aside>