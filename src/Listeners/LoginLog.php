<?php

namespace Biin2013\Tiger\Admin\Listeners;

use Biin2013\Tiger\Admin\Events\Login as Event;
use Biin2013\Tiger\Admin\Models\Log\Login;

class LoginLog
{
    public function handle(Event $event): void
    {
        Login::query()->create([
            'username' => $event->username,
            'user_id' => $event->userid,
            'ip' => $event->ip,
            'agent' => $event->agent,
            'status' => $event->status
        ]);
    }
}