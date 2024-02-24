<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


class Tool extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'name', 'slug', 'meta_title',
        'meta_description', 'lang', 'parent_id', 'content', 'is_home', 'request_limit',
    ];
    public function children()
    {
        return $this->hasMany(Tool::class, 'parent_id');
    }
    public function parent()
    {
        return $this->belongsTo(Tool::class, 'parent_id');
    }
    public function get_parent_tool($parent_id)
    {
        return Tool::select('name')->where('id', $parent_id)->first()['name'];
    }
}
