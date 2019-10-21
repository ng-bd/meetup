<p style="text-align: center">
    <img src="data:image/png;base64, {{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(300)->merge('/public/angularbd/images/qr_code.png')->generate(route('attendee.verify', $attendee->uuid))) }} ">
    <br>
    <span style="font-size: x-small">Scan Qr code or use {{ $attendee->mobile }} for participate in the event.</span>
</p>
