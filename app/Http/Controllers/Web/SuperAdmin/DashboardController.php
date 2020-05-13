<?php

namespace App\Http\Controllers\Web\SuperAdmin;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function dashboard()
    {
        $data['mainMenu'] = 'dashboard';
        $data['menuName'] = __('Dashboard');

        return view('super_admin.dashboard', $data);
    }
}
