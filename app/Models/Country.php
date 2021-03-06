<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table         = 'country';
    protected $primaryKey    = 'id_country';
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    //protected $dateFormat = 'U';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_zone' , 'id_currency' , 'iso_code' , 'call_prefix' , 'active' , 'contains_states' , 'need_identification_number' , 'need_zip_code' , 'zip_code_format' , 'display_tax_label'
    ];

    /**
     * Validation that check request.
     *
     * @var array
     */

    /**
     * Relation with other models to relation data through it.
     */

    public function countryLang()
    {
        return $this->hasOne('App\Models\CountryLang','id_country','id_country');
    }

    public function zone()
    {
        return $this->belongsTo('App\Models\Zone','id_zone','id_zone');
    }
    public function currency()
    {
        return $this->hasOne('App\Models\Currency','id_currency','id_currency');
    }
}
