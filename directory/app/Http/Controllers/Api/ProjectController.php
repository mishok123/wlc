<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::with('category')
            ->where('list_status', '!=', 'proposed')
            ->paginate(20);

        return response()->json($projects);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $project = Project::with(['category', 'reviews', 'reviews.user'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json($project);
    }
}
