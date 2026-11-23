<?php

namespace Database\Seeders;

use App\Models\Obat;
use App\Models\Ulasan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ObatSeeder extends Seeder
{
    public function run(): void
    {
        $produkList = [
            // === OBAT BEBAS ===
            [
                'nama_obat' => 'Promag Tablet Kunyah',
                'kategori' => 'Obat Bebas',
                'harga' => 9500,
                'stok' => 65,
                'tanggal_kadaluarsa' => '2028-04-15',
                'deskripsi' => 'Meredakan sakit maag, nyeri lambung, perut kembung, dan rasa mual akibat asam lambung berlebih.',
                'dosis' => '10 Tablet Kunyah',
                'warna' => '#16A34A',
                'jenis_kemasan' => 'box_bebas',
            ],
            [
                'nama_obat' => 'Bodrex Extra',
                'kategori' => 'Obat Bebas',
                'harga' => 5500,
                'stok' => 80,
                'tanggal_kadaluarsa' => '2028-01-20',
                'deskripsi' => 'Meringankan sakit kepala, pusing, migrain, dan membantu menurunkan demam ringan.',
                'dosis' => '4 Kaplet',
                'warna' => '#DC2626',
                'jenis_kemasan' => 'box_bebas',
            ],
            [
                'nama_obat' => 'Panadol Extra Merah',
                'kategori' => 'Obat Bebas',
                'harga' => 14500,
                'stok' => 45,
                'tanggal_kadaluarsa' => '2027-11-30',
                'deskripsi' => 'Meredakan sakit kepala membandel, pusing, sakit gigi, dan nyeri otot dengan cepat.',
                'dosis' => '10 Kaplet 500 mg',
                'warna' => '#B91C1C',
                'jenis_kemasan' => 'box_bebas',
            ],
            [
                'nama_obat' => 'CTM 4 mg Tablet',
                'kategori' => 'Obat Bebas',
                'harga' => 4000,
                'stok' => 90,
                'tanggal_kadaluarsa' => '2027-09-10',
                'deskripsi' => 'Meredakan gejala alergi, gatal-gatal pada kulit, bersin, dan pilek akibat alergi.',
                'dosis' => '12 Tablet 4 mg',
                'warna' => '#D97706',
                'jenis_kemasan' => 'box_bebas',
            ],

            // === OBAT KERAS ===
            [
                'nama_obat' => 'Amoxicillin 500 mg',
                'kategori' => 'Obat Keras',
                'harga' => 12000,
                'stok' => 35,
                'tanggal_kadaluarsa' => '2027-08-25',
                'deskripsi' => 'Antibiotik untuk mengatasi infeksi bakteri pada saluran pernapasan dan kulit. Harus dengan resep dokter.',
                'dosis' => '10 Kaplet 500 mg',
                'warna' => '#0284C7',
                'jenis_kemasan' => 'box_keras',
            ],
            [
                'nama_obat' => 'Asam Mefenamat 500 mg',
                'kategori' => 'Obat Keras',
                'harga' => 8500,
                'stok' => 50,
                'tanggal_kadaluarsa' => '2027-10-12',
                'deskripsi' => 'Pereda nyeri sedang hingga berat seperti sakit gigi, nyeri sendi, dan nyeri haid. Wajib resep dokter.',
                'dosis' => '10 Kaplet 500 mg',
                'warna' => '#2563EB',
                'jenis_kemasan' => 'box_keras',
            ],
            [
                'nama_obat' => 'Cetirizine 10 mg',
                'kategori' => 'Obat Keras',
                'harga' => 7500,
                'stok' => 40,
                'tanggal_kadaluarsa' => '2028-02-18',
                'deskripsi' => 'Antihistamin untuk meredakan alergi, biduran, dan bersin tanpa menyebabkan kantuk berlebih.',
                'dosis' => '10 Tablet 10 mg',
                'warna' => '#0D9488',
                'jenis_kemasan' => 'box_keras',
            ],
            [
                'nama_obat' => 'Omeprazole 20 mg',
                'kategori' => 'Obat Keras',
                'harga' => 15000,
                'stok' => 30,
                'tanggal_kadaluarsa' => '2027-07-05',
                'deskripsi' => 'Mengatasi asam lambung tinggi, maag kronis, tukak lambung, dan gejala GERD sesuai anjuran dokter.',
                'dosis' => '10 Kapsul 20 mg',
                'warna' => '#4F46E5',
                'jenis_kemasan' => 'box_keras',
            ],

            // === VITAMIN & SUPLEMEN ===
            [
                'nama_obat' => 'Enervon-C Multivitamin',
                'kategori' => 'Vitamin & Suplemen',
                'harga' => 42000,
                'stok' => 38,
                'tanggal_kadaluarsa' => '2028-06-01',
                'deskripsi' => 'Kombinasi Vitamin C 500 mg dan Vitamin B Kompleks untuk membantu menjaga daya tahan tubuh harian.',
                'dosis' => 'Botol 30 Tablet',
                'warna' => '#EA580C',
                'jenis_kemasan' => 'botol_vitamin',
            ],
            [
                'nama_obat' => 'Imboost Force Kaplet',
                'kategori' => 'Vitamin & Suplemen',
                'harga' => 78000,
                'stok' => 25,
                'tanggal_kadaluarsa' => '2028-03-14',
                'deskripsi' => 'Suplemen peningkat daya tahan tubuh dengan Echinacea dan Zinc untuk mempercepat pemulihan.',
                'dosis' => 'Botol 30 Kaplet',
                'warna' => '#0F766E',
                'jenis_kemasan' => 'botol_vitamin',
            ],
            [
                'nama_obat' => 'Vitamin D3 1000 IU',
                'kategori' => 'Vitamin & Suplemen',
                'harga' => 36000,
                'stok' => 55,
                'tanggal_kadaluarsa' => '2028-09-20',
                'deskripsi' => 'Memenuhi kebutuhan vitamin D harian untuk menjaga kesehatan tulang, otot, dan sistem imun.',
                'dosis' => 'Botol 30 Softgel',
                'warna' => '#CA8A04',
                'jenis_kemasan' => 'botol_vitamin',
            ],
            [
                'nama_obat' => 'Neurobion Forte',
                'kategori' => 'Vitamin & Suplemen',
                'harga' => 48000,
                'stok' => 28,
                'tanggal_kadaluarsa' => '2028-05-11',
                'deskripsi' => 'Vitamin neurotropik B1, B6, dan B12 untuk menjaga kesehatan saraf serta mengatasi kebas dan kesemutan.',
                'dosis' => 'Botol 30 Tablet',
                'warna' => '#BE185D',
                'jenis_kemasan' => 'botol_vitamin',
            ],

            // === SIRUP ===
            [
                'nama_obat' => 'OBH Combi Batuk Berdahak',
                'kategori' => 'Sirup',
                'harga' => 19500,
                'stok' => 44,
                'tanggal_kadaluarsa' => '2027-12-10',
                'deskripsi' => 'Sirup obat batuk berdahak rasa menthol segar yang membantu mengencerkan dan mengeluarkan dahak.',
                'dosis' => 'Sirup 100 ml',
                'warna' => '#047857',
                'jenis_kemasan' => 'botol_sirup',
            ],
            [
                'nama_obat' => 'Sanmol Sirup Anak',
                'kategori' => 'Sirup',
                'harga' => 22000,
                'stok' => 50,
                'tanggal_kadaluarsa' => '2028-01-28',
                'deskripsi' => 'Sirup Paracetamol penurun demam dan pereda nyeri ringan untuk anak dengan rasa buah.',
                'dosis' => 'Sirup 60 ml',
                'warna' => '#E11D48',
                'jenis_kemasan' => 'botol_sirup',
            ],
            [
                'nama_obat' => 'Siladex Antitussive',
                'kategori' => 'Sirup',
                'harga' => 18000,
                'stok' => 32,
                'tanggal_kadaluarsa' => '2027-11-05',
                'deskripsi' => 'Sirup pereda batuk tidak berdahak dan tenggorokan gatal, bebas alkohol dan tidak bikin ngantuk.',
                'dosis' => 'Sirup 60 ml',
                'warna' => '#0284C7',
                'jenis_kemasan' => 'botol_sirup',
            ],
            [
                'nama_obat' => 'Termorex Plus Sirup',
                'kategori' => 'Sirup',
                'harga' => 21000,
                'stok' => 29,
                'tanggal_kadaluarsa' => '2028-02-09',
                'deskripsi' => 'Meringankan gejala flu pada anak seperti demam, sakit kepala, hidung tersumbat, pilek, dan batuk.',
                'dosis' => 'Sirup 60 ml',
                'warna' => '#EA580C',
                'jenis_kemasan' => 'botol_sirup',
            ],
        ];

        $ulasanDummy = [
            ['nama' => 'Budi Santoso', 'rating' => 5, 'komentar' => 'Produk asli, pengiriman cepat dan kadaluarsa masih sangat panjang.'],
            ['nama' => 'Siti Rahma', 'rating' => 5, 'komentar' => 'Kemasan rapi dan aman, cocok buat stok kotak P3K di rumah.'],
            ['nama' => 'Dedi Kurniawan', 'rating' => 4, 'komentar' => 'Respon apoteker cepat, harga sesuai di apotek.'],
        ];

        foreach ($produkList as $index => $item) {
            $slug = Str::slug($item['nama_obat']);
            $path = 'obat/' . $slug . '.svg';

            // Buat file gambar kemasan SVG ke storage/app/public/obat/
            $svg = $this->generateKemasanSvg(
                $item['nama_obat'],
                $item['dosis'],
                $item['warna'],
                $item['jenis_kemasan']
            );
            Storage::disk('public')->put($path, $svg);

            $obat = Obat::updateOrCreate(
                ['nama_obat' => $item['nama_obat']],
                [
                    'kategori' => $item['kategori'],
                    'harga' => $item['harga'],
                    'stok' => $item['stok'],
                    'tanggal_kadaluarsa' => $item['tanggal_kadaluarsa'],
                    'deskripsi' => $item['deskripsi'],
                    'gambar' => $path,
                ]
            );

            // Tambah 1-2 ulasan awal pada sebagian produk
            if ($obat->ulasans()->count() === 0 && $index % 2 === 0) {
                $sample = $ulasanDummy[$index % count($ulasanDummy)];
                Ulasan::create([
                    'obat_id' => $obat->id,
                    'nama' => $sample['nama'],
                    'rating' => $sample['rating'],
                    'komentar' => $sample['komentar'],
                ]);
            }
        }
    }

    private function generateKemasanSvg(string $nama, string $dosis, string $warna, string $jenis): string
    {
        $safeNama = htmlspecialchars($nama, ENT_QUOTES, 'UTF-8');
        $safeDosis = htmlspecialchars($dosis, ENT_QUOTES, 'UTF-8');

        // Pecah nama jika terlalu panjang jadi 2 baris
        $words = explode(' ', $safeNama);
        $line1 = implode(' ', array_slice($words, 0, 2));
        $line2 = implode(' ', array_slice($words, 2));

        // === KEMASAN KOTAK + BLISTER (OBAT BEBAS & KERAS) ===
        if ($jenis === 'box_bebas' || $jenis === 'box_keras') {
            $logoBadge = $jenis === 'box_bebas'
                ? '<circle cx="298" cy="76" r="14" fill="#16A34A" stroke="#0F172A" stroke-width="2.5"/>'
                : '<circle cx="298" cy="76" r="14" fill="#DC2626" stroke="#0F172A" stroke-width="2.5"/><text x="298" y="82" text-anchor="middle" font-family="Arial, sans-serif" font-size="16" font-weight="bold" fill="#0F172A">K</text>';

            return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="400" height="320" viewBox="0 0 400 320">
  <defs>
    <filter id="sh" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="6" stdDeviation="6" flood-color="#0F172A" flood-opacity="0.12"/>
    </filter>
  </defs>
  <rect width="400" height="320" fill="#FFFFFF"/>
  <!-- Kotak Obat -->
  <g filter="url(#sh)">
    <rect x="65" y="45" width="260" height="155" rx="10" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
    <rect x="65" y="45" width="260" height="18" rx="4" fill="{$warna}"/>
    <rect x="65" y="184" width="260" height="16" rx="4" fill="{$warna}" opacity="0.15"/>
    {$logoBadge}
    <text x="84" y="102" font-family="Arial, sans-serif" font-size="20" font-weight="700" fill="#0F172A">{$line1}</text>
    <text x="84" y="126" font-family="Arial, sans-serif" font-size="18" font-weight="600" fill="{$warna}">{$line2}</text>
    <text x="84" y="154" font-family="Arial, sans-serif" font-size="13" fill="#64748B">{$safeDosis}</text>
    <rect x="84" y="166" width="54" height="5" rx="2.5" fill="{$warna}" opacity="0.5"/>
  </g>
  <!-- Strip Blister di Depan -->
  <g filter="url(#sh)">
    <rect x="115" y="160" width="225" height="110" rx="14" fill="#E2E8F0" stroke="#94A3B8" stroke-width="2"/>
    <rect x="122" y="167" width="211" height="96" rx="10" fill="#F1F5F9" stroke="#CBD5E1" stroke-width="1"/>
    <!-- Baris Pil 1 -->
    <circle cx="152" cy="195" r="14" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
    <circle cx="190" cy="195" r="14" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
    <circle cx="228" cy="195" r="14" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
    <circle cx="266" cy="195" r="14" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
    <circle cx="304" cy="195" r="14" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
    <!-- Baris Pil 2 -->
    <circle cx="152" cy="235" r="14" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
    <circle cx="190" cy="235" r="14" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
    <circle cx="228" cy="235" r="14" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
    <circle cx="266" cy="235" r="14" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
    <circle cx="304" cy="235" r="14" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
  </g>
</svg>
SVG;
        }

        // === KEMASAN BOTOL VITAMIN ===
        if ($jenis === 'botol_vitamin') {
            return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="400" height="320" viewBox="0 0 400 320">
  <defs>
    <filter id="sh" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="8" stdDeviation="7" flood-color="#0F172A" flood-opacity="0.14"/>
    </filter>
  </defs>
  <rect width="400" height="320" fill="#FFFFFF"/>
  <g filter="url(#sh)">
    <!-- Tutup Botol -->
    <rect x="148" y="34" width="104" height="32" rx="6" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
    <line x1="162" y1="38" x2="162" y2="62" stroke="#E2E8F0" stroke-width="3"/>
    <line x1="176" y1="38" x2="176" y2="62" stroke="#E2E8F0" stroke-width="3"/>
    <line x1="190" y1="38" x2="190" y2="62" stroke="#E2E8F0" stroke-width="3"/>
    <line x1="204" y1="38" x2="204" y2="62" stroke="#E2E8F0" stroke-width="3"/>
    <line x1="218" y1="38" x2="218" y2="62" stroke="#E2E8F0" stroke-width="3"/>
    <line x1="232" y1="38" x2="232" y2="62" stroke="#E2E8F0" stroke-width="3"/>
    <!-- Leher -->
    <rect x="158" y="66" width="84" height="14" fill="#F1F5F9" stroke="#CBD5E1" stroke-width="1.5"/>
    <!-- Badan Botol -->
    <rect x="118" y="78" width="164" height="206" rx="24" fill="#F8FAFC" stroke="#CBD5E1" stroke-width="2"/>
    <!-- Label -->
    <rect x="120" y="112" width="160" height="140" fill="#FFFFFF" stroke="#E2E8F0" stroke-width="1"/>
    <rect x="120" y="112" width="160" height="32" fill="{$warna}"/>
    <text x="200" y="133" text-anchor="middle" font-family="Arial, sans-serif" font-size="11" font-weight="700" fill="#FFFFFF" letter-spacing="1">DAILY SUPPLEMENT</text>
    <text x="200" y="176" text-anchor="middle" font-family="Arial, sans-serif" font-size="17" font-weight="700" fill="#0F172A">{$line1}</text>
    <text x="200" y="198" text-anchor="middle" font-family="Arial, sans-serif" font-size="14" font-weight="600" fill="{$warna}">{$line2}</text>
    <rect x="148" y="214" width="104" height="22" rx="11" fill="{$warna}" opacity="0.14"/>
    <text x="200" y="229" text-anchor="middle" font-family="Arial, sans-serif" font-size="11" font-weight="700" fill="{$warna}">{$safeDosis}</text>
  </g>
</svg>
SVG;
        }

        // === KEMASAN KOTAK + BOTOL SIRUP ===
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="400" height="320" viewBox="0 0 400 320">
  <defs>
    <filter id="sh" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="7" stdDeviation="6" flood-color="#0F172A" flood-opacity="0.14"/>
    </filter>
  </defs>
  <rect width="400" height="320" fill="#FFFFFF"/>
  <!-- Kotak Sirup di Belakang -->
  <g filter="url(#sh)">
    <rect x="85" y="42" width="138" height="236" rx="8" fill="#FFFFFF" stroke="#CBD5E1" stroke-width="2"/>
    <rect x="85" y="42" width="138" height="46" rx="6" fill="{$warna}"/>
    <circle cx="198" cy="65" r="10" fill="#16A34A" stroke="#FFFFFF" stroke-width="2"/>
    <text x="100" y="120" font-family="Arial, sans-serif" font-size="16" font-weight="700" fill="#0F172A">{$line1}</text>
    <text x="100" y="142" font-family="Arial, sans-serif" font-size="14" font-weight="600" fill="{$warna}">{$line2}</text>
    <text x="100" y="255" font-family="Arial, sans-serif" font-size="12" font-weight="600" fill="#64748B">{$safeDosis}</text>
  </g>
  <!-- Botol Kaca Amber di Depan -->
  <g filter="url(#sh)">
    <rect x="244" y="64" width="44" height="22" rx="4" fill="#FFFFFF" stroke="#94A3B8" stroke-width="2"/>
    <path d="M248 86 L284 86 L296 116 L236 116 Z" fill="#78350F"/>
    <rect x="220" y="114" width="92" height="164" rx="16" fill="#78350F" stroke="#451A03" stroke-width="2"/>
    <!-- Highlight Kaca -->
    <rect x="230" y="124" width="8" height="140" rx="4" fill="#FFFFFF" opacity="0.2"/>
    <!-- Label Botol -->
    <rect x="222" y="148" width="88" height="96" fill="#FFFFFF"/>
    <rect x="222" y="148" width="88" height="16" fill="{$warna}"/>
    <text x="266" y="188" text-anchor="middle" font-family="Arial, sans-serif" font-size="11" font-weight="700" fill="#0F172A">{$line1}</text>
    <text x="266" y="205" text-anchor="middle" font-family="Arial, sans-serif" font-size="10" fill="{$warna}">{$line2}</text>
    <text x="266" y="232" text-anchor="middle" font-family="Arial, sans-serif" font-size="9" font-weight="700" fill="#64748B">{$safeDosis}</text>
  </g>
</svg>
SVG;
    }
}