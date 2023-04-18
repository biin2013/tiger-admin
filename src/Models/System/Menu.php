<?php

namespace Biin2013\Tiger\Admin\Models\System;

use Biin2013\Tiger\Admin\Models\AdminModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends AdminModel
{
    protected $fillable = [
        'pid',
        'name',
        'full_name',
        'icon',
        'url',
        'permission_id',
        'seq',
        'locale',
        'keep_alive',
        'redirect'
    ];
    /**
     * @var string[]
     */
    protected $casts = [
        'full_name' => 'array',
        'keep_alive' => 'boolean'
    ];

    /**
     * @param array<mixed> $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->setTable(config('tiger.admin.database.menu_table'));
        parent::__construct($attributes);
    }

    public function subs(): HasMany
    {
        return $this->hasMany($this, 'id', 'pid');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo($this, 'pid');
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(config('permission.models.permission'), 'permission_id');
    }
}