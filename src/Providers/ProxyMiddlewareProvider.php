<?php

namespace KiraPHP\LaravelProxyMiddleware\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use KiraPHP\LaravelProxyMiddleware\Http\Middleware\ProxyMiddleware;
use Exception;

class ProxyMiddlewareProvider extends ServiceProvider {

  public function boot()
  {
    try {
      $kernel = $this->app->make(Kernel::class);
      $kernel->pushMiddleware(ProxyMiddleware::class);
    } catch (Exception $e) {}
  }

}