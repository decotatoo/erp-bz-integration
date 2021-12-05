<?php

namespace Decotatoo\Bz\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class VerifyWebhookSignature
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
            ! $request->hasHeader('x-wc-webhook-source') 
            || ! $request->hasHeader('x-wc-webhook-signature') 
            || ! $request->hasHeader('x-wc-webhook-topic')
            || rtrim($request->header('x-wc-webhook-source'), '/') !== config('bz.woocommerce.store_url')
            || $request->header('x-wc-webhook-signature') !== base64_encode(hash_hmac('sha256', $request->getContent(), config('bz.webhook.secret'), true))
        ) {
            throw new AccessDeniedHttpException('Invalid webhook signature.');
        }

        return $next($request);
    }
}
