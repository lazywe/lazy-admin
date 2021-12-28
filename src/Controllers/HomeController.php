<?php

namespace Lazy\Admin\Controllers;

use Lazy\Admin\Models\Menus;
use Illuminate\Support\Facades\Auth;
use Lazy\Admin\Guard;

class HomeController extends Controller
{
    /**
     * 首页
     *
     * @return void
     */
    public function index()
    {
        return view('lazy-view::index');
    }

    /**
     * demo
     *
     * @return void
     */
    public function demo()
    {
        return view('lazy-view::demo');
    }
}
