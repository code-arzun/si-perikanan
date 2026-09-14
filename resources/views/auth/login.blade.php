<x-layouts.app>
    <x-slot:title>Login Akun</x-slot:title>

    <div style="max-width: 400px; margin: 3rem auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h2 style="margin-top: 0;">Masuk Akun</h2>

        @if ($errors->any())
            <div style="background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; padding: 0.75rem; border-radius: 6px; margin-bottom: 1rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Username / No HP / Email</label>
                <input type="text" name="login" value="{{ old('login') }}" required placeholder="Masukkan Username, No HP, atau Email" style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 4px; font-weight: bold;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <button type="submit" style="width: 100%; background: #2563eb; color: white; padding: 10px; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                Masuk
            </button>
        </form>
    </div>
</x-layouts.app>