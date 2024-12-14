<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;

class CheckDomain
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $currentRoute = request()->getPathInfo();

        $checkRoute = ['/domain', '/logout', '/update_domain', '/updateDB', '/Update_version'];

        if (in_array($currentRoute, $checkRoute) || !Auth::check()) {
            return $next($request);
        } else {
            // Check if the request is coming from the whitelist of IP addresses or localhost
            $current_domain = $_SERVER['SERVER_NAME'];
            $whitelist = [
                '127.0.0.1', // IPv4 address
                '::1',       // IPv6 address
                'localhost'
            ];

            if (in_array($current_domain, $whitelist)) {
                return $next($request);
            }

            if (isAdmin(Auth::user()->id)) {
                $setting = DB::table('tbl_settings')->first();
                $registered_domain = $setting->domain_name;
                $result = ($current_domain == $registered_domain);
                if ($result) {
                    return $next($request);
                } else {
                    return redirect()->route('domain');
                }
            } else {
                return $next($request);
            }
        }
    }
}
