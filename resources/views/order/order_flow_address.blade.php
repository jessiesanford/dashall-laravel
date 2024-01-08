<h2 class="align-center">What is your address?</h2>
<form method="POST" action="" id="submit-address" class="form_fill">
    <label class="block push-bottom">
        <div>Street Address</div>
        <input type="text" class="textbox wid-100" id="address-street" name="address_street" placeholder="Enter Street..."
               value="{{$order->address && $order->address->street != '' ? $order->address->street : Auth::user()->address->street}}" />
    </label>
    {{--<label class="block push-bottom">--}}
        {{--<div>City</div>--}}
        {{--<input type="text" class="textbox wid-100" id="address-city" name="address_city" maxlength="6" value="St. John's" readonly>--}}
    {{--</label>--}}
    {{--<label class="block push-bottom">--}}
        {{--<div>Province</div>--}}
        {{--<select class="wid-100" disabled>--}}
            {{--<option>Alberta</option>--}}
            {{--<option>British Columbia</option>--}}
            {{--<option>Manitoba</option>--}}
            {{--<option>New Brunswick</option>--}}
            {{--<option selected>Newfoundland</option>--}}
            {{--<option>Nova Scotia</option>--}}
            {{--<option>Ontario</option>--}}
            {{--<option>Prince Edward Island</option>--}}
            {{--<option>Quebec</option>--}}
            {{--<option>Saskatchewan</option>--}}
        {{--</select>--}}
    {{--</label>--}}
    {{--<label class="block push-bottom">--}}
        {{--<div>Postal Code</div>--}}
        {{--<input type="text" class="textbox wid-100 " id="address-postal-code" name="address_postal_code" maxlength="6"  placeholder="Enter Postal Code..."--}}
               {{--value="{{$order->address && $order->address->postal_code != '' ? $order->address->postal_code : Auth::user()->address->postal_code}}">--}}
    {{--</label>--}}
    <br />
    <div class="align-center">
        <input type="submit" class="button button_lrg resp_expand" id="button -order" name="submit" value="Next Step" />
    </div>
</form>