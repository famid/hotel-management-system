<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard()
    {
        $data['mainMenu'] = 'dashboard';
        $data['menuName'] = __('Dashboard');

        return view('super_admin.dashboard', $data);
    }
}
