<?php

namespace App\Models;

use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Event
 *
 * @property int $id
 * @property string $title
 * @property string|null $sub_title
 * @property \Illuminate\Support\Carbon|null $sale_start
 * @property \Illuminate\Support\Carbon|null $sale_end
 * @property bool|null $public
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Artist[] $artists
 * @property-read int|null $artists_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Date[] $dates
 * @property-read int|null $dates_count
 * @property-read \App\Models\Slug|null $slug
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereSaleEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereSaleStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereSubTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $image
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereImage($value)
 */

class Event extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'title',
        'sub_title',
        'sale_start',
        'sale_end',
        'public',
        'image',
    ];

    protected $casts = [
        'sale_start' => 'datetime',
        'sale_end' => 'datetime',
        'public' => 'boolean',
    ];

    public function artists(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class, 'artist_event')->withTimestamps();
    }

    public function dates(): HasMany
    {
        return $this->hasMany(Date::class);
    }

    protected static function booted()
    {
        static::deleting(function (Event $event) {
            $image = 'images/events/' . $event->image;
            if (Storage::disk('public')->exists($image)) {
                Storage::disk('public')->delete($image);
            }
            $event->artists()->detach();
            $event->dates()->delete();
        });
    }
}
