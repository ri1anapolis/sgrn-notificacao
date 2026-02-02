<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

trait HandlesDownloads
{
    /**
     * Download a file with no-cache headers to prevent browser caching.
     */
    protected function downloadFile(string $path, ?string $fileName = null, array $headers = []): BinaryFileResponse
    {
        $defaultHeaders = [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        return response()
            ->download($path, $fileName, array_merge($defaultHeaders, $headers))
            ->deleteFileAfterSend(true);
    }

    /**
     * Download a template file (without deleting it after send).
     */
    protected function downloadTemplate(string $path, ?string $fileName = null, array $headers = []): BinaryFileResponse
    {
        $defaultHeaders = [
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        return response()
            ->download($path, $fileName, array_merge($defaultHeaders, $headers));
    }
}
