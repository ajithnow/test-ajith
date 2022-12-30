<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AppointmentController extends Controller
{   

    public function index(){
        $appointments = Appointment::all();
        return view('appointments.all-appointments', compact('appointments'));
    }

    public function upcoming(){

        $appointments = Appointment::where('start_time', '>=', Carbon::now())
        ->get();
        return view('appointments.upcoming-appointments', compact('appointments'));
    }


    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required',
            'driver_license' => 'required|mimes:pdf|max:5000|min:2000',
            'vehicle_number' => 'required',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
        ]);

        if ($validator->fails()){
            return $validator->messages();
        }

        $slots = Helper::generateSlots(5,130);

        asort($slots);

        foreach ($slots as &$slot){

            try{
            $overlapping_appointments = Appointment::where('slot',$slot)
            ->whereTime('start_time', '>=', $request->start_time)
            ->whereTime('start_time', '<', $request->end_time)
            ->orWhereTime('end_time', '>', $request->start_time)
            ->whereTime('end_time', '<=', $request->end_time)
            ->orWhereTime('start_time', '<', $request->start_time)
            ->whereTime('end_time', '>', $request->end_time)
            ->orderBy('appointment_number','desc')->get();

            }catch(\Exception $e){
                return $e->getMessage();
            }

            if (count($overlapping_appointments) == 0){
                                    //if($request->hasFile('driver_license')){
                    //    $extension = $request->file('driver_license')->getClientOriginalExtension();
                    //    $file_name = $request->start_time.$request->vehicle_number .'.'. $extension;
                    //    $path = 'public/storage/documents';
                    //        Storage::disk('local')->put($path . '/' . $file_name, $request->file('driver_license'));
//
                    //        return $path;
                    //    }
                $request->merge([ 'slot' => $slot ,
                                'appointment_number' => Helper::generateAppointmentNumber($slot),
                                'parking_fee' => Carbon::parse($request->start_time)->diffInHours(Carbon::parse($request->end_time)) <= 3 ? 10 : (Carbon::parse($request->start_time)->diffInHours(Carbon::parse($request->end_time))-3) * 5 + 10
                            ]);
                Appointment::create($request->all());
                $response = [
                    'appointment_number' => $request->appointment_number,
                    'slot_number' => $request->slot,
                    'parking_fees'=> $request->parking_fee
                ];
                return $response;
            }


            if (count($overlapping_appointments) > 0){
                if(Appointment::where('slot',$slot)->count() == 0){
                    $existing_appointment = Appointment::where('vehicle_number', $request->vehicle_number)
                        ->where('start_time', '<=', $request->end_time)
                        ->where('end_time', '>=', $request->start_time)
                        ->first();
                    if($existing_appointment){
                        return "Already Booked";
                    }
                    $request->merge([ 'slot' => $slot ,
                                    'appointment_number' => Helper::generateAppointmentNumber($slot),
                                    'parking_fee' => Carbon::parse($request->start_time)->diffInHours(Carbon::parse($request->end_time)) <= 3 ? 10 : (Carbon::parse($request->start_time)->diffInHours(Carbon::parse($request->end_time))-3) * 5 + 10
                                ]);
                    //if($request->hasFile('driver_license')){
                    //    $extension = $request->file('driver_license')->getClientOriginalExtension();
                    //    $file_name = $request->start_time.$request->vehicle_number .'.'. $extension;
                    //    $path = 'public/storage/documents';
                    //        Storage::disk('local')->put($path . '/' . $file_name, $request->file('driver_license'));
//
                    //        return $path;
                    //    }
                    Appointment::create($request->all());
                    $response = [
                        'appointment_number' => $request->appointment_number,
                        'slot_number' => $request->slot,
                        'parking_fees'=> $request->parking_fee
                    ];
                    return $response;
                }
            }

            unset($slot);
            continue;

        }
    }
}
