<?php

namespace Biin2013\Tiger\Admin\Events;

use Biin2013\Tiger\Admin\Enums\LoginStatus;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Login
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public string      $username,
        public int         $userid,
        public string      $ip,
        public string      $device,
        public LoginStatus $status
    )
    {
    }
}