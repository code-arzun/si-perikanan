<x-layouts.app>
    <x-slot:title>Dashboard Tenant</x-slot:title>

    <div style="background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e5e7eb; max-width: 600px; margin: 2rem auto;">
        <h2>Login Berhasil! 🎉</h2>
        <p><strong>Nama:</strong> {{ auth()->user()->name }}</p>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
        <p><strong>Nama Tenant/Usaha:</strong> {{ auth()->user()->tenant ? auth()->user()->tenant->name : 'SaaS Superadmin' }}</p>
        <p><strong>Role Hak Akses:</strong> <span style="background: #e0e7ff; color: #4338ca; padding: 2px 8px; border-radius: 4px;">{{ auth()->user()->getRoleNames()->first() }}</span></p>

        <hr style="margin: 1.5rem 0; border: 0; border-top: 1px solid #e5e7eb;">

        <form action="/logout" method="POST">
            @csrf
            <button type="submit" style="background: #ef4444; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: bold;">
                Logout / Keluar
            </button>
        </form>
    </div>
</x-layouts.app>