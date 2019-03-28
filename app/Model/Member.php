<?php
/**
 * Created by PhpStorm.
 * User: Supakit
 * Date: 7/3/2019
 * Time: 10:50 PM
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;


class Member extends Model
{
    protected $primaryKey = 'uid';
    protected $fillable = ['password', 'name'];
}
?>