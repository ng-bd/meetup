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
Route::get('register/sponsor', 'TicketController@showOtherRegistration')->name('register.sponsor');
Route::get('register/guest', 'TicketController@showOtherRegistration')->name('register.guest');
Route::get('register/volunteer', 'TicketController@showOtherRegistration')->name('register.volunteer');

Route::post('get/ticket', 'TicketController@storeAttendee')->name('buy.ticket.post');

Route::get('attendee/{uuid}/verify', 'TicketController@verifyAttendee')->name('attendee.verify');
Route::get('attendee/{uuid}/attend', 'TicketController@approveAttendance')->name('attendee.attend');
Route::get('attendee/search', 'TicketController@searchAttendee')->name('attendee.search');

Route::get('attendee/{email}', 'TicketController@getAttendeeByEmail')->name('attendee.search.email');

Route::get('ticket/payment/{attendee}', 'TicketController@ticketPayment')->name('ticket.payment');

Route::post('payment/success', 'TicketController@paymentSuccessOrFailed')->name('payment.success');
Route::post('payment/failed', 'TicketController@paymentSuccessOrFailed')->name('payment.failed');
Route::post('payment/cancel', 'TicketController@paymentSuccessOrFailed')->name('payment.cancel');


Route::get('qrcode/{attendee}', function (\App\Models\Attendee $attendee) {
    return view('emails.payment.qr_code', compact('attendee'));
});
