<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TodoModel extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'description'
      ];
}
