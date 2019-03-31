<?php

namespace App\Http\Controllers;

use App\User;
use Hekmatinasser\Verta\Verta;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
//    public function pdf($id , $mode , $date)
//    {
//        if (Auth::user()->is_super_admin==0 && Auth::user()->id!=$id){
//            abort(403);
//        };
//        switch ($mode){
//            case 'payment':
//                $payments = User::FindOrFail($id)->Payment()->OrderByDesc('date_time')->get();
//                foreach ($payments as $payment){
//                $payment -> sum = $payment->payment_cost+$payment->loan_payment_force+$payment->loan_payment+$payment->payment;
//                }
//                Controller::NumberFormat($payments);
//                $pdf = PDF::loadView('pdf.payment', compact('payments'));
//                return $pdf->stream('customers.pdf');
//            break;
//
//            case 'loan':
//                $loans = User::FindOrFail($id)->Loan()->OrderByDesc('date_time')->get();
//                Controller::NumberFormat($loans);
//                $pdf = PDF::loadView('pdf.loan', compact('loans'));
//                return $pdf->stream('customers.pdf');
//            break;
//
//            case 'request':
//                $requests = User::FindOrFail($id)->request()->OrderByDesc('date_time')->get();
//                Controller::NumberFormat($requests);
//                $pdf = PDF::loadView('pdf.request', compact('requests'));
//                return $pdf->stream('customers.pdf');
//            break;
//        }
//
//    }

    public function full_pdf($id)
    {
        if (Auth::user()->is_super_admin==0 && Auth::user()->id!=$id){
            abort(403);
        };
        $user = User::FindOrFail($id);
        $date = Verta::now();
        $payments = User::FindOrFail($id)->Payment()->OrderByDesc('date_time')->get();
        foreach ($payments as $payment){
            $payment -> sum = $payment->payment_cost+$payment->loan_payment_force+$payment->loan_payment+$payment->payment;
        }
        Controller::NumberFormat($payments);
        $loans = User::FindOrFail($id)->Loan()->OrderByDesc('date_time')->get();
        Controller::NumberFormat($loans);
        $requests = User::FindOrFail($id)->request()->OrderByDesc('date_time')->get();
        Controller::NumberFormat($requests);
        $summary = $user->summary();
        $pdf = PDF::loadView('pdf.full', compact('payments','loans','requests','user','date','summary'));
        $file_name = $user->f_name.' '.$user->l_name;
        return $pdf->stream($file_name);
    }
}
