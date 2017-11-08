<?php

namespace App\Auth;

use Illuminate\Support\Str;
use Illuminate\Contracts\Auth\UserProvider;
//use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;


use Illuminate\Auth\EloquentUserProvider;
//use Illuminate\Support\Str;
//use phpseclib\Crypt\RSA;

use \phpseclib\Crypt\RSA;

class HamWanUserProvider extends EloquentUserProvider
{


    public function validateCredentials(UserContract $user, array $credentials)
    {
        $rsa = new RSA();
        $rsa->setSignatureMode(RSA::SIGNATURE_PKCS1);

        $token = \Session::token();

        $signature = $credentials['password'];

        if (!$signature) {
            return false;
        }


        if ( strlen($signature) & 1 ) {

        } else {
            foreach ($user->rsa_keys as $key) {
                $rsa->loadKey($key->public_key);
                $status = $rsa->verify(\Session::token(), hex2bin($signature));
                if ($status == true) {
                    return $status;
                }
            }
        }


        //TODO: check if user is allowed to use a password or not
        // Fall Through to password checking
        return parent::validateCredentials($user, $credentials);
    }

}
