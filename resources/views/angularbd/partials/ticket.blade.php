<main class="Meetup__ticket">
    @if($attendeeType == \App\Enums\AttendeeType::ATTENDEE)
    <h4 class="Meetup__sectionTitle">Register and get your ticket</h4>
    <p class="Meetup__sectionCopy">Please make sure that your entering the <strong>correct email and mobile number</strong> as we are delivering the meetup ticket over email and SMS. After the registration you are require to <strong>pay the registration fee 250tk to complete the process.</strong>.
    <br/><br/>
    At the event, in case you need support for physical disabilities, health issues, food alergies, or any information that we need to know, please use "Additional Note" for this purpose.
    <br/><br/>
    <strong>Angular question poped into your head?</strong> or Any specific topic that you you wanted to hear/discuss in the event? please feel free to leave "Additional Note".
    </p>
    @endif

    @if($attendeeType == \App\Enums\AttendeeType::SPONSOR || $attendeeType == \App\Enums\AttendeeType::VOLUNTEER || $attendeeType == \App\Enums\AttendeeType::GUEST)
    <h4 class="Meetup__sectionTitle">Delighted to have you here, please register yourself.</h4>
    @endif

    <p class="Meetup__sectionCopy"></p>
    <form class="Meetup__form" action="{{ route('buy.ticket.post') }}" method="post" id="buyTicket">
        @csrf
        <input type="hidden" name="type" value="{{ $attendeeType }}">
        <div class="Field {{ $errors->has('name') ? ' Field--error' : '' }}">
          <label class="Field__label">Name</label>
          <div class="Field__control">
            <input class="Field__input" value="{{ old('name') }}" name="name" id="name" type="text" placeholder="Write your name">
          </div>
          @if($errors->has('name'))
            <p class="Field__validation">{{ $errors->first('name') }}</p>
          @endif
        </div>
        <div class="Field {{ $errors->has('email') ? ' Field--error' : '' }}">
          <label class="Field__label">Email</label>
          <div class="Field__control">
            <input class="Field__input" value="{{ old('email') }}" name="email" id="email" type="email"  placeholder="Write your email address">
          </div>
          @if($errors->has('email'))
            <p class="Field__validation">{{ $errors->first('email') }}</p>
          @endif
        </div>
        <div class="Field {{ $errors->has('mobile') ? ' Field--error' : '' }}">
          <label class="Field__label">Mobile</label>
          <div class="Field__control">
            <input class="Field__input" value="{{ old('mobile') }}" name="mobile" id="mobile" type="number" placeholder="Enter phone number">
          </div>
          @if($errors->has('mobile'))
            <p class="Field__validation">{{ $errors->first('mobile') }}</p>
          @endif
        </div>
        <div class="Field {{ $errors->has('profession') ? ' Field--error' : '' }}">
          <label class="Field__label">Profession</label>
          <div class="Field__control">
            <input class="Field__input" value="{{ old('profession') }}" name="profession" id="profession" type="text" placeholder="Write your profession">
          </div>
          @if($errors->has('profession'))
            <p class="Field__validation">{{ $errors->first('profession') }}</p>
          @endif
        </div>
        <div class="Field {{ $errors->has('social_profile_url') ? ' Field--error' : '' }}">
          <label class="Field__label">Social profile link</label>
          <div class="Field__control">
            <input class="Field__input" value="{{ old('social_profile_url') }}" name="social_profile_url" id="social_profile_url" type="text" placeholder="Enter your social profile url">
          </div>
          @if($errors->has('social_profile_url'))
            <p class="Field__validation">{{ $errors->first('social_profile_url') }}</p>
          @endif
        </div>
        <div class="Field {{ $errors->has('misc.tshirt') ? ' Field--error' : '' }}">
            <label class="Field__label">T-shirt size</label>
            <div class="Field__control Field__control--select">
                <select class="Field__input" name="misc[tshirt]" id="tshirt">
                    <option value="" selected hidden>Select your t-shirt size</option>
                    @foreach(trans('t_shirt') as $key => $tShirt)
                        <option value="{{ $tShirt }}" @if(old('misc.tshirt') == $tShirt) selected @endif>{{ $tShirt }}</option>
                    @endforeach
                </select>
            </div>
            @if($errors->has('misc.tshirt'))
                <p class="Field__validation">{{ $errors->first('misc.tshirt') }}</p>
            @endif
        </div>
        <div class="Field {{ $errors->has('misc.working') ? ' Field--error' : '' }}">
          <label class="Field__label">How long you are working with Angular?</label>
          <div class="Field__control Field__control--select">
            <select class="Field__input" name="misc[working]" id="working">
              <option value="" selected hidden>Select here</option>
              <option value="0">Never Heard of it</option>
              <option value="1">0-1 Year</option>
              <option value="2">2 Years</option>
              <option value="3">3 Years</option>
              <option value="4">4 Years</option>
              <option value="4+">4+ Years</option>
            </select>
          </div>
          @if($errors->has('misc.working'))
            <p class="Field__validation">{{ $errors->first('misc.working') }}</p>
          @endif
        </div>
        <div class="Field">
          <label class="Field__label">Additional note</label>
          <div class="Field__control Field__control--textarea">
            <textarea placeholder="Leave any additional notes" rows="3" class="Field__input" name="misc[instruction]"></textarea>
          </div>
        </div>
        <div class="Field Field--block">
          <button type="submit" class="Button Button--submit">Submit</button>
        </div>
    </form>
</main>
