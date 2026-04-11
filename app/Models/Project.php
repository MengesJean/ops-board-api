<?php

namespace App\Models;

use App\Enums\ProjectHealth;
use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    protected $table = 'project';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'client_id',
        'name',
        'reference',
        'description',
        'status',
        'priority',
        'health',
        'start_date',
        'due_date',
        'budget',
        'notes',
    ];

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
            'priority' => ProjectPriority::class,
            'health' => ProjectHealth::class,
            'start_date' => 'date',
            'due_date' => 'date',
            'budget' => 'decimal:2',
        ];
    }
}
