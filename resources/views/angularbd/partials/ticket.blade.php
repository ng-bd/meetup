<main class="Meetup__ticket">
    <form class="form-contact buy-ticket" action="{{ route('buy.ticket.post') }}" method="post" id="buyTicket">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <input value="{{ old('name') }}" class="form-control" name="name" id="name" type="text" placeholder="Enter your name">
                    <small class="text-danger">{{ $errors->first('name') }}</small>
                </div>
            </div>

            <div class="col-sm-6 user_detail">
                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <input value="{{ old('email') }}" class="form-control" name="email" id="email" type="email" placeholder="Enter email address">
                    <small class="text-danger">{{ $errors->first('email') }}</small>
                </div>
            </div>
            <div class="col-sm-6 user_detail">
                <div class="form-group{{ $errors->has('mobile') ? ' has-error' : '' }}">
                    <input value="{{ old('mobile') }}" class="form-control" name="mobile" id="mobile" type="text" placeholder="Enter phone number">
                    <small class="text-danger">{{ $errors->first('mobile') }}</small>
                </div>
            </div>
            <div class="col-sm-6 user_detail">
                <div class="form-group {{ $errors->has('profession') ? ' has-error' : '' }}">
                    <input class="form-control" name="profession" id="profession" type="text" placeholder="Enter your profession"
                           value="{{ old('profession') }}"
                    />
                    <small class="text-danger">{{ $errors->first('profession') }}</small>
                </div>
            </div>
            <div class="col-sm-6 user_detail">
                <div class="form-group {{ $errors->has('social_profile_url') ? ' has-error' : '' }}">
                    <input class="form-control" name="social_profile_url" id="social_profile_url" type="text" placeholder="Enter your social profile url"
                           value="{{ old('social_profile_url') }}"
                    />
                    <small class="text-danger">{{ $errors->first('social_profile_url') }}</small>
                </div>
            </div>
            <div class="col-sm-6 user_detail">
                <div class="form-group {{ $errors->has('misc.tshirt') ? ' has-error' : '' }}">
                    <select class="form-control" name="misc[tshirt]" id="tshirt" placeholder="T-shirt Size">
                        <option value="">Select Your T-shirt size</option>
                        @foreach(trans('t_shirt') as $key => $tShirt)
                            <option value="{{ $tShirt }}" @if(old('misc.tshirt') == $tShirt) selected @endif> {{ $tShirt }} </option>
                        @endforeach
                    </select>
                    <small class="text-danger">{{ $errors->first('misc.tshirt') }}</small>
                </div>
            </div>
        </div>
        <div class="form-group mt-3 user_detail">
            <button type="submit" class="button button-contactForm">Submit</button>
        </div>
    </form>

</main>
