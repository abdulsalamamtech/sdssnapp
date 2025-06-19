<?php 

namespace App\Helpers;

use App\Models\Api\Membership;
use App\Models\Certification;

class CustomGenerator {

    /**
     * Generates a random atc number
     */
    public static function generateCertificateSerialNo(){
        $uniqid = strtoupper(uniqid('SDSSN'));
        while(Membership::where('serial_no', $uniqid)?->exists()){
            $uniqid = strtoupper(uniqid('SDSSN'));
        }
        return $uniqid;
    }

    // generateMembershipCode
    public static function generateMembershipCode($membership_id){
        // $membership_id = 1;
        $cert_type = Membership::where('id', $membership_id)?->first();
        // $certificationRequest->certification->abbreviation_code
        if($cert_type?->certificationRequest?->certification?->abbreviation_code){
            $cert_type = $cert_type?->certificationRequest?->certification?->abbreviation_code;
        }else{
            $cert_type = $cert_type?->certificationRequest?->certification?->type;
        }
        // get the first 3 letters
        $cer_abbr = substr($cert_type, 0, 3);
        $uniqid = strtoupper($cer_abbr) . date('y') . '00' . date('s') . $membership_id;
        while(Membership::where('membership_code', $uniqid)?->exists()){
            $uniqid = strtoupper($cer_abbr) . date('y') . '00' . date('s') . $membership_id;
        }

        return $uniqid;
    }

    public static function generateUniqueName($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString.= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function generateUniquePhoneNumber($length = 10) {
        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString.= $characters[rand(0, strlen($characters) - 1)];
        }
        return '+234'. $randomString;
    }
    
    public static function generateUniqueEmail($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString.= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString. '@tmmmsapp.com';
    }
    public static function generateUniqueID($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString.= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function generateUniqueNumber($length = 10) {
        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString.= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function generateUniqueString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function generateUniqueCode($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function generateRandomNumber($length = 10) {
        $characters = '0123456789';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString.= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    public static function generateRandomString($length = 10) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString.= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
}