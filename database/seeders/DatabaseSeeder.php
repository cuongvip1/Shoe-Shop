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
   
        $this->call(DanhGiaTableSeeder::class);
        $this->call(DonHangTableSeeder::class);
        $this->call(FailedJobsTableSeeder::class);
        $this->call(GiayTableSeeder::class);
        $this->call(KhuyenMaiTableSeeder::class);
        $this->call(LoaiGiayTableSeeder::class);
        $this->call(MigrationsTableSeeder::class);
        $this->call(PasswordResetsTableSeeder::class);
        $this->call(PersonalAccessTokensTableSeeder::class);
        $this->call(PhanQuyenTableSeeder::class);
        $this->call(SessionsTableSeeder::class);
        $this->call(SizeTableSeeder::class);
        $this->call(ThuongHieuTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
