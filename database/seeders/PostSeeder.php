<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();

        if (!$admin) {
            return;
        }

        $posts = [
            [
                'title' => 'The Critical Role of Emotional Intelligence in Teen Development',
                'excerpt' => 'Why IQ isn\'t enough anymore. Discover how emotional resilience helps young Muslims navigate social media pressures and modern challenges.',
                'content' => '<p>In today\'s rapidly changing world, emotional intelligence (EQ) has become more crucial than ever for teenager development. While academic excellence remains important, the ability to understand, manage, and express emotions effectively determines how well a young person navigates the complexities of modern life.</p>
                <p>For young Muslims, this challenge is doubled. They must balance their faith identity with secular societal pressures, often finding themselves at a crossroads of conflicting values. EQ provides the resilient foundation needed to maintain spiritual clarity while excelling in their social and academic circles.</p>
                <h3>Why EQ Matters for Young Muslims</h3>
                <ul>
                    <li><strong>Social Media Navigation:</strong> Helping teens understand that what they see online is filtered, preventing comparison traps.</li>
                    <li><strong>Identity Confidence:</strong> Developing the emotional strength to stand by their values when challenged by peers.</li>
                    <li><strong>Conflict Resolution:</strong> Learning to respond with the prophetic character (Akhlaq) rather than reacting out of impulse.</li>
                </ul>
                <p>Our mentorship programs focus heavily on these soft skills, ensuring that every student who leaves YPMMH is not just knowledgeable, but emotionally grounded and spiritually resilient.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1456513080510-7bf3a84b82f8?auto=format&fit=crop&w=1200&q=80',
                'category' => 'Psychology',
                'published_at' => '2025-10-24 10:00:00',
            ],
            [
                'title' => 'Balancing Deen and Dunya in Exams',
                'excerpt' => 'Practical tips for maintaining spiritual routines during stressful academic periods.',
                'content' => '<p>Exam season is often seen as a legitimate excuse to put our spiritual lives on hold. We tell ourselves that once the exams are over, we will get back to our prayers and Quranic reflections. However, this is precisely when we need the barakah of Allah the most.</p>
                <p>Maintaining a balance between Deen and Dunya during high-pressure times isn\'t just about time management; it\'s about heart management. When we prioritize our relationship with the Creator, He puts ease in the tasks of the creation.</p>
                <h3>How to Maintain Balance</h3>
                <ol>
                    <li><strong>Prayer as a Break:</strong> Use your five daily prayers as mandatory mental breaks. They refresh the mind more than scrolling on a phone ever could.</li>
                    <li><strong>Tahajjud for Clarity:</strong> The early morning hours are blessed. Studying after Fajr or during the time of Tahajjud brings a clarity that late-night sessions often lack.</li>
                    <li><strong>Dua is the Weapon:</strong> Never underestimate the power of asking Allah for success. It shifts your mindset from "I am doing this" to "Allah is helping me."</li>
                </ol>',
                'featured_image' => 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?auto=format&fit=crop&w=600',
                'category' => 'Education',
                'published_at' => '2025-10-20 09:00:00',
            ],
            [
                'title' => 'Building Halal Friendships',
                'excerpt' => 'How to choose friends who remind you of Allah and support your growth journey.',
                'content' => '<p>The Prophet Muhammad (peace be upon him) said: "A person follows the religion of his close friend; so each one should consider whom he makes his close friend."</p>
                <p>For young people, peers are the strongest influence on their habits and mindset. Choosing friends who share your values isn\'t about isolation; it\'s about building a circle that supports your ascension toward spiritual and worldly excellence.</p>
                <h3>Green Flags in Friendships</h3>
                <ul>
                    <li>They remind you of Allah without judging you.</li>
                    <li>They are honest with you, even when the truth is uncomfortable.</li>
                    <li>They celebrate your successes rather than feeling jealous of them.</li>
                </ul>',
                'featured_image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=600',
                'category' => 'Community',
                'published_at' => '2025-10-18 14:00:00',
            ],
            [
                'title' => 'Understanding Gen Z Muslims',
                'excerpt' => 'A guide for parents on connecting with their children\'s unique worldview.',
                'content' => '<p>Generation Z is the first truly digital generation. They process information differently, communicate differently, and have concerns that might seem foreign to previous generations.</p>
                <p>To bridge the gap, parents must move from a model of "command and control" to one of "connection and conversation." Understanding their world doesn\'t mean compromising our values; it means translating those values into a language they resonate with.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1609839462529-5735234563a6?auto=format&fit=crop&w=600',
                'category' => 'Parenting',
                'published_at' => '2025-10-15 11:30:00',
            ],
        ];

        foreach ($posts as $postData) {
            $postData['slug'] = Str::slug($postData['title']);
            $postData['author_id'] = $admin->id;
            $postData['status'] = 'published';
            Post::updateOrCreate(['slug' => $postData['slug']], $postData);
        }
    }
}
