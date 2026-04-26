<?php

namespace Glacueva\LaravelQueueSchema\Infrastructure\Schema\Models;

use Illuminate\Database\Eloquent\Model;

#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'id',
    'publisher',
    'consumers',
    'version',
    'rules',
])]
#[\Illuminate\Database\Eloquent\Attributes\Table(name: 'queue_schemas', keyType: 'string')]
class QueueSchema extends Model
{
    public $incrementing = false;

    protected $casts = [
        'consumers' => 'json',
        'rules' => 'json',
    ];
}
