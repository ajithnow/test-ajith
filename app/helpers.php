<?php

namespace App\Helpers;

use App\Models\Appointment;

use Illuminate\Support\Facades\Cache;

class Helper{

    //This function will create slots with Alphabets[A-Z] and $number[5] ~ A05 to Z05
    public static function generateSlots($number,$count){
        $cached_slots = Cache::get('slots');

        if($cached_slots && count($cached_slots) === $count)
            return $cached_slots;

        else{

            $slots =[];
            for ($i = 0; $i < 26; $i++) {
                for ($j = 1; $j <= $number; $j++) {
                    $slotName = chr(65 + $i) . sprintf('%02d', $j);
                    $slots[] = $slotName;
                }
            }

            //check or update
            Cache::put('slots',$slots) || Cache::add('slots',$slots);

            return $slots;
        }
    }

    // this will generate a sequence of name if not available in db
    public static function generateAppointmentNumber($slot) {
            foreach(range('A','Z') as $i) {
                foreach(range('A','Z') as $j) {	
                    foreach(range('A','Z') as $k) {	
                            $sequence = $i.$j.$k;
                            $appointment = Appointment::where('appointment_number', $slot.$sequence)->first();
                            if (!$appointment) {
                            return $slot.$sequence;
                            break;
                            }
                    }
                }
            }
        }
    

}