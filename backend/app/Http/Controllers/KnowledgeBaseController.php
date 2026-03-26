<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBaseArticle;
use App\Traits\HasFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KnowledgeBaseController extends Controller
{
    use HasFilters;

    public function __construct()
    {
        $this->middleware('role:super-admin|admin|sales-cs|noc|technician');
    }

    public function index(Request $request)
    {
        $query = KnowledgeBaseArticle::with('author')
            ->where('is_published', true);

        // Apply global filters
        $this->applyGlobalFilters($query, $request, [
            'dateColumn' => 'published_at',
            'searchColumns' => ['title', 'content']
        ]);

        // Apply category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $articles = $query->latest()->paginate(12)->withQueryString();

        $categories = [
            'getting-started' => 'Memulai',
            'billing' => 'Billing',
            'technical' => 'Teknis',
            'troubleshooting' => 'Troubleshooting',
            'faq' => 'FAQ',
        ];

        $stats = [
            'total' => KnowledgeBaseArticle::where('is_published', true)->count(),
            'categories' => KnowledgeBaseArticle::where('is_published', true)->distinct()->count('category'),
        ];

        return view('support.knowledge-base.index', compact('articles', 'categories', 'stats'));
    }

    public function create()
    {
        return view('support.knowledge-base.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $validated['author_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->has('is_published');

        if ($validated['is_published']) {
            $validated['published_at'] = now();
        }

        KnowledgeBaseArticle::create($validated);

        return redirect()->route('knowledge-base.index')
            ->with('success', 'Artikel berhasil dibuat!');
    }

    public function show(KnowledgeBaseArticle $knowledgeBase)
    {
        $knowledgeBase->increment('views');
        $knowledgeBase->load('author');

        $relatedArticles = KnowledgeBaseArticle::where('category', $knowledgeBase->category)
            ->where('id', '!=', $knowledgeBase->id)
            ->where('is_published', true)
            ->limit(3)
            ->get();

        return view('support.knowledge-base.show', compact('knowledgeBase', 'relatedArticles'));
    }

    public function edit(KnowledgeBaseArticle $knowledgeBase)
    {
        return view('support.knowledge-base.edit', compact('knowledgeBase'));
    }

    public function update(Request $request, KnowledgeBaseArticle $knowledgeBase)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        if ($validated['is_published'] && !$knowledgeBase->published_at) {
            $validated['published_at'] = now();
        }

        $knowledgeBase->update($validated);

        return redirect()->route('knowledge-base.index')
            ->with('success', 'Artikel berhasil diperbarui!');
    }

    public function destroy(KnowledgeBaseArticle $knowledgeBase)
    {
        $knowledgeBase->delete();
        return redirect()->route('knowledge-base.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }
}
