<?php 

namespace App\Helpers;

class ActorHelper {

    /** 
     * Retrieve user ID from authenticated user and return.
     */
    public static function getUserId(){
        // Implement logic to fetch user ID from authenticated actor
        // Return the user ID as a string
        return request()->user()->id ?? 1;
    }

    /** 
     * Retrieve Refinery's ID from authenticated actor and return.
     */
    public static function getRefineryId(){
        // Return the user Refinery's ID
        return request()->user()->id ?? 1;

        // Getting refinery information
        // $refinery = Refinery::where('user_id', request()->user()->id)->first();
        // return $refinery->id?? 1;
    } 

    /** 
     * Retrieve Marketer's ID from authenticated actor and return.
     */
    public static function getMarketerId(){
        // Return the user marketer's ID
        return request()->user()->id ?? 1;

        // Getting marketer information
        // $marketer = Marketer::where('user_id', request()->user()->id)->first();
        // return $marketer->id?? 1;
    }


    /** 
     * Retrieve Transporter's ID from authenticated actor and return.
     */
    public static function getTransporterId(){
        // Return the user Transporter's ID
        return request()->user()->id ?? 1;

        // Getting transporter information
        // $transporter = Transporter::where('user_id', request()->user()->id)->first();
        // return $transporter->id?? 1;
    }    


    /** 
     * Retrieve Driver's ID from authenticated actor and return.
     */
    public static function getDriverId(){
        // Return the user Driver's ID
        return request()->user()->id ?? 1;

        // Getting driver information
        // $driver = Driver::where('user_id', request()->user()->id)->first();
        // return $driver->id?? 1;
    }     



    // public static function getActorByUserId($userId) {
    //     // Implement logic to fetch actor from database using user ID
    //     // Return the actor object
    // }
}