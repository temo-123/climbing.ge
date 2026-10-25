<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shop\Product_option;
use App\Models\Shop\StockNotification;

class StockNotificationController extends Controller
{
    // option ids of this product the user is waiting for
    public function get_my_stock_notifications(Request $request, $product_id)
    {
        $option_ids = Product_option::where('product_id', $product_id)->pluck('id');

        return StockNotification::where('user_id', $request->user()->id)
            ->whereIn('option_id', $option_ids)
            ->whereNull('notified_at')
            ->pluck('option_id');
    }

    public function subscribe(Request $request, $option_id)
    {
        $option = Product_option::findOrFail($option_id);

        StockNotification::updateOrCreate(
            ['user_id' => $request->user()->id, 'option_id' => $option->id],
            ['notified_at' => null, 'locale' => $request->input('locale') === 'ka' ? 'ka' : 'us']
        );

        return response()->json(['success' => true]);
    }

    public function unsubscribe(Request $request, $option_id)
    {
        StockNotification::where('user_id', $request->user()->id)->where('option_id', $option_id)->delete();

        return response()->json(['success' => true]);
    }
}
