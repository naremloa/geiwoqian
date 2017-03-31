<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Contribute
 * @package App\Model
 *
 * @property int $user_id
 * @property int $fund_per_month
 * @property int $contribute_grade
 * @property int $producer_id
 * @property string $create_time
 *
 */

class Contribute extends Model
{
    //
    protected $table = 'contribute';

    public $timestamps = false;

    public static function getContributer($producer_id){
        $contributers = Contribute::where('producer_id',$producer_id)->get()->toArray();

    }
}
