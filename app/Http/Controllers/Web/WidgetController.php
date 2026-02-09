<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class WidgetController extends Controller
{
    public function get()
    {
        return view('Widget.widget');
    }
}
