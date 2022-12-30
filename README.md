
# Test Parking

This project is to manage parking appointmnets

# Note

    Used memory optimized approach, there is only one table ("Appointments") - Appointment Model
    Slot number is generated in the server side and cached.
    sequence number is dynamically generated on appointment.

# How to run

    1. Clone the repo
    2. run "composer install" in root
    3. rename .env.exmple to .env and add DB details
    4. run "php artisan key:generate"
    5. run "php artisan migrate" or "php artisan migrate:fresh"
    6. run "php artisan serve"

# Testing

Create an API to book Parking Slot Appointment, you need to consider the following parameters.
• Customer Name
• Driver License [ Should be PDF file, maximum 5mb, minimum 2Mb]
• Vehicle Number [ No other Vehicles should book at the same time also the same vehicle
should’nt get booked at a different slot for the same time.]
• Booking Start Date Time & End Date Time [ should not be overlapped with other appointments]
• Slot [ Total 130 slots - named as A01 to A05--> Z01 to Z05] (Allocation should be based on
availability and slot priority order)
• Appointment number ( OOOXXX [ Slot Number+ Appointment Sequence ] ) [ Appointment
Sequence - AAA - ZZZ ]
• Parking Fee
- Up to 3hr - Rs 10
- For every additional 1hr - Rs 5 each

# API END POINTS AND TEST BODY

    Used Postman to test

- POST
    - /api/create-appointments
- BODY - FORM DATA
    - customer_name:Ajith
    - vehicle_number:KL1205603178
    - driver_license : File.pdf
    - start_time:2023-01-02 15:00:00
    - end_time:2023-01-02 22:30:00


# UI WEB Routes

    -   host:port/appointments/all
    -   host:port/appointments/upcoming

# Validations
    1. start_time is only allowed after now
    2. end_time only allowed after start_time