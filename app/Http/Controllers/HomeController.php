<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Factories\NewsFactory;
use App\Models\PinnedArticle;

class HomeController extends Controller
{
    public function index(Request $request) {
        $pinnedArticles = PinnedArticle::all();

        $articles = NewsFactory::create('the_guardian');

        if ($request->search) {
            $articles = $articles->search($request->search);
        }

        $articles = $articles->fetch([
            'page-size' => 20
        ]);

        return Inertia::render('Home', [
            'articles' => $articles,
            'pinnedArticles' => $pinnedArticles,
            'search' => $request->search,
        ]);
    }
}
