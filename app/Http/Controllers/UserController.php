<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * User profile
     */
    public function profile()
    {
        $user = auth()->user();
        return $this->sendJsonSuccess( $user );
    }


}
