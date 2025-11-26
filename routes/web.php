<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use PhpOffice\PhpWord\TemplateProcessor;


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/testar-word', function () {
    // 1. Carrega o template que você salvou
    // O storage_path aponta para a pasta /storage do seu projeto
    $templatePath = storage_path('app/templates/teste.docx');

    // Verifica se o arquivo existe antes de tentar (pra não dar erro feio)
    if (!file_exists($templatePath)) {
        return 'ERRO: Arquivo não encontrado em: ' . $templatePath;
    }

    // 2. Inicia o Processador de Template
    $templateProcessor = new TemplateProcessor($templatePath);

    // 3. Substitui as variáveis (Onde tem ${...} no Word)
    $templateProcessor->setValue('nome_usuario', 'Gustavo Developer');
    $templateProcessor->setValue('protocolo', '999.888');
    $templateProcessor->setValue('data_atual', date('d/m/Y H:i'));

    // 4. Salva o arquivo processado em uma pasta temporária do sistema
    $tempFileName = tempnam(sys_get_temp_dir(), 'SGRN_word_');
    $templateProcessor->saveAs($tempFileName);

    // 5. Força o download para o navegador e deleta o arquivo temporário depois
    return response()->download($tempFileName, 'documento_gerado.docx')->deleteFileAfterSend(true);
});

require __DIR__.'/notifications.php';
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/data_processing.php';


