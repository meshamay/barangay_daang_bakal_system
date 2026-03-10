<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($path)
    {
        $file = storage_path('app/public/' . $path);

        if (!file_exists($file)) {
            abort(404);
        }

        return response()->file($file);
    }
}

// urls.py
from django.conf import settings
from django.conf.urls.static import static

urlpatterns = [
    # ...existing code...
] + static(settings.MEDIA_URL, document_root=settings.MEDIA_ROOT)