<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ThuongHieuTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('thuong_hieu')->delete();
        
        \DB::table('thuong_hieu')->insert(array (
            0 => 
            array (
                'id_thuong_hieu' => 1,
                'ten_thuong_hieu' => 'Nike',
            ),
            1 => 
            array (
                'id_thuong_hieu' => 2,
                'ten_thuong_hieu' => 'Adidas',
            ),
            2 => 
            array (
                'id_thuong_hieu' => 3,
                'ten_thuong_hieu' => 'Converse',
            ),
            3 => 
            array (
                'id_thuong_hieu' => 4,
                'ten_thuong_hieu' => 'Gucci',
            ),
            4 => 
            array (
                'id_thuong_hieu' => 5,
                'ten_thuong_hieu' => 'Puma',
            ),
            5 => 
            array (
                'id_thuong_hieu' => 6,
                'ten_thuong_hieu' => 'Vans',
            ),
            6 => 
            array (
                'id_thuong_hieu' => 7,
                'ten_thuong_hieu' => 'New Balance',
            ),
            7 => 
            array (
                'id_thuong_hieu' => 8,
                'ten_thuong_hieu' => 'Balenciaga',
            ),
            8 => 
            array (
                'id_thuong_hieu' => 9,
                'ten_thuong_hieu' => 'VietNam',
            ),
        ));
        
        
    }
}