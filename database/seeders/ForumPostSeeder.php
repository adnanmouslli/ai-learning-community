<?php

namespace Database\Seeders;

use App\Models\ForumPost;
use App\Models\ForumTopic;
use App\Models\User;
use Illuminate\Database\Seeder;

class ForumPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $topics = ForumTopic::all();

        // إنشاء بعض الردود لكل موضوع
        foreach ($topics as $topic) {
            $postCount = rand(1, 5); // عدد عشوائي من الردود لكل موضوع
            
            for ($i = 0; $i < $postCount; $i++) {
                $user = $users->random(); // اختيار مستخدم عشوائي
                
                $post = ForumPost::create([
                    'topic_id' => $topic->id,
                    'user_id' => $user->id,
                    'content' => $this->getRandomResponse(),
                    'upvotes' => rand(0, 15),
                    'downvotes' => rand(0, 5),
                ]);
                
                // جعل أحد الردود كحل للموضوع (بنسبة 30%)
                if ($i === 0 && rand(1, 10) <= 3) {
                    $post->is_solution = true;
                    $post->save();
                    
                    $topic->is_solved = true;
                    $topic->save();
                }
            }
        }
    }

    /**
     * الحصول على رد عشوائي
     */
    private function getRandomResponse(): string
    {
        $responses = [
            'أعتقد أن أفضل طريقة هي استخدام PyTorch لأنها أكثر مرونة وسهولة في التعلم.',
            'من تجربتي، أنصحك بالبدء بـ TensorFlow لأنه يحتوي على توثيق جيد ومجتمع كبير.',
            'جرب استخدام تقنية التعلم بالنقل (Transfer Learning)، فهي توفر نتائج جيدة مع القليل من البيانات.',
            'يمكنك تحسين النموذج باستخدام تقنيات تعديل معدل التعلم (Learning Rate) وزيادة حجم البيانات.',
            'في رأيي، أفضل طريقة للبدء هي فهم المفاهيم الأساسية أولاً ثم الانتقال إلى التطبيقات العملية.',
            'قمت بتجربة هذا النهج وحصلت على نتائج ممتازة. أنصحك بمتابعة هذا المسار.',
            'هناك العديد من الموارد المجانية عبر الإنترنت للتعلم. أنصحك بدورات Coursera و Stanford.',
            'واجهت نفس المشكلة سابقًا. المفتاح هو تنظيف البيانات جيدًا قبل تدريب النموذج.',
            'تحقق من استخدام طبقات الـ Dropout لمنع المبالغة في التعلم (Overfitting).',
            'أفضل المكتبات في رأيي هي Keras لسهولة الاستخدام والـ PyTorch لمرونته.',
            'يمكنك الاطلاع على مقالتي التي نشرتها حول هذا الموضوع، فقد تناولت فيها تفاصيل هذه التقنية.',
            'استخدم خوارزمية Adam Optimizer للحصول على نتائج أفضل وتدريب أسرع.',
        ];

        return $responses[array_rand($responses)];
    }
}