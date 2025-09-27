<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerCategory extends Model
{
    use HasFactory;

    protected $fillable = ['category'];

    public function workers()
    {
        return $this->hasMany(Worker::class, 'worker_category_id');
    }
}
