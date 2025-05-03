<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class, // أولاً: إنشاء المستخدمين
            ForumCategorySeeder::class, // ثانياً: إنشاء فئات المنتدى
            ForumTopicSeeder::class, // ثالثاً: إنشاء مواضيع المنتدى
            ForumPostSeeder::class, // رابعاً: إنشاء الردود
            CompetitionSeeder::class, // خامساً: إنشاء المنافسات
            CompetitionSubmissionSeeder::class, // سادساً: إنشاء مشاركات المنافسات
        ]);
    }
}