<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreKnowledgeBaseRequest;
use App\Http\Requests\Admin\UpdateKnowledgeBaseRequest;
use App\Http\Resources\KnowledgeBaseResource;
use App\Models\KnowledgeBaseArticle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KnowledgeBaseController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = KnowledgeBaseArticle::with('author');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        $articles = $query->latest()->paginate($request->integer('per_page', 15));

        return KnowledgeBaseResource::collection($articles);
    }

    public function store(StoreKnowledgeBaseRequest $request): KnowledgeBaseResource
    {
        $data = $request->validated();
        $data['author_id'] = Auth::id();
        $data['slug'] = Str::slug($data['title']);
        
        if (($data['is_published'] ?? false)) {
            $data['published_at'] = now();
        }

        $article = KnowledgeBaseArticle::create($data);

        return new KnowledgeBaseResource($article);
    }

    public function show(KnowledgeBaseArticle $knowledgeBase): KnowledgeBaseResource
    {
        $knowledgeBase->increment('views');
        return new KnowledgeBaseResource($knowledgeBase->load('author'));
    }

    public function update(UpdateKnowledgeBaseRequest $request, KnowledgeBaseArticle $knowledgeBase): KnowledgeBaseResource
    {
        $data = $request->validated();

        if (isset($data['title']) && $data['title'] !== $knowledgeBase->title) {
            $data['slug'] = Str::slug($data['title']);
        }

        if (($data['is_published'] ?? false) && !$knowledgeBase->published_at) {
            $data['published_at'] = now();
        }

        $knowledgeBase->update($data);

        return new KnowledgeBaseResource($knowledgeBase);
    }

    public function destroy(KnowledgeBaseArticle $knowledgeBase): JsonResponse
    {
        $knowledgeBase->delete();

        return response()->json([
            'message' => 'Artikel berhasil dihapus'
        ]);
    }
}
