<?php

namespace Lazy\Admin\Commands;

use Illuminate\Console\Command;
use Lazy\Admin\Models\User;
use Illuminate\Support\Facades\Schema;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lazy-admin:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'lazy-admin安装';

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
        $this->info("发布配置");
        $this->publish();
        $this->info("生成数据库文件");
        $this->makeSchema();
        $end   = microtime(true);
        $t     = round($end-$start);
        $this->info("================ 安装结束，运行{$t}秒 =================");
    }

    /**
     * public config
     *
     * @return void
     */
    public function publish()
    {
        // 生成配置
        $this->call('vendor:publish', ["--provider"=>"Spatie\Permission\PermissionServiceProvider", "--tag" => "config"]);
        // 生成静态文件
        $this->call('vendor:publish', ["--provider"=>"Lazy\Admin\ServiceProvider", "--tag" => "lazy-admin-assets"]);
        // 生成配置文件
        $this->call('vendor:publish', ["--provider"=>"Lazy\Admin\ServiceProvider", "--tag" => "lazy-admin-config"]);
    }

    /**
     * 生成数据库文件
     *
     * @return void
     */
    public function makeSchema()
    {
        if (Schema::hasTable('model_has_permissions') || Schema::hasTable('model_has_roles') || Schema::hasTable('password_resets') || Schema::hasTable('permissions') || Schema::hasTable('role_has_permissions') || Schema::hasTable('roles') || Schema::hasTable('users')) {
            $this->info("表结构已经存在, 若重新安装请删除");
            return false;
        }
        // 生成表结构数据
        $this->call('vendor:publish', ["--provider"=>"Lazy\Admin\ServiceProvider", "--tag" => "lazy-admin-migrations"]);
        // 创建表
        $this->call('migrate');
        // 生成数据库文件
        $this->call('db:seed', ['--class' => \Lazy\Admin\Models\DbSeeder::class]);
    }
}
