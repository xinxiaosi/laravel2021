<?php

namespace App\Console\Commands;

use App\Models\UserCenter\Models\AdminModel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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

    public function handle()
    {
        $insert = [];

        echo date('Y-m-d H:i:s', time()) . "开始运行 \n";
        $value = 123456;
        for ($i = 0; $i <= 1000; $i++) {
            $uid = uniqid('xin_');
            $name = $uid . $i;
            $insert[] = [
                'role_id' => rand(1, 4),
                'name' => $name,
                'password' => password_hash(md5($value), PASSWORD_BCRYPT),//运行很慢
                'uid' => $uid,
                'token' => md5($uid . time() . $i),
                'email' => $name . '@email.com',
                'phone' => rand(10000000000, 99999999999),
                'status' => rand(1, 2),
            ];
        }
        echo date('Y-m-d H:i:s', time()) . "结束计算 \n";
        DB::connection('userCenter')->table('admin')->insert($insert);
        echo date('Y-m-d H:i:s', time()) . "结束运行 \n";
        echo '=========================生成成功=========================';

    }
}
