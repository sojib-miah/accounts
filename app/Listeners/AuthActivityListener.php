<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Support\Facades\Request;

class AuthActivityListener
{
    public function registered(Registered $event): void
    {
        $this->log(
            'User registered successfully',
            'registered',
            $event->user
        );
    }

    public function login(Login $event): void
    {
        $this->log(
            'User logged in successfully',
            'login',
            $event->user
        );
    }

    public function logout(Logout $event): void
    {
        $this->log(
            'User logged out successfully',
            'logout',
            $event->user
        );
    }

    public function failed(Failed $event): void
    {
        $credentials = $event->credentials;

        // Never store passwords or other secrets.
        $identity = $credentials['email']
            ?? $credentials['username']
            ?? null;

        $this->log(
            'Failed login attempt',
            'failed_login',
            $event->user,
            [
                'login_identity' => $identity,
            ]
        );
    }

    private function log(
        string $description,
        string $event,
        $user = null,
        array $extra = []
    ): void {
        $properties = array_merge([
            'company_id' => $user?->company_id,
            'branch_id' => $user?->branch_id,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
        ], $extra);

        $activity = activity('authentication')
            ->event($event)
            ->withProperties($properties);

        if ($user) {
            $activity->causedBy($user);
        }

        $activity->log($description);
    }
}
