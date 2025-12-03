<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheStaticAssets
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only cache static assets
        if ($this->isStaticAsset($request)) {
            $response->header('Cache-Control', 'public, max-age=2592000'); // 30 days
            $response->header('Expires', gmdate('D, d M Y H:i:s', time() + 2592000) . ' GMT');
        }
        
        return $response;
    }

    private function isStaticAsset($request)
    {
        $path = $request->path();
        return preg_match('/\.(css|js|jpg|jpeg|png|gif|svg|ico|woff|woff2|ttf|otf|webp)$/i', $path);
    }
}
