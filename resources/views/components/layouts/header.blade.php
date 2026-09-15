<header style="background: #0f172a; color: white; padding: 0.75rem 1.5rem; display: flex; justify-content: space-between; align-items: center; height: 60px; position: fixed; top: 0; left: 0; right: 0; z-index: 100; border-bottom: 1px solid #1e293b;">
    <div style="display: flex; align-items: center; gap: 10px;">
        <span style="font-size: 1.2rem;">🐟</span>
        <strong style="color: #38bdf8; font-size: 1.1rem; letter-spacing: 0.5px;">SUPERADMIN PLATFORM</strong>
    </div>

    <div style="display: flex; align-items: center; gap: 1rem;">
        <div style="text-align: right; font-size: 0.85rem;">
            <div style="font-weight: bold; color: white;">{{ auth()->user()->name ?? 'Administrator' }}</div>
            <small style="color: #38bdf8;">Superadmin Role</small>
        </div>
        
        <form action="/logout" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" style="background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-weight: bold;">
                Logout
            </button>
        </form>
    </div>
</header>