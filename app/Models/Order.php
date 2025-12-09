<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['order_id', 'is_sent_to_emaar','branch_id', 'tax_rate', 'tax_amount', 'net_amount_without_tax', 'price', 'business_date', 'branch_name', 'status', 'payment_method_id'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }
    public function payment()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id', 'payment_id');
    }
}
