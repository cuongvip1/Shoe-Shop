<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KhuyenMaiTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('khuyen_mai')->delete();
        
        \DB::table('khuyen_mai')->insert(array (
            0 => 
            array (
                'id_khuyen_mai' => 1,
                'ten_khuyen_mai' => 'Không khuyễn mãi',
                'gia_tri_khuyen_mai' => '0',
            ),
            1 => 
            array (
                'id_khuyen_mai' => 2,
                'ten_khuyen_mai' => 'Ngày lễ',
                'gia_tri_khuyen_mai' => '15',
            ),
            2 => 
            array (
                'id_khuyen_mai' => 3,
                'ten_khuyen_mai' => 'Mới ra mắt',
                'gia_tri_khuyen_mai' => '10',
            ),
            3 => 
            array (
                'id_khuyen_mai' => 4,
                'ten_khuyen_mai' => 'Sale cuối năm',
                'gia_tri_khuyen_mai' => '5',
            ),
            4 => 
            array (
                'id_khuyen_mai' => 5,
                'ten_khuyen_mai' => 'Hôm nay vui',
                'gia_tri_khuyen_mai' => '3',
            ),
        ));
        
        
    }
}