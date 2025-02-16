<?php

namespace App\Enums;

enum UserRoleEnum : string
{
    case SUPERADMIN = "super-admin";
    case ADMIN = 'admin';
    case USER = "user";

    case REFINERY = "refinery";
    case MARKETER = "marketer";
    case TRANSPORTER = "transporter";

    case DRIVER = "driver";
    case REPRESENTATIVE = "representative";
    case CUSTOMER = "customer";



    public function label(): string {
        return match($this) {
            UserRoleEnum::SUPERADMIN => "super-admin",
            UserRoleEnum::ADMIN => 'admin',
            UserRoleEnum::USER => "user",

            UserRoleEnum::REFINERY => "refinery",
            UserRoleEnum::MARKETER => "marketer",
            UserRoleEnum::TRANSPORTER => "transporter",

            UserRoleEnum::DRIVER => "driver",
            UserRoleEnum::REPRESENTATIVE => "representative",
            UserRoleEnum::CUSTOMER => "customer",
        };

        // return  (string) $this;

    }


    public function value(): string {
        return match($this) {

            UserRoleEnum::SUPERADMIN => 'super-admin',
            UserRoleEnum::ADMIN => 'admin',
            UserRoleEnum::USER => "user",
            
            UserRoleEnum::REFINERY => "refinery",
            UserRoleEnum::MARKETER => "marketer",
            UserRoleEnum::TRANSPORTER => "transporter",

            UserRoleEnum::DRIVER => "driver",
            UserRoleEnum::REPRESENTATIVE => "representative",
            UserRoleEnum::CUSTOMER => "customer",

        };
    }

    public static function getValues(): array {  
        return array_column(self::cases(), 'value');  
    } 

}
