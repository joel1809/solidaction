<?php

use App\Http\Middleware\EnsureUserIsStaff;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\ShareSiteData;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(SecurityHeaders::class);

        $middleware->web(append: [
            ShareSiteData::class,
        ]);

        $middleware->alias([
            'staff' => EnsureUserIsStaff::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Envoi dépassant post_max_size : PHP a vidé la requête avant la session,
        // on renvoie donc vers le formulaire avec un indicateur dans l'URL.
        $exceptions->render(function (PostTooLargeException $e, Request $request) {
            $message = 'Les fichiers envoyés sont trop volumineux (limite totale : '
                .ini_get('post_max_size').'o par envoi). Ajoutez moins de photos ou vidéos à la fois.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 413);
            }

            $referer = (string) $request->headers->get('referer');

            if ($referer === '' || parse_url($referer, PHP_URL_HOST) !== $request->getHost()) {
                return response($message, 413);
            }

            $referer = preg_replace('/([?&])envoi=trop-volumineux(&|$)/', '$1', $referer);
            $separator = str_contains(rtrim($referer, '?&'), '?') ? '&' : '?';

            return redirect(rtrim($referer, '?&').$separator.'envoi=trop-volumineux');
        });
    })->create();
