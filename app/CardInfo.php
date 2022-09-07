<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CardInfo extends Model
{
    protected $primaryKey = 'no_kartu';
    protected $fillable = [
        'no_kartu', 'exp_date', 'tipe_kartu', 'nama_pemilik'
    ];

    public function getCreatedAtAttribut()
    {
        if (!is_null($this->attributes['created_at'])) {
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribut()
    {
        if (!is_null($this->attributes['updated_at'])) {
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getDeletedAtAttribut()
    {
        if (!is_null($this->attributes['deleted_at'])) {
            return Carbon::parse($this->attributes['deleted_at'])->format('Y-m-d H:i:s');
        }
    }
}