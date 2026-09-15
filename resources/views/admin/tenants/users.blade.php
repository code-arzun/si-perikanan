<x-layouts.app>
    <x-slot:title>Daftar Tim/User - {{ $tenant->name }}</x-slot:title>

    <x-admin.header 
        title="Daftar Pengguna / Tim: {{ $tenant->name }}" 
        subtitle="Anggota tim, pengelola, dan operator yang terhubung pada akun tenant ini.">
        <x-slot:action>
            <x-admin.button href="{{ route('admin.tenants.show', $tenant->id) }}" variant="secondary">
                ← Kembali ke Detail Tenant
            </x-admin.button>
        </x-slot:action>
    </x-admin.header>

    <div style="background: white; border-radius: 8px; padding: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; text-align: left;">
                    <th style="padding: 12px;">Nama Lengkap</th>
                    <th style="padding: 12px;">Username</th>
                    <th style="padding: 12px;">Email / No HP</th>
                    <th style="padding: 12px;">Role Hak Akses</th>
                    <th style="padding: 12px;">Tanggal Dibuat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 12px; font-weight: bold; color: #1e293b;">{{ $user->name }}</td>
                        <td style="padding: 12px; color: #64748b;">{{ $user->username ?? '-' }}</td>
                        <td style="padding: 12px; color: #334155;">{{ $user->email ?? $user->phone ?? '-' }}</td>
                        <td style="padding: 12px;">
                            @forelse($user->roles as $role)
                                <span style="padding: 2px 6px; background: #e0f2fe; color: #0369a1; border-radius: 4px; font-size: 0.78rem; font-weight: bold; margin-right: 2px;">
                                    {{ $role->name }}
                                </span>
                            @empty
                                <span style="color: #94a3b8; font-size: 0.8rem;">Tidak Ada Role</span>
                            @endforelse
                        </td>
                        <td style="padding: 12px; color: #64748b;">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: #94a3b8;">
                            Belum ada anggota tim/user terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if(is_object($users) && method_exists($users, 'links'))
            <div style="margin-top: 1rem;">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>