<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $fillable = ['id', 'name', 'email', 'phone', 'department_id', 'created_at', 'updated_at'];

    public function department() {
        return $this->belongsTo(Department::class);
    }
}
