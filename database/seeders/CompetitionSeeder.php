<?php

namespace Database\Seeders;

use App\Models\Competition;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $competitions = [
            [
                'title' => 'تحدي تصنيف الصور الطبية',
                'description' => 'تحدي لإنشاء نموذج تعلم عميق لتصنيف صور الأشعة السينية للصدر وتحديد الإصابة بمرض معين.',
                'rules' => 'يجب استخدام مجموعة البيانات المتاحة فقط. يمكن استخدام أي مكتبة للتعلم الآلي. يجب تقديم التعليمات البرمجية مع النتائج.',
                'evaluation_criteria' => 'سيتم تقييم النماذج بناءً على دقة التصنيف (Accuracy)، الاستدعاء (Recall)، والدقة (Precision).',
                'status' => 'active',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(20),
                'is_featured' => true,
            ],
            [
                'title' => 'تحدي تحليل المشاعر من التغريدات',
                'description' => 'تطوير نموذج لتحليل المشاعر من التغريدات العربية وتصنيفها إلى إيجابية، سلبية، أو محايدة.',
                'rules' => 'يمكن استخدام أي تقنية أو مكتبة للمعالجة اللغوية. يجب تقديم شرح للنهج المتبع مع النتائج.',
                'evaluation_criteria' => 'سيتم التقييم بناءً على دقة التصنيف العامة ومقاييس F1-score.',
                'status' => 'upcoming',
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(35),
                'is_featured' => false,
            ],
            [
                'title' => 'تحدي التنبؤ بأسعار العقارات',
                'description' => 'تطوير نموذج للتنبؤ بأسعار العقارات بناءً على مجموعة من الخصائص مثل المساحة، الموقع، وعدد الغرف.',
                'rules' => 'يمكن استخدام أي خوارزمية تعلم آلة. يجب تقديم تحليل للبيانات والنتائج.',
                'evaluation_criteria' => 'سيتم التقييم بناءً على مقياس RMSE (الجذر التربيعي لمتوسط مربع الخطأ) ومقياس R-squared.',
                'status' => 'completed',
                'start_date' => Carbon::now()->subDays(60),
                'end_date' => Carbon::now()->subDays(30),
                'is_featured' => false,
            ],
            [
                'title' => 'تحدي ترجمة النصوص العربية إلى الإنجليزية',
                'description' => 'تطوير نموذج ترجمة آلية للنصوص من العربية إلى الإنجليزية باستخدام تقنيات التعلم العميق.',
                'rules' => 'يمكن استخدام النماذج المدربة مسبقًا مع fine-tuning. يجب تقديم التعليمات البرمجية والنتائج.',
                'evaluation_criteria' => 'سيتم التقييم بناءً على مقاييس BLEU وROUGE.',
                'status' => 'active',
                'start_date' => Carbon::now()->subDays(5),
                'end_date' => Carbon::now()->addDays(25),
                'is_featured' => true,
            ],
        ];

        foreach ($competitions as $competition) {
            Competition::create([
                'title' => $competition['title'],
                'slug' => Str::slug($competition['title']),
                'description' => $competition['description'],
                'rules' => $competition['rules'],
                'evaluation_criteria' => $competition['evaluation_criteria'],
                'status' => $competition['status'],
                'start_date' => $competition['start_date'],
                'end_date' => $competition['end_date'],
                'is_featured' => $competition['is_featured'],
                'max_daily_submissions' => rand(3, 5),
            ]);
        }
    }
}