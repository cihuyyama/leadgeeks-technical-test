<?php

namespace App\Models;

use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $category
 * @property string $priority
 * @property string $status
 * @property string $assigned_person
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['title', 'category', 'priority', 'status', 'assigned_person', 'notes'])]
class Ticket extends Model
{
    /** @use HasFactory<TicketFactory> */
    use HasFactory;
}
