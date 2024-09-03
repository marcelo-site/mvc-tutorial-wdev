<?php

namespace App\Http\Middleware;

use App\Session\Admin\Login as SessionAdminLogin;

class RequireAdminLogout
{
  public function handle($request, $next)
  {
    // echo '<pre>';
    // print_r(!SessionAdminLogin::isLogged());
    // echo '</pre>';
    // exit;


    if (SessionAdminLogin::isLogged()) {
      $request->getRouter()->redirect('/admin');
    }
    return $next($request);
  }
}
