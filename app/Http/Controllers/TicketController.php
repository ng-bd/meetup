<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendeeRequest;
use App\Models\Attendee;
use App\Models\Payment;
use App\Enums\AttendeeType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
//use Shipu\Aamarpay\Facades\Aamarpay;

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
    protected function redirectToIndex($message = null, $toastType = 'info')
    {
        if (!blank($message)) {
            toast($message, $toastType);
        }

        return redirect()->route('angularbd.index');
    }

    public function index()
    {
        if ($this->closeRegistration()) {
            return $this->redirectToIndex('Registration Closed', 'error');
        }
        $attendeeType = AttendeeType::ATTENDEE;
        return view('angularbd.buy-ticket', compact('attendeeType'));
    }

    public function showOtherRegistration(Request $request)
    {
        $route = $request->route()->getName();
        $attendeeType = AttendeeType::GUEST;

        if($route == 'register.sponsor') {
            $attendeeType = AttendeeType::SPONSOR;
        } elseif($route == 'register.volunteer') {
            $attendeeType = AttendeeType::VOLUNTEER;
        }

        return view('angularbd.buy-ticket', compact('attendeeType'));
    }

    public function storeAttendee(AttendeeRequest $request)
    {
        $attendeeType = $request->get('type');
        if ($attendeeType != AttendeeType::ATTENDEE) {
            $attendee = Attendee::create($request->all());
            if (!blank($attendee)) {
                Log::info("Attendee type " . AttendeeType::ATTENDEE . " created successfully!");
                return $this->redirectToIndex(env('SUCCESSFUL_REGISTRATION_MESSAGE'), 'success');
            } else {
                Log::info("Attendee type " . AttendeeType::ATTENDEE . " creation failed!");
                return $this->redirectToIndex("Something Went Wrong !!", 'error');
            }
        }

        if ($this->closeRegistration()) {
            return $this->redirectToIndex('Registration Closed', 'error');
        }

        $attendee = Attendee::where([
            'email' => $request->get('email'),
        ])->first();

        if (blank($attendee)) {
            $attendee = Attendee::create($request->all());
        }

        //        dispatch(new SendEmailJob($attendee, new ConfirmTicket($ticket)));
        //        dispatch(new SendSmsJob($attendee, env('CONFIRM_MESSAGE')));

        if (!blank($attendee)) {
            Log::info("Attendee created successfully!");
            toast(env('EVENT_SUCCESSFUL_REGISTRATION_MESSAGE'), 'success');

            return redirect()->route('ticket.payment', $attendee->id);
        }

        return $this->redirectToIndex("Something Went Wrong !!", 'error');
    }

    public function ticketPayment(Attendee $attendee)
    {
        $total = Attendee::where('is_paid', 1)->count();
        if ($total >= env('PUBLIC_TICKET')) {
            return $this->redirectToIndex("Sold Out !!!");
        }

        if ($attendee->is_paid) {
            return $this->redirectToIndex("We have received your payment already, Thank you.");
        }

        return view('angularbd.ticket-payment', compact('attendee'));
    }

    public function paymentSuccessOrFailed(Request $request)
    {
        Log::info($request->ip());
        Log::info($request->getRequestUri());
        Log::info($request->route()->getName());
        Log::debug($request->all());

        if ($request->get('pay_status') == 'Failed') {
            Log::info("pay_status failure");
            return $this->redirectToIndex(env('PAYMENT_ERROR_MESSAGE'), 'error');
        }

        $attendee = Attendee::find(data_get($request, 'attendee_id', null));

        if (blank($attendee)) {
            Log::info("Attendee is not available! id: " . data_get($request, 'attendee_id', null));
            return $this->redirectToIndex("Attendee is not available!", 'error');
        }
        Log::info("Attendee is available, creating payments");

        $amount = env('EVENT_TICKET_PRICE');

        $payment = $this->createPayment($attendee, $request);
        if (!blank($payment)) {
            //                dispatch(new SendEmailJob($attendee, new SucccessPayment($attendee)));
            //                dispatch(new SendSmsJob($attendee, env('SUCCESS_MESSAGE')));
            Log::info("Paid successfully!");
            return $this->redirectToIndex(env('PAYMENT_SUCCESS_MESSAGE'), 'success');
        } else {
            Log::info("Payments failed!");
        }


        return $this->redirectToIndex(env('PAYMENT_ERROR_MESSAGE'), 'error');
    }

    public function createPayment($attendee, Request $request)
    {
        $attendee->is_paid = true;
        $attendee->save();

        if (
            Payment::where('attendee_id', data_get($request, 'attendee_id', null))->exists() ||
            Payment::where('transaction_id', data_get($request, 'transaction_id', 'done'))->exists()
        ) {
            Log::info("Already paid! id: " . $attendee->id);
            return $this->redirectToIndex("We have received your payment already!");
        }

        return Payment::create([
            'attendee_id'    => $attendee->id,
            'card_type'      => data_get($request, 'card_type', null),
            'transaction_id' => data_get($request, 'transaction_id', 'ok'),
            'amount'         => data_get($request, 'amount', 0),
            'api_response'   => $request->all()
        ]);
    }

    public function verifyAttendee($uuid)
    {
        $attendee = Attendee::where('uuid', $uuid)->first(['uuid', 'name', 'email', 'mobile', 'is_paid', 'attend_at']);

        if (!$attendee) return response()->json([
            'status' => Response::HTTP_NOT_FOUND
        ], Response::HTTP_NOT_FOUND);

        if ($attendee->attend_at) return response()->json([
            'status' => Response::HTTP_UNAUTHORIZED
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
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn('type', [
                        AttendeeType::VOLUNTEER,
                        AttendeeType::SPONSOR,
                        AttendeeType::GUEST
                    ]);
                });
                $query->orWhere(function ($query) {
                    $query->where('is_paid', 1)
                        ->where('type', AttendeeType::ATTENDEE);
                });
            })
            ->whereNull('attend_at')
            ->first();

        if ($attendee) {
            $attendee->attend_at = Carbon::now();
            $saved = $attendee->save();

            if ($saved) {
                return response()->json([
                    'code' => Response::HTTP_OK,
                    'message' => 'Approved successfully!'
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

        $attendee = Attendee::
            where(function ($query) use ($search) {
                $query->where('email', $search)
                    ->orWhere('mobile', $search);
            })
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereIn('type', [
                        AttendeeType::VOLUNTEER,
                        AttendeeType::SPONSOR,
                        AttendeeType::GUEST
                    ]);
                });
                $query->orWhere(function ($query) {
                    $query->where('is_paid', 1)
                        ->where('type', AttendeeType::ATTENDEE);
                });
            })
            ->first(['uuid', 'name', 'type', 'email', 'mobile', 'is_paid', 'attend_at']);

        if (!$attendee) {
            return response()->json([
                'status' => Response::HTTP_NOT_FOUND,
            ], Response::HTTP_NOT_FOUND);
        }

        if ($attendee->attend_at) {
            return response()->json([
                'status' => Response::HTTP_UNAUTHORIZED,
                'data' => $attendee->toArray()
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'status'    => Response::HTTP_OK,
            'approve_url' => route('attendee.attend', $attendee->uuid),
            'data' => $attendee->toArray()
        ]);
    }

    public function getAttendeeByEmail($email)
    {
        $attendee = Attendee::where('email', $email)->first();

        if (blank($attendee)) {
            return $this->redirectToIndex("Attendee is not available!", 'error');
        }
        return view('angularbd.ticket-payment', compact('attendee'));
    }
}
