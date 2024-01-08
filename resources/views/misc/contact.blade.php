@extends('layouts.default')
@section('content')
    <div id="container">

        <div class="section wrap faq">
            <div class="page-heading">
                <h1 class="page-title">Contact Us</h1>
            </div>

            <form method="POST" action="" name="contact_form" id="contact_form" class="form_fill">

                <label class="block" for="name">
                    <div>Name</div>
                    <input class="textbox wid_100" type="text" id="name" name="name" placeholder="John Doe" />
                </label>
                <label class="block push_top" for="email">
                    <div>Email</div>
                    <input class="textbox wid_100" type="text" id="email" name="email" placeholder="johndoe@email.com" />
                </label>
                <label class="block push_top" for="message">
                    <div>Message</div>
                    <textarea class="textarea wid_100" name="message" id="message" placeholder="Write the message here." rows="4"></textarea>
                </label>
                <label class="block push_top" for="security_question">
                    <div>What noise does a cat make?</div>
                    <input class="textbox wid_100" type="text" id="security_question" name="security_question" placeholder="Answer"  />
                </label>

                <div class="align_center">
                    <input type="submit" class="button resp_expand push_top_20" id="button" name="submit" value="Send Message" />
                </div>

            </form>

        </div>
    </div>

@stop