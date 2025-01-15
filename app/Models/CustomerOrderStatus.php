<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerOrderStatus extends Model
{
    use HasFactory;

    protected $guarded = [];




    const STATUS = [
        'register' => 'ثبت سفارش مشتری',
        'processing_by_accountant_step_1' => 'در انتظار بررسی توسط حسابدار',
        'pre_invoice' => 'صدور پیش فاکتور',
        'awaiting_confirm_by_sales_manager' => 'در انتظار تایید توسط همکار فروش',
        'upload_receipt_by_sales_manager' => 'آپلود رسید پرداخت توسط همکار فروش',
        'setad_fee' => 'ثبت کارمزد ستاد',
        'processing_by_accountant_step_2' => 'در انتظار بررسی توسط حسابدار',
        'upload_setad_fee' => 'آپلود رسید کارمزد',
        'send_factor' => 'صدور فاکتور',
        'send_exit_remittance' => 'ارسال حواله خروج به انباردار',
        'waiting_for_send_exit_remittance' => 'در انتظار ثبت خروجی توسط انباردار',
        'approved_exit_remittance' => 'تایید و ثبت خروج توسط انباردار',
    ];


    const ORDER = [
        1 => 'register',
        2 => 'processing_by_accountant_step_1',
        3 => 'pre_invoice',
        4 => 'awaiting_confirm_by_sales_manager',
        5 => 'upload_receipt_by_sales_manager',
        6 => 'setad_fee',
        7 => 'processing_by_accountant_step_2',
        8 => 'upload_setad_fee',
        9 => 'send_factor',
        10 => 'send_exit_remittance',
        11 => 'waiting_for_send_exit_remittance',
        12 => 'approved_exit_remittance',
    ];
    const ORDER_OTHER = [
        1 => 'register',
        2 => 'processing_by_accountant_step_1',
        3 => 'pre_invoice',
        4 => 'awaiting_confirm_by_sales_manager',
        5 => 'upload_receipt_by_sales_manager',
        9 => 'send_factor',
        10 => 'send_exit_remittance',
        11 => 'waiting_for_send_exit_remittance',
        12 => 'approved_exit_remittance',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
