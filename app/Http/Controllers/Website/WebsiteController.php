<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Website\Announcement;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->take(3)->get();
        return view('website.index', compact('announcements'));
    }

    // Single Announcement
    public function announcement($title)
    {
        $announcement = Announcement::where('title', $title)->firstOrFail();
        return view('website.pages.announcement', compact('announcement'));
    }
}
