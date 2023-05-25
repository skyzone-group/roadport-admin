<?php

namespace App\Http\Controllers\Blade;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Botuser;
use App\Models\Orders;


class OrderController extends Controller
{
    // index
    public function index()
    {
        $orders = Orders::deepFilters()
            ->whereIn('status', [1, 2])
            ->orderByDesc('id')
            ->with(['owner', 'regionFrom', 'regionTo', 'driver' => function ($query) {
                $query->with('car');
            }])
            ->paginate(15);
        //dd($orders);
        return view('pages.order.index',compact('orders'));
    }
}
