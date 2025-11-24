<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('don_hang', 'trang_thai')) {
            Schema::table('don_hang', function (Blueprint $table) {
                $table->enum('trang_thai', ['cho', 'da_xac_nhan', 'tu_choi'])
                      ->default('cho')
                      ->after('hoa_don');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('don_hang', 'trang_thai')) {
            Schema::table('don_hang', function (Blueprint $table) {
                $table->dropColumn('trang_thai');
            });
        }
    }
};
