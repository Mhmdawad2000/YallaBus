<?php

namespace App\Providers;
use Illuminate\Support\Facades\Auth;
use Modules\Settings\Models\SystemLog;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;

class AuditLogServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    /**
     * Summary of boot
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->registerModelObservers();
        });
    }

    /**
     * Summary of registerModelObservers
     * @return void
     */
    protected function registerModelObservers()
    {
        $models = config('audit.allow_models', []);

        foreach ($models as $model) {
            $modelClass = $model['class'];
            $modelTable = $model['table'];

            if (!class_exists($modelClass)) {
                continue;
            }

            $modelClass::created(function ($modelInstance) use ($modelTable) {
                $this->logAction($modelInstance, 'create', $modelTable);
            });

            $modelClass::updated(function ($modelInstance) use ($modelTable) {
                $this->logAction($modelInstance, 'update', $modelTable);
            });

            $modelClass::deleted(function ($modelInstance) use ($modelTable) {
                $this->logAction($modelInstance, 'delete', $modelTable);
            });
        }

    }

    /**
     * Summary of logAction
     * @param mixed $model
     * @param string $action
     * @param string $modelTable
     * @return void
     */
    protected function logAction($model, string $action, string $modelTable)
    {
        $changes = [];
        $original = [];

        if ($action === 'update') {
            $changes = $model->getChanges();
            $original = array_intersect_key($model->getOriginal(), $changes);
        } elseif ($action === 'create') {
            $changes = $model->getAttributes();
        } elseif ($action === 'delete') {
            $original = $model->getAttributes();
        }

        SystemLog::create([
            'model_type' => $modelTable,
            'model_id' => $model->getKey(),
            'action' => $action,
            'old_data' => $action === 'update' ? $original : ($action === 'delete' ? $original : null),
            'new_data' => in_array($action, ['create', 'update']) ? $changes : null,
            'user_id' => Auth::id(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

}
