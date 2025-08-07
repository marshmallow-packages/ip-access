<?php

namespace Marshmallow\IpAccess\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Marshmallow\HelperFunctions\Facades\Ip;
use Symfony\Component\HttpFoundation\IpUtils;
use Marshmallow\HelperFunctions\Facades\Builder as BuilderHelper;

class IpAccess extends Model
{
    public const SINGLE = 'SINGLE';
    public const RANGE = 'RANGE';

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'from' => 'datetime',
        'till' => 'datetime',
    ];

    public function isCurrentIp()
    {
        return Ip::forcedIpv4($this->ip_address) == Ip::forcedIpv4(request()->ip());
    }

    public function scopeSingle(Builder $builder)
    {
        $builder->where('type', self::SINGLE);
    }

    public function scopeRange(Builder $builder)
    {
        $builder->where('type', self::RANGE);
    }

    public function scopeActive(Builder $builder)
    {
        BuilderHelper::published($builder, 'from', 'till');
    }

    public function setIpVersionHelperColumns()
    {
        if (Ip::isv4($this->ip_address)) {
            $this->ip_address_v4 = $this->ip_address;
            $this->ip_address_v6 = null;
        } else {
            $this->ip_address_v4 = Ip::forcedIpv4($this->ip_address);
            $this->ip_address_v6 = $this->ip_address;
        }
    }

    public static function hasBackofficeAccess()
    {
        $ip_address = Ip::forcedIpv4(request()->ip());
        $ipRanges = IpAccess::where('backoffice_access', true)
            ->active()
            ->get()
            ->pluck('ip_address_v4')
            ->toArray();

        return IpUtils::checkIp($ip_address, $ipRanges);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ip_access) {
            $ip_access->setIpVersionHelperColumns();
        });

        static::updating(function ($ip_access) {
            if ($ip_access->isDirty('ip_address')) {
                $ip_access->setIpVersionHelperColumns();
            }
        });
    }
}
