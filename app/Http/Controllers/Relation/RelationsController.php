<?php

namespace App\Http\Controllers\Relation;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Hospital;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Phone;
use App\Models\Service;
use App\User;
use Illuminate\Http\Request;

class RelationsController extends Controller
{
    public function hasOneRelation()
    {
        $user = \App\User::with(['phone' => function ($q) {
            $q->select('code', 'phone', 'user_id');
        }])->find(1);

        // return $user -> phone -> code;
        // $phone = $user -> phone;

        return response()->json($user);
    }

    public function hasOneRelationReverse()
    {
        $phone = Phone::with(['user' => function($q) {
            $q->select('id', 'name');
    }])->find(1);
        // make some attribute visible
        $phone -> makeVisible(['user_id']);
        // $phone -> makeHidden(['code']);
        // return $phone -> user; // return user of this phone number
        // get all data phone + user

        return $phone;
    }

    public function getUserHasPhone()
    {
        return User::whereHas('phone')->get();
    }

    public function getUserNotHasPhone()
    {
        return User::wheredoesnthave('phone')->get();
    }

    public function getUserWhereHasPhoneWithCondithion()
    {
        return User::whereHas('phone',function ($q) {
            $q->where('code','02');
        })->get();
    }

    ###################################### one to many relationship methods ############################

    public function getHospitalDoctors()
    {
        $hospital = Hospital::find(1);  // Hospital::where('id',1) -> first(); // Hospital::first();

       // return $hospital->doctors;  // return hospital doctors

        $hospital = Hospital::with('doctors')->find(1);

        // return $hospital->name;

        $doctors = $hospital -> doctors;

/*        foreach ($doctors as $doctor) {
            echo $doctor -> name . '<br>';
        }*/

        $doctor = Doctor::find(3);

        return $doctor -> hospital ->name;
    }

    public function hospitals()
    {
        $hospitals = Hospital::select('id', 'name', 'address')->get();
        return view('doctors.hospitals', compact('hospitals'));
    }

    public function doctors($hospital_id)
    {
        $hospital = Hospital::find($hospital_id);

        $doctors = $hospital -> doctors;

        return view('doctors.doctors', compact('doctors'));
    }

    public function hospitalsHasDoctor()
    {
        return $hospitals = Hospital::whereHas('doctors')->get();
    }

    public function hospitalsHasOnlyMaleDoctors()
    {
        return $hospitals = Hospital::with('doctors')->whereHas('doctors', function ($q){
            $q->where('gender', '1');
        })->get();
    }

    public function hospitalsNotHasDoctorsMale()
    {
        return $hospitals = Hospital::with('doctors')->wheredoesnthave('doctors', function ($q){
            $q->where('gender', '1');
        })->get();
    }

    public function deleteHospital($hospital_id)
    {
        $hospital = Hospital::find($hospital_id);
        if (!$hospital)
            return abort('404');

        // delete doctors in this hospital
        $hospital -> doctors() -> delete();
        $hospital -> delete();

        return redirect() -> reoute('hospital.all');
    }

    public function getDoctorServices()
    {
        return $doctor = Doctor::with('services')->find(1);

        // return $doctor -> services;


    }

    public function getServicesDoctors()
    {
        return $doctors = Service::with(['doctors' => function($q) {
            $q-> select('doctors.id', 'name', 'title');
        }]);
    }

    public function getDoctorServicesById($doctorId)
    {
        $doctors = Doctor::find($doctorId);
        $services = $doctors -> services; // doctor services

        $doctors = Doctor::select('id', 'name')->get();
        $allServices = Service::select('id', 'name')-> get(); // all db serves

        return view('doctors.services', compact('services', 'doctors', 'allServices'));
    }

    public function saveServiceToDoctors(Request $request)
    {
        $doctor = Doctor::find($request -> doctor_id);
        if (!$doctor)
            return abort('404');

        // $doctor->services()->attach($request -> servicesIds); // many to many insert to databases

        // $doctor -> services()-> sync($request -> servicesIds); // تستخدم لتحديث البيانات وبتمسح القديم

        $doctor -> services()->syncWithoutDetaching($request -> servicesIds); // تخزن البيانات بدون تكرار ولا بتمسح القديم

        return 'success';
    }

    public function  getPatientDoctor()
    {
        $patient = Patient::find(2);

        return $patient -> doctor;
    }

    public function getCountryDoctor()
    {
        $country = Country::find(1);

        return $country -> doctors;
    }

    public function getDoctor()
    {
        return $doctors = Doctor::select('id', 'name', 'gender')->get();

/*        if (isset($doctors) && $doctors -> count() > 0) {
            foreach ($doctors as $doctor) {
                $doctor->gender = $doctor->gender == 1 ? 'male' : 'female';
            }
        }
        return $doctors;*/
    }
}
