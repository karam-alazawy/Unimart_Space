<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Permissions;
use App\Models\Country;
use App\Models\Customer;
use App\Models\Office;
use App\Models\Reservation;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function newBooking(Request $request){
        Permissions::checkActive();
        Permissions::havePermission("addReservation");
        $lang=1;
        $checkNeed= Permissions::haveAllPermission();

        $province=auth()->user()->province;
        $customer_id=$request['customer_id'];
        $offices = Office::with(['officeLang' => function ($q) use ($lang) {
            $q->where('lang_id',$lang);
            // $q->addSelect('?')
        }])->when(!$checkNeed,function ($q) use ($province){
            $q->where('office_province',$province);
        })
        ->where('active',1)->get();
        return view('booking.add',compact('offices','customer_id'));
    }  
    public function approve($id)
    {
        Permissions::havePermission("addReservation");

        $Reservation = Reservation::where('id', $id)
        ->update(['status' => 'approve']);
        return back()->withStatus(__('Reservation Successfully Approved.'));
    }
    public function cancel($id)
    {
        Permissions::havePermission("addReservation");

        $Reservation = Reservation::where('id', $id)
        ->update(['status' => 'cancel']);
        return back()->withStatus(__('Reservation Successfully Canceld.'));
    }
 
 
    public function booking(Request $request)
    {     
        // return $request;
        Permissions::havePermission("addReservation");

   
        if ($request->q) {
            $customer=Customer::where('email',  'like', '%' . $request->q . '%')->pluck('id');
            $reservations = Reservation::with(['customerRes' => function ($q)  {
                // $q->addSelect('?')
                // $q->where('email',  'like', '%' . $request->q . '%');
            }])->with(['roomRes2' => function ($q)  {
                $q->with(['roomLang' => function ($q)  {
                   
                }]);
                $q->with(['officeFromRoom' => function ($q)  {
                   
                }]);
            }])->with(['tableRes' => function ($q)  {
                $q->with(['tableLang' => function ($q)  {
                   
                }]);
             
            }])->whereIn('customer_id',$customer)->orderBy('created_at', 'desc')->
           get();
        }else
        $reservations = Reservation::with(['customerRes' => function ($q)  {
            // $q->addSelect('?')
        }])->with(['roomRes2' => function ($q)  {
            $q->with(['roomLang' => function ($q)  {
               
            }]);
            $q->with(['officeFromRoom' => function ($q)  {
               
            }]);
        }])->with(['tableRes' => function ($q)  {
            $q->with(['tableLang' => function ($q)  {
               
            }]);
         
        }])->orderBy('created_at', 'desc')
        ->get();

        //  return $reservations;
        return view('booking.booking',compact('reservations'));
    }
    
    public function getCustomer(){
        Permissions::checkActive();
        Permissions::havePermission("addReservation");
        $lang=1;

        $customer = Customer::where('subscription_date','>=',Carbon::now())->get();
        return view('booking.getCustomer',compact('customer'));
    }

    public function addNewBooking(Request $request)
    {

        Permissions::checkActive();
        Permissions::havePermission("addReservation");
        if (empty($request['table_id'])) {
            return back()->withStatus(__('Select Table'));
        }
        $reservation_date = Reservation::select("table_id")->where("table_id",$request['table_id'])
        ->where(function($query) use ($request){
            $query->whereBetween('date_in', [$request->date_in,$request->date_out])
                  ->orWhereBetween('date_out', [$request->date_in,$request->date_out]) ;
          })
        // ->whereBetween('date_in',[$request->date_in,$request->date_out])
        // ->orWhereBetween('date_out',[$request->date_in,$request->date_out])

        // ->where("date_in",">=",$request['date_in'])
        // ->where("date_out","<=",$request['date_out'])
        ->first();
        if ($reservation_date) {
            return back()->withStatus(__('This Date Already Reservation'));
        }
        
        $Reservation = Reservation::create([
            'office_country' => $request['country'],
            'office_province' => $request['province'],
            'customer_id' => $request['customer_id'],
            'room_id' => $request['room_id'],
            'table_id' => $request['table_id'],
            'date_in' => $request['date_in'],
            'date_out' => $request['date_out'],
            'addedByUserId' => auth()->user()->id
            ]);

         
        return back()->withStatus(__('Reservation successfully added.'));

    }
}
