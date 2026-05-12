<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Redirect;
use App\Models\Product;

class EnsureUserIsOwner
{
    /**
     * Посредник, определяющий, что Пользователь является собственником арта.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $productId = $request->productId;
        $product = Product::findOrFail($productId);
        $userId = $product->user_id;
        if ($request->user()->id !== $userId) {
            return Redirect::route('product.show', ['productId' => $productId]);
        }
        return $next($request);
    }
}
