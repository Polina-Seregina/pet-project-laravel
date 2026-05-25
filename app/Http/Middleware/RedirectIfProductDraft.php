<?php

namespace App\Http\Middleware;

use App\Enums\ProductsStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfProductDraft
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $product = $request->product;
        $userId = $product->user_id;

        if (($request->user()->id !== $userId) && ($product->status === ProductsStatus::DRAFT->label())) {
            return Redirect::route('products.index');
        }

        return $next($request);
    }
}
