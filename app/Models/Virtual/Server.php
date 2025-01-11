<?php

namespace App\Models\Virtual;

use App\Models\Authority;
use App\Models\Remote;
use App\Services\CheckpointerService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Sushi\Sushi;

/**
 * @property string $name
 * @property string $ip_address
 * @property int $port
 * @property string $map
 * @property string $gamemode
 * @property string $country
 * @property int $latency
 * @property int $players_current
 * @property int $players_max
 * @property Carbon $last_updated
 * @property string $game_version
 */
class Server extends Model
{
    use Sushi;

    protected $hidden = [
        'remote',
        'authority',
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];

    /* sushi internal */
    public function getRows()
    {
        return app(CheckpointerService::class)->getList(cacheRemotes: true)->toArray();
    }

    protected function identifier(): Attribute
    {
        return Attribute::get(function(): string {
            $address = explode('.', $this->ip_address);
            $reversedAddress = implode('.', array_reverse($address));
            $decimal = ip2long($reversedAddress);

            return "aos://{$decimal}:{$this->port}";
        });
    }

    public function remote(): BelongsTo
    {
        return $this->belongsTo(Remote::class, foreignKey: 'remote_id');
    }

    public function authority(): BelongsTo
    {
        return $this->belongsTo(Authority::class, foreignKey: 'authority_id');
    }
}
