<x-layouts.app>
    <x-slot:title>Superadmin Dashboard</x-slot:title>

    <div style="margin-bottom: 1.5rem;">
        <h2 style="color: #1e3a8a; margin: 0;">Beranda Provider Superadmin</h2>
        <span style="color: #6b7280; font-size: 0.9rem;">Performa platform SaaS dan ringkasan ekosistem seluruh customer.</span>
    </div>

    {{-- Grid KPI Utama Provider --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
        
        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #2563eb;">
            <div style="font-size: 0.8rem; color: #6b7280; font-weight: bold; text-transform: uppercase;">Total Tenant</div>
            <div style="font-size: 1.8rem; font-weight: bold; color: #1e3a8a; margin: 4px 0;">{{ $totalTenants }}</div>
            <small style="color: #059669; font-weight: bold;">+{{ $newTenantsThisMonth }} Bulan Ini</small>
        </div>

        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #10b981;">
            <div style="font-size: 0.8rem; color: #6b7280; font-weight: bold; text-transform: uppercase;">Tenant Aktif</div>
            <div style="font-size: 1.8rem; font-weight: bold; color: #047857; margin: 4px 0;">{{ $activeTenants }}</div>
            <small style="color: #6b7280;">{{ $suspendedTenants }} Suspended</small>
        </div>

        <div style="background: white; padding: 1.25rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-left: 4px solid #8b5cf6;">
            <div style="font-size: 0.8rem; color: #6b7280; font-weight: bold; text-transform: uppercase;">Total Pengguna Platform</div>
            <div style="font-size: 1.8rem; font-weight: bold; color: #6d28d9; margin: 4px 0;">{{ $totalUsers }}</div>
            <small style="color: #6b7280;">Seluruh Tim Tenant</small>
        </div>

    </div>

    {{-- Tabel Tenant Terdaftar Terbaru --}}
    <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3 style="margin: 0; color: #1e293b;">Tenant Terdaftar Terbaru</h3>
            <a href="/admin/tenants" style="color: #2563eb; text-decoration: none; font-size: 0.85rem; font-weight: bold;">Lihat Semua Tenant &rarr;</a>
        </div>

        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead>
                <tr style="background: #f8fafc; text-align: left; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 10px;">Nama Perusahaan / Tambak</th>
                    <th style="padding: 10px;">Total Pengguna</th>
                    <th style="padding: 10px;">Status</th>
                    <th style="padding: 10px;">Tanggal Bergabung</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestTenants as $tenant)
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px; font-weight: bold;">{{ $tenant->name }}</td>
                        <td style="padding: 10px;">{{ $tenant->users_count }} Pengguna</td>
                        <td style="padding: 10px;">
                            @if($tenant->where('status', 'active')->exists())
                                <span style="padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: bold; background: #dcfce7; color: #15803d;">Aktif</span>
                            @elseif($tenant->where('status', 'uji coba')->exists())
                                <span style="padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: bold; background: #fee2e2; color: #991b1b;">Masa Uji Coba</span>
                            @elseif($tenant->where('status', 'suspended')->exists())
                                <span style="padding: 2px 8px; border-radius: 10px; font-size: 11px; font-weight: bold; background: #fee2e2; color: #991b1b;">Suspended</span>
                            @endif
                        </td>
                        <td style="padding: 10px; color: #64748b;">{{ $tenant->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 15px; text-align: center; color: #64748b;">Belum ada data tenant.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layouts.app>