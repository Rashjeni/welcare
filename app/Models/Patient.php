<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = ['code', 'name', 'phone', 'email', 'address'];

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}
