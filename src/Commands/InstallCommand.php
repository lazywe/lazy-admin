<?php

namespace Lazy\Admin\Commands;

use Illuminate\Console\Command;

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
        $this->publish();
        $end   = microtime(true);
        $t     = round($end-$start);
        $this->info("================ 配置生成结束，运行{$t}秒 =================");
    }

    /**
     * public config
     *
     * @return void
     */
    public function publish()
    {
        // 生成配置
        $this->info("生成配置");
        $this->call('vendor:publish', ["--provider"=>"Lazy\Admin\ServiceProvider", "--tag" => "permission"]);
        $this->call('vendor:publish', ["--provider" => "Lazy\Admin\ServiceProvider", "--tag" => "lazy-admin-config"]);

        // 生成静态文件
        $this->info("生成静态资源");
        $this->call('vendor:publish', ["--provider"=>"Lazy\Admin\ServiceProvider", "--tag" => "lazy-admin-assets"]);

        // 生成静态文件
        $this->info("生成数据库文件");
        $this->call('vendor:publish', ["--provider"=>"Lazy\Admin\ServiceProvider", "--tag" => "lazy-admin-migrations"]);
    }
}
