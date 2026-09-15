<x-layouts.app>
    <x-slot:title>Superadmin - Kelola Tenant</x-slot:title>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <div>
            <h2 style="color: #1e3a8a; margin: 0;">Superadmin Platform Control</h2>
            <span style="color: #6b7280; font-size: 0.9rem;">Daftar Tenant (Customer Tambak) Terdaftar</span>
        </div>
        <a href="/admin/tenants/create" style="background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: bold;">+ Daftarkan Tenant Baru</a>
    </div>

    @if(session('success'))
        <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 6px; margin-bottom: 1rem;">
            {{ session('success') }}
        </div>
    @endif

    <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <thead>
            <tr style="background: #f1f5f9; text-align: left; border-bottom: 1px solid #e2e8f0;">
                <th style="padding: 12px;">Nama Perusahaan / Tambak</th>
                <th style="padding: 12px;">Nama Pemilik</th>
                <th style="padding: 12px;">Total Pengguna</th>
                <th style="padding: 12px;">Status</th>
                <th style="padding: 12px;">Tanggal Daftar</th>
                <th style="padding: 12px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tenants as $tenant)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 12px; font-weight: bold; color: #1e293b;">{{ $tenant->name }}</td>
                    <td style="padding: 12px; font-weight: bold; color: #1e293b;">{{ $tenant->users->first()->name ?? 'N/A' }}</td>
                    <td style="padding: 12px; color: #475569;">{{ $tenant->users_count }} Pengguna</td>
                    <td style="padding: 12px;">
                        @if($tenant->is_active)
                            <span style="padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; background: #dcfce7; color: #15803d;">Aktif</span>
                        @else
                            <span style="padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: bold; background: #fee2e2; color: #991b1b;">Suspended</span>
                        @endif
                    </td>
                    <td style="padding: 12px; color: #64748b;">{{ $tenant->created_at->format('d M Y') }}</td>
                    <td style="padding: 12px; text-align: center;">
                        <x-admin.button href="/admin/tenants/{{ $tenant->id }}" variant="primary" title="Detail & Summary" style="margin-right: 4px; padding: 6px 10px;">
                            👁️
                        </x-admin.button>

                        <x-admin.button href="/admin/tenants/{{ $tenant->id }}/edit" variant="secondary" title="Edit Status / Profil" style="margin-right: 4px; padding: 6px 10px; background: #f59e0b; color: white;">
                            ✏️
                        </x-admin.button>

                        <form action="/admin/tenants/{{ $tenant->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tenant ini beserta SELURUH penggunanya? Action ini tidak dapat dibatalkan!');" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <x-admin.button type="submit" variant="danger" title="Hapus Tenant" style="padding: 6px 10px;">
                                🗑️
                            </x-admin.button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 20px; text-align: center; color: #64748b;">Belum ada tenant terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $tenants->links() }}
    </div>
</x-layouts.app>