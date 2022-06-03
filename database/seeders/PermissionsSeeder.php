<?php

namespace Database\Seeders;

use App\Services\Permission\InitService;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds
     *
     * @throws \Exception
     */
    public function run()
    {
        /* Init permissions service */
        (new InitService())->run();
    }
}
