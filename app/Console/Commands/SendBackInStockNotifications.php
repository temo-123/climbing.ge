<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UserNotifications;
use App\Models\Shop\StockNotification;
use App\Services\ProductService;

class SendBackInStockNotifications extends Command
{
    protected $signature = 'app:send-back-in-stock';

    protected $description = 'Email users whose "notify me when back in stock" product option has stock again';

    public function handle()
    {
        $sent = 0;

        StockNotification::whereNull('notified_at')
            ->with(['user:id,email', 'option.product', 'option.warehouse'])
            ->chunkById(200, function ($subscriptions) use (&$sent) {
                foreach ($subscriptions as $sub) {
                    $option = $sub->option;
                    $product = $option?->product;
                    if (!$option || !$product || !$sub->user || !$sub->user->email) continue;
                    if (!$product->published || ProductService::get_option_stock_quantity($option) <= 0) continue;

                    $url = 'https://' . config('app.shop_url') . ($sub->locale === 'ka' ? '/ka' : '') . '/product/' . $product->url_title;
                    $subject = $sub->locale === 'ka' ? 'პროდუქტი ისევ მარაგშია' : 'Back in stock';
                    $text = $sub->locale === 'ka'
                        ? 'პროდუქტი, რომელსაც ელოდებოდით („' . $option->name . '“), ისევ მარაგშია.'
                        : 'The product option you were waiting for ("' . $option->name . '") is back in stock.';

                    UserNotifications::dispatch($url, $text, $subject, $sub->user->email)->onQueue('emails');
                    $sub->notified_at = now();
                    $sub->save();
                    $sent++;
                }
            });

        $this->info("Back-in-stock emails queued: {$sent}");
        return Command::SUCCESS;
    }
}
