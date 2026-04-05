<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

    protected $fillable = ['id', 'name', 'created_at', 'updated_at' ];

    public function programmes() {
        return $this->hasMany(Programme::class);
    }
}
