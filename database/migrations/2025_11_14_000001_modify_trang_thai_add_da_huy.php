<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modify enum to add 'da_huy'
        DB::statement("ALTER TABLE `don_hang` MODIFY COLUMN `trang_thai` ENUM('cho','da_xac_nhan','tu_choi','da_huy') NOT NULL DEFAULT 'cho'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // revert to previous enum without 'da_huy'
        DB::statement("ALTER TABLE `don_hang` MODIFY COLUMN `trang_thai` ENUM('cho','da_xac_nhan','tu_choi') NOT NULL DEFAULT 'cho'");
    }
};
