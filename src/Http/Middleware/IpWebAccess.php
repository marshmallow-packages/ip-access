<?php

namespace Marshmallow\IpAccess\Http\Middleware;

use Symfony\Component\HttpFoundation\IpUtils;
use Illuminate\Http\Request;
use Closure;

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
        $ipAccessEnabled = config('ip-access.whitelist_env');

        if (app()->environment() !== $excludedEnv || !$ipAccessEnabled) {
            return $next($request);
        }

        foreach ($request->getClientIps() as $ip) {

            if (!$this->isValidIp($ip) && !$this->isValidIpRange($ip)) {

                // THROW ERROR
                return redirect()->away(config('ip-access.redirect_to'));

                //TO DO: THROW ERROR
                $response_status = config('ip-access.response_status');
                $response_message = config('ip-access.response_message');
                abort($response_status, $response_message);

                //TO DO: REDIRECT IF NEEDED
                $redirect_to = config('ip-access.redirect_to');
                if (!empty($redirect_to) && filter_var($redirect_to, FILTER_VALIDATE_URL)) {
                    return redirect()->to($redirect_to);
                }
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

        return in_array($ip, $this->ips);
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
        return IpUtils::checkIp($ip, $this->ipRanges);
    }
}
