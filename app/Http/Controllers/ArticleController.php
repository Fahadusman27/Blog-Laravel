<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ApiService;

class ArticleController extends Controller
{
    /**
     * Show the form for creating a new article.
     */
    public function create(ApiService $api)
    {
        if (!session('is_logged_in')) {
            return redirect()->route('frontend.auth.login');
        }

        try {
            // Fetch categories to populate the dropdown
            $categories = $api->getCategories();
        } catch (\Exception $e) {
            $categories = [];
        }

        return view('pages.articles.create', compact('categories'));
    }

    /**
     * Store a newly created article in the Go backend.
     */
    public function store(Request $request, ApiService $api)
    {
        if (!session('is_logged_in')) {
            return redirect()->route('frontend.auth.login');
        }

        $data = $request->validate([
            'title'       => 'required|string|min:5|max:255',
            'category_id' => 'required|integer',
            'excerpt'     => 'required|string|max:500',
            'body'        => 'required|string',
        ]);

        try {
            // Send data to Go Fiber backend
            $api->post('/posts', $data);

            return redirect()->route('dashboard')->with('flash_success', 'Your article has been submitted for review!');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['message' => 'Failed to publish article: ' . $e->getMessage()]);
        }
    }
}
