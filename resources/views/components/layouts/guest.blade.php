<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Manajemen Budidaya' }}</title>
    <style>
       * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background-color: #f8fafc; color: #1e293b; line-height: 1.5; }
        a { text-decoration: none; color: inherit; }
        
        /* Navbar Styles*/
        .navbar { background: #0f172a; color: white; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .navbar-brand { font-size: 1.25rem; font-weight: 700; color: #38bdf8; display: flex; align-items: center; gap: 8px; }
        .navbar-nav { display: flex; align-items: center; gap: 1rem; }
        .btn-link { color: #f1f5f9; font-weight: 500; font-size: 0.95rem; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; transition: background 0.2s; border: none; background: transparent; }
        .btn-link:hover { background: rgba(255,255,255,0.1); }
        .btn-primary { background: #2563eb; color: white; font-weight: 600; font-size: 0.95rem; padding: 0.5rem 1.25rem; border-radius: 6px; cursor: pointer; transition: background 0.2s; border: none; display: inline-block; }
        .btn-primary:hover { background: #1d4ed8; }

        /* Modal Overlay & Box*/
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(4px); z-index: 1000; justify-content: center; align-items: center; padding: 1rem; }
        .modal-overlay.active { display: flex; }
        .modal-card { background: white; width: 100%; max-width: 440px; border-radius: 12px; padding: 2rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2); position: relative; animation: modalFadeIn 0.25s ease-out; }
        
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-12px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-close { position: absolute; top: 1rem; right: 1rem; background: none; border: none; font-size: 1.25rem; color: #64748b; cursor: pointer; }
        .modal-close:hover { color: #0f172a; }

        /* Form Controls*/
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.25rem; color: #334155; }
        .form-input { width: 100%; padding: 0.625rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.95rem; outline: none; transition: border-color 0.2s; }
        .form-input:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
        .form-submit { width: 100%; margin-top: 0.5rem; }

        footer { background: #0f172a; color: #94a3b8; text-align: center; padding: 2rem 1rem; font-size: 0.875rem; margin-top: 4rem; }
    </style>
</head>
<body>

    <!-- Navigation Header -->
    <nav class="navbar">
        <a href="/" class="navbar-brand">
            🐟 Budidaya Platform
        </a>
        <div class="navbar-nav">
            <button type="button" onclick="openModal('loginModal')" class="btn-link">Masuk</button>
            <button type="button" onclick="openModal('registerModal')" class="btn-primary">Daftar Gratis</button>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Modal Login -->
    <div id="loginModal" class="modal-overlay {{ $errors->has('login') ? 'active' : '' }}">
        <div class="modal-card">
            <button type="button" onclick="closeModal('loginModal')" class="modal-close">&times;</button>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; color: #0f172a;">Masuk ke Akun</h2>
            <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 1.5rem;">Kelola operasional dan pantau kolam Anda.</p>

            @if($errors->has('login'))
                <div style="background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 6px; font-size: 0.875rem; margin-bottom: 1rem;">
                    {{ $errors->first('login') }}
                </div>
            @endif

            <form action="/login" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Username atau Email</label>
                    <input type="text" name="login" required placeholder="Masukkan username/email" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" required placeholder="••••••••" class="form-input">
                </div>
                <button type="submit" class="btn-primary form-submit">Masuk Sekarang</button>
            </form>

            <p style="text-align: center; font-size: 0.875rem; color: #64748b; margin-top: 1.25rem;">
                Belum punya akun? <a href="#" onclick="switchModal('loginModal', 'registerModal', event)" style="color: #2563eb; font-weight: 600;">Daftar di sini</a>
            </p>
        </div>
    </div>

    <!-- Modal Register -->
    <div id="registerModal" class="modal-overlay {{ $errors->any() && !$errors->has('login') ? 'active' : '' }}">
        <div class="modal-card">
            <button type="button" onclick="closeModal('registerModal')" class="modal-close">&times;</button>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; color: #0f172a;">Daftar Akun Baru</h2>
            <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 1.25rem;">Coba gratis layanan budidaya sekarang.</p>

            @if($errors->any() && !$errors->has('login'))
                <div style="background: #fee2e2; color: #991b1b; padding: 0.75rem; border-radius: 6px; font-size: 0.875rem; margin-bottom: 1rem;">
                    <ul style="padding-left: 1rem; margin: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/register" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Pemilik/Pengguna*</label>
                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama Anda" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Username*</label>
                    <input type="text" name="username" value="{{ old('username') }}" required placeholder="Username" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp*</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="Nomor WhatsApp" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Email (Opsional)</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="E-Mail (Opsional/Tidak Wajib)" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Password*</label>
                    <input type="password" name="password" required class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password*</label>
                    <input type="password" name="password_confirmation" required class="form-input">
                </div>
                <button type="submit" class="btn-primary form-submit">Daftar Sekarang</button>
            </form>

            <p style="text-align: center; font-size: 0.875rem; color: #64748b; margin-top: 1rem;">
                Sudah punya akun? <a href="#" onclick="switchModal('registerModal', 'loginModal', event)" style="color: #2563eb; font-weight: 600;">Masuk di sini</a>
            </p>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; {{ date('Y') }} Budidaya Platform. Seluruh hak cipta dilindungi.</p>
    </footer>

    <!-- Script Kontrol Modal -->
    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        }

        function switchModal(closeId, openId, event) {
            if (event) event.preventDefault();
            closeModal(closeId);
            openModal(openId);
        }

        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        window.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.modal-overlay.active').forEach(modal => {
                    modal.classList.remove('active');
                });
                document.body.style.overflow = '';
            }
        });
    </script>
</body>
</html>