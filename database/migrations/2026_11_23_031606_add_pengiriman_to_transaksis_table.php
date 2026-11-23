<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->string('kurir')->default('Ambil di Apotek')->after('metode_pembayaran');$table->integer('ongkir')->default(0)->after('kurir');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {$table->dropColumn(['kurir', 'ongkir']);
        });
    }
};