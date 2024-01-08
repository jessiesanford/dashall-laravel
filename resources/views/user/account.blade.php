@extends('layouts.default')
@section('content')

  <div id="container">
  <div class="section wrap">

    <div class="page-heading">
      <h1 class="page-title">Account Settings</h1>
      <div class="page_desc">Details and settings pertaining to your DashAll account.</div>
    </div>

    <div class="flex-row row-collapse row-top">

      {{--<account-info-form :user="{{json_encode($user)}}" :user-address="{{json_encode($user->address)}}"></account-info-form>--}}

      {{-- LEFT COL --}}
      <div class="flex-cell">
        <ul class="tab-menu push-bottom-40">
            <li class="selected" data="tab-personal"><i class="fa fa-user"></i>&nbsp; <span>Information</span></li>
            <li data="tab-password"><i class="fa fa-key"></i>&nbsp; <span>Password</span></li>
            <li data="tab-payment"><i class="fa fa-dollar"></i>&nbsp; <span>Payment</span></li>
        </ul>
      </div>

      {{-- RIGHT COL --}}
      <div class="flex-cell cell-grow" id="account-settings-view">
        <div class="tab-panel tab-current" id="tab-personal">
          <form id="updateSettings" method="POST">

            <div class="flex-row row-top row-collapse">
              <div class="flex-cell wid-25 push-bottom-10">
                <strong>Avatar</strong>
              </div>
              <div>
                <div class="flex-row row-collapse row-top">
                  <div class="flex-cell">
                    <label class="block">
                      @if ($user->avatar)
                        <img src="" alt="" />
                      @else
                        <input type="file" name="pic" accept="image/*">
                      @endif
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <hr>

            <div class="flex-row row-top row-collapse">
              <div class="flex-cell wid-25 push-bottom-10">
                <strong>Contact</strong>
              </div>
              <div>
                <div class="flex-row row-collapse row-top">
                  <div class="flex-cell push-right-20">
                    <label class="block push-bottom-10">
                      <div>First Name</div>
                      <input type="text" class="textbox" value="{{$user->first_name}}" name="first_name" />
                    </label>
                  </div>
                  <div class="flex-cell">
                    <label class="block">
                      <div>Last Name</div>
                      <input type="text" class="textbox" value="{{$user->last_name}}" name="last_name" />
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <hr>

            <div class="flex-row row-top row-collapse">
              <div class="flex-cell wid-25 push-bottom-10">
                <strong>Email Address</strong>
              </div>
              <div>
                <div class="flex-row row-collapse row-top">
                  <div class="flex-cell push-right-20">
                    <label class="block push-bottom">
                      {{$user->email}} <br>
                      (<a href="#" id="initChangeEmailAddress">Change email address</a>)
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <hr>

            <div class="flex-row row-top row-collapse">
              <div class="flex-cell wid-25 push-bottom-10">
                <strong>Phone Number</strong>
              </div>
              <div>
                <div class="flex-row row-collapse row-top">
                  <div class="flex-cell push-right-20">
                    <label class="block push-bottom">
                      +{{$user->phone}} <br>(<a href="#" id="initChangePhoneNumber">Change phone number</a>)
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <hr>

            <div class="flex-row row-top row-collapse">
              <div class="flex-cell wid-25 push-bottom-10">
                <strong>Address</strong>
              </div>
              <div>
                <div class="flex-row row-collapse row-top">
                  <div class="flex-cell push-right-20">
                      @if ($user->address)
                        <div id="address-formatted">
                          {{$user->address->street}}<br>
                          {{$user->address->city}}, {{$user->address->province}}<br>
                          {{$user->address->postal_code}}<br>
                        </div>
                    @endif
                    <label id="address-input-label" class="block push-bottom-10 {{$user->address ? 'hidden' : ''}}">
                      <div>Street</div>
                      <input type="textbox" id="account-address" class="textbox" name="address" value="" />
                    </label>
                    (<a href="" class="init-change-address" id="init-change-address">Change Address</a>)
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="align_center">
              <button type="submit">Update Info</button>
              <button type="reset">Cancel</button>
            </div>
          </form>
        </div>

        <div class="tab-panel" id="tab-password">
          <form id="changePassword" method="POST">
            <div class="flex-row row-collapse row-top">
            <div class="flex-cell wid-25 push-bottom-10">
              <strong>Change Password</strong>
            </div>
            <div class="flex-cell">
              <label class="block push-bottom">
                <div>Current Password</div>
                <input type="password" class="textbox" name="current_password" />
              </label>
              <label class="block push-bottom">
                <div>New Password</div>
                <input type="password" class="textbox" name="password" />
              </label>
              <label class="block push-bottom">
                <div>Confirm New Password</div>
                <input type="password" class="textbox" name="password_confirmation" />
              </label>
            </div>
            </div>
            <hr>
            <div class="align_center">
              <button type="submit">Change Password</button>
              <button type="reset">Cancel</button>
            </div>
          </form>
        </div>

        <div class="tab-panel" id="tab-payment">
          <h4>Payment Methods</h4>
          <div class="row row-collapse">
            @if ($stripeCustomer)
              <div class="cell">
                <i class="fa fa-cc-{{strtolower($stripeCustomer->brand)}}"></i>&nbsp; {{$stripeCustomer->brand}} **** **** **** {{$stripeCustomer->last4}}<br />
              </div>
              <div class="cell">
                Expires {{$stripeCustomer->exp_month}} / {{$stripeCustomer->exp_year}}
              </div>
              <div class="cell">
                <button class="button" id="initRemovePaymentMethod" type="submit">Remove Card</button>
              </div>
              @else
              <div class="cell">
                You have no payment methods set up.
              </div>
            @endif
          </div>
          <hr>

          <br />
          <h4>DashCash (Current Balance: BALANCE HERE</h4>


            <div class="thead row">
              <div class="cell wid-25">
                Trans ID
              </div>
              <div class="cell wid-25">
                Amount
              </div>
              <div class="cell wid-25">
                Description
              </div>
              <div class="cell cell-grow align-right">
                Date
              </div>

            </div>

              <div class="row">
                <div class="cell wid-25">
                </div>
                <div class="cell wid-25">
                </div>
                <div class="cell wid-25">
                </div>
                <div class="cell cell-grow align-right">
                </div>
              </div>
            <br />
            <h3>Referrals</h3>
            <p>Each time you refer a user to DashAll they are listed here, each referral that makes an order earns you $2 in DashCash to use towards future orders you make!</p>
            <p>Use this link to refer friends!</p>
            <input type="textbox" class="textbox block wid-100 push-bottom" style="font-weight: 500; border-color: #000" value="http://dashall.ca/user/refer/" readonly />

            <div class="thead row">
              <div class="cell wid-25">
                Username
              </div>
              <div class="cell wid-25">
                Payout
              </div>
              <div class="cell cell-grow align-right">
                Date
              </div>

            </div>



              <div class="row">
                <div class="cell wid-25">
                  referral first/last
                </div>
                <div class="cell wid-25">
                  $2.00
                </div>
                <div class="cell cell-grow align-right">
                  referral time
                </div>
              </div>
            </div>

        </div>

    </div>


  </div>
</div>
@stop
