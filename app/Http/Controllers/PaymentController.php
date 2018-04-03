<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\payment;

class PaymentController extends Controller
{
    public function confirm($id)
    {
        $payment = Payment::FindOrFail($id);
        $payment->is_proved = 1;
        $payment->proved_by = Auth::User()->name;
        $payment-> save();

        return redirect()->back();
    }

    public function delete($id)
    {
        Payment::destroy($id);
        return redirect()->back();
    }

    public function create(request $request)
    {
        switch($request->online_payment) {

            case '0':
                $proved_by= null;
                if($request->is_proved==1){
                    $proved_by = Auth::user()->name;
                };

                $user_id= basename(url()->previous());

                if(($user_id)=='home'){
                    $user_id = Auth::user()->id;
                }

                if (Auth::user()->is_super_admin==0 && Auth::user()->id!=$user_id){
                    abort(500);
                };

                $this->Validate($request,[
                    'payment' => 'required|integer',
                    'loan_payment'=> 'nullable|integer',
                    'description' => 'nullable|string',
                    'date_time' => 'required',
                    'is_proved' => 'nullable',
                ]);

                Payment::create([
                    'user_id' => $user_id,
                    'payment' => $request['payment'],
                    'loan_payment'=> $request['loan_payment'],
                    'description' => $request['description'],
                    'date_time' => $request['date_time'],
                    'is_proved' => $request['is_proved'],
                    'proved_by' => $proved_by,
                ]);
                break;

            case '1':
                //ToDO adding online payment
                break;
        }
        return back();
    }
}
