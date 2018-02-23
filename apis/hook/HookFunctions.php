<?php

class HookFunctions{
    
    static function getReferralCode($id, $email, $created_at){
        return sha1($id.$email.$created_at);
    }
}