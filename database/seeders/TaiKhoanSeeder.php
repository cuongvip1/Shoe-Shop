<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\Hash;


class TaiKhoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('users')->insert([
            'ten_nguoi_dung' => 'Admin',
            'email' => 'admin@gmail.com',
            'sdt' => '0123456789',
            'Ten_dang_nhap' => 'admin',
            'password' => Hash::make('admin'),
            'id_phan_quyen' => '1',
        ]);

        DB::table('users')->insert([
            'ten_nguoi_dung' => 'Trần Duy Bảo',
            'email' => 'bao@gmail.com',
            'sdt' => '0123456788',
            'Ten_dang_nhap' => 'bao',
            'password' => Hash::make('123456789'),
            'id_phan_quyen' => '2',
        ]);

    }
}
	
