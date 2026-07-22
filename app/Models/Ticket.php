<?php

namespace App\Models;

use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
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

    /**
     * Search title, assignee, notes, category.
     *
     * @param  Builder<Ticket>  $query
     * @return Builder<Ticket>
     */
    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);

        if ($term === '') {
            return $query;
        }

        $like = '%'.$term.'%';

        return $query->where(function (Builder $builder) use ($like): void {
            $builder
                ->where('title', 'like', $like)
                ->orWhere('assigned_person', 'like', $like)
                ->orWhere('notes', 'like', $like)
                ->orWhere('category', 'like', $like);
        });
    }

    /**
     * @param  Builder<Ticket>  $query
     * @return Builder<Ticket>
     */
    public function scopeStatus(Builder $query, ?string $status): Builder
    {
        if ($status === null || $status === '' || $status === 'all') {
            return $query;
        }

        return $query->where('status', $status);
    }

    /**
     * @param  Builder<Ticket>  $query
     * @return Builder<Ticket>
     */
    public function scopePriority(Builder $query, ?string $priority): Builder
    {
        if ($priority === null || $priority === '' || $priority === 'all') {
            return $query;
        }

        return $query->where('priority', $priority);
    }

    /**
     * @param  Builder<Ticket>  $query
     * @return Builder<Ticket>
     */
    public function scopeCategory(Builder $query, ?string $category): Builder
    {
        if ($category === null || $category === '' || $category === 'all') {
            return $query;
        }

        return $query->where('category', $category);
    }

    /**
     * Sort by status, priority, created_at, or title.
     *
     * @param  Builder<Ticket>  $query
     * @return Builder<Ticket>
     */
    public function scopeSortedBy(Builder $query, ?string $sort, ?string $direction = 'desc'): Builder
    {
        $direction = strtolower((string) $direction) === 'asc' ? 'asc' : 'desc';
        $sort = $sort ?: 'created_at';

        return match ($sort) {
            'title' => $query->orderBy('title', $direction),
            'status' => $query
                ->orderByRaw(
                    "CASE status
                        WHEN 'Open' THEN 1
                        WHEN 'In Progress' THEN 2
                        WHEN 'Resolved' THEN 3
                        WHEN 'Closed' THEN 4
                        ELSE 5
                    END {$direction}"
                )
                ->orderBy('created_at', 'desc'),
            'priority' => $query
                ->orderByRaw(
                    "CASE priority
                        WHEN 'High' THEN 1
                        WHEN 'Medium' THEN 2
                        WHEN 'Low' THEN 3
                        ELSE 4
                    END {$direction}"
                )
                ->orderBy('created_at', 'desc'),
            'category' => $query->orderBy('category', $direction)->orderBy('created_at', 'desc'),
            'assigned_person' => $query->orderBy('assigned_person', $direction)->orderBy('created_at', 'desc'),
            default => $query->orderBy('created_at', $direction),
        };
    }
}
