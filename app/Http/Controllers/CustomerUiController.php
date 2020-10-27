<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Office;
use App\Models\Reservation;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CustomerUiController extends Controller
{
    public function login(Request $request){
        //  return  Carbon::now()->addMonths(1);
         
        $customer = Customer::where('email',$request->email)->where('password',md5($request['password']))->where('active',1)->
        // where('subscription_date','>=',Carbon::now())->
        first();

        if ($customer) {
           
            session(['customer' => $customer]);
            return redirect()->to('/customerLogin'); 

            //return session('customer');
        }
        return view('customerui.login');;

        //else
       // return back()->withStatus(__('This email address is not available. choose a different address'));

           
  
      }

      public function reservations()
      {
          
        $reservations = Reservation::where("customer_id",session('customer')->id)

        ->with(['customerRes' => function ($q)  {
            // $q->addSelect('?')
        }])->with(['roomRes' => function ($q)  {
            // $q->addSelect('?')
        }])->get();
        //return $reservations;
        return view('customerui.reservations',compact('reservations'));
      }

      public function addNewBooking(Request $request)
      {
        $sub_date =  $customer = Customer::where('id',session('customer')->id)->where('subscription_date','>=',Carbon::now())->
        first();
        if (!$sub_date) {
            return back()->withStatus(__('Your Subscription End'));
        }
          if (empty($request['table_id'])) {
              return back()->withStatus(__('Select Table'));
          }
          $reservation_date = Reservation::select("table_id")->where("table_id",$request['table_id'])
          ->where(function($query) use ($request){
              $query->whereBetween('date_in', [$request->date_in,$request->date_out])
                    ->orWhereBetween('date_out', [$request->date_in,$request->date_out]) ;
            })->where('status','approve')
          // ->whereBetween('date_in',[$request->date_in,$request->date_out])
          // ->orWhereBetween('date_out',[$request->date_in,$request->date_out])
  
          // ->where("date_in",">=",$request['date_in'])
          // ->where("date_out","<=",$request['date_out'])
          ->first();
          if ($reservation_date) {
              return back()->withStatus(__('This Date Already Reservation'));
          }
            if ($request->date_in>=$request->date_out) {
              return back()->withStatus(__('Please Choose Correct Date'));
          }
          $Reservation = Reservation::create([
              'customer_id' => $request['customer_id'],
              'room_id' => $request['room_id'],
              'table_id' => $request['table_id'],
              'date_in' => $request['date_in'],
              'date_out' => $request['date_out'],
              'addedByUserId' => -1
              ]);
  
           
          return back()->withStatus(__('Reservation successfully added.'));
  
      }
}
