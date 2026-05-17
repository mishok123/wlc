<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\Review;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ProjectReviews extends Component
{
    use WithPagination;

    public Project $project;
    
    // Form fields
    public $sentiment = 'positive';
    public $title = '';
    public $content = '';

    protected $rules = [
        'sentiment' => 'required|in:positive,neutral,negative',
        'title' => 'nullable|string|max:255',
        'content' => 'required|string|min:10',
    ];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function submitReview()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate();

        $this->project->reviews()->create([
            'user_id' => Auth::id(),
            'sentiment' => $this->sentiment,
            'rating' => match($this->sentiment) {
                'positive' => 5,
                'neutral' => 3,
                'negative' => 1,
            },
            'title' => $this->title,
            'content' => $this->content,
        ]);

        // Update aggregated counts
        if ($this->sentiment === 'positive') {
            $this->project->increment('positive_count');
        } elseif ($this->sentiment === 'neutral') {
            $this->project->increment('neutral_count');
        } elseif ($this->sentiment === 'negative') {
            $this->project->increment('negative_count');
        }

        $this->project->increment('review_count');

        // Recalculate project stats & scores
        $this->project->calculateScores();

        $this->reset(['sentiment', 'title', 'content']);
        session()->flash('message', 'Feedback submitted successfully!');
    }

    public function render()
    {
        return view('livewire.project-reviews', [
            'reviews' => $this->project->reviews()->latest()->with('user')->paginate(5),
        ]);
    }
}
