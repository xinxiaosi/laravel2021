<?php

namespace App\Console\Commands;

use App\Models\UserCenter\Models\AdminModel;
use Illuminate\Console\Command;

class AdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:createAdmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $insert = [];

        for ($i = 0; $i <= 1000; $i++) {
            $name = uniqid('xin') . $i;
            $insert[] = [
                'role_id' => rand(1, 4),
                'name' => $name,
                'password' => md5('123456'),
                'uid' => md5($name . '' . time()),
                'token' => md5($name . '' . time() . $i),
                'email' => $name . '@email.com',
                'phone' => rand(10000000000, 99999999999),
                'status' => rand(1, 3),
            ];
        }

        $model = new AdminModel();
        $model->insert($insert);
        echo '生成成功';
    }
}
