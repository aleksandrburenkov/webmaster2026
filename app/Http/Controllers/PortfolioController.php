<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        $projects = Project::where('status', 'published')
            ->orderBy('sort_order')
            ->paginate(12);

        return view('pages.portfolio', compact('projects'));
    }

    public function show($slug)
    {
        $project = Project::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        $relatedProjects = Project::where('status', 'published')
            ->where('id', '!=', $project->id)
            ->where(function ($query) use ($project) {
                if ($project->category) {
                    $query->where('category', $project->category);
                }
            })
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        return view('pages.project', compact('project', 'relatedProjects'));
    }
}