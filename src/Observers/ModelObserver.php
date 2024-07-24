<?php

namespace Treconyl\ActivityHistory\Observers;

use Throwable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use Treconyl\ActivityHistory\Models\ActivityHistory;

class ModelObserver
{
    public function created(Model $model)
    {
        $this->logAction($model, 'created');
    }

    public function updated(Model $model)
    {
        $this->logAction($model, 'updated');
    }

    public function deleted(Model $model)
    {
        $this->logAction($model, 'deleted');
    }

    protected function logAction(Model $model, $action)
    {
        // Bỏ qua nếu mô hình nằm trong danh sách loại trừ
        $excludedModels = config('activity-history.excluded_models', []);
        if (in_array(get_class($model), $excludedModels)) {
            return;
        }

        // Lấy thông tin mô hình và hành động
        $modelPath = get_class($model);
        $updatedFields = $model->getDirty();
        $userId = Auth::id() ?? 0;
        $modelId = $model->getKey();

        // Lấy thông tin role từ cấu hình
        $roleResolver = config('activity-history.role_resolver', fn() => 0);
        $roleId = Auth::check() ? $roleResolver(Auth::user()) : 0;

        // Chỉ lấy các trường ban đầu có sự thay đổi
        $changedOriginalFields = array_intersect_key($model->getOriginal(), $updatedFields);

        try {
            ActivityHistory::create([
                'user_id' => $userId,
                'role_id' => $roleId,
                'model' => $modelPath,
                'model_id' => $modelId,
                'action' => $action,
                'updated_fields' => json_encode($updatedFields),
                'original_fields' => json_encode($changedOriginalFields)
            ]);
        } catch (Throwable $exception) {
            Log::error($exception->getMessage());
        }
    }
}
