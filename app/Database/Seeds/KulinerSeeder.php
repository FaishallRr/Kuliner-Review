<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

/**
 * KulinerSeeder: Mengisi database dengan data lokasi kuliner nyata
 * di sekitar kampus UDINUS Semarang menggunakan Faker.
 */
class KulinerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create('id_ID');

        $this->seedUsers($faker);
        $this->seedCategories();
        $this->seedTags();
        $this->seedPlaces($faker);
        $this->seedPlaceTags();
        $this->seedReviews($faker);
        $this->seedFavorites($faker);
        $this->seedNotifications($faker);
    }

    /**
     * Seed tabel users: 1 admin + 4 contributor.
     */
    private function seedUsers($faker): void
    {
        $users = [
            [
                'username'  => 'admin',
                'email'     => 'admin@udinus.ac.id',
                'password'  => 'admin123',
                'full_name' => 'Administrator UDINUS',
                'role'      => 'admin',
            ],
            [
                'username'  => 'budi_s',
                'email'     => 'budi@student.udinus.ac.id',
                'password'  => 'contrib123',
                'full_name' => 'Budi Santoso',
                'role'      => 'contributor',
            ],
            [
                'username'  => 'siti_n',
                'email'     => 'siti@student.udinus.ac.id',
                'password'  => 'contrib123',
                'full_name' => 'Siti Nurhaliza',
                'role'      => 'contributor',
            ],
            [
                'username'  => 'rendi_p',
                'email'     => 'rendi@student.udinus.ac.id',
                'password'  => 'contrib123',
                'full_name' => 'Rendi Prasetyo',
                'role'      => 'contributor',
            ],
            [
                'username'  => 'dewi_a',
                'email'     => 'dewi@student.udinus.ac.id',
                'password'  => 'contrib123',
                'full_name' => 'Dewi Anggraini',
                'role'      => 'contributor',
            ],
        ];

        $userModel = model('App\Models\UserModel');
        foreach ($users as $user) {
            $userModel->insert($user);
        }
    }

    /**
     * Seed tabel categories: kategori kuliner khas Semarang.
     */
    private function seedCategories(): void
    {
        $categories = [
            ['name' => 'Angkringan',    'slug' => 'angkringan',    'description' => 'Warung kecil yang menjual makanan dan minuman tradisional dengan harga terjangkau.'],
            ['name' => 'Warung Makan',  'slug' => 'warung-makan',  'description' => 'Warung nasi dan lauk tradisional Indonesia.'],
            ['name' => 'Kedai Kopi',    'slug' => 'kedai-kopi',    'description' => 'Tempat nongkrong dengan kopi dan snack ringan.'],
            ['name' => 'Jajanan Kaki Lima', 'slug' => 'jajanan-kaki-lima', 'description' => 'Penjual keliling dengan gerobak dan tenda di pinggir jalan.'],
            ['name' => 'Rumah Makan',   'slug' => 'rumah-makan',   'description' => 'Restoran skala menengah dengan menu lengkap khas Jawa Tengah.'],
            ['name' => 'Café & Resto',  'slug' => 'cafe-resto',    'description' => 'Tempat makan modern dengan konsep dan suasana nyaman.'],
        ];

        $categoryModel = model('App\Models\CategoryModel');
        foreach ($categories as $category) {
            $categoryModel->insert($category);
        }
    }

    /**
     * Seed tabel tags: label populer untuk kuliner Semarang.
     */
    private function seedTags(): void
    {
        $tags = [
            ['name' => 'Murah Meriah',     'slug' => 'murah-meriah'],
            ['name' => 'Halal',            'slug' => 'halal'],
            ['name' => '24 Jam',           'slug' => '24-jam'],
            ['name' => 'Wifi Gratis',      'slug' => 'wifi-gratis'],
            ['name' => 'Parkir Luas',      'slug' => 'parkir-luas'],
            ['name' => 'Nggak Lesehan',    'slug' => 'nggak-lesehan'],
            ['name' => 'Spesial Semarang',  'slug' => 'spesial-semarang'],
            ['name' => 'Tahan Lama',       'slug' => 'tahan-lama'],
            ['name' => 'Pedas Level Dewa', 'slug' => 'pedas-level-dewa'],
            ['name' => 'Recommended',       'slug' => 'recommended'],
        ];

        $tagModel = model('App\Models\TagModel');
        foreach ($tags as $tag) {
            $tagModel->insert($tag);
        }
    }

    /**
     * Seed tabel places: 24 lokasi kuliner nyata sekitar UDINUS Semarang.
     * Koordinat latitude/longitude disesuaikan dengan lokasi asli.
     */
    private function seedPlaces($faker): void
    {
        $places = [
            [
                'name'        => 'Angkringan Pendopo Semarang',
                'slug'        => 'angkringan-pendopo-semarang',
                'description' => 'Angkringan legendaris dekat Tugu Muda dengan menu nasi kucing, sate usus, dan kopi jos.',
                'address'     => 'Jl. Imam Bonjol No.1, Pendopo, Semarang',
                'latitude'    => -6.9840,
                'longitude'   => 110.4075,
                'category_id' => 1,
                'user_id'     => 2,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Warung Makan Bu Siti',
                'slug'        => 'warung-makan-bu-siti',
                'description' => 'Warung nasi yang legendaris di Simpang Lima dengan nasi ayam semur dan soto kudus.',
                'address'     => 'Jl. Gajah Mada, Semarang',
                'latitude'    => -6.9835,
                'longitude'   => 110.4110,
                'category_id' => 2,
                'user_id'     => 2,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Kedai Kopi Kothak',
                'slug'        => 'kedai-kopi-kothak',
                'description' => 'Kedai kopi kece di dekat UDINUS dengan racikan kopi nusantara dan suasana cozy.',
                'address'     => 'Jl. Nakulo, Pleburan, Semarang',
                'latitude'    => -6.9785,
                'longitude'   => 110.4085,
                'category_id' => 3,
                'user_id'     => 3,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Wedang Ronde Keroncong',
                'slug'        => 'wedang-ronde-keroncong',
                'description' => 'Tempat wedang ronde legendaris dekat Pasar Johar dengan suara keroncong setiap malam.',
                'address'     => 'Jl. Petudungan, Pekunden, Semarang',
                'latitude'    => -6.9705,
                'longitude'   => 110.4225,
                'category_id' => 4,
                'user_id'     => 4,
                'status'      => 'approved',
            ],
            [
                'name'        => 'RM Padang Sederhana Simpang Lima',
                'slug'        => 'rm-padang-sederhana-simpang-lima',
                'description' => 'Rumah makan Padang populer di Simpang Lima dengan rendang dan gulai ayam khas Minang.',
                'address'     => 'Jl. Pandanaran, Semarang',
                'latitude'    => -6.9830,
                'longitude'   => 110.4145,
                'category_id' => 5,
                'user_id'     => 5,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Café Batavia Semarang',
                'slug'        => 'cafe-batavia-semarang',
                'description' => 'Café modern di kawasan Old City Semarang dengan menu fusion dan view sungai.',
                'address'     => 'Jl. Kepodang, Semarang',
                'latitude'    => -6.9685,
                'longitude'   => 110.4240,
                'category_id' => 6,
                'user_id'     => 3,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Lumpia Gang Lombok',
                'slug'        => 'lumpia-gang-lombok',
                'description' => 'Lumpia Semarang paling terkenal di Gang Lombok dengan isian rebung udang yang khas.',
                'address'     => 'Jl. Gang Lombok No.11, Semarang',
                'latitude'    => -6.9710,
                'longitude'   => 110.4260,
                'category_id' => 4,
                'user_id'     => 2,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Soto Bangkong Semarang',
                'slug'        => 'soto-bangkong-semarang',
                'description' => 'Soto ayam bangkong yang sudah ada sejak tahun 1950an dengan kuah bening.',
                'address'     => 'Jl. Pemuda No.116, Semarang',
                'latitude'    => -6.9770,
                'longitude'   => 110.4175,
                'category_id' => 4,
                'user_id'     => 4,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Tahu Pong Semarang',
                'slug'        => 'tahu-pong-semarang',
                'description' => 'Tahu pong khas Semarang dengan isian tenggiri yang gurih dan renyah.',
                'address'     => 'Jl. Letjend Suprapto, Semarang',
                'latitude'    => -6.9725,
                'longitude'   => 110.4190,
                'category_id' => 4,
                'user_id'     => 5,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Nasi Ayam Bu Yuti',
                'slug'        => 'nasi-ayam-bu-yuti',
                'description' => 'Nasi ayam semur khas Semarang dengan ayam kampung yang empuk dan kuah semur manis.',
                'address'     => 'Jl. Pandanaran No.55, Semarang',
                'latitude'    => -6.9825,
                'longitude'   => 110.4140,
                'category_id' => 2,
                'user_id'     => 5,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Es Campur Pak Min Semarang',
                'slug'        => 'es-campur-pak-min-semarang',
                'description' => 'Es campur segar dengan cincau, kolang-kaling, dan sirup gula merah khas Semarang.',
                'address'     => 'Jl. Pemuda No.70, Semarang',
                'latitude'    => -6.9780,
                'longitude'   => 110.4170,
                'category_id' => 4,
                'user_id'     => 2,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Warung Kopi Beta',
                'slug'        => 'warung-kopi-beta',
                'description' => 'Kedai kopi tradisional dengan kopi tubruk dan roti bakar di kawasan Pleburan dekat UDINUS.',
                'address'     => 'Jl. Pleburan No.12, Semarang',
                'latitude'    => -6.9790,
                'longitude'   => 110.4070,
                'category_id' => 3,
                'user_id'     => 3,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Mie Ayam Pak Bro',
                'slug'        => 'mie-ayam-pak-bro',
                'description' => 'Mie ayam dan bakso dengan kuah kaldu sapi yang gurih dekat kampus UDINUS.',
                'address'     => 'Jl. Nakulo Barat No.8, Semarang',
                'latitude'    => -6.9792,
                'longitude'   => 110.4078,
                'category_id' => 2,
                'user_id'     => 4,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Angkringan Pak Kumut',
                'slug'        => 'angkringan-pak-kumut',
                'description' => 'Angkringan favorit mahasiswa UDINUS dengan nasi kucing, sate usus, dan kopi saring.',
                'address'     => 'Jl. Imam Bonjol, Pleburan, Semarang',
                'latitude'    => -6.9786,
                'longitude'   => 110.4080,
                'category_id' => 1,
                'user_id'     => 2,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Sego Abang Bu Karto',
                'slug'        => 'sego-abang-bu-karto',
                'description' => 'Nasi merah (sego abang) khas Semarang dengan lauk ayam suwir dan sambal terasi.',
                'address'     => 'Jl. Purwandono, Semarang',
                'latitude'    => -6.9775,
                'longitude'   => 110.4105,
                'category_id' => 5,
                'user_id'     => 3,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Pecel Pincuk Bu Darmi',
                'slug'        => 'pecel-pincuk-bu-darmi',
                'description' => 'Pecel sayuran dengan bumbu kacang dan lontong yang gurih di kawasan UDINUS.',
                'address'     => 'Jl. Dr. Cipto No.22, Semarang',
                'latitude'    => -6.9790,
                'longitude'   => 110.4095,
                'category_id' => 2,
                'user_id'     => 5,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Kedai 78 Pleburan',
                'slug'        => 'kedai-78-pleburan',
                'description' => 'Kedai kekinian dekat UDINUS dengan menu kopi spesial dan nasi goreng kambing.',
                'address'     => 'Jl. Pleburan Raya No.78, Semarang',
                'latitude'    => -6.9782,
                'longitude'   => 110.4065,
                'category_id' => 6,
                'user_id'     => 4,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Bah Kresna Njumplik',
                'slug'        => 'bah-kresna-njumplik',
                'description' => 'Warung makan yang terkenal dengan masakan Jawa autentik dan porsi besar.',
                'address'     => 'Jl. Nangka No.5, Semarang',
                'latitude'    => -6.9750,
                'longitude'   => 110.4130,
                'category_id' => 5,
                'user_id'     => 4,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Es Dawet Bu Kumini',
                'slug'        => 'es-dawet-bu-kumini',
                'description' => 'Es dawet Ireng khas Semarang dengan santan dan gula merah yang legit.',
                'address'     => 'Jl. Pandanaran No.30, Semarang',
                'latitude'    => -6.9828,
                'longitude'   => 110.4142,
                'category_id' => 4,
                'user_id'     => 5,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Martabak Pec Semarang',
                'slug'        => 'martabak-pec-semarang',
                'description' => 'Martabak manis dan telur legendaris yang buka dari sore hingga larut malam.',
                'address'     => 'Jl. Pemuda No.90, Semarang',
                'latitude'    => -6.9778,
                'longitude'   => 110.4180,
                'category_id' => 4,
                'user_id'     => 2,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Warung Lontong Dekat UDINUS',
                'slug'        => 'warung-lontong-dekat-udinus',
                'description' => 'Lontong sayur dan lontong opor dengan rasa rumahan dekat gerbang UDINUS.',
                'address'     => 'Jl. Nakulo Timur No.15, Semarang',
                'latitude'    => -6.9788,
                'longitude'   => 110.4088,
                'category_id' => 2,
                'user_id'     => 3,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Bakso Urat Pak Dhe',
                'slug'        => 'bakso-urat-pak-dhe',
                'description' => 'Bakso urat kenyal dengan kuah sapi gurih, favorit mahasiswa UDINUS saat hujan.',
                'address'     => 'Jl. Pleburan IV No.3, Semarang',
                'latitude'    => -6.9780,
                'longitude'   => 110.4072,
                'category_id' => 2,
                'user_id'     => 5,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Jenang Kudus Bu Warni',
                'slug'        => 'jenang-kudus-bu-warni',
                'description' => 'Jenang (dodol) Kudus khas yang bisa dibawa sebagai oleh-oleh dari Semarang.',
                'address'     => 'Jl. MT Haryono No.40, Semarang',
                'latitude'    => -6.9795,
                'longitude'   => 110.4115,
                'category_id' => 4,
                'user_id'     => 4,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Nasi Goreng Babat Simpang Lima',
                'slug'        => 'nasi-goreng-babat-simpang-lima',
                'description' => 'Nasi goreng babat dengan bumbu khas yang buka dari malam hingga subuh.',
                'address'     => 'Jl. Pandanaran, Simpang Lima, Semarang',
                'latitude'    => -6.9832,
                'longitude'   => 110.4148,
                'category_id' => 4,
                'user_id'     => 2,
                'status'      => 'approved',
            ],
            [
                'name'        => 'Warung Gudeg Bu Harti',
                'slug'        => 'warung-gudeg-bu-harti',
                'description' => 'Gudeg khas Jogja yang legendaris di daasan Semarang, buka dari pagi hingga siang.',
                'address'     => 'Jl. Dr. Wahidin No.15, Semarang',
                'latitude'    => -6.9710,
                'longitude'   => 110.4180,
                'category_id' => 2,
                'user_id'     => 3,
                'status'      => 'pending',
            ],
            [
                'name'        => 'Kedai Susu Lembu Jantan',
                'slug'        => 'kedai-susu-lembu-jantan',
                'description' => 'Kedai susu segar dengan varian rasa unik dekat kampus UDINUS.',
                'address'     => 'Jl. Nakulo Selatan No.22, Semarang',
                'latitude'    => -6.9795,
                'longitude'   => 110.4090,
                'category_id' => 6,
                'user_id'     => 4,
                'status'      => 'pending',
            ],
            [
                'name'        => 'Warung Bebek Goreng Pak Kempli',
                'slug'        => 'warung-bebek-goreng-pak-kempli',
                'description' => 'Bebek goreng kremes dengan sambal korek yang pedas menggugah selera.',
                'address'     => 'Jl. Mataram No.10, Semarang',
                'latitude'    => -6.9715,
                'longitude'   => 110.4165,
                'category_id' => 5,
                'user_id'     => 5,
                'status'      => 'rejected',
                'rejection_note' => 'Data lokasi tidak valid: alamat tidak ditemukan di peta dan foto tidak sesuai dengan lokasi sebenarnya.',
            ],
        ];

        $placeModel = model('App\Models\PlaceModel');
        foreach ($places as $place) {
            $placeModel->skipValidation(true)->insert($place);
        }
    }

    /**
     * Seed tabel place_tags: hubungkan tempat dengan tag.
     */
    private function seedPlaceTags(): void
    {
        $placeTags = [
            ['place_id' => 1,  'tag_id' => 1],
            ['place_id' => 1,  'tag_id' => 2],
            ['place_id' => 1,  'tag_id' => 3],
            ['place_id' => 2,  'tag_id' => 1],
            ['place_id' => 2,  'tag_id' => 2],
            ['place_id' => 2,  'tag_id' => 10],
            ['place_id' => 3,  'tag_id' => 4],
            ['place_id' => 3,  'tag_id' => 10],
            ['place_id' => 4,  'tag_id' => 7],
            ['place_id' => 4,  'tag_id' => 1],
            ['place_id' => 5,  'tag_id' => 2],
            ['place_id' => 5,  'tag_id' => 5],
            ['place_id' => 5,  'tag_id' => 10],
            ['place_id' => 6,  'tag_id' => 4],
            ['place_id' => 6,  'tag_id' => 10],
            ['place_id' => 7,  'tag_id' => 7],
            ['place_id' => 7,  'tag_id' => 8],
            ['place_id' => 7,  'tag_id' => 1],
            ['place_id' => 8,  'tag_id' => 2],
            ['place_id' => 8,  'tag_id' => 10],
            ['place_id' => 9,  'tag_id' => 7],
            ['place_id' => 9,  'tag_id' => 1],
            ['place_id' => 10, 'tag_id' => 2],
            ['place_id' => 10, 'tag_id' => 10],
            ['place_id' => 11, 'tag_id' => 1],
            ['place_id' => 11, 'tag_id' => 7],
            ['place_id' => 12, 'tag_id' => 4],
            ['place_id' => 12, 'tag_id' => 1],
            ['place_id' => 13, 'tag_id' => 2],
            ['place_id' => 13, 'tag_id' => 1],
            ['place_id' => 14, 'tag_id' => 1],
            ['place_id' => 14, 'tag_id' => 3],
            ['place_id' => 14, 'tag_id' => 9],
            ['place_id' => 15, 'tag_id' => 2],
            ['place_id' => 15, 'tag_id' => 7],
            ['place_id' => 16, 'tag_id' => 2],
            ['place_id' => 16, 'tag_id' => 1],
            ['place_id' => 17, 'tag_id' => 4],
            ['place_id' => 17, 'tag_id' => 10],
            ['place_id' => 18, 'tag_id' => 2],
            ['place_id' => 18, 'tag_id' => 5],
            ['place_id' => 18, 'tag_id' => 10],
            ['place_id' => 19, 'tag_id' => 7],
            ['place_id' => 19, 'tag_id' => 1],
            ['place_id' => 20, 'tag_id' => 3],
            ['place_id' => 20, 'tag_id' => 9],
            ['place_id' => 21, 'tag_id' => 2],
            ['place_id' => 21, 'tag_id' => 1],
            ['place_id' => 22, 'tag_id' => 1],
            ['place_id' => 22, 'tag_id' => 2],
            ['place_id' => 22, 'tag_id' => 10],
            ['place_id' => 23, 'tag_id' => 7],
            ['place_id' => 23, 'tag_id' => 8],
            ['place_id' => 24, 'tag_id' => 3],
            ['place_id' => 24, 'tag_id' => 9],
            ['place_id' => 25, 'tag_id' => 2],
            ['place_id' => 25, 'tag_id' => 10],
            ['place_id' => 26, 'tag_id' => 4],
            ['place_id' => 26, 'tag_id' => 1],
            ['place_id' => 27, 'tag_id' => 9],
            ['place_id' => 27, 'tag_id' => 2],
        ];

        $this->db->table('place_tags')->insertBatch($placeTags);
    }

    /**
     * Seed tabel reviews: ulasan acak dari contributor.
     */
    private function seedReviews($faker): void
    {
        $reviews = [];

        $comments = [
            'Tempatnya nyaman dan makanannya enak banget!',
            'Harganya sangat terjangkau untuk mahasiswa.',
            'Porsinya banyak, rasa bersaing!',
            'Suasana asik buat nongkrong bareng teman.',
            'Kopinya mantap, pelayanannya ramah.',
            'Favorit sejak zaman kuliah dulu.',
            'Tempatnya bersih dan rapi.',
            'Rumahan banget rasanya, suka!',
            'Menu andalan tiap lebaran.',
            'Recommended banget buat yang suka makan enak murah.',
        ];

        for ($placeId = 1; $placeId <= 27; $placeId++) {
            $reviewCount = rand(2, 5);
            $usedUsers = [];
            for ($i = 0; $i < $reviewCount; $i++) {
                $userId = rand(2, 5);
                $key = "{$userId}-{$placeId}";
                if (in_array($key, $usedUsers, true)) {
                    continue;
                }
                $usedUsers[] = $key;

                $reviews[] = [
                    'user_id'     => $userId,
                    'place_id'    => $placeId,
                    'rating'      => rand(1, 5),
                    'comment'     => $comments[array_rand($comments)],
                    'created_at'  => $faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d H:i:s'),
                    'updated_at'  => $faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d H:i:s'),
                ];
            }
        }

        $this->db->table('reviews')->insertBatch($reviews);
    }

    /**
     * Seed tabel favorites: favorit acak dari contributor.
     */
    private function seedFavorites($faker): void
    {
        $favorites = [];
        $usedPairs = [];

        for ($i = 0; $i < 30; $i++) {
            $userId  = rand(2, 5);
            $placeId = rand(1, 27);
            $key     = "{$userId}-{$placeId}";

            if (in_array($key, $usedPairs, true)) {
                continue;
            }

            $usedPairs[] = $key;

            $favorites[] = [
                'user_id'    => $userId,
                'place_id'   => $placeId,
                'created_at' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d H:i:s'),
            ];
        }

        $this->db->table('favorites')->insertBatch($favorites);
    }

    /**
     * Seed tabel notifications: notifikasi moderasi untuk contributor.
     */
    private function seedNotifications($faker): void
    {
        $notifications = [
            [
                'user_id'    => 2,
                'title'      => 'Tempat Disetujui',
                'message'    => 'Tempat kuliner "Angkringan Pendopo Semarang" telah disetujui admin dan sekarang tampil publik.',
                'is_read'    => 1,
                'created_at' => $faker->dateTimeBetween('-2 weeks', '-1 week')->format('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 3,
                'title'      => 'Tempat Disetujui',
                'message'    => 'Tempat kuliner "Kedai Kopi Kothak" telah disetujui admin dan sekarang tampil publik.',
                'is_read'    => 1,
                'created_at' => $faker->dateTimeBetween('-2 weeks', '-1 week')->format('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 4,
                'title'      => 'Tempat Disetujui',
                'message'    => 'Tempat kuliner "Wedang Ronde Keroncong" telah disetujui admin dan sekarang tampil publik.',
                'is_read'    => 0,
                'created_at' => $faker->dateTimeBetween('-3 days', 'now')->format('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 5,
                'title'      => 'Tempat Ditolak',
                'message'    => 'Tempat kuliner "Warung Baru" ditolak admin. Alasan: Data lokasi tidak valid dan alamat kurang jelas.',
                'is_read'    => 0,
                'created_at' => $faker->dateTimeBetween('-1 day', 'now')->format('Y-m-d H:i:s'),
            ],
            [
                'user_id'    => 2,
                'title'      => 'Tempat Disetujui',
                'message'    => 'Tempat kuliner "Lumpia Gang Lombok" telah disetujui admin dan sekarang tampil publik.',
                'is_read'    => 0,
                'created_at' => $faker->dateTimeBetween('-1 day', 'now')->format('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('notifications')->insertBatch($notifications);
    }
}