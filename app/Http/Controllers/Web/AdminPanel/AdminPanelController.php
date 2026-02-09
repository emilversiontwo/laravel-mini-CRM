<?php

namespace App\Http\Controllers\Web\AdminPanel;

use App\Http\Controllers\Controller;

class AdminPanelController extends Controller
{
    public function dashboard()
    {
        return view('AdminPanel.dashboard');
    }
}
