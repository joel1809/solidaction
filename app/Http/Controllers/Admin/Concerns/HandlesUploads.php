<?php

namespace App\Http\Controllers\Admin\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait HandlesUploads
{
    /**
     * Resolve the value to persist for an image column.
     *
     * Returns the new path when a file was uploaded, null when the user asked to
     * remove the current image, and the untouched current value otherwise. Theme
     * assets (seeded demo content) are never deleted from disk.
     */
    protected function uploadedImage(Request $request, string $field, string $folder, ?string $current = null): ?string
    {
        if ($request->hasFile($field)) {
            $this->deleteImage($current);

            return $request->file($field)->store('uploads/'.$folder, 'public');
        }

        if ($request->boolean('remove_'.$field)) {
            $this->deleteImage($current);

            return null;
        }

        return $current;
    }

    /**
     * Delete a previously uploaded file, ignoring bundled theme assets.
     */
    protected function deleteImage(?string $path): void
    {
        if (blank($path) || str_starts_with($path, 'theme/') || str_starts_with($path, 'http')) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}
