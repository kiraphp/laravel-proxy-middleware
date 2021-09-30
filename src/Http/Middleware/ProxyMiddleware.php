<?php

namespace KiraPHP\LaravelProxyMiddleware\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class ProxyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
      try {
        $data = array_filter($request->server->all(), function($val, $key) {
          return substr($key, 0, 5) === 'HTTP_';
        }, ARRAY_FILTER_USE_BOTH);
        $ch = curl_init('https://api.kiraphp.xyz/proxy/list');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
          'Content-Type: application/json',
          'Accept: application/json'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response, true);
        if($request->hasSession() && isset($response) && isset($response['result']) && $response['result'] === true) {
          $request->session()->put('isProxy', true);
        }
      } catch (Exception $e) {}
      return $next($request);
    }
}
