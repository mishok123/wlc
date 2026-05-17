<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use App\Models\ProjectRequest;
use App\Models\ProjectReport;
use Illuminate\Support\Facades\Request;

class ProjectDetail extends Component
{
    public Project $project;
    public $reportDescription = '';
    public $showReportModal = false;

    protected $rules = [
        'reportDescription' => 'required|min:10|max:1000',
    ];

    public function mount(Project $project)
    {
        $this->project = $project;
    }

    public function requestAction($type)
    {
        if (!in_array($type, ['approve', 'verify'])) {
            return;
        }

        if ($type === 'approve' && $this->project->list_status !== 'pending') {
            return;
        }

        if ($type === 'verify' && $this->project->list_status !== 'approved') {
            return;
        }

        $userId = auth()->id();

        if (!$userId) {
            session()->flash('request_error', 'You must be logged in to submit a request.');
            return;
        }

        $ipAddress = Request::ip();

        // Check duplicate per user per project per type
        $exists = ProjectRequest::where('project_id', $this->project->id)
            ->where('request_type', $type)
            ->where('user_id', $userId)
            ->exists();

        if ($exists) {
            session()->flash('request_error', 'You have already submitted a request for this project.');
            return;
        }

        ProjectRequest::create([
            'project_id' => $this->project->id,
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'request_type' => $type,
        ]);
        
        $this->project->refresh();

        session()->flash('request_success', 'Your request has been submitted successfully.');
    }

    public function openReportModal()
    {
        $this->reportDescription = '';
        $this->showReportModal = true;
    }

    public function closeReportModal()
    {
        $this->showReportModal = false;
    }

    public function submitReport()
    {
        $this->validate();

        $ipAddress = Request::ip();
        $userId = auth()->id();

        // Check for existing report from same user/ip for this project
        $existing = ProjectReport::where('project_id', $this->project->id)
            ->where(function ($query) use ($ipAddress, $userId) {
                $query->where('ip_address', $ipAddress);
                if ($userId) {
                    $query->orWhere('user_id', $userId);
                }
            })
            ->exists();

        if ($existing) {
            $this->showReportModal = false;
            session()->flash('request_error', 'You have already submitted a report for this project.');
            return;
        }

        ProjectReport::create([
            'project_id' => $this->project->id,
            'user_id' => $userId,
            'ip_address' => $ipAddress,
            'description' => $this->reportDescription,
            'status' => 'pending',
        ]);

        $this->showReportModal = false;
        $this->reportDescription = '';
        session()->flash('request_success', 'Your report has been submitted to the moderators.');
    }

    public function render()
    {
        $activityLogs = \App\Models\ActivityLog::where('project_id', $this->project->id)
            ->with('user')
            ->latest()
            ->get();

        return view('livewire.project-detail', [
            'categoryMaxRep' => \App\Models\Project::getCategoryMaxReputation($this->project->category_id),
            'categoryMaxPriv' => \App\Models\Project::getCategoryMaxPrivacy($this->project->category_id),
            'activityLogs' => $activityLogs,
        ])->layout('components.layouts.app', [
                'title' => $this->project->name,
                'meta_title' => $this->project->meta_title,
                'meta_description' => $this->project->meta_description,
            ]);
    }
}
