<?php

namespace Decotatoo\WoocommerceIntegration\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VerifyDwiSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (
            ! $request->hasHeader('x-dwi-signature') 
            || $request->header('x-dwi-signature') !== config('woocommerce-integration.secret')
        ) {
            throw new AccessDeniedHttpException('Invalid Dwi signature.');
        }

        return $next($request);
    }
}
