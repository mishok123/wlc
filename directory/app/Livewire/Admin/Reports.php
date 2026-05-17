<?php

namespace App\Livewire\Admin;

use App\Models\ProjectReport;
use Livewire\Component;
use Livewire\WithPagination;

class Reports extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = ''; // empty means all

    public $selectedReport = null;
    public $showDetailModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function changeStatus($reportId, $newStatus)
    {
        $report = ProjectReport::find($reportId);
        if ($report) {
            $report->update(['status' => $newStatus]);
            session()->flash('message', 'Report #' . $reportId . ' status updated to ' . ucfirst($newStatus) . '!');
        }
    }

    public function deleteReport($reportId)
    {
        $report = ProjectReport::find($reportId);
        if ($report) {
            $report->delete();
            if ($this->selectedReport && $this->selectedReport['id'] == $reportId) {
                $this->closeDetailModal();
            }
            session()->flash('message', 'Report #' . $reportId . ' has been deleted successfully!');
        }
    }

    public function viewReport($reportId)
    {
        $report = ProjectReport::with(['project', 'user'])->find($reportId);
        if ($report) {
            $this->selectedReport = $report->toArray();
            $this->showDetailModal = true;
        }
    }

    public function closeDetailModal()
    {
        $this->selectedReport = null;
        $this->showDetailModal = false;
    }

    public function render()
    {
        $query = ProjectReport::query()
            ->with(['project', 'user'])
            ->when($this->search, function ($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('project', function ($pq) {
                      $pq->where('name', 'like', '%' . $this->search . '%');
                  });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            });

        $reports = $query->latest()->paginate(10);

        // Stats queries
        $stats = [
            'total' => ProjectReport::count(),
            'pending' => ProjectReport::where('status', 'pending')->count(),
            'resolved' => ProjectReport::where('status', 'resolved')->count(),
            'dismissed' => ProjectReport::where('status', 'dismissed')->count(),
        ];

        return view('livewire.admin.reports', [
            'reports' => $reports,
            'stats' => $stats,
        ])->layout('components.admin.layout', ['title' => 'Project Reports']);
    }
}
