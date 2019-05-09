<?php

namespace Lazy\Admin\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class DBCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lazy-admin:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '初始化数据库';

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
     * @return mixed
     */
    public function handle()
    {
        //
        $this->info("==================== 开始安装 =====================");
        $start = microtime(true);
        $this->info("生成数据库文件");
        $this->makeSchema();
        $end   = microtime(true);
        $t     = round($end-$start);
        $this->info("================ 安装结束，运行{$t}秒 =================");
    }

    /**
     * 生成数据库文件
     *
     * @return void
     */
    public function makeSchema()
    {
        if (Schema::connection(config('lazy-admin.connection'))->hasTable('model_has_permissions')
        || Schema::connection(config('lazy-admin.connection'))->hasTable('model_has_roles')
        || Schema::connection(config('lazy-admin.connection'))->hasTable('password_resets')
        || Schema::connection(config('lazy-admin.connection'))->hasTable('permissions')
        || Schema::connection(config('lazy-admin.connection'))->hasTable('role_has_permissions')
        || Schema::connection(config('lazy-admin.connection'))->hasTable('roles')
        || Schema::connection(config('lazy-admin.connection'))->hasTable('admin_users')) {
            $this->info("表结构已经存在, 若重新安装请删除");
            return false;
        }
        // 生成表结构数据
        $this->call('vendor:publish', ["--provider"=>"Lazy\Admin\ServiceProvider", "--tag" => "lazy-admin-migrations"]);
        // 创建表
        $this->call('migrate', ["--database" => config('lazy-admin.connection')]);
        // 生成数据库文件
        $this->call('db:seed', ['--class' => \Lazy\Admin\Models\DbSeeder::class]);
    }
}
