<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
		$exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
			if ($request->is('api/*')) {
				return response()->json(['error' => 'Non autenticato.', 'message' => 'È necessario autenticarsi per accedere a questa risorsa.'], 401);
			}
		});

		$exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
			if ($request->is('api/*')) {
				return response()->json(['error' => 'Risorsa non trovata.'], 404);
			}
		});

		$exceptions->renderable(function (Throwable $e, $request) {
			if ($request->is('api/*')) {
				return response()->json([
					'error' => 'Errore del server.',
					'message' => $e->getMessage(),
					'trace' => $e->getTraceAsString(),
				], 500);
			}
		});
    })->create();