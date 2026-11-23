<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Obat - Toko Apotek</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 p-8">
    <div class="max-w-lg mx-auto bg-white p-6 rounded-xl shadow">
        <h2 class="text-2xl font-bold text-teal-700 mb-4">Tambah Data Obat</h2>

        <form action="{{ route('obat.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Nama Obat</label>
                <input type="text" name="nama_obat" required class="w-full border rounded-lg p-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Kategori</label>
                <select name="kategori" class="w-full border rounded-lg p-2">
                    <option value="Obat Bebas">Obat Bebas</option>
                    <option value="Obat Keras">Obat Keras</option>
                    <option value="Vitamin & Suplemen">Vitamin & Suplemen</option>
                    <option value="Sirup">Sirup</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Harga (Rp)</label>
                    <input type="number" name="harga" required class="w-full border rounded-lg p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Stok</label>
                    <input type="number" name="stok" required class="w-full border rounded-lg p-2">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal Kadaluarsa</label>
                <input type="date" name="tanggal_kadaluarsa" class="w-full border rounded-lg p-2">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full border rounded-lg p-2" placeholder="Kegunaan / dosis singkat..."></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Foto Obat</label>
                <input type="file" name="gambar" accept="image/*" class="w-full border rounded-lg p-2 text-sm">
            </div>
            <div class="flex justify-between pt-2">
                <a href="{{ route('obat.index') }}" class="text-slate-500 hover:underline py-2">Kembali</a>
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-5 py-2 rounded-lg font-medium">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>