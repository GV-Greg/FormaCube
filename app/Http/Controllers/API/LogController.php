<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LogResource;
use App\Model\Log;

class LogController extends Controller
{
    public function show($id) {
        $logs = Log::where('inscrit_id', $id)->get();
        $logsList = [];
        foreach ($logs as $item) {
            $log_id = $item->id;
            $log = new LogResource(Log::findOrFail($log_id));
            array_push($logsList, $log);
        }

        return response()->json([
            'logs' => $logsList
        ], 200);
    }
}
