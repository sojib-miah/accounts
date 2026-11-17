<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CountUniqueVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only count normal website page visits.
        if (
            !$request->isMethod('GET') ||
            $request->expectsJson() ||
            $request->is('admin/*') ||
            $request->is('api/*')
        ) {
            return $next($request);
        }

        $cookieName = 'website_visitor';
        $visitorId = $request->cookie($cookieName);

        $needsCookie = false;

        // Generate a new visitor ID if cookie does not exist.
        if (!$visitorId || !Str::isUuid($visitorId)) {
            $visitorId = (string) Str::uuid();
            $needsCookie = true;
        }

        // Store only a hash of the visitor ID.
        $visitorKey = hash('sha256', $visitorId);

        // Unique database constraint prevents duplicate counting.
        Visitor::firstOrCreate(
            ['visitor_key' => $visitorKey],
            ['first_visited_at' => now()]
        );

        $response = $next($request);

        // Keep visitor identity for one year.
        if ($needsCookie) {
            $response->headers->setCookie(
                cookie(
                    $cookieName,
                    $visitorId,
                    60 * 24 * 365,
                    '/',
                    null,
                    $request->isSecure(),
                    true,
                    false,
                    'lax'
                )
            );
        }

        return $response;
    }
}
