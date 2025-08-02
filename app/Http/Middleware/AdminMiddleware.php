<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            Log::warning('Unauthenticated user attempted to access admin route', [
                'ip' => $request->ip(),
                'path' => $request->path(),
            ]);
            return redirect('/login');
        }

        if ($request->user()->type !== 'admin') {
            Log::warning('Unauthorized access attempt to admin route', [
                'user_id' => $request->user()->id,
                'user_email' => $request->user()->email,
                'path' => $request->path(),
            ]);
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}

