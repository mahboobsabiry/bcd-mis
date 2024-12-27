<?php

namespace Database\Seeders;

use App\Models\Website\Announcement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnnouncementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('announcements')->delete();

        $announcements = [
            [
                'title' => 'گمرک سرحدی حیرتان آمادگی ارائه خدمات بصورت ۲۴ ساعته را اعلام کرد',
                'body'  => 'گمرک سرحدی حیرتان جهت سهولت برای تجاران محترم کشور، اعلام آمادگی کار ۲۴ ساعته را نمود. <br> به نظر می‌رسد ادارات ذیربط نیز با این تغییرات هماهنگ بوده باشند.',
                'img'   => 'ann-1.png'
            ],
            [
                'title' => 'گمرک محصولی نایب آباد به فعالیت خویش آغاذ نمود',
                'body'  => 'مولوی محمد اعظم طارق مدیر عمومی گمرک نایب آباد می‌گوید: بعد از آغاز فعالیت این گمرک، ایجاد یک باب هنگر در پورت چهارم نیز منظور و کار آن افتتاح گردید.',
                'img'   => 'ann-2.png'
            ],
            [
                'title' => 'افزایش کم‌نظیر عواید سال مالی 1403 نسبت به سال‌های قبل',
                'body'  => 'به نسبت تلاش های مکرر مسئولین و کارمندان، عواید از درک های مختلف گمرکی نسبت به سال های قبل خویش افزایش چشمگیری داشته است.',
                'img'   => 'ann-3.png'
            ]
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
