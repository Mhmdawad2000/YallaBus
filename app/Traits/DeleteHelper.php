<?php
namespace App\Traits;

use Illuminate\Support\Facades\Log;


trait DeleteHelper
{
    protected function attemptDelete(string $entityLabel, $name, $model, array $relations = []): array
    {
        try {
            foreach ($relations as $relation => $label) {
                if ($model->$relation()->exists()) {
                    Log::info("$entityLabel => [ID: {$model->id}] Cannot delete: related $label exists.");
                    return [2, "$name المراد حذفه له علاقة مع عنصر اخر"];
                }
            }

            $model->delete();
            Log::info("$entityLabel soft deleted successfully => [ID: {$model->id}]");
            return [true, ''];
        } catch (\Exception $e) {
            Log::error("Failed to delete $entityLabel " . $e->getMessage());
            return [false, '"فشل في حذف ال $name"'];
        }
    }
}