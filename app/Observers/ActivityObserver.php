<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityObserver
{
    public function created(Model $model): void
    {
        $this->record($model, 'created');
    }

    public function updated(Model $model): void
    {
        $this->record($model, 'updated');
    }

    public function deleted(Model $model): void
    {
        $this->record($model, 'deleted');
    }

    private function record(Model $model, string $event): void
    {
        $user = Auth::user();

        $attributes = $model->getAttributes();

        // Never log sensitive fields
        $excluded = [
            'password',
            'remember_token',
            'api_token',
            'secret',
            'token',
        ];

        $attributes = collect($attributes)
            ->except($excluded)
            ->toArray();

        // For updates, capture only changed fields
        if ($event === 'updated') {
            $changes = collect($model->getChanges())
                ->except($excluded)
                ->toArray();

            $original = collect($model->getRawOriginal())
                ->only(array_keys($changes))
                ->except($excluded)
                ->toArray();

            $properties = [
                'old' => $original,
                'attributes' => $changes,
            ];
        } else {
            $properties = [
                'attributes' => $attributes,
            ];
        }

        $properties['company_id'] = $model->company_id
            ?? $user?->company_id;

        $properties['branch_id'] = $model->branch_id
            ?? $user?->branch_id;

        $properties['ip_address'] = Request::ip();
        $properties['url'] = Request::fullUrl();
        $properties['method'] = Request::method();

        $activity = activity($this->getLogName($model))
            ->event($event)
            ->withProperties($properties);

        if ($user) {
            $activity->causedBy($user);
        }

        $activity->performedOn($model)
            ->log(
                class_basename($model) . ' ' . $event
            );
    }

    private function getLogName(Model $model): string
    {
        return strtolower(class_basename($model));
    }
}

