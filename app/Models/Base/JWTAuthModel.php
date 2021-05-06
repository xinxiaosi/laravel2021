<?php


namespace App\Models\Base;

use JWTAuth;

class JWTAuthModel extends JWTAuth
{
    public function attempt($credentials)
    {
        if (! $this->auth->byCredentials($credentials)) {
            return false;
        }

        return $this->fromUser($this->user());
    }
}
