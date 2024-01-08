@extends('layouts.default')

@section('content')
    <div id="showcase">
        <div class="overlay"></div>
        <div class="showcase_content align_center">
            <div class="cell">
                <h1 class="showcase_heading">Delivery from any restaurant in St. John&#39;s.</h1>
                <h2 class="showcase_heading">Your favorite meals, delivered from your favorite restaurants.</h2>
                <div class="push-top-20 align_center" style="color: #fff !important;">
                    <a class="register_button showcase_button resp_expand" href="./order"><i class="fa fa-cutlery"></i>&nbsp; Get Started</a>
                </div>
            </div>
        </div>
    </div>

    <div id="container">
        <div class="wrap">

            <div class="section align_center padd_y_40">

                <h2>What kind of food and drink can we deliver to you?</h2>
                <div class="row row-collapse no_border resp_hide">
                    <div class="cell w_33">
                        <div class="icon_circle"><img src="./img/fast_food.png" alt="Fast Food" /></div>
                        <h3>Fast Food</h3>
                        <p>
                            Craving some McDonald&#39;s? Or maybe some A&W or Wendy&#39;s? As long as they&#39;re open, we can have it delivered to you.
                        </p>
                    </div>
                    <div class="cell w_33">
                        <div class="icon_circle"><img src="./img/intn_food.png" alt="Fast Food" /></div>
                        <h3>International Food</h3>
                        <p>
                            We also provide delivery for establishments that make international foods such as sushi or tacos.
                        </p>
                    </div>
                    <div class="cell w_33">
                        <div class="icon_circle"><img src="./img/coffee.png" alt="Fast Food" /></div>
                        <h3>Coffee</h3>
                        <p>
                            Perhaps you need something with a little more kick. We can delivery of coffee, tea, and other specialty drinks.
                        </p>
                    </div>
                </div>
                <hr />
                <br />
            </div>

        </div>
    </div>
@endsection
