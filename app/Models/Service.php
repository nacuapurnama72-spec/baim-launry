<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = ['nama_layanan', 'harga_per_kg', 'unit', 'estimasi_waktu'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'id_layanan');
    }
}
