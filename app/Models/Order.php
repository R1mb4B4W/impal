<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'orders';
    protected $fillable = ['receiver', 'address', 'total_price', 'date', 'status', 'detail_status', 'catatan', 'user_id'];

    public static $deliveryStatuses = [
        'menunggu konfirmasi pembayaran',
        'pesanan sedang disiapkan',
        'pesanan selesai, menunggu konfirmasi penjemputan',
        'selesai'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function confirm()
    {
        return $this->hasOne(Confirm::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
                    ->withPivot('quantity', 'subtotal');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}