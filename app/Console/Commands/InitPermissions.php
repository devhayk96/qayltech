<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Permission\InitService;

class InitPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create, if not exists, permissions in DB by declared permissions in App\Enums';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        return (int) (new InitService())->run();
    }
}
