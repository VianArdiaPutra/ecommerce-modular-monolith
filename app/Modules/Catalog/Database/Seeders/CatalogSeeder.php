<?php

namespace App\Modules\Catalog\Database\Seeders;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            'Elektronik',
            'Fashion',
            'Rumah Tangga',
            'Olahraga',
            'Buku',
            'Aksesoris',
        ])->mapWithKeys(fn (string $name) => [
            $name => Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name],
            ),
        ]);

        foreach ($this->products() as [$name, $description, $price, $stock, $categoryNames, $isActive]) {
            $product = Product::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'description' => $description,
                    'price' => $price,
                    'stock' => $stock,
                    'is_active' => $isActive,
                ],
            );

            $product->categories()->sync($categories->only($categoryNames)->pluck('id'));
        }
    }

    /**
     * Data produk: [nama, deskripsi, harga, stok, kategori, aktif].
     *
     * @return array<int, array{0: string, 1: string, 2: int, 3: int, 4: array<int, string>, 5: bool}>
     */
    private function products(): array
    {
        return [
            // Elektronik
            ['Headphone Nirkabel Noise Cancelling', 'Headphone over-ear dengan peredam bising aktif dan baterai tahan hingga 30 jam.', 899000, 25, ['Elektronik', 'Aksesoris'], true],
            ['Power Bank 20.000 mAh', 'Power bank berkapasitas besar dengan dua port USB dan pengisian cepat.', 349000, 0, ['Elektronik'], true],
            ['Speaker Bluetooth Mini', 'Speaker portabel tahan cipratan air dengan suara jernih dan bass yang padat.', 279000, 40, ['Elektronik'], true],
            ['Keyboard Mekanik Kompak', 'Keyboard 75% dengan switch taktil dan lampu latar putih hangat.', 599000, 18, ['Elektronik'], true],

            // Fashion
            ['Kaos Katun Oversize Krem', 'Kaos katun tebal berpotongan longgar, nyaman dipakai sehari-hari.', 129000, 60, ['Fashion'], true],
            ['Jaket Denim Klasik', 'Jaket denim berwarna biru tua dengan potongan klasik yang mudah dipadukan.', 459000, 22, ['Fashion'], true],
            ['Sepatu Sneakers Putih', 'Sneakers putih polos dengan sol empuk untuk aktivitas harian.', 389000, 35, ['Fashion'], true],
            ['Kemeja Linen Lengan Panjang', 'Kemeja linen ringan yang adem untuk cuaca panas.', 249000, 15, ['Fashion'], false],

            // Rumah Tangga
            ['Lampu Meja Keramik', 'Lampu meja berbahan keramik dengan cahaya hangat yang menenangkan.', 189000, 20, ['Rumah Tangga'], true],
            ['Set Cangkir Keramik 4 Pcs', 'Satu set cangkir keramik bertekstur matte, cocok untuk kopi dan teh.', 139000, 45, ['Rumah Tangga'], true],
            ['Selimut Rajut Tebal', 'Selimut rajut lembut berwarna netral untuk sofa dan tempat tidur.', 245000, 28, ['Rumah Tangga'], true],
            ['Rak Dinding Kayu Minimalis', 'Rak dinding kayu solid untuk buku, tanaman, dan pajangan kecil.', 175000, 32, ['Rumah Tangga'], true],

            // Olahraga
            ['Dumbbell Neoprene 5 kg', 'Dumbbell berlapis neoprene anti selip, dijual per buah.', 159000, 50, ['Olahraga'], true],
            ['Matras Yoga Anti Slip', 'Matras yoga tebal 6 mm dengan permukaan anti selip dan tali pengikat.', 129000, 38, ['Olahraga'], true],
            ['Botol Minum Stainless 750 ml', 'Botol minum stainless yang menjaga minuman tetap dingin hingga 24 jam.', 99000, 70, ['Olahraga', 'Rumah Tangga'], true],
            ['Tali Skipping Speed', 'Tali lompat dengan bearing halus dan panjang yang bisa diatur.', 49000, 80, ['Olahraga'], true],

            // Buku
            ['Novel Fiksi Sejarah Nusantara', 'Novel berlatar kerajaan Nusantara yang mengisahkan persahabatan dan pengkhianatan.', 89000, 30, ['Buku'], true],
            ['Panduan Dasar Pemrograman Web', 'Buku pengantar HTML, CSS, dan JavaScript untuk pemula.', 125000, 24, ['Buku'], true],
            ['Buku Resep Masakan Rumahan', 'Kumpulan 100 resep masakan rumahan dengan langkah yang mudah diikuti.', 95000, 26, ['Buku'], true],
            ['Jurnal Harian Sampul Keras', 'Jurnal polos bersampul keras dengan kertas krem 120 gsm.', 69000, 55, ['Buku', 'Aksesoris'], true],

            // Aksesoris
            ['Jam Tangan Analog Klasik', 'Jam tangan analog dengan tali kulit dan bodi tipis yang elegan.', 425000, 16, ['Aksesoris'], true],
            ['Tas Selempang Kulit', 'Tas selempang berbahan kulit sintetis dengan penutup magnet dan tali yang bisa diatur.', 349000, 21, ['Aksesoris', 'Fashion'], true],
            ['Kacamata Hitam Retro', 'Kacamata hitam berbingkai retro dengan lensa anti UV.', 159000, 42, ['Aksesoris'], true],
            ['Dompet Kulit Lipat', 'Dompet lipat dengan banyak slot kartu dan kantong uang kertas.', 175000, 36, ['Aksesoris', 'Fashion'], true],
        ];
    }
}
