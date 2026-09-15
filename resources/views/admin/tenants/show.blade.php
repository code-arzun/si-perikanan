<x-layouts.app>
    <x-slot:title>Detail Tenant - {{ $tenant->name }}</x-slot:title>

    {{-- Page Header --}}
    <x-admin.header 
        title="Detail & Ringkasan Tenant" 
        subtitle="Informasi profil usaha dan statistik operasional tambak.">
        <x-slot:action>
            <x-admin.button href="/admin/tenants" variant="secondary" style="margin-right: 8px;">
                ← Kembali ke Daftar
            </x-admin.button>
            <x-admin.button href="/admin/tenants/{{ $tenant->id }}/edit" variant="primary">
                Edit Status / Profile
            </x-admin.button>
        </x-slot:action>
    </x-admin.header>

    {{-- Alert Messages --}}
    @if(session('success'))
        <x-admin.alert type="success">{{ session('success') }}</x-admin.alert>
    @endif

    {{-- BARIS 1: Profil Tenant & Quick KPI Summary --}}
    <div style="display: grid; grid-template-columns: 320px 1fr; gap: 1.5rem; margin-bottom: 2rem; align-items: start;">
        
        {{-- Card Information Profil Tenant --}}
        <div style="background: white; padding: 1.5rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-top: 4px solid #2563eb;">
            <h3 style="margin-top: 0; color: #1e293b; font-size: 1.1rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px;">Profil Usaha</h3>
            
            <div style="margin-bottom: 0.85rem;">
                <small style="color: #64748b; font-weight: bold; display: block;">NAMA TENANT / TAMBAK</small>
                <strong style="font-size: 1.05rem; color: #1e3a8a;">{{ $tenant->name }}</strong>
            </div>

            <div style="margin-bottom: 0.85rem;">
                <small style="color: #64748b; font-weight: bold; display: block;">TIPE USAMA</small>
                <span style="display: inline-block; padding: 2px 8px; background: #e0f2fe; color: #0369a1; border-radius: 4px; font-size: 0.8rem; font-weight: bold; text-transform: uppercase;">
                    {{ $tenant->tenant_type ?? 'Individual' }}
                </span>
            </div>

            <div style="margin-bottom: 0.85rem;">
                <small style="color: #64748b; font-weight: bold; display: block;">STATUS AKSES</small>
                @if($tenant->status === 'active')
                    <span style="padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: bold; background: #dcfce7; color: #15803d;">Aktif (Normal)</span>
                @else
                    <span style="padding: 2px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: bold; background: #fee2e2; color: #991b1b;">{{ ucfirst($tenant->status) }}</span>
                @endif
            </div>

            <div style="margin-bottom: 0.85rem;">
                <small style="color: #64748b; font-weight: bold; display: block;">PEMILIK / OWNER</small>
                <span style="color: #334155; font-weight: 500;">{{ $tenant->users->first()->name ?? 'N/A' }}</span>
            </div>

            <div style="margin-bottom: 0.85rem;">
                <small style="color: #64748b; font-weight: bold; display: block;">KONTAK (HP / EMAIL)</small>
                <span style="color: #334155;">{{ $tenant->phone_or_email }}</span>
            </div>

            <div style="margin-bottom: 0;">
                <small style="color: #64748b; font-weight: bold; display: block;">TANGGAL TERDAFTAR</small>
                <span style="color: #64748b; font-size: 0.85rem;">{{ $tenant->created_at->format('d F Y (H:i)') }}</span>
            </div>
        </div>

        {{-- Grid Card KPI Ringkasan Data Operasional --}}
        <div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                {{-- Card Kolam --}}
                <a href="{{ route('admin.tenants.ponds', $tenant->id) }}" style="text-decoration: none;">
                    <x-admin.stat-card title="Total Kolam" :value="$summary['total_ponds'] . ' Kolam'" subtext="Klik untuk detail →" borderColor="#0284c7" />
                </a>

                {{-- Card Siklus --}}
                <a href="{{ route('admin.tenants.batches', $tenant->id) }}" style="text-decoration: none;">
                    <x-admin.stat-card title="Siklus Budidaya" :value="$summary['active_batches'] . ' Batch'" subtext="Klik untuk detail →" borderColor="#16a34a" valueColor="#15803d" />
                </a>

                {{-- Card Tim / Users --}}
                <a href="{{ route('admin.tenants.users', $tenant->id) }}" style="text-decoration: none;">
                    <x-admin.stat-card title="Total Pengguna" :value="$summary['total_users'] . ' Orang'" subtext="Klik untuk detail →" borderColor="#f59e0b" />
                </a>

                {{-- Card Harvest --}}
                <a href="{{ route('admin.tenants.harvests', $tenant->id) }}" style="text-decoration: none;">
                    <x-admin.stat-card title="Total Panen" :value="number_format($summary['total_harvest_kg'], 0, ',', '.') . ' kg'" subtext="Histori Panen →" borderColor="#8b5cf6" />
                </a>
            </div>

            {{-- Metric Total Hasil Panen Akumulasi --}}
            <div style="background: linear-gradient(135deg, #1e3a8a 0%, #0284c7 100%); color: white; padding: 1.25rem 1.5rem; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 0.85rem; text-transform: uppercase; opacity: 0.9; font-weight: bold;">Akumulasi Produksi Panen Tenant</div>
                    <div style="font-size: 1.8rem; font-weight: bold; margin-top: 4px;">{{ number_format($summary['total_harvest_kg'], 0, ',', '.') }} kg</div>
                </div>
                <div style="background: rgba(255,255,255,0.2); padding: 8px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: bold;">
                    📈 Produktivitas High
                </div>
            </div>
        </div>

    </div>

    {{-- BARIS 2: Tabel Preview Preview Data Kolam & Navigasi Lebih Lanjut --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        
        {{-- Box Ringkasan Kolam --}}
        <div style="background: white; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                <h3 style="margin: 0; color: #1e293b; font-size: 1rem;">🏊‍♂️ Daftar Kolam (Preview 5 Terakhir)</h3>
                {{-- Link Navigasi Drill-Down ke Detail Lebih Far --}}
                <a href="/admin/tenants/{{ $tenant->id }}/ponds" style="color: #2563eb; text-decoration: none; font-size: 0.85rem; font-weight: bold;">Lihat Semua Kolam ({{ $summary['total_ponds'] }}) →</a>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left;">
                        <th style="padding: 8px;">Nama Kolam</th>
                        <th style="padding: 8px;">Tipe</th>
                        <th style="padding: 8px;">Luas / Volume</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pondsPreview as $pond)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 8px; font-weight: bold; color: #1e293b;">{{ $pond->name }}</td>
                            <td style="padding: 8px; text-transform: capitalize;">{{ $pond->type ?? 'Beton/Terpal' }}</td>
                            <td style="padding: 8px; color: #64748b;">{{ $pond->area_m2 ?? '-' }} m²</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding: 12px; text-align: center; color: #94a3b8;">Belum ada data kolam.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Box Ringkasan Siklus Aktif --}}
        <div style="background: white; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                <h3 style="margin: 0; color: #1e293b; font-size: 1rem;">🔄 Siklus Budidaya Berjalan</h3>
                {{-- Link Navigasi Drill-Down ke Detail Lebih Far --}}
                <a href="/admin/tenants/{{ $tenant->id }}/batches" style="color: #2563eb; text-decoration: none; font-size: 0.85rem; font-weight: bold;">Lihat Semua Siklus →</a>
            </div>

            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left;">
                        <th style="padding: 8px;">Kode Batch</th>
                        <th style="padding: 8px;">Kolam</th>
                        <th style="padding: 8px;">Tebar Benih</th>
                        <th style="padding: 8px;">Jumlah Tebar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activeBatchesPreview as $batch)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 8px; font-weight: bold; color: #2563eb;">{{ $batch->batch_code }}</td>
                            <td style="padding: 8px;">{{ $batch->pond->name ?? '-' }}</td>
                            <td style="padding: 8px; color: #64748b;">{{ \Carbon\Carbon::parse($batch->start_date)->format('d M Y') }}</td>
                            <td style="padding: 8px; font-weight: 500;">{{ number_format($batch->seed_count, 0, ',', '.') }} ekor</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="padding: 12px; text-align: center; color: #94a3b8;">Tidak ada siklus aktif saat ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-layouts.app>