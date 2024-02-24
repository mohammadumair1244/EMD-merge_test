<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmdComponent extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'name',
        'key',
        'lang',
        'json_body',
        'parent_id',
    ];
    public function self_parent()
    {
        return $this->belongsTo(EmdComponent::class, 'parent_id');
    }
    public function self_child()
    {
        return $this->hasMany(EmdComponent::class, 'parent_id');
    }
}
