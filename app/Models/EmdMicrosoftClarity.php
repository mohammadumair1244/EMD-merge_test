<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

class EmdMicrosoftClarity extends Model implements Auditable
{
    use HasFactory, SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    protected $fillable = [
        'tool_id',
        'emd_custom_page_id',
        'is_tool_pages',
        'is_custom_pages',
        'show_percentage',
        'clarity_json',
    ];
    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
    public function emd_custom_page()
    {
        return $this->belongsTo(EmdCustomPage::class);
    }
}
