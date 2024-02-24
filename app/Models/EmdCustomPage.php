<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmdCustomPage extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'name',
        'slug',
        'page_key',
        'blade_file',
        'content',
        'meta_title',
        'meta_description',
    ];
    public function scopeSitemap($query)
    {
        return $query->where('sitemap', 1);
    }
}
