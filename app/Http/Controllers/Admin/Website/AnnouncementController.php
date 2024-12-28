<?php

namespace App\Http\Controllers\Admin\Website;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Website\Announcement;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class AnnouncementController extends Controller
{

    // Authorize
    public function __construct()
    {
        $this->middleware('permission:website_announcement_view', ['only' => ['index', 'show']]);
        $this->middleware('permission:website_announcement_create', ['only' => ['create','store']]);
        $this->middleware('permission:website_announcement_edit', ['only' => ['edit','update']]);
        $this->middleware('permission:website_announcement_delete', ['only' => ['destroy']]);
    }

    // Index
    public function index()
    {
        $announcements = Announcement::all();
        return view('admin.website.announcements.index', compact('announcements'));
    }

    // Create
    public function create()
    {
        return view('admin.website.announcements.create');
    }

    // Store
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:500',
            'body'  => 'required',
            'img'   => 'nullable'
        ]);

        $announcement = new Announcement();
        // Upload Photo
        if ($request->hasFile('img')) {
            $image_tmp = $request->file('img');
            if ($image_tmp->isValid()) {
                $extension = $image_tmp->getClientOriginalExtension();
                $imageName = rand(11111, 99999) . '.' . $extension;
                $large_image_path = 'website/images/announcements/' . $imageName;
                Image::make($image_tmp)->save($large_image_path);
            }

            $image = $imageName;
        }
        $announcement->title    = $request->title;
        $announcement->body     = $request->body;
        $announcement->img      = $image;
        $announcement->save();

        return redirect()->route('admin.website.announcements.show', $announcement->id);
    }

    // Show
    public function show(Announcement $announcement)
    {
        return view('admin.website.announcements.show', compact('announcement'));
    }

    // Edit
    public function edit(Announcement $announcement)
    {
        return view('admin.website.announcements.edit', compact('announcement'));
    }

    // Update
    public function update(Request $request, Announcement $announcement)
    {
        $request->validate([
            'title' => 'required|max:500',
            'body'  => 'required',
            'img'   => 'nullable'
        ]);

        // Upload Image
        if ($request->hasFile('img')) {
            $image_tmp = $request->file('img');
            if ($image_tmp->isValid()) {
                $extension = $image_tmp->getClientOriginalExtension();
                $imageName = rand(11111, 99999) . '.' . $extension;
                $large_image_path = 'website/images/announcements/' . $imageName;
                Image::make($image_tmp)->save($large_image_path);
            }

            $image = $imageName;

            // If file exists during Updation then delete it
            if ($announcement->img) {
                // Get Image path
                $image_path = 'website/images/announcements/';

                // Delete from path and storage
                if (file_exists($image_path.$announcement->img)) {
                    unlink($image_path.$announcement->img);
                }
            }
        } else {
            $image = $announcement->img;
        }

        $announcement->title    = $request->title;
        $announcement->body     = $request->body;
        $announcement->img      = $image;
        $announcement->save();

        return redirect()->route('admin.website.announcements.show', $announcement->id);
    }

    // Delete
    public function destroy(Announcement $announcement)
    {
        // Get category Image path
        $image_path = 'website/images/announcements/';
        // Delete from path and storage
        if (file_exists($image_path.$announcement->img)) {
            unlink($image_path.$announcement->img);
        }
        $announcement->delete();
        return redirect()->route('admin.website.announcements.index');
    }
}
