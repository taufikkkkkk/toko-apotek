<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Toko Apotek</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-xl shadow w-full max-w-sm">
        <h2 class="text-2xl font-bold text-teal-700 text-center mb-6">🔐 Login Admin</h2>

        @error('email')
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-sm">{{ $message }}</div>
        @enderror

        <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="admin@apotek.com" required class="w-full border rounded-lg p-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" required class="w-full border rounded-lg p-2">
            </div>
            <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 rounded-lg font-medium">Masuk</button>
        </form>
        <div class="text-center mt-4">
            <a href="{{ route('toko.index') }}" class="text-sm text-slate-500 hover:underline">← Kembali ke Toko</a>
        </div>
    </div>
</body>
</html>