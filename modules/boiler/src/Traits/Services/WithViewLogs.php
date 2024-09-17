<?php

namespace Boiler\Traits\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Boiler\Http\Resources\ActivityIndexFormat;

trait WithViewLogs
{
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return array
     */
    public function getModelActivity(Request $request, Model $model): array
    {
        $query = $this->getSpecificActivity($model)->with(['causer', 'subject']);
        $query = $this->filterActivityLogs($query, $request);
        $query = $this->sortActivityLogs($query, $request);

        return [
            'logs' => match ($request->query('tab')) {
                'activity_logs' => ActivityIndexFormat::collection($query->paginate($request->input('per_page'))->appends($request->query())),
                default => [],
            },
            'logsCount' => $query->count(),
        ];
    }
}
