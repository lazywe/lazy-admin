<?php

namespace Lazy\Admin\Controllers;

use Lazy\Admin\Models\Menus;
use Illuminate\Support\Facades\Auth;
use Lazy\Admin\Models\AuthLog;

class AuthLogController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $list = AuthLog::orderBy('id', 'desc')->with('user')->paginate(20);
        return view('lazy-view::auth.log', compact('list'));
    }
}
