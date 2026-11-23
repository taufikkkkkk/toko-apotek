<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Resep Dokter — Apotek Sehat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F8FAFC] text-slate-900 min-h-screen antialiased">

    <header class="bg-white border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <a href="{{ route('toko.index') }}" class="font-bold text-[#0F3D3E] text-base">Apotek Sehat</a>
            <a href="{{ route('toko.index') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900">Kembali ke Katalog</a>
        </div>
    </header>

    <main class="max-w-xl mx-auto px-4 sm:px-6 py-10">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Tebus Resep Dokter</h1>
            <p class="text-sm text-slate-500 mt-1">Unggah foto resep Anda. Apoteker kami akan memverifikasi ketersediaan obat dan mengonfirmasi total biaya via WhatsApp.</p>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-lg mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6 text-sm">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <form action="{{ route('toko.resep.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Nama Pasien / Pemesan</label>
                    <input type="text" name="nama_pasien" required placeholder="Nama lengkap" class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#0F3D3E]">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Nomor WhatsApp Aktif</label>
                    <input type="text" name="no_hp" required placeholder="08xxxxxxxxxx" class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#0F3D3E]">
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Foto Resep Dokter (JPG/PNG, Maks 3MB)</label>
                    <label class="mt-1 flex flex-col items-center justify-center border-2 border-dashed border-slate-300 hover:border-[#0F3D3E] rounded-xl p-6 cursor-pointer bg-slate-50/50 transition">
                        <img id="previewImg" src="" class="hidden max-h-56 rounded-lg mb-3 object-contain">
                        <span id="uploadText" class="text-sm font-medium text-slate-600">Klik untuk pilih foto resep</span>
                        <span class="text-xs text-slate-400 mt-1">Pastikan tulisan dokter terbaca jelas</span>
                        <input type="file" name="foto_resep" accept="image/*" required class="hidden" onchange="previewFile(this)">
                    </label>
                </div>

                <div>
                    <label class="block text-xs font-medium text-slate-600 mb-1">Catatan Tambahan (Opsional)</label>
                    <textarea name="catatan" rows="2" placeholder="Contoh: Alergi obat tertentu, atau minta diantar sore hari..." class="w-full border border-slate-200 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-[#0F3D3E]"></textarea>
                </div>

                <button type="submit" class="w-full bg-[#0F3D3E] hover:bg-[#0b2f30] text-white py-2.5 rounded-lg font-semibold text-sm transition">
                    Kirim Resep ke Apoteker
                </button>
            </form>
        </div>
    </main>

    <script>
        function previewFile(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('previewImg');
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    document.getElementById('uploadText').innerText = 'Ganti foto resep';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>