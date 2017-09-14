<?php

namespace App\Providers;

use Log;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }



  /**
   * Boot the authentication services for the application.
   *
   * @return void
   */
  public function boot()
  {
    // Here you may define how you wish users to be authenticated for your Lumen
    // application. The callback which receives the incoming request instance
    // should return either a User instance or null. You're free to obtain
    // the User instance via an API token or any other method necessary.

    $this->app['auth']->viaRequest('api', function ($request) {

      // authentication URL query string
      if ($request->input('api_token')) {

        return User::where('api_token', $request->input('api_token'))->first();
      }

      // authentication via HTTP Header 
      $token = $request->header('Authorization');
      Log::info($token);
      if ($token) {
        $token = substr($token, 7); // Remove the 'Bearer ' part
        return User::where('api_token', $token)->first();
      }

    });
  }
}
