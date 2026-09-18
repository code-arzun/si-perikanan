<x-layouts.guest>
    <x-slot:title>Budidaya Platform - Kelola Tambak & Kolam Lebih Efisien</x-slot:title>

    <style>
        .hero { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white; padding: 5rem 1.5rem; text-align: center; }
        .hero-title { font-size: 2.75rem; font-weight: 800; line-height: 1.2; margin-bottom: 1.25rem; }
        .hero-title span { color: #38bdf8; }
        .hero-subtitle { font-size: 1.125rem; color: #94a3b8; max-width: 650px; margin: 0 auto 2.5rem auto; }
        .hero-cta { display: flex; justify-content: center; gap: 1rem; }
        
        .btn-hero-primary { background: #2563eb; color: white; padding: 0.875rem 2rem; border-radius: 8px; font-weight: 700; font-size: 1.05rem; border: none; cursor: pointer; }
        .btn-hero-primary:hover { background: #1d4ed8; }
        .btn-hero-secondary { background: rgba(255, 255, 255, 0.1); color: white; padding: 0.875rem 2rem; border-radius: 8px; font-weight: 600; font-size: 1.05rem; border: 1px solid rgba(255,255,255,0.2); cursor: pointer; }
        .btn-hero-secondary:hover { background: rgba(255, 255, 255, 0.2); }

        .features { max-width: 1100px; margin: -3rem auto 0 auto; padding: 0 1.5rem; position: relative; z-index: 10; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; }
        .card { background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .card-icon { font-size: 2rem; margin-bottom: 1rem; }
        .card-title { font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem; color: #0f172a; }
        .card-desc { color: #64748b; font-size: 0.95rem; }

        .cta-section { max-width: 900px; margin: 5rem auto 2rem auto; text-align: center; background: #eff6ff; border: 1px solid #bfdbfe; padding: 3rem 1.5rem; border-radius: 16px; }
        .cta-title { font-size: 1.75rem; font-weight: 800; color: #1e3a8a; margin-bottom: 0.75rem; }
        .cta-desc { color: #1e40af; margin-bottom: 1.5rem; }
    </style>

    <!-- Hero Section -->
    <section class="hero">
        <h1 class="hero-title">Sistem Manajemen <span>Budidaya Perikanan</span> Modern</h1>
        <p class="hero-subtitle">
            Pantau operasional kolam, kualitas air, pemberian pakan, hingga pencatatan panen dan keuangan usaha Anda dalam satu platform terpadu.
        </p>
        <div class="hero-cta">
            <button onclick="openModal('registerModal')" class="btn-hero-primary">Mulai Uji Coba Gratis</button>
            <button onclick="openModal('loginModal')" class="btn-hero-secondary">Masuk ke Akun</button>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features">
        <div class="grid">
            <div class="card">
                <div class="card-icon">🌊</div>
                <h3 class="card-title">Manajemen Kolam & Siklus</h3>
                <p class="card-desc">Kelola data seluruh kolam budidaya, jadwal penebaran benih, hingga pemantauan umur siklus secara real-time.</p>
            </div>
            <div class="card">
                <div class="card-icon">📊</div>
                <h3 class="card-title">Pencatatan Harian</h3>
                <p class="card-desc">Catat pemberian pakan harian, pengecekan parameter kualitas air, sampling, dan riwayat perlakuan/obat dengan mudah.</p>
            </div>
            <div class="card">
                <div class="card-icon">💰</div>
                <h3 class="card-title">Hasil Panen & Keuangan</h3>
                <p class="card-desc">Rekapitulasi tonase panen, pencatatan arus kas operasional, dan analisis laba-rugi tiap siklus budidaya.</p>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <h2 class="cta-title">Siap Meningkatkan Hasil Budidaya Anda?</h2>
        <p class="cta-desc">Daftarkan usaha Anda sekarang dan nikmati masa uji coba gratis tanpa syarat yang rumit.</p>
        <button onclick="openModal('registerModal')" class="btn-hero-primary">Daftarkan Usaha Sekarang</button>
    </section>
</x-layouts.guest>