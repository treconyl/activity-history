<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ActivityHistory extends Model
{
    protected $table = 'activity_history';
    protected $guarded = [];

    const IS_ADMIN = 1;
    const IS_USER = 2;

    /**
     * Get the user associated with the ActivityHistory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Scope a query to only include activities with specific user keywords.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $keywords
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeKeywords($query, $keywords)
    {
        if ($keywords) {
            return $query->whereHas('user', function ($query) use ($keywords) {
                $query->where('name', 'LIKE', '%' . $keywords . '%');
            });
        }
        return $query;
    }

    /**
     * Scope a query to only include activities with specific status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * Scope a query to only include activities with specific role.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $role
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRole($query, $role)
    {
        if ($role) {
            return $query->where('role', $role);
        }
        return $query;
    }

    /**
     * Scope a query to only include activities with specific activity type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $activity_type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActivityType($query, $activity_type)
    {
        if ($activity_type) {
            return $query->where('activity_type', $activity_type);
        }
        return $query;
    }

    /**
     * Scope a query to only include activities with specific action.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $activity_type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAction($query, $activity_type)
    {
        if (strlen($activity_type) > 0) {
            return $query->where('activity_type', $activity_type);
        }
        return $query;
    }

    /**
     * Scope a query to only include activities within specific date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $start_date
     * @param string|null $end_date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDate($query, $start_date, $end_date)
    {
        if ($start_date && $end_date) {
            return $query->whereBetween('created_at', [$start_date, $end_date]);
        } elseif ($start_date) {
            return $query->where('created_at', '>=', $start_date);
        } elseif ($end_date) {
            return $query->where('created_at', '<=', $end_date);
        }
        return $query;
    }
}
