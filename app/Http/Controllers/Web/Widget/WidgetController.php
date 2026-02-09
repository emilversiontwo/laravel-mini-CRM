<?php

namespace App\Http\Controllers\Web\Widget;

use App\Http\Controllers\Controller;

class WidgetController extends Controller
{
    public function get()
    {
        return view('Widget.widget');
    }
}
