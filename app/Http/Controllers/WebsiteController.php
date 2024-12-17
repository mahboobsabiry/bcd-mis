<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    // Website
    public function index()
    {
        $setting = Setting::pluck('value', 'key');
        return view('website.index', compact('setting'));
    }
}
