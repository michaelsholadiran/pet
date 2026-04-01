<?php

namespace App\Livewire;

use App\Models\Article;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ArticleShowPage extends Component
{
    public string $slug;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $article = Article::with('category')
            ->where('slug', $this->slug)
            ->where('is_published', true)
            ->firstOrFail();

        $rawContent = (string) $article->content;
        preg_match_all('/<h2[^>]*>(.*?)<\/h2>/is', $rawContent, $headingMatches);

        $tableOfContents = [];
        $renderedContent = preg_replace_callback(
            '/<h2([^>]*)>(.*?)<\/h2>/is',
            static function (array $matches) use (&$tableOfContents): string {
                $headingText = trim(strip_tags($matches[2]));
                $anchor = Str::slug($headingText);
                if ($anchor === '') {
                    $anchor = 'section-'.(count($tableOfContents) + 1);
                }

                $tableOfContents[] = [
                    'id' => $anchor,
                    'label' => $headingText,
                ];

                return '<h2 id="'.e($anchor).'"'.$matches[1].'>'.$matches[2].'</h2>';
            },
            $rawContent
        ) ?? $rawContent;

        $relatedArticles = Article::query()
            ->where('is_published', true)
            ->where('id', '!=', $article->id)
            ->when($article->category_id, fn ($query) => $query->where('category_id', $article->category_id))
            ->latest()
            ->limit(2)
            ->get();

        if ($relatedArticles->count() < 2) {
            $additional = Article::query()
                ->where('is_published', true)
                ->where('id', '!=', $article->id)
                ->whereNotIn('id', $relatedArticles->pluck('id'))
                ->latest()
                ->limit(2 - $relatedArticles->count())
                ->get();
            $relatedArticles = $relatedArticles->concat($additional);
        }

        $wordCount = str_word_count(strip_tags($article->content));
        $readMinutes = max(1, (int) ceil($wordCount / 220));
        $categoryName = $article->category?->name ?? 'Puppy Guide';
        $excerpt = Str::limit(strip_tags($article->content), 220);

        view()->share([
            'page_title' => $article->title.' - '.config('puppiary.name'),
            'page_description' => Str::limit(strip_tags($article->content), 160),
            'page_canonical' => '/guide/'.$this->slug,
        ]);

        return view('livewire.article-show-page', [
            'article' => $article,
            'renderedContent' => $renderedContent,
            'tableOfContents' => $tableOfContents,
            'relatedArticles' => $relatedArticles,
            'readMinutes' => $readMinutes,
            'categoryName' => $categoryName,
            'excerpt' => $excerpt,
        ]);
    }
}
