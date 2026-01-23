<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentTemplate extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'description',
        'updated_by',
    ];

    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function isCustomized(): bool
    {
        return file_exists($this->getCustomPath());
    }

    public function getActivePath(): string
    {
        if ($this->isCustomized()) {
            return $this->getCustomPath();
        }

        return $this->getDefaultPath();
    }

    public function getDefaultPath(): string
    {
        return storage_path("app/templates/default/{$this->slug}.docx");
    }

    public function getCustomPath(): string
    {
        return storage_path("app/templates/custom/{$this->slug}.docx");
    }
}
