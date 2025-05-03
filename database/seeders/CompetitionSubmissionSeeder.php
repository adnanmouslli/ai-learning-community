<?php

namespace Database\Seeders;

use App\Models\Competition;
use App\Models\CompetitionRanking;
use App\Models\CompetitionSubmission;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompetitionSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $competitions = Competition::all();
        
        // التحقق من وجود مستخدمين كافيين
        $totalUsers = $users->count();
        if ($totalUsers === 0) {
            echo "لا يوجد مستخدمين لإنشاء مشاركات.\n";
            return;
        }

        // لكل منافسة نشطة أو مكتملة
        foreach ($competitions as $competition) {
            if ($competition->status === 'active' || $competition->status === 'completed') {
                // التأكد من أن عدد المشاركين لا يتجاوز عدد المستخدمين المتاحين
                $participantCount = min(rand(2, 5), $totalUsers);
                $participants = $users->random($participantCount);
                
                // إنشاء تقديمات لكل مشارك
                foreach ($participants as $index => $user) {
                    $submissionCount = rand(1, 3); // عدد التقديمات لكل مستخدم
                    
                    for ($i = 0; $i < $submissionCount; $i++) {
                        $submission = CompetitionSubmission::create([
                            'competition_id' => $competition->id,
                            'user_id' => $user->id,
                            'title' => "مشاركة {$user->name} رقم " . ($i + 1),
                            'description' => "وصف المشاركة: استخدمت نموذج " . $this->getRandomModel() . " مع تقنية " . $this->getRandomTechnique(),
                            'file_path' => null, // يمكن تعيين مسار ملف وهمي إذا لزم الأمر
                            'github_url' => 'https://github.com/user/project-' . rand(1000, 9999),
                        ]);

                        // إذا كانت المنافسة مكتملة، قم بتقييم المشاركة
                        if ($competition->status === 'completed') {
                            $accuracyScore = rand(70, 98) / 100;
                            $performanceScore = rand(65, 95) / 100;
                            $finalScore = ($accuracyScore + $performanceScore) / 2;
                            
                            $submission->accuracy_score = $accuracyScore;
                            $submission->performance_score = $performanceScore;
                            $submission->final_score = $finalScore;
                            $submission->feedback = $this->getRandomFeedback();
                            $submission->save();
                        }
                    }
                    
                    // إنشاء ترتيب للمستخدم في المنافسة (إذا كانت مكتملة)
                    if ($competition->status === 'completed') {
                        $bestSubmission = CompetitionSubmission::where('competition_id', $competition->id)
                            ->where('user_id', $user->id)
                            ->whereNotNull('final_score')
                            ->orderBy('final_score', 'desc')
                            ->first();
                            
                        if ($bestSubmission) {
                            CompetitionRanking::create([
                                'competition_id' => $competition->id,
                                'user_id' => $user->id,
                                'rank' => $index + 1, // سيتم تحديثه لاحقًا
                                'score' => $bestSubmission->final_score,
                                'points_earned' => 10 + (10 - min(10, $index + 1)) * 5, // مكافآت متناقصة حسب الترتيب
                            ]);
                        }
                    }
                }
                
                // تحديث الترتيب في المنافسات المكتملة
                if ($competition->status === 'completed') {
                    $this->updateRankings($competition->id);
                }
            }
        }
    }

    /**
     * تحديث ترتيب المشاركين
     */
    private function updateRankings($competitionId): void
    {
        $rankings = CompetitionRanking::where('competition_id', $competitionId)
            ->orderBy('score', 'desc')
            ->get();
        
        $rank = 1;
        $lastScore = null;
        $lastRank = 1;
        
        foreach ($rankings as $ranking) {
            if ($lastScore !== null && $ranking->score === $lastScore) {
                $ranking->rank = $lastRank;
            } else {
                $ranking->rank = $rank;
                $lastRank = $rank;
                $lastScore = $ranking->score;
            }
            
            $ranking->save();
            $rank++;
        }
    }

    /**
     * الحصول على نموذج عشوائي
     */
    private function getRandomModel(): string
    {
        $models = ['BERT', 'GPT', 'ResNet', 'VGG', 'U-Net', 'EfficientNet', 'LSTM', 'Transformer', 'XGBoost', 'Random Forest'];
        return $models[array_rand($models)];
    }

    /**
     * الحصول على تقنية عشوائية
     */
    private function getRandomTechnique(): string
    {
        $techniques = ['Transfer Learning', 'Fine-tuning', 'Data Augmentation', 'Ensemble Learning', 'Regularization', 'Batch Normalization', 'Feature Engineering', 'Hyperparameter Optimization'];
        return $techniques[array_rand($techniques)];
    }

    /**
     * الحصول على تعليق تقييمي عشوائي
     */
    private function getRandomFeedback(): string
    {
        $feedback = [
            'نموذج جيد مع دقة عالية. يمكن تحسين زمن التنفيذ.',
            'استخدام جيد للتقنيات المتقدمة. يمكن تحسين معالجة القيم المفقودة.',
            'أداء ممتاز. النموذج يعمل بشكل جيد على بيانات الاختبار.',
            'نموذج واعد. يحتاج إلى تحسين في التعامل مع الحالات الشاذة.',
            'فكرة مبتكرة ولكن تحتاج إلى تحسين في التنفيذ.',
            'استخدام جيد للبيانات المتاحة. يمكن تجربة تقنيات إضافية لتحسين الأداء.',
        ];
        
        return $feedback[array_rand($feedback)];
    }
}