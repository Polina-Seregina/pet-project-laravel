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
     * Редирект при условии, что арт не принадлежит пользователю и арт не в продаже. 
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $product = $request->product;
        $user = $request->user();

        if (!$user->is($product->user) && ($product->status !== ProductsStatus::FORSALE)) {
            return Redirect::route('products.index');
        }

        return $next($request);
    }
}
