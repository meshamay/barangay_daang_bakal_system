<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function show(string $path)
    {
        if (!Auth::check() && !Auth::guard('admin')->check()) {
            abort(403);
        }

        $normalized = str_replace('\\', '/', trim($path));
        $normalized = ltrim($normalized, '/');
        $normalized = preg_replace('#^app/public/#', '', $normalized);
        $normalized = preg_replace('#^public/storage/#', '', $normalized);
        $normalized = preg_replace('#^storage/#', '', $normalized);
        $normalized = preg_replace('#^public/#', '', $normalized);

        if ($normalized === '' || str_contains($normalized, '..')) {
            abort(404);
        }

        if (Storage::disk('public')->exists($normalized)) {
            return response()->file(Storage::disk('public')->path($normalized), [
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        if (file_exists(public_path($normalized))) {
            return response()->file(public_path($normalized), [
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        abort(404);
    }
}
