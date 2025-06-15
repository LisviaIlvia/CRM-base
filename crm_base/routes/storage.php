<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

Route::get('images/{path?}', function ($path = null)
{
    if (!Auth::check()) {
        abort(403);
    }

    $fullPath = $path ? 'app/private/images/' . $path : 'app/private';

    $storagePath = storage_path($fullPath);

    if (!File::exists($storagePath)) {
        abort(404);
    }

    $file = File::get($storagePath);
    $type = File::mimeType($storagePath);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->where('path', '.*');

Route::get('images-public/{path?}', function ($path = null)
{
    if ($path === null) {
        abort(404);
    }

    $fullPath = 'app/public/' . $path;

    $storagePath = storage_path($fullPath);

    if (!File::exists($storagePath)) {
        abort(404);
    }

    $file = File::get($storagePath);
    $type = File::mimeType($storagePath);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->where('path', '.*');

Route::get('media/{path?}', function ($path = null)
{
    if (!Auth::check()) {
        abort(403);
    }

    $fullPath = $path ? 'app/private/media/' . $path : 'app/private';

    $storagePath = storage_path($fullPath);

    if (!File::exists($storagePath)) {
        abort(404);
    }

    $file = File::get($storagePath);
    $type = File::mimeType($storagePath);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
})->where('path', '.*');