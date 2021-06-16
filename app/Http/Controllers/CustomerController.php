<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Permissions;
use App\Models\Subscription;
use App\Models\SubscriptionName;
use App\Models\Country;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CustomerController extends Controller
{

    public function newCustomer(){
          Permissions::checkActive();
          Permissions::havePermission("addCustomer");
     
        $lang=1;

        $country = Country::with(['countryLang' => function ($q) use ($lang) {
            $q->where('id_lang',$lang);
            // $q->addSelect('?')
        }])
        ->get();
        //return $country;
       // return $offices->officeLang->office_id;
          // return view('users.add',['offices' => $offices]);
           return view('customers.add',compact('country'));
  
      }
      public function unactive($id)
      {
          $Customer = Customer::where('id', $id)
          ->update(['active' => 0]);
          return back()->withStatus(__('Customer Successfully Unactive.'));
      }
      public function subscriptionUnactive($id)
      {
          $Subscription = Subscription::where('id', $id)
          ->update(['active' => 0]);
          return back()->withStatus(__('Subscription Successfully Unactive.'));
      }

      public function customerSubscription(Request $request){
        $id=$request['id'];
        Permissions::checkActive();
        Permissions::havePermission("userSubscription");
        $lang=1;
        $subscriptions = Subscription::with(['subscriptionLang' => function ($q) use ($lang) {
            $q->where('lang_id',$lang);
            // $q->addSelect('?')
        }])->where('active',1)->get();

        return view('customers.subscription',compact('subscriptions','id'));
    }
    
      public function customers(Request $request){
        Permissions::checkActive();
        Permissions::havePermission("editCustomers");
        if ($request->q) {
            $customers = Customer::where('email',  'like', '%' . $request->q . '%')->where('active',1)->get();
        }else
        $customers = Customer::where('active',1)->get();
        //return $customers;
        return view('customers.index', compact('customers'));
    }


      public function addNewCustomer(Request $request)
      {
          Permissions::checkActive();
          Permissions::havePermission("addCustomer");
      
          try { 
              $customer = Customer::create([
                  'name' => $request['name'],
                  'email' => $request['email'],
                  'country' => $request['country'],
                  'province' => $request['province'],
                  'phone' => $request['phone'],
                  'password' => md5($request['password']),
                  'addedByUserId' => auth()->user()->id
                  ]);
                  // if (!$users) {
                  //     return back()->withStatus(__("error"));
                  // }

                  $subscription_date=Carbon::now();
                  $date=Carbon::parse($subscription_date)->addMonths(3);

            $renewalSubscription = Customer::where('id',  $customer->id)
            ->update(['subscription_date' => $date]);

                  return back()->withStatus(__('User successfully added.'));
            } catch(\Illuminate\Database\QueryException $ex){ 
              return back()->withStatus(__('This email address is not available. choose a different address'));
              // Note any method of class PDOException can be called on $ex.
            }
      
  
      }
    public function renewalSubscription(Request $request)
    {
        Permissions::checkActive();
        Permissions::havePermission("renewalSubscription");

        $subscription = Subscription::where('active',1)->where( 'id',$request['type'])->first();

        $flager=1;
        $date=0;
        $subscription_date = Customer::select("subscription_date")->find($request['id']);
        $subscription_date = $subscription_date->subscription_date;
        if ($subscription_date<Carbon::now()) {
            $subscription_date=Carbon::now();
        }
          switch ($subscription->type) {
            case 'daily':
                $date=Carbon::parse($subscription_date)->addDays($subscription->period);
                break;
            case 'weekly':
                $date=Carbon::parse($subscription_date)->addWeeks($subscription->period);
                    break;
            case 'monthly':
                $date=Carbon::parse($subscription_date)->addMonths($subscription->period);
                break;
            case 'yearly':
                    $date=Carbon::parse($subscription_date)->addYears($subscription->period);
                    break;
                  
              default:
                  $flager=0;
                  break;
          }
          if ($flager) {

            
            $renewalSubscription = Customer::where('id',  $request['id'])
            ->update(['subscription_date' => $date]);
            return back()->withStatus(__("Successfully"));

          }
          return back()->withStatus(__("error"));

    }

     
    public function addSubscription(){
        //  return  Carbon::now()->addMonths(1);
          Permissions::checkActive();
          Permissions::havePermission("addSubscription");
       
           return view('users.addSubscription');
  
      }
      public function addNewSubscription(Request $request)
      {
          Permissions::checkActive();
          Permissions::havePermission("addSubscription");
          $subscription = Subscription::create([
              'price' => $request['price'],
              'type' => $request['type'],
              'period' => $request['period'],
              'addedByUserId' => auth()->user()->id
              ]);
              if ($subscription) {
                  $id=$subscription->id;
                  for ($i=1; $i <4 ; $i++) { 
                      $subscription_name = SubscriptionName::create([
                          'subscription_name' => $request['name'],
                          'subscription_id' => $id,
                          'lang_id' => $i,
                          ]);
                  }
                
              }
             
          return back()->withStatus(__('Subscription successfully added.'));
  
      }
    
      public function subscriptions()
      {
           Permissions::checkActive();
           Permissions::havePermission("editSubscriptions");
           $lang=1;
           $subscriptions = Subscription::with(['subscriptionLang' => function ($q) use ($lang) {
              $q->where('lang_id',$lang);
              // $q->addSelect('?')
           }])->where('active',1)->get();
            return view('users.subscriptions',compact('subscriptions'));
   
      }
      
}
