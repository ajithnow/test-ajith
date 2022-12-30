<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Appointments</title>
</head>
<body>
 <h1>All Appointments</h1>
 <div>
    <a href="{{route('get-upcoming-appoinements-web')}}">Upcoming</a>
 </div>
 <table>
    <thead>
        <tr>
            <th>Appointment Number</th>
            <th>Vehicle Number</th>
            <th>Slot</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Parking Fee</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($appointments as $appointment)

        <tr>
            <td>{{$appointment->appointment_number}}</td>
            <td>{{$appointment->vehicle_number}}</td>
            <td>{{$appointment->slot}}</td>
            <td>{{$appointment->start_time}}</td>
            <td>{{$appointment->end_time}}</td>
            <td>{{$appointment->parking_fee}}</td>
        </tr>
            
        @endforeach
        <tr>
            <td>Total Fees</td>
            <td>{{ $appointments->sum('parking_fee') }}</td>
        </tr>
    </tbody>
 </table>

    
</body>
</html>