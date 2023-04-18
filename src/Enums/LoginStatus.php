<?php

namespace Biin2013\Tiger\Admin\Enums;

enum LoginStatus: int
{
    case SUCCESS = 1;
    case USERNAME = 2;
    case PASSWORD = 3;
}