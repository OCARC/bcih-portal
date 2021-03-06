<?php

namespace App\Auth;
use http\Exception;
use Symfony\Component\Ldap\Ldap;
use Symfony\Component\Ldap\Exception\ConnectionException;

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

    private $ldap;
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */


    public function retrieveByCredentials(array $credentials)
    {
        if ( env('APP_LDAP_AUTH_ENABLED', false) === true ) {
            if ( $credentials['realm'] == 'ldap') {
                // We should sync the LDAP user here

                // Try to load user from local database
                $query = $this->createModel()->newQuery();
                $query->where('callsign', 'ldap_' . $credentials['callsign']);
                $query->where('realm', $credentials['realm']);

                $user = $query->first();

                if ($this->ldap_bind($credentials) === true) {

                    // If one doesnt exist create one
                    if (!$user) {
                        // Create local user from LDAP

                        // Bind
                        $user = \App\User::create(
                            array(
                                'callsign' => 'ldap_' . strtoupper( $credentials['callsign']),
                                'name' => 'LDAP User',
                                'email' => 'LDAP User',
                                'realm' => 'ldap',
                                'password' => 'LDAP User'
                            )
                        );


                    }
                } else {
                    session()->flash('msg', 'LDAP: Server rejected your credentials');
                    return new \App\User();
                }

                // Sync

                $query = $this->getLDAP()->query('dc=hamwan,dc=ca', '(&(uid=' . $credentials['callsign'] . '))');
                $results = $query->execute();
                if ($results->count() >= 2) {
                    session()->flash('msg', 'LDAP: Server returned more than one account');
                    return new \App\User();
                }
                if ($results->count() == 0) {
                    session()->flash('msg', 'LDAP: Server did not return an account');
                    return new \App\User();
                }

                if ($results->count() == 1) {
                    $entry = $results[0];

                    $user->name = $entry->getAttribute('cn')[0];
                    $user->email = $entry->getAttribute('mail')[0];

                    $user->save();
                    if ( $user->wasChanged() ) {
                        session()->flash('msg', 'LDAP: Your account information was updated with information received from the server.');
                    }
                   // return $user;
                }

                // Sync groups
                $query = $this->getLDAP()->query( 'dc=hamwan,dc=ca','(&(member=uid=va7stv,OU=Users,DC=hamwan,DC=ca)(objectClass=posixGroup))');
                $results = $query->execute();

//                dd( $results );
//                dd('ddd');
                //
//                return new \App\User();
//                foreach ($results as $entry) {
//
//                }
                return $user;
            }
        }

        if ( env('APP_LOCAL_AUTH_ENABLED', true) === true ) {

            if ($credentials['realm'] == 'local') {
                // local user login
                return parent::retrieveByCredentials($credentials); // TODO: Change the autogenerated stub
            }
        }


        return parent::retrieveByCredentials(array()); // TODO: Change the autogenerated stub
    }

    public function validateCredentials(UserContract $user, array $credentials)
    {

        if ( env('APP_LDAP_AUTH_ENABLED', false) === true ) {
            if ( $credentials['realm'] == 'ldap') {
                if ( $this->ldap_bind( $credentials) === true  ) {
                    // Auth Success
                    return true;
                } else {
                    // Fall Through
                }

            }
        }

        if ( env('APP_LOCAL_AUTH_ENABLED', false) === true ) {
            if ( $credentials['realm'] == 'local') {
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

    }

    private function getLDAP( ) {

        if ( env('APP_LDAP_AUTH_ENABLED',false) == true ) {
            if ($this->ldap) {
                return $this->ldap;

            } else {
                $this->ldap = Ldap::create('ext_ldap', array(
                    'host' => env('APP_LDAP_SERVER'),
                    'encryption' => env('APP_LDAP_ENCRYPTION', 'none'),
                ));

            }
            return $this->ldap;

        }

    }

    private function ldap_bind( array $credentials) {


        try {
            $this->getLDAP()->bind("uid=" . $credentials['callsign'] . "," . env('APP_LDAP_DN'), $credentials['password']);

            return true;
        } catch ( ConnectionException $e ) {
            session()->flash('msg', 'LDAP: ' . $e->getMessage() );


            return false;
        }

        return false;

    }

}
