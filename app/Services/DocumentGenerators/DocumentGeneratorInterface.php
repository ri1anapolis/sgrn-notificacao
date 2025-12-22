<?php

namespace App\Services\DocumentGenerators;

use App\Models\Notification;

interface DocumentGeneratorInterface
{
    /**
     * Generate a notification document.
     *
     * @return string The path to the generated temporary file
     */
    public function generate(Notification $notification): string;
}
