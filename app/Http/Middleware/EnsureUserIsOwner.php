<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsOwner
{
    /**
     * Посредник, определяющий, что Пользователь является собственником арта.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $product = $request->product;
        $userId = $product->user_id;
        if ($request->user()->id !== $userId) {
            return Redirect::route('product.show', ['product' => $product]);
        }

        return $next($request);
    }
}
