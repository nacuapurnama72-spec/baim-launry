<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $guarded = ['id'];

    protected $casts = ['tanggal_masuk' => 'date', 'estimasi_selesai' => 'datetime', 'tanggal_selesai' => 'datetime', 'berat_kg' => 'decimal:2'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'id_layanan');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
