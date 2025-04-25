<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Service\Payment\PaymentGatewayInterface;
use App\Service\Payment\StripePaymentGateway;
use App\Service\Payment\PaypalPaymentGateway;
use App\Service\CostAdjustmentService;
use App\Interfaces\EmailServiceInterface;
use App\Service\Email\MailgunEmailService;
use App\Service\Email\MailtrapEmailService;
use App\Models\Order;
use App\Observers\OrderObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentGatewayInterface::class, function ($app) {
            $gateway = env('PAYMENT_GATEWAY', 'stripe');

            return $gateway === 'paypal'
                ? new PaypalPaymentGateway()
                : new StripePaymentGateway();
        });
        
        $this->app->bind('cost.adjustment', function () {
            return new CostAdjustmentService();
        });

        $this->app->bind(EmailServiceInterface::class, function ($app) {
            $provider = config('mail.service');

            if ($provider === 'mailgun') {
                return new MailgunEmailService();
            }

            return new MailtrapEmailService();
        });
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Order::observe(OrderObserver::class);
    }
}
