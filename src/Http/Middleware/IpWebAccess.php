<?php

namespace Marshmallow\IpAccess\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Marshmallow\IpAccess\Models\IpAccess;
use Symfony\Component\HttpFoundation\IpUtils;

class IpWebAccess
{
    /**
     * List of valid IPs.
     *
     * @var array
     */
    protected $ips =  [];

    /**
     * List of valid IP-ranges.
     *
     * @var array
     */
    protected $ipRanges = [];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->ips = config('ip-access.whitelist.list', null);
        $excludedEnv = config('ip-access.whitelist_env');
        $ipAccessEnabled = config('ip-access.enabled');

        if (app()->environment() !== $excludedEnv || !$ipAccessEnabled) {
            return $next($request);
        }

        foreach ($request->getClientIps() as $ip) {

            if (!$this->isValidIp($ip) && !$this->isValidIpRange($ip)) {

                $redirect_to = config('ip-access.redirect_to');

                if (!empty($redirect_to) && filter_var($redirect_to, FILTER_VALIDATE_URL)) {
                    return redirect()->away($redirect_to);
                }

                $response_status = config('ip-access.response_status');
                $response_message = config('ip-access.response_message');

                abort($response_status, $response_message);
            }
        }

        return $next($request);
    }

    /**
     * Check if the given IP is valid.
     *
     * @param $ip
     * @return bool
     */
    protected function isValidIp($ip)
    {
        $whitelistEnv = env('IPACCESS_WHITELIST', null);

        if (isset($whitelistEnv) && !empty($whitelistEnv)) {
            $whitelistEnv = explode(',', $whitelistEnv);
            $this->ips = array_merge($this->ips, $whitelistEnv);
        }

        if (config('ip-access.use_nova')) {
            $this->addNovaSingleIpAddress();
        }

        return in_array($ip, $this->ips);
    }

    protected function addNovaIpAddress(array $ip_address, string $column)
    {
        $this->{$column} = array_merge($this->{$column}, $ip_address);
        $this->{$column} = array_unique($this->{$column});
    }

    protected function addNovaSingleIpAddress()
    {
        $nova_ips = IpAccess::single()->active()->get()->pluck('ip_address')->toArray();
        $this->addNovaIpAddress($nova_ips, 'ips');
    }

    protected function addNovaRangeIpAddress()
    {
        $nova_ips = IpAccess::range()->active()->get()->pluck('ip_address')->toArray();
        $this->addNovaIpAddress($nova_ips, 'ipRanges');
    }

    /**
     * Check if the ip is in the given IP-range.
     *
     * @param $ip
     * @return bool
     */
    protected function isValidIpRange($ip)
    {
        $this->ipRanges = config('ip-access.whitelist.range', null);
        if (config('ip-access.use_nova')) {
            $this->addNovaRangeIpAddress();
        }
        return IpUtils::checkIp($ip, $this->ipRanges);
    }
}
