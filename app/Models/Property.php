<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Property
 *
 * @package App
 * @property string $name
 * @property string $address
 * @property string $photo
*/
class Property extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ['name', 'address', 'photo', 'user_id'];
    
    
    
}
