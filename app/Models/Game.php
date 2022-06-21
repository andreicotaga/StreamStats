<?php

namespace App\Models;

use Database\Factories\GameFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Game
 *
 * @property int $id
 * @property string $name
 * @property string|null $box_art_url
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection|Stream[] $streams
 * @property-read int|null $streams_count
 * @method static GameFactory factory(...$parameters)
 * @method static Builder|Game newModelQuery()
 * @method static Builder|Game newQuery()
 * @method static Builder|Game query()
 * @method static Builder|Game whereBoxArtUrl($value)
 * @method static Builder|Game whereCreatedAt($value)
 * @method static Builder|Game whereId($value)
 * @method static Builder|Game whereName($value)
 * @method static Builder|Game whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
    ];

    public function streams(): HasMany
    {
        return $this->hasMany(Stream::class);
    }
}
