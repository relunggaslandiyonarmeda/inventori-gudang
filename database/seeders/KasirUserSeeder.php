<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class KasirUserSeeder extends Seeder
{
    /**
     * Create the default kasir (cashier) account.
     * The account uses the "user" role with full operational menu access
     * so it can be used on a shared terminal and stays logged in (session
     * lifetime is 1 year and "Ingat saya" keeps the remember-me cookie).
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['username' => 'kasir'],
            [
                'name' => 'Kasir',
                'email' => 'kasir@inventori.local',
                'password' => bcrypt('kasir12345'),
                'role' => 'user',
                'menu_permissions' => [
                    'master_barang',
                    'barang_masuk',
                    'barang_keluar',
                    'barang_retur',
                    'barang_rusak',
                ],
            ]
        );
    }
}
