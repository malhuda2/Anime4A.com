<?php
/**
 * Created by PhpStorm.
 * User: Azure Cloud
 * Date: 11/12/2016
 * Time: 8:08 PM
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class DBCategory extends Model
{
    //
    protected $connection = 'mysql';
    protected $table = 'anime4a_category';
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @param $id
     * @return string
     */
    public static function GetName($id)
    {
        try
        {
            $obj = DBCategory::find($id);
            if(!is_null($obj))
                return $obj->name;
            return '';
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
        }
    }
}