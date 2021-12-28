<?php

namespace Lazy\Admin\Controllers;

use Lazy\Admin\Models\Menus;
use Illuminate\Support\Facades\Auth;
use Lazy\Admin\Models\AuthLog;

class DemoController extends Controller
{
    /**
     * demo
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('lazy-view::demo.index');
    }

    /**
     * demo
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function table()
    {
        return view('lazy-view::demo.table');
    }

    /**
     * demo
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function form()
    {
        return view('lazy-view::demo.form');
    }

    /**
     * demo
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function button()
    {
        return view('lazy-view::demo.button');
    }
}
