<?php

namespace App\Http\Controllers\Settings;

use App\Data\DocumentTemplateData;
use App\Http\Controllers\Controller;
use App\Models\DocumentTemplate;
use App\Traits\HandlesDownloads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentTemplateController extends Controller
{
    use HandlesDownloads;

    public function index()
    {
        $templates = DocumentTemplate::with('updatedByUser')
            ->orderBy('title')
            ->get()
            ->map(fn ($template) => DocumentTemplateData::fromModel($template));

        return Inertia::render('settings/Templates', [
            'templates' => $templates,
        ]);
    }

    public function download(DocumentTemplate $template): BinaryFileResponse
    {
        $path = $template->getActivePath();

        if (! file_exists($path)) {
            abort(404, 'Template não encontrado');
        }

        return $this->downloadTemplate($path, $template->slug.'.docx');
    }

    public function downloadOriginal(DocumentTemplate $template): BinaryFileResponse
    {
        $path = $template->getDefaultPath();

        if (! file_exists($path)) {
            abort(404, 'Template original não encontrado');
        }

        return $this->downloadTemplate($path, $template->slug.'_original.docx');
    }

    public function update(Request $request, DocumentTemplate $template)
    {
        $request->validate([
            'file' => ['required', 'file', 'extensions:docx', 'max:5120'],
        ]);

        $customDir = storage_path('app/templates/custom');
        if (! is_dir($customDir)) {
            mkdir($customDir, 0777, true);
        }

        $request->file('file')->move($customDir, $template->slug.'.docx');

        $template->update([
            'updated_by' => Auth::id(),
        ]);

        $template->touch();

        return redirect()->route('templates.index')
            ->with('success', 'Template atualizado com sucesso!');
    }

    public function restore(DocumentTemplate $template)
    {
        $customPath = $template->getCustomPath();

        if (file_exists($customPath)) {
            unlink($customPath);
        }

        $template->update([
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('templates.index')
            ->with('success', 'Template restaurado para o padrão original!');
    }
}
