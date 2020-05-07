<?php

namespace App\Services;

use App\Jobs\LogJob;
use App\MstOrder;
use Carbon\CarbonImmutable;

class ProcessOrderCustomer
{
    /**
     * @param CarbonImmutable $fromDate
     * @param CarbonImmutable $toDate
     * @param float $maxProfitRateNew
     * @return void
     */
    public function processOrder(CarbonImmutable $fromDate, CarbonImmutable $toDate, float $maxProfitRateNew) : void
    {
        $orderModel = new MstOrder;
        $orders = $orderModel->getOrders($fromDate, $toDate, $maxProfitRateNew);

        $orders->each(function (MstOrder $order) use ($maxProfitRateNew) {
            $priceInvoice = $order->price_invoice;
            $maxProfitPrice = $priceInvoice / (1 - $maxProfitRateNew);
            $order->max_profit_rate = $maxProfitRateNew;
            $order->max_profit_price = $maxProfitPrice;
            $order->save();

            LogJob::dispatch($order, ['max_profit_rate' => $order->max_profit_rate, 'max_profit_price' => $order->max_profit_price])
                ->onQueue('slow-queue');
        });
    }
}
