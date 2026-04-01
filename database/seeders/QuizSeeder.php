<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\QuizOption;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::where('is_active', true)->pluck('id')->toArray();

        $questions = [
            [
                'question_text' => 'How old is your puppy?',
                'sort_order' => 1,
                'options' => [
                    ['option_text' => 'Under 3 months', 'product_ids' => []],
                    ['option_text' => '3–6 months', 'product_ids' => []],
                    ['option_text' => '6–12 months', 'product_ids' => []],
                    ['option_text' => 'Over 12 months', 'product_ids' => []],
                ],
            ],
            [
                'question_text' => 'What size is your puppy?',
                'sort_order' => 2,
                'options' => [
                    ['option_text' => 'Small (under 10 kg)', 'product_ids' => []],
                    ['option_text' => 'Medium (10–25 kg)', 'product_ids' => []],
                    ['option_text' => 'Large (over 25 kg)', 'product_ids' => []],
                ],
            ],
            [
                'question_text' => "What's your main priority?",
                'sort_order' => 3,
                'options' => [
                    ['option_text' => 'Teething relief', 'product_ids' => []],
                    ['option_text' => 'Training essentials', 'product_ids' => []],
                    ['option_text' => 'Grooming & comfort', 'product_ids' => []],
                    ['option_text' => 'Play & entertainment', 'product_ids' => []],
                ],
            ],
        ];

        foreach ($questions as $qData) {
            $options = $qData['options'];
            unset($qData['options']);

            $question = QuizQuestion::firstOrCreate(
                ['question_text' => $qData['question_text']],
                array_merge($qData, ['is_active' => true])
            );

            foreach ($options as $i => $optData) {
                $productIds = ! empty($products) ? collect($products)->shuffle()->take(rand(1, 3))->values()->all() : [];
                QuizOption::firstOrCreate(
                    [
                        'question_id' => $question->id,
                        'option_text' => $optData['option_text'],
                    ],
                    [
                        'sort_order' => $i,
                        'product_ids' => $productIds,
                    ]
                );
            }
        }

        // Seed sample quiz results
        $users = User::limit(5)->get();
        $allQuestions = QuizQuestion::with('options')->get();

        if ($users->isNotEmpty() && $allQuestions->isNotEmpty() && ! empty($products)) {
            foreach ($users->take(3) as $user) {
                $answers = [];
                $recommended = [];

                foreach ($allQuestions as $q) {
                    $opt = $q->options->random();
                    $answers[$q->id] = $opt->id;
                    if (! empty($opt->product_ids)) {
                        $recommended = array_merge($recommended, $opt->product_ids);
                    }
                }

                QuizResult::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'session_id' => 'seed-'.$user->id.'-'.time(),
                    ],
                    [
                        'answers' => $answers,
                        'recommended_products' => array_unique(array_slice($recommended ?: $products, 0, 5)),
                    ]
                );
            }
        }
    }
}
