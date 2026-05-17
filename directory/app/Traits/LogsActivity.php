<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            $model->logAction('created');
        });

        static::updated(function ($model) {
            $model->logAction('updated');
        });

        static::deleted(function ($model) {
            $model->logAction('deleted');
        });
    }

    public function logAction(string $action): void
    {
        if ($this instanceof ActivityLog) {
            return;
        }

        $userId = Auth::id();
        $ipAddress = Request::ip();
        
        $projectId = null;
        if ($this instanceof \App\Models\Project) {
            $projectId = $this->id;
        } elseif (isset($this->project_id)) {
            $projectId = $this->project_id;
        }

        $changes = null;
        $description = '';

        if ($action === 'created') {
            $description = "Created " . class_basename($this) . " (ID: {$this->id})";
            if ($this instanceof \App\Models\Project) {
                $description = "Project '{$this->name}' was added to the platform.";
            } elseif ($this instanceof \App\Models\Review) {
                $description = "A new review was submitted.";
            }
        } elseif ($action === 'deleted') {
            $description = "Deleted " . class_basename($this) . " (ID: {$this->id})";
            if ($this instanceof \App\Models\Project) {
                $description = "Project '{$this->name}' was deleted.";
            }
        } elseif ($action === 'updated') {
            $dirty = $this->getDirty();
            $changes = [];
            $details = [];

            foreach ($dirty as $key => $newValue) {
                if (in_array($key, ['created_at', 'updated_at'])) {
                    continue;
                }
                $oldValue = $this->getOriginal($key);
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue
                ];

                $label = ucwords(str_replace('_', ' ', $key));

                if ($this instanceof \App\Models\Project) {
                    if ($key === 'list_status') {
                        $details[] = "Status updated from '{$oldValue}' to '{$newValue}'";
                    } elseif ($key === 'ownership_verified') {
                        $details[] = "Ownership Verified status changed to " . ($newValue ? 'Yes' : 'No');
                    } else {
                        $details[] = "'{$label}' updated";
                    }
                } elseif ($this instanceof \App\Models\ProjectFieldValue) {
                    if ($key === 'value') {
                        $fieldName = $this->field?->name ?? 'Custom attribute';
                        $details[] = "'{$fieldName}' updated from '{$oldValue}' to '{$newValue}'";
                    }
                } else {
                    $details[] = "'{$label}' updated";
                }
            }

            if (empty($details)) {
                return; 
            }

            $description = "Updated " . class_basename($this) . ": " . implode(', ', $details);
        }

        ActivityLog::create([
            'user_id' => $userId,
            'project_id' => $projectId,
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'action' => $action,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => $ipAddress,
        ]);
    }
}
