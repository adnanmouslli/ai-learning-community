<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ForumCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'الشبكات العصبية',
                'description' => 'نقاشات حول الشبكات العصبية الاصطناعية وتطبيقاتها',
                'icon' => 'fa-brain',
                'order' => 1,
            ],
            [
                'name' => 'التعلم العميق',
                'description' => 'كل ما يتعلق بالتعلم العميق وتقنياته',
                'icon' => 'fa-network-wired',
                'order' => 2,
            ],
            [
                'name' => 'معالجة اللغات الطبيعية',
                'description' => 'تقنيات وتطبيقات معالجة اللغات الطبيعية',
                'icon' => 'fa-language',
                'order' => 3,
            ],
            [
                'name' => 'الرؤية الحاسوبية',
                'description' => 'مناقشات حول الرؤية الحاسوبية وتحليل الصور',
                'icon' => 'fa-eye',
                'order' => 4,
            ],
            [
                'name' => 'تعلم الآلة',
                'description' => 'خوارزميات ومفاهيم تعلم الآلة',
                'icon' => 'fa-cogs',
                'order' => 5,
            ],
            [
                'name' => 'أسئلة عامة',
                'description' => 'أي أسئلة عامة حول الذكاء الاصطناعي',
                'icon' => 'fa-question-circle',
                'order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            ForumCategory::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'icon' => $category['icon'],
                'order' => $category['order'],
            ]);
        }
    }
}