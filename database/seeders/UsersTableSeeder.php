<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'ten_nguoi_dung' => 'Admin',
                'email' => 'admin@gmail.com',
                'sdt' => '0123456789',
                'Ten_dang_nhap' => 'admin',
                'password' => '$2y$10$UZzte57n35euYs01U./6P.o9X3fv0cklbH7k40/T2SRSvDSnQ6Xem',
                'id_phan_quyen' => '1',
            ),
            1 => 
            array (
                'id' => 2,
                'ten_nguoi_dung' => 'Trần Duy Bảo',
                'email' => 'bao@gmail.com',
                'sdt' => '0123456788',
                'Ten_dang_nhap' => 'bao',
                'password' => '$2y$10$RNPLYXZ/Fs84IIoK0HPyMOlnhBWcukhXUMVlu.PLEQ4rwKpWSVef.',
                'id_phan_quyen' => '2',
            ),
            2 => 
            array (
                'id' => 12,
                'ten_nguoi_dung' => 'thoai',
                'email' => 'thoai@gmail.com',
                'sdt' => '09887654321',
                'Ten_dang_nhap' => 'thoai',
                'password' => '$2y$10$6JkWljrBXR91xNzI9lyUiOrPkyv7dBDS4kBvnxxCcChR4zVV9MRq2',
                'id_phan_quyen' => '2',
            ),
        ));
        
        
    }
}