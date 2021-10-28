<?php

namespace Marshmallow\IpAccess\Http\Middleware;

use Closure;
use Exception;
use Marshmallow\IpAccess\Models\IpAccess;
use Marshmallow\HelperFunctions\Facades\Ip;
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

        $accessPath = config('ip-access.access_path') ?? 'ip-access';
        $accessPathEnabled = config('ip-access.access_path_enabled') ?? false;

        if (app()->environment() !== $excludedEnv || !$ipAccessEnabled) {
            return $next($request);
        }

        if ($this->inExceptArray($request)) {
            return $next($request);
        }

        foreach ($request->getClientIps() as $ip) {

            if (!$this->isValidIp($ip) && !$this->isValidIpRange($ip)) {

                if ($accessPathEnabled && $request->path() == $accessPath && app()->bound('sentry')) {
                    $exception = new Exception("Access with IP {$ip} is requested but denied", 403);
                    report($exception);
                }

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
        $ip = Ip::forcedIpv4($ip);
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

    /**
     * Helper method to merge database results to the
     * properties containing the ip addresses.
     *
     * @license Test
     * @link    Test
     * @param   array  $ip_address Array of ip addresses from the database
     * @param   string $parameter  The property we store it in ($ips | $ipRanges)
     * @return  void
     */
    protected function addNovaIpAddress(array $ip_address, string $parameter)
    {
        $this->{$parameter} = array_merge($this->{$parameter}, $ip_address);
        $this->{$parameter} = array_unique($this->{$parameter});
    }

    /**
     * Get all single ip addresses from the database.
     *
     * @return void
     */
    protected function addNovaSingleIpAddress()
    {
        $nova_ips = IpAccess::single()->active()->get()->pluck('ip_address_v4')->toArray();
        $this->addNovaIpAddress($nova_ips, 'ips');
    }

    /**
     * Get all ranges from the database.
     *
     * @return void
     */
    protected function addNovaRangeIpAddress()
    {
        $nova_ips = IpAccess::range()->active()->get()->pluck('ip_address_v4')->toArray();
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
        $ip = Ip::forcedIpv4($ip);
        $this->ipRanges = config('ip-access.whitelist.range', null);
        if (config('ip-access.use_nova')) {
            $this->addNovaRangeIpAddress();
        }
        return IpUtils::checkIp($ip, $this->ipRanges);
    }

    /**
     * Determine if the request has a URI that should pass through IP verification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        $excepts = config('ip-access.except', null);

        foreach ($excepts as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }
}
