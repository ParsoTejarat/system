<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\BotUser;
use App\Models\Customer;
use App\Models\Factor;
use App\Models\Inventory;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Printer;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Notifications\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function createOrder(Request $request)
    {


        Log::info('response:', $request->all());
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'national_number' => 'required',
            'province' => 'required',
            'city' => 'required',
            'address_1' => 'required',
            'postal_code' => 'required',
            'payment_type' => 'required',
            'items' => 'required|array|min:1',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.total' => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            Log::error('Validation errors: ', $validator->errors()->toArray());
            return response()->json([
                'error' => 'Validation failed',
                'details' => $validator->errors()
            ], 422);
        }
        $data = $request->all();
        // محاسبه هزینه ارسال

        $role_id = \App\Models\Role::whereHas('permissions', function ($permission) {
            $permission->where('name', 'online_sale');
        })->pluck('id');

        $single_price_user = User::whereIn('role_id', $role_id)->latest()->first();



        $customer = Customer::where('phone1', 'like', '%' . $data['phone'] . '%')->first();


        if ($customer) {
            Log::error('Validation errors: Here The Code' . $customer);

            $customer->update([
                'user_id' => $single_price_user->id,
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'type' => 'private',
                'economical_number' => 0,
                'province' => $data['province'],
                'city' => $data['city'],
                'national_number' => $data['national_number'],
                'address1' => $data['address_1'],
                'postal_code' => $data['postal_code'],
                'customer_type' => 'online-sale',
                'code' => $this->getNextCustomerCode(),
            ]);
        } else {
            Log::error('Validation errors: Here The else Code' . $customer);
            $customer = Customer::create([
                'phone1' => $data['phone'],
                'user_id' => $single_price_user->id,
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'type' => 'private',
                'economical_number' => 0,
                'province' => $data['province'],
                'city' => $data['city'],
                'national_number' => $data['national_number'],
                'address1' => $data['address_1'],
                'postal_code' => $data['postal_code'],
                'customer_type' => 'online-sale',
                'code' => $this->getNextCustomerCode(),
            ]);
        }

        $products = [];
        $other_products = [];

        foreach ($request->items as $item2) {
            $other_products[] = [
                'other_products' => (string)$item2['name'],
                'other_colors' => 'نامشخص',
                'other_counts' => (string)$item2['quantity'],
                'other_units' => 'number',
                'other_prices' => (string)($item2['total'] / $item2['quantity']) * 10,
                'other_total_prices' => (string)$item2['total'] * 10,
            ];
        }

        $products_data = json_encode([
            'products' => $products,
            'other_products' => $other_products,
        ]);


        $order = \App\Models\Order::create([
            'description' => 'خرید از سایت',
            'type' => 'private',
            'req_for' => 'invoice',
            'code' => $this->generateCode(),
            'user_id' => $single_price_user->id,
            'customer_id' => $customer->id,
            'create_in' => $data['created_in'],
            'products' => $products_data,
        ]);
        $order->order_status()->updateOrCreate(
            ['status' => 'register'],
            ['orders' => 1, 'status' => 'register']
        );


        $notifiables = User::whereHas('role', function ($role) {
            $role->whereHas('permissions', function ($q) {
                $q->whereIn('name', ['ceo', 'sales-manager', 'admin']);
            });
        })->get();

        if ($data['created_in'] == 'website') {
            $notif_message = 'یک سفارش از سایت بارمان سیستم دریافت گردید';
            $notif_title = 'سفارش از سایت';
        } else {
            $notif_message = 'یک سفارش از اپلیکیشن بارمان سیستم دریافت گردید';
            $notif_title = 'سفارش از اپلیکیشن';
        }

        $url = route('orders.index');
        Notification::send($notifiables, new SendMessage($notif_message, $url, $notif_title));

        Log::info('Order processed successfully', ['data' => $data]);
        return response()->json([
            'success' => true,
            'message' => 'سفارش با موفقیت از طریق سایت در اتوماسیون ایجاد شد',
            'data' => $data
        ], 200);
    }

    public function generateCode()
    {
        $code = '666' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);

        while (Order::where('code', $code)->lockForUpdate()->exists()) {
            $code = '666' . str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        }

        return $code;
    }

    public function getNextCustomerCode()
    {
        return Customer::max('code') + 1;
    }

    private function send_notif_to_accountants(Order $order)
    {
        $roles_id = Role::whereHas('permissions', function ($q) {
            $q->where('name', 'accountant');
        })->pluck('id');
        $accountants = User::where('id', '!=', auth()->id())->whereIn('role_id', $roles_id)->get();

        $url = route('invoices.edit', $order->id);
        $title = "سفارش مشتری از سایت";
        $message = "سفارش '{$order->customer->name}' ثبت شد";

        Notification::send($accountants, new SendMessage($message, $url, $title));
    }
}
