<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_id',
        'invoice_no',
        'sale_date',
        'subtotal',
        'discount_amount',
        'discount_type',
        'vat_amount',
        'vat_percentage',
        'total_amount',
        'paid_amount',
        'due_amount',
        'payment_method',
        'payment_status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'sale_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'vat_percentage' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_amount' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // Generate unique invoice number
    public static function generateInvoiceNo($userId)
    {
        $lastSale = self::where('user_id', $userId)->latest()->first();
        $lastNumber = $lastSale ? (int) substr($lastSale->invoice_no, -6) : 0;
        $nextNumber = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
        return 'INV-' . date('Ymd') . '-' . $nextNumber;
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('sale_date', today());
    }

    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('sale_date', [$from, $to]);
    }

    public function scopeMonthly($query, $year, $month)
    {
        return $query->whereYear('sale_date', $year)->whereMonth('sale_date', $month);
    }
}
