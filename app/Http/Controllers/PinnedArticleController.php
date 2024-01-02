<?php

namespace App\Http\Controllers;

use App\Models\PinnedArticle;
use App\Http\Requests\PinnedArticleRequest;

use Illuminate\Http\Request;

class PinnedArticleController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(PinnedArticleRequest $request)
    {
        $article = PinnedArticle::create($request->validated());

        return $article;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        PinnedArticle::destroy($id);

        return [
            'success' => true,
        ];
    }
}
