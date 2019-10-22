<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendeeRequest;
use App\Models\Attendee;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
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

    public function verifyAttendee($uuid)
    {
        $attendee = Attendee::where('uuid', $uuid)->first(['uuid', 'name', 'email', 'mobile', 'is_paid', 'attend_at']);

        if (!$attendee) return response()->json([
            'status'=> Response::HTTP_NOT_FOUND
        ], Response::HTTP_NOT_FOUND);

        if ($attendee->attend_at) return response()->json([
            'status'=> Response::HTTP_UNAUTHORIZED
        ], Response::HTTP_UNAUTHORIZED);

        return response()->json([
            'status'    => Response::HTTP_OK,
            'approve_url' => route('attendee.attend', $uuid),
            'data' => $attendee->toArray()
        ]);
    }

    public function approveAttendance($uuid)
    {
        $attendee = Attendee::where('uuid', $uuid)
            ->where('is_paid', 1)
            ->whereNull('attend_at')
            ->first();

        if ($attendee) {
            $attendee->attend_at = Carbon::now();
            $saved = $attendee->save();

            if ($saved) {
                return response()->json([
                    'code' => Response::HTTP_OK,
                    'message' => 'Successfully Approved!'
                ]);
            }
        }

        return response()->json([
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => 'Invalid Request!'
        ], Response::HTTP_BAD_REQUEST);
    }

    public function searchAttendee()
    {
        $search = request()->get('q', '');

        $attendee = Attendee::where('is_paid', 1)
            ->where(function($query) use($search) {
                $query->where('email', $search)
                    ->orWhere('mobile', $search);
            })
            ->first(['uuid', 'name', 'email', 'mobile', 'is_paid', 'attend_at']);

        if (!$attendee) return response()->json([
            'status'=> Response::HTTP_NOT_FOUND
        ], Response::HTTP_NOT_FOUND);

        if ($attendee->attend_at) return response()->json([
            'status'=> Response::HTTP_UNAUTHORIZED
        ], Response::HTTP_UNAUTHORIZED);

        return response()->json([
            'status'    => Response::HTTP_OK,
            'approve_url' => route('attendee.attend', $attendee->uuid),
            'data' => $attendee->toArray()
        ]);
    }
}
