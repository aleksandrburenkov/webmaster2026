<?php

namespace App\Http\Controllers;

use App\Models\Project;

class HomeController extends Controller
{
    public function __invoke()
    {
        $featuredProjects = Project::where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $allProjects = Project::where('status', 'published')
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        return view('pages.home', compact('featuredProjects', 'allProjects'));
    }
}