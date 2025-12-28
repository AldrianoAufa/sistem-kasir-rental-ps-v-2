<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('page_key')->unique();    // e.g., 'dashboard', 'transaction', 'fnb'
            $table->string('page_name');             // Display name for the UI
            $table->boolean('admin_access')->default(true);  // Whether admin can access this page
            $table->timestamps();
        });

        // Seed default page permissions
        $pages = [
            ['page_key' => 'dashboard', 'page_name' => 'Dashboard', 'admin_access' => true],
            ['page_key' => 'transaction', 'page_name' => 'Transaksi', 'admin_access' => true],
            ['page_key' => 'device', 'page_name' => 'Data Perangkat', 'admin_access' => true],
            ['page_key' => 'stock', 'page_name' => 'Lihat Stok', 'admin_access' => true],
            ['page_key' => 'expense', 'page_name' => 'Pengeluaran', 'admin_access' => true],
            ['page_key' => 'report', 'page_name' => 'Laporan Penjualan', 'admin_access' => true],
            ['page_key' => 'fnb-laporan', 'page_name' => 'Laporan FnB', 'admin_access' => true],
        ];

        foreach ($pages as $page) {
            \DB::table('page_permissions')->insert([
                'page_key' => $page['page_key'],
                'page_name' => $page['page_name'],
                'admin_access' => $page['admin_access'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_permissions');
    }
}
