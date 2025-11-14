<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Local seeding disabled.
        // Data will be loaded from the API / external phpMyAdmin database instead.
        // To re-enable local seeding, uncomment the following and run `php artisan db:seed`:
        // $this->call([
        //     GiaySeeder::class,
        //     KhuyenMaiSeeder::class,
        //     ThuongHieuSeeder::class,
        //     LoaiGiaySeeder::class,
        //     PhanQuyenSeeder::class,
        //     TaiKhoanSeeder::class,
        // ]);
    }
}
