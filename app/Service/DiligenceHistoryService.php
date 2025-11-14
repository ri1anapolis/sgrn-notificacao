<?php

namespace App\Service;

use App\Models\Diligence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiligenceHistoryService
{
    public function updateDiligence(Diligence $diligence, array $newData): void
    {
        $hasChangesToLog = false;
        $historyData = [
            'diligence_id' => $diligence->id,
            'user_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $oldResultId = $diligence->diligence_result_id ?? 0;
        $newResultId = $newData['diligence_result_id'] ?? 0;

        if ($oldResultId != $newResultId) {
            $historyData['old_diligence_result_id'] = $diligence->diligence_result_id;
            $historyData['new_diligence_result_id'] = $newData['diligence_result_id'];
            $hasChangesToLog = true;
        }

        $oldObs = $diligence->observations ?? '';
        $newObs = $newData['observations'] ?? '';

        if ($oldObs != $newObs) {
            $historyData['old_observations'] = $diligence->observations;
            $historyData['new_observations'] = $newData['observations'];
            $hasChangesToLog = true;
        }

        if ($hasChangesToLog) {
            DB::table('diligence_histories')->insert($historyData);
        }
    }
}
