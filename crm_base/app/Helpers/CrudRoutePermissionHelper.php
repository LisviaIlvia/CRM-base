<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

class CrudRoutePermissionHelper
{
    public static function resource(string $prefix, string $bind, string $controller, array $options = [])
    {
        $defaults = [
            'export' => false,
            'pdf' => false,
			'clone' => false,
            'magic' => false,
            'permission' => '',
            'routes_excluded' => []
        ];

        $options = array_merge($defaults, $options);
        $permission = $options['permission'] ?: $prefix;

        $operations = [
            'export' => function() use ($prefix, $bind, $controller, $permission) { self::export($prefix, $bind, $controller, $permission); },
            'pdf' => function() use ($prefix, $bind, $controller, $permission) { self::pdf($prefix, $bind, $controller, $permission); },
			'clone' => function() use ($prefix, $bind, $controller, $permission) { self::clone($prefix, $bind, $controller, $permission); },
            'magic' => function() use ($prefix, $bind, $controller, $permission) { self::magic($prefix, $bind, $controller, $permission); },
            'create' => function() use ($prefix, $controller, $permission) { self::create($prefix, $controller, $permission); },
            'show' => function() use ($prefix, $bind, $controller, $permission) { self::show($prefix, $bind, $controller, $permission); },
            'edit' => function() use ($prefix, $bind, $controller, $permission) { self::edit($prefix, $bind, $controller, $permission); },
            'delete' => function() use ($prefix, $bind, $controller, $permission) { self::delete($prefix, $bind, $controller, $permission); },
        ];

        foreach ($operations as $operation => $callback) {
            if ($operation === 'export' || $operation === 'pdf' || $operation === 'magic') {
                if ($options[$operation] === true) {
                    $callback();
                }
            } else {
                if (!in_array($operation, $options['routes_excluded'])) {
                    $callback();
                }
            }
        }
    }

    public static function create(string $prefix, string $controller, string $permission, bool $only_store = false)
    {
        Route::group(['middleware' => ["permission:{$permission}.create"]], function () use ($prefix, $controller, $only_store) {
            if($only_store === false) Route::get("/{$prefix}/create", [$controller, 'create'])->name($prefix.'.create');
            Route::post("/{$prefix}", [$controller, 'store'])->name($prefix.'.store');
        });
    }

    public static function show(string $prefix, ?string $bind, string $controller, string $permission, bool $only_index = false)
    {
        Route::group(['middleware' => ["permission:{$permission}.show"]], function () use ($prefix, $bind, $controller, $only_index) {
            Route::get("/{$prefix}", [$controller, 'index'])->name($prefix.'.index');
            if ($only_index === false) {
                Route::get("/{$prefix}/{{$bind}}", [$controller, 'show'])->name($prefix.'.show');
            }
        });
    }

    public static function edit(string $prefix, string $bind, string $controller, string $permission, bool $only_update = false)
    {
        Route::group(['middleware' => ["permission:{$permission}.edit"]], function () use ($prefix, $bind, $controller, $only_update) {
            if($only_update === false) Route::get("/{$prefix}/{{$bind}}/edit", [$controller, 'edit'])->name($prefix.'.edit');
            Route::put("/{$prefix}/{{$bind}}", [$controller, 'update'])->name($prefix.'.update');
        });
    }

    public static function delete(string $prefix, string $bind, string $controller, string $permission)
    {
        Route::group(['middleware' => ["permission:{$permission}.delete"]], function () use ($prefix, $bind, $controller) {
            Route::delete("/{$prefix}/{{$bind}}", [$controller, 'destroy'])->name($prefix.'.destroy');
        });
    }

    public static function export(string $prefix, ?string $bind = null, string $controller, string $permission)
    {
        Route::group(['middleware' => ["permission:{$permission}.export"]], function () use ($prefix, $bind, $controller) {
            Route::get("/{$prefix}/export/{{$bind}?}", [$controller, 'export'])->name($prefix.'.export');
        });
    }

    public static function pdf(string $prefix, string $bind, string $controller, string $permission)
    {
        Route::group(['middleware' => ["permission:{$permission}.pdf"]], function () use ($prefix, $bind, $controller) {
            Route::get("/{$prefix}/pdf/{{$bind}}", [$controller, 'pdf'])->name($prefix.'.pdf');
        });
    }

	public static function clone(string $prefix, string $bind, string $controller, string $permission)
    {
        Route::group(['middleware' => ["permission:{$permission}.clone"]], function () use ($prefix, $bind, $controller) {
            Route::post("/{$prefix}/{{$bind}}/clone", [$controller, 'clone'])->name($prefix.'.clone');
        });
    }

    public static function magic(string $prefix, string $bind, string $controller, string $permission)
    {
        Route::group(['middleware' => ["permission:{$permission}.magic"]], function () use ($prefix, $bind, $controller) {
			Route::get("/{$prefix}/magic/{{$bind}}", [$controller, 'magic'])->name($prefix.'.magic');
            Route::post("/{$prefix}/magic-sync/{{$bind}}", [$controller, 'magicSync'])->name($prefix.'.magicsync');
        });
    }
}