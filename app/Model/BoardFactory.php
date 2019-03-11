<?php
/**
 * Created by PhpStorm.
 * User: Supakit
 * Date: 7/3/2019
 * Time: 10:55 PM
 */

namespace App\Model;
use Illuminate\Database\Eloquent\Model;

class BoardFactory extends Model
{
    protected $fillable = ['private_key', 'actived', 'chip_id', 'uid'];
}