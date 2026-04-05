<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = ['id', 'name', 'email', 'phone', 'department_id', 'programme_id', 'created_at', 'updated_at'];

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function programme() {
        return $this->belongsTo(Programme::class);
    }
}
