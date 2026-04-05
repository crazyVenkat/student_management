<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    protected $table = 'programmes';

    protected $fillable = ['id', 'department_id', 'name', 'created_at', 'updated_at'];

    public function department() {
        return $this->belongsTo(Department::class);
    }
}
