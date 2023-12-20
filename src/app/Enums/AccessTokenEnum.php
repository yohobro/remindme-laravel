<?php

namespace App\Enums;

enum AccessTokenEnum:string
{
    case REFRESH_ACCESS_TOKEN = 'refresh-access-token';
    case ACCESS_TOKEN = 'access-token';
};
