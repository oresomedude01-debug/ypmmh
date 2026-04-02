<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        if (!$admin)
            return;

        $items = [
            [
                'image_path' => 'https://images.unsplash.com/photo-1523240715639-93f8faa0effb?auto=format&fit=crop&w=600&q=80',
                'title' => 'Leadership Workshop',
                'category' => 'workshops',
                'is_featured' => true
            ],
            [
                'image_path' => 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&w=600&q=80',
                'title' => 'Group Mentoring Session',
                'category' => 'mentoring',
                'is_featured' => false
            ],
            [
                'image_path' => 'https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&w=600&q=80',
                'title' => 'Annual Community Event',
                'category' => 'events',
                'is_featured' => false
            ],
            [
                'image_path' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=600&q=80',
                'title' => 'Youth Gathering',
                'category' => 'events',
                'is_featured' => true
            ],
            [
                'image_path' => 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?auto=format&fit=crop&w=600&q=80',
                'title' => 'Team Building Activity',
                'category' => 'workshops',
                'is_featured' => false
            ],
            [
                'image_path' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=600&q=80',
                'title' => 'Collaborative Learning',
                'category' => 'mentoring',
                'is_featured' => false
            ],
            [
                'image_path' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=600&q=80',
                'title' => 'Strategy Session',
                'category' => 'workshops',
                'is_featured' => false
            ],
            [
                'image_path' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=600&q=80',
                'title' => 'One-on-One Mentoring',
                'category' => 'mentoring',
                'is_featured' => true
            ],
            [
                'image_path' => 'https://images.unsplash.com/photo-1515187029135-18ee286d815b?auto=format&fit=crop&w=600&q=80',
                'title' => 'Career Day Event',
                'category' => 'events',
                'is_featured' => false
            ],
            [
                'image_path' => 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=600&q=80',
                'title' => 'Public Speaking Training',
                'category' => 'workshops',
                'is_featured' => false
            ],
            [
                'image_path' => 'https://images.unsplash.com/photo-1560439514-4e9645039924?auto=format&fit=crop&w=600&q=80',
                'title' => 'Peer Support Circle',
                'category' => 'mentoring',
                'is_featured' => false
            ],
            [
                'image_path' => 'https://images.unsplash.com/photo-1543269865-cbf427effbad?auto=format&fit=crop&w=600&q=80',
                'title' => 'Celebration Event',
                'category' => 'events',
                'is_featured' => false
            ],
        ];

        foreach ($items as $item) {
            GalleryImage::create(array_merge($item, [
                'author_id' => $admin->id,
                'status' => 'active'
            ]));
        }
    }
}
