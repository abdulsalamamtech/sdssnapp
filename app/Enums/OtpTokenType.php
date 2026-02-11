<?php

namespace App\Enums;

enum OtpTokenType: string
{
    case ACCOUNT_VERIFICATION = 'account-verification';
    case RESET_PASSWORD = 'reset-password';
    case FORGET_PASSWORD = 'forget-password';

}
