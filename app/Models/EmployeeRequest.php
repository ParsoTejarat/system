<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS = [
        'pending' => 'در انتظار بررسی',
        'approved' => 'تایید شد',
        'not_approved' => 'عدم تایید',
    ];
    const PRIORITY = [
        'low' => 'پایین',
        'medium' => 'متوسط',
        'high' => 'بالا',
    ];

    const REQUEST_FOR = [
        'request_documents' => 'مدارک',
        'request_petty_cash' => 'تنخواه',
        'request_payment_order' => 'دستور پرداخت',
        'request_transportation_cost' => 'پرداخت هزینه ایاب ‌و ذهاب',
        'request_employee_loan' => 'وام پرسنلی',
        'request_office_equipment' => 'تجهیزات اداری',
        'request_employment_certificate' => 'گواهی اشتغال به کار',
        'request_clearance' => 'تسویه حساب',
    ];


    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

}
