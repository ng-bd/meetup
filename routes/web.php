<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'angularbd.app')->name('angularbd.index');

Route::get('get/ticket', 'TicketController@index')->name('buy.ticket');
Route::post('get/ticket', 'TicketController@storeAttendee')->name('buy.ticket.post');

Route::post('attendee/{uuid}/verify', 'TicketController@verifyAttendee')->name('attendee.verify');
Route::post('attendee/{uuid}/attend', 'TicketController@approveAttendance')->name('attendee.attend');

Route::get('ticket/payment/{attendee}', 'TicketController@ticketPayment')->name('ticket.payment');


Route::post('payment/success', 'TicketController@paymentSuccessOrFailed')->name('payment.success');
Route::post('payment/failed', 'TicketController@paymentSuccessOrFailed')->name('payment.failed');
Route::post('payment/cancel', 'TicketController@paymentSuccessOrFailed')->name('payment.cancel');


Route::get('email', function () {
    $attendee = \App\Models\Attendee::find(7);
    //    echo '<img width="200" height="200" src="'.\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(100)->generate(\Illuminate\Support\Facades\Request::url()).'">';
//    return view('emails.payment.ticket', compact('attendee'));
    $pdf = app('dompdf.wrapper');
    $pdf->loadHtml(view('emails.payment.ticket', compact('attendee'))->render());

    return $pdf->stream();
});
