<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SizeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('size')->delete();
        
        \DB::table('size')->insert(array (
            0 => 
            array (
                'id' => 1,
                'so_size' => 35,
            ),
            1 => 
            array (
                'id' => 2,
                'so_size' => 36,
            ),
            2 => 
            array (
                'id' => 3,
                'so_size' => 37,
            ),
            3 => 
            array (
                'id' => 4,
                'so_size' => 38,
            ),
            4 => 
            array (
                'id' => 5,
                'so_size' => 39,
            ),
            5 => 
            array (
                'id' => 6,
                'so_size' => 40,
            ),
            6 => 
            array (
                'id' => 7,
                'so_size' => 41,
            ),
            7 => 
            array (
                'id' => 8,
                'so_size' => 42,
            ),
            8 => 
            array (
                'id' => 9,
                'so_size' => 43,
            ),
            9 => 
            array (
                'id' => 10,
                'so_size' => 44,
            ),
            10 => 
            array (
                'id' => 11,
                'so_size' => 45,
            ),
        ));
        
        
    }
}