<?php

namespace Decotatoo\WoocommerceIntegration\Http\Controllers;

use Decotatoo\WoocommerceIntegration\Http\Middleware\VerifyWebhookSignature;
use Decotatoo\WoocommerceIntegration\Models\WiProduct;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * TODO:PLACEHOLDER
 */
class WebhookController extends Controller
{
    /**
     * Create a new WebhookController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(VerifyWebhookSignature::class);
    }

    /**
     * Handle successful calls on the controller.
     *
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function successMethod($parameters = [])
    {
        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle calls to missing methods on the controller.
     *
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function missingMethod($parameters = [])
    {
        return new Response;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $method = 'handle' . Str::studly(str_replace('.', '_', $request->header('x-wc-webhook-topic')));

        if (method_exists($this, $method)) {
            return $this->{$method}($request);
        }

        return $this->missingMethod($request);
    }

    protected function handleProductDeleted(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:'.WiProduct::class.',wp_product_id'],
        ]);

        $wcProduct = WiProduct::query()->where('wc_product_id', $request->input('id'))->first();

        $wcProduct->delete();

        return $this->successMethod();
    }

    protected function handleCustomerCreated(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'unique:App\Modiles\WooCommerce\WcCustomer,wc_customer_id',]
        ]);

        $wcCustomer = new WcCustomer();
        $wcCustomer->wc_customer_id = $request->id;
        # code...

        // if customer email exist in erp_customer, then associate, else create new erp_customer instance

        $wcCustomer->save();

        return $this->successMethod();
    }

    protected function handleCustomerUpdated(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:App\Models\WooCommerce\WcCustomer,wc_customer_id'],
        ]);

        $wcCustomer = WcCustomer::query()->where('wc_customer_id', $request->input('id'))->first();

        # code...

        $wcCustomer->save();

        return $this->successMethod();
    }

    protected function handleCustomerDeleted(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:App\Models\WooCommerce\WcCustomer,wc_customer_id'],
        ]);

        $wcCustomer = WcCustomer::query()->where('wc_customer_id', $request->input('id'))->first();

        $wcCustomer->delete();

        return $this->successMethod();
    }

    protected function handleOrderCreated(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:App\Models\WooCommerce\WcOrder,wc_order_id'],
        ]);

        $wcOrder = new WcOrder();

        # code...

        $wcOrder->save();

        return $this->successMethod();
    }

    protected function handleOrderUpdated(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:App\Models\WooCommerce\WcOrder,wc_order_id'],
        ]);

        $wcOrder = WcOrder::query()->where('wc_order_id', $request->input('id'))->first();

        # code...

        $wcOrder->save();

        return $this->successMethod();
    }

    protected function handleOrderDeleted(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer', 'exists:App\Models\WooCommerce\WcOrder,wc_order_id'],
        ]);

        $wcOrder = WcOrder::query()->where('wc_order_id', $request->input('id'))->first();

        $wcOrder->delete();

        return $this->successMethod();
    }
}
