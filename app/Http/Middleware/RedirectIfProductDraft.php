<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Redirect;

class RedirectIfProductDraft
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $product = $request->product;
        $userId = $product->user_id;

        if (($request->user()->id !== $userId) && !($product->status === 'public')) {
            return Redirect::route('products.index');
        }

        return $next($request);
    }
}
