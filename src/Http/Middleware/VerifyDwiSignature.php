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
            || $request->header('x-dwi-signature') !== base64_encode(hash_hmac('sha256', config('woocommerce-integration.credential.username'), config('woocommerce-integration.credential.password'), true))
        ) {
            throw new AccessDeniedHttpException('Invalid Dwi signature.');
        }

        return $next($request);
    }
}
