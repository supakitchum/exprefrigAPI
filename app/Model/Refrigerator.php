<?php
/**
 * Created by PhpStorm.
 * User: Supakit
 * Date: 7/3/2019
 * Time: 10:52 PM
 */

namespace App\Model;
use Illuminate\Database\Eloquent\Model;


class Refrigerator extends Model
{
    protected $fillable = ['refrig_id', 'name_refrig', 'uid'];
}
?>