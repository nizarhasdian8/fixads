<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Fix Advertising</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { 50: '#fff7ed', 100: '#ffedd5', 500: '#f97316', 600: '#ea580c', 700: '#c2410c' },
                    },
                    fontFamily: { sans: ['"Inter"', 'system-ui', 'sans-serif'] }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', system-ui, sans-serif; }</style>
</head>
<body class="bg-stone-50 text-stone-900 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-sm">
        <div class="flex flex-col items-center mb-8">
            <div class="w-12 h-12 rounded-xl bg-brand-500 flex items-center justify-center mb-4">
                <span class="text-white font-bold text-lg">FA</span>
            </div>
            <h1 class="text-xl font-bold text-stone-900">Fix Advertising</h1>
            <p class="text-sm text-stone-400 mt-1">Sistem Pencatatan Pesanan Internal</p>
        </div>

        <div class="bg-white border border-stone-200 rounded-2xl p-6 sm:p-8 shadow-sm">
            <h2 class="text-lg font-semibold text-stone-900 mb-1">Masuk ke akun Anda</h2>
            <p class="text-sm text-stone-400 mb-6">Khusus untuk CIO Marketing &amp; CIO Production</p>

            @if ($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-700 text-sm font-medium px-4 py-3 rounded-xl">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-stone-700 mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
                        placeholder="nama@fixadvertising.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-stone-700 mb-1.5">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-stone-300 text-sm placeholder:text-stone-400 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition"
                        placeholder="••••••••">
                </div>

                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="rounded border-stone-300 text-brand-600 focus:ring-brand-500">
                    <span class="text-sm text-stone-600">Ingat saya</span>
                </label>

                <button type="submit"
                    class="w-full bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white font-semibold text-sm py-2.5 rounded-xl transition shadow-sm shadow-brand-500/20">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-stone-400 mt-6">&copy; {{ date('Y') }} Fix Advertising. Internal use only.</p>
    </div>

</body>
</html>
