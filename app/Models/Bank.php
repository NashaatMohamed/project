<?php

namespace App\Models;

use App\Traits\UUIDTrait;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use UUIDTrait;

    /** 
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'logo'
    ];

    /**
     * Return Default Bank Logo Url
     * 
     * @return string (url)
     */
    public function getDefaultLogo()
    {
        return asset('assets/images/account-add-photo.svg');
    }

    /**
     * Get Logo Url || Default Logo
     * 
     * @return string (url)
     */
    public function getAvatarAttribute()
    {
        return $this->logo ? asset($this->logo) : $this->getDefaultLogo();
    }

    /**
     * List Banks for Select2 Javascript Library
     * 
     * @return collect
     */
    public static function getSelect2Array() {        
        $response = collect();
        foreach(self::all() as $bank){
            $response->push([
                'id' => $bank->id,
                'logo' => $bank->avatar,
                'text' => $bank->name
            ]);
        }
        return $response;
    }
}
