<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use App\Models\ForumTopic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ForumTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = ForumCategory::all();

        $topics = [
            [
                'title' => 'ما هي أفضل مكتبة للشبكات العصبية؟',
                'content' => 'أبحث عن أفضل مكتبة للشبكات العصبية للبدء في مشروع جديد. هل تنصحون بـ TensorFlow أم PyTorch أم هناك خيارات أخرى؟',
                'is_pinned' => true,
            ],
            [
                'title' => 'كيفية تحسين دقة نموذج التعلم العميق؟',
                'content' => 'أواجه مشكلة في تحسين دقة نموذج التعلم العميق الخاص بي. هل لديكم اقتراحات لتقنيات يمكنني تجربتها؟',
                'is_pinned' => false,
            ],
            [
                'title' => 'ما هو BERT وكيف يعمل؟',
                'content' => 'سمعت كثيرًا عن نموذج BERT في معالجة اللغات الطبيعية. هل يمكن لأحد شرح كيف يعمل وما هي مميزاته؟',
                'is_pinned' => false,
            ],
            [
                'title' => 'تطبيقات الرؤية الحاسوبية في الطب',
                'content' => 'أبحث عن أمثلة لتطبيقات الرؤية الحاسوبية في المجال الطبي. هل هناك أبحاث حديثة في هذا المجال؟',
                'is_pinned' => false,
            ],
            [
                'title' => 'مقارنة بين خوارزميات تصنيف الصور',
                'content' => 'ما هي أفضل خوارزميات تصنيف الصور حاليًا؟ وما هي الفروق الرئيسية بينها من حيث الأداء والدقة؟',
                'is_pinned' => false,
            ],
            [
                'title' => 'كيفية بدء مشروع في الذكاء الاصطناعي؟',
                'content' => 'أنا مبتدئ في مجال الذكاء الاصطناعي وأريد البدء في مشروع عملي. ما هي النصائح التي يمكنكم تقديمها لي؟',
                'is_pinned' => true,
            ],
        ];

        foreach ($topics as $index => $topic) {
            // اختيار مستخدم وفئة عشوائيين
            $user = $users->random();
            $category = $categories->random();

            ForumTopic::create([
                'title' => $topic['title'],
                'slug' => Str::slug($topic['title'] . '-' . Str::random(5)),
                'content' => $topic['content'],
                'user_id' => $user->id,
                'category_id' => $category->id,
                'is_pinned' => $topic['is_pinned'],
                'is_solved' => false,
                'views' => rand(10, 100),
            ]);
        }

        // إنشاء المزيد من المواضيع باستخدام Factory
        // ForumTopic::factory(20)->create();
    }
}