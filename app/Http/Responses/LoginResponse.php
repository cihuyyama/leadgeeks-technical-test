<?php

namespace App\Http\Responses;

use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Fortify;
use Symfony\Component\HttpFoundation\Response;

/**
 * Always land on the ticket dashboard after login.
 *
 * Fortify's default uses redirect()->intended(), which reopens whatever
 * protected URL a guest hit first (e.g. /tickets/create). That confuses
 * reviewers when create/edit are modal-only on the dashboard.
 */
class LoginResponse implements LoginResponseContract
{
    /**
     * @param  Request  $request
     * @return Response
     */
    public function toResponse($request)
    {
        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        // Drop any prior "intended" URL so login never opens create/edit pages.
        $request->session()->forget('url.intended');

        return redirect()->to(Fortify::redirects('login', '/dashboard'));
    }
}
