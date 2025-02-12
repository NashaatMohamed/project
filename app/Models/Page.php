<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'name',
        'description',
        'content',
        'is_active',
        'is_deletable',
        'order',
    ];

    /**
     * Automatically cast attributes to given types
     * 
     * @var array
     */
    protected $casts = [
        'slug' => 'string',
        'is_active' => 'boolean',
        'is_deletable' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Activate the plan.
     *
     * @return $this
     */
    public function activate()
    {
        $this->update(['is_active' => true]);

        return $this;
    }

    /**
     * Deactivate the plan.
     *
     * @return $this
     */
    public function deactivate()
    {
        $this->update(['is_active' => false]);

        return $this;
    }

    /**
     * Find by slug
     */
    public static function findBySlug($slug)
    {
        return self::where('slug', $slug)->first();
    }

    /**
     * List Plans for Select2 Javascript Library
     * 
     * @return collect
     */
    public static function getSelect2Array() {        
        $response = collect();
        foreach(self::all() as $page){
            $response->push([
                'id' => $page->id,
                'text' => $page->name
            ]);
        }
        return $response;
    }
}
