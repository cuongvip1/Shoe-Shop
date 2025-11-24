<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DanhGiaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('danh_gia')->delete();
        
        \DB::table('danh_gia')->insert(array (
            0 => 
            array (
                'id_danh_gia' => 1,
                'id_user' => '2',
                'ten_danh_gia' => 'Trần Duy Bảo',
                'danh_gia' => '4.5',
                'danh_gia_binh_luan' => 'hiiihi',
                'id_giay' => '1',
                'created_at' => '2025-11-13 21:53:48',
                'updated_at' => '2025-11-13 21:57:46',
            ),
            1 => 
            array (
                'id_danh_gia' => 2,
                'id_user' => '2',
                'ten_danh_gia' => 'Trần Duy Bảo',
                'danh_gia' => '4.5',
                'danh_gia_binh_luan' => 'hiiiii',
                'id_giay' => '1',
                'created_at' => '2025-11-13 22:04:22',
                'updated_at' => '2025-11-13 22:04:22',
            ),
        ));
        
        
    }
}