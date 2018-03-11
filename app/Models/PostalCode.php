<?php
/**
 * Created by PhpStorm.
 * User: sithu
 * Date: 3/8/18
 * Time: 10:34 PM
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


/**
 * Class PostalCode
 * @package App\Models
 */
class PostalCode extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'postal_code',
        'post_office',
        'township',
        'district',
        'region'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id'
    ];

}