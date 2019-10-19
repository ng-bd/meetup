<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendeeRequest;
use App\Models\Attendee;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Shipu\Aamarpay\Facades\Aamarpay;

class TicketController extends Controller
{
    protected function closeRegistration()
    {
        $registrationStart = env('EVENT_REGISTRATION_START', false);

        return !$registrationStart;
    }

    /**
     * @param null $message
     * @param $toastType
     *
     * @return RedirectResponse
     */
    protected function redirectToIndex( $message = null, $toastType = 'info' )
    {
        if ( !blank($message) ) {
            toast($message, $toastType);
        }

        return redirect()->route('angularbd.index');
    }

    public function index()
    {
        if ( $this->closeRegistration() ) {
            return $this->redirectToIndex('Registration Closed', 'error');
        }

        return view('angularbd.buy-ticket');
    }

    public function storeAttendee( AttendeeRequest $request )
    {
        if ( $this->closeRegistration() ) {
            return $this->redirectToIndex('Registration Closed', 'error');
        }

        $attendee = Attendee::where([
            'email' => $request->get('email'),
        ])->first();

        if ( blank($attendee) ) {
            $attendee = Attendee::create($request->all());
        }

        //        dispatch(new SendEmailJob($attendee, new ConfirmTicket($ticket)));
        //        dispatch(new SendSmsJob($attendee, env('CONFIRM_MESSAGE')));

        if ( !blank($attendee) ) {
            toast(env('EVENT_SUCCESSFUL_REGISTRATION_MESSAGE'), 'success');

            return redirect()->route('ticket.payment', $attendee->id);
        }

        return $this->redirectToIndex("Something Went Wrong !!", 'error');
    }

    public function ticketPayment( Attendee $attendee )
    {
        $total = Attendee::where('is_paid', 1)->count();
        if ( $total >= env('PUBLIC_TICKET') ) {
            return $this->redirectToIndex("Sold Out !!!");
        }

        if ( $attendee->is_paid ) {
            return $this->redirectToIndex("Already we are receive your payment, Thank you !!!");
        }

        return view('angularbd.ticket-payment', compact('attendee'));
    }

    public function paymentSuccessOrFailed( Request $request )
    {
        if ( $request->get('pay_status') == 'Failed' ) {
            return $this->redirectToIndex(env('PAYMENT_ERROR_MESSAGE'), 'error');
        }

        $attendee = Attendee::find(data_get($request, 'opt_a', null));

        if ( blank($attendee) ) {
            return $this->redirectToIndex("Attendee Not Found !!", 'error');
        }

        $amount = env('EVENT_TICKET_PRICE');
        $valid  = Aamarpay::validAmount($request, $amount);

        if ( $valid ) {
            $payment = $this->createPayment($attendee, $request);
            if ( !blank($payment) ) {
                //                dispatch(new SendEmailJob($attendee, new SucccessPayment($attendee)));
                //                dispatch(new SendSmsJob($attendee, env('SUCCESS_MESSAGE')));
                return $this->redirectToIndex(env('PAYMENT_SUCCESS_MESSAGE'), 'success');
            }
        }

        return $this->redirectToIndex(env('PAYMENT_ERROR_MESSAGE'), 'error');
    }

    public function createPayment( $attendee, Request $request )
    {
        $attendee->is_paid = true;
        $attendee->save();

        if ( Payment::where('transaction_id', data_get($request, 'pg_txnid', 'done'))->exists() ) {
            return $this->redirectToIndex("Already we are receive your payment !!!");
        }

        return Payment::create([
            'attendee_id'    => $attendee->id,
            'card_type'      => data_get($request, 'card_type', null),
            'transaction_id' => data_get($request, 'pg_txnid', 'ok'),
            'amount'         => data_get($request, 'amount', 0),
            'api_response'   => $request->all()
        ]);
    }
}
