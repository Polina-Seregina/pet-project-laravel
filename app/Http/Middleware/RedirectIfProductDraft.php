<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\ProductsStatus;

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

        if (($request->user()->id !== $userId) && ! ($product->status === ProductsStatus::FORSALE->label())) {
            return Redirect::route('products.index');
        }

        return $next($request);
    }
}
