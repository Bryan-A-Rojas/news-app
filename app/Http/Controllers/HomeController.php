<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Services\TheGuardianService;

class HomeController extends Controller
{
    public function index(Request $request) {
        $articles = TheGuardianService::fetch([
            'page-size' => 20
        ]);

        return Inertia::render('Home', [
            'articles' => $articles,
        ]);
    }

    public function search(Request $request) {
        $articles = TheGuardianService::fetch([
            'page-size' => 20,
            'q' => $request->search
        ]);

        return $articles;
    }
}
