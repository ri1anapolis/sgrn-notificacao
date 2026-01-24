<?php

namespace App\Http\Controllers\Settings;

use App\Data\DiligenceResultData;
use App\Http\Controllers\Controller;
use App\Models\DiligenceResult;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DiligenceResultController extends Controller
{
    public function index()
    {
        $results = DiligenceResult::orderBy('group')->orderBy('order')->get();

        $grouped = $results->groupBy('group')->map(function ($items, $group) {
            return [
                'title' => $group,
                'results' => DiligenceResultData::collect($items),
            ];
        })->values();

        return Inertia::render('settings/DiligenceResults', [
            'groups' => $grouped,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'group' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:diligence_results,code',
            'description' => 'required|string|max:1000',
        ]);

        $maxOrder = DiligenceResult::where('group', $validated['group'])->max('order') ?? 0;

        DiligenceResult::create([
            'group' => $validated['group'],
            'code' => $validated['code'],
            'description' => $validated['description'],
            'order' => $maxOrder + 1,
            'active' => true,
            'is_custom' => true,
        ]);

        return redirect()->back()->with('success', 'Opção de diligência criada com sucesso.');
    }

    public function update(Request $request, DiligenceResult $result)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:1000',
        ]);

        $result->update(['description' => $validated['description']]);

        return redirect()->back()->with('success', 'Descrição atualizada com sucesso.');
    }

    public function toggleActive(DiligenceResult $result)
    {
        $result->update(['active' => ! $result->active]);

        $message = $result->active
            ? 'Opção ativada com sucesso.'
            : 'Opção desativada com sucesso.';

        return redirect()->back()->with('success', $message);
    }

    public function restore(DiligenceResult $result)
    {
        if ($result->original_description === null) {
            return redirect()->back()->with('error', 'Esta opção não possui descrição original.');
        }

        $result->update(['description' => $result->original_description]);

        return redirect()->back()->with('success', 'Descrição restaurada ao padrão.');
    }
}
