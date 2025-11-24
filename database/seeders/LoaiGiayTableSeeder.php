<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LoaiGiayTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('loai_giay')->delete();
        
        \DB::table('loai_giay')->insert(array (
            0 => 
            array (
                'id_loai_giay' => 1,
                'ten_loai_giay' => 'Thể thao',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id_loai_giay' => 2,
                'ten_loai_giay' => 'Sandanl',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id_loai_giay' => 3,
                'ten_loai_giay' => 'Sneaker',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id_loai_giay' => 4,
                'ten_loai_giay' => 'Boots',
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}