<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Device extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = ['private_key', 'uid', 'refrig_id', 'noti_id', 'dateTime', 'id', 'dateTimeYellow', 'image'];
}

?>