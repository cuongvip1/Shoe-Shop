<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PhanQuyenTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('phan_quyen')->delete();
        
        \DB::table('phan_quyen')->insert(array (
            0 => 
            array (
                'id_phan_quyen' => 1,
                'ten_phan_quyen' => 'Quản trị viên',
            ),
            1 => 
            array (
                'id_phan_quyen' => 2,
                'ten_phan_quyen' => 'Người dùng',
            ),
        ));
        
        
    }
}