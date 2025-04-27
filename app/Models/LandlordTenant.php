<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Property
 *
 * @package App
 * @property string $name
 * @property string $address
 * @property string $photo
*/
class LandlordTenant extends Model
{
    use SoftDeletes;

    protected $table = 'landlord_tenants';

    protected $guarded = ['id'];

    // public function property()
    // {
    //     return $this->belongsTo(Property::class);
    // }
    
    public function tenant()
    {
        return $this->belongsTo(User::class);
    }

    public function landlord()
    {
        return $this->belongsTo(User::class);
    }
    
}
