<h3 class="align-center">Your order has been completed!</h3>
<p class="push-bottom-40 align-center">Please take a moment to review your completed order...</p>
<form id="order-feedback" class="register align_center">
    <div class="push-bottom-20">
        <h4>How would you rate your order correctness?</h4>
        <div id="correctness_rating" name="correctness_rating" class="starrr" data-rating="0"></div>
    </div>
    <div class="push-bottom-20">
        <h4>How would you rate the delivery time?</h4>
        <div id="timing_rating" name="timing_rating" class="starrr" data-rating="0"></div>
    </div>
    <div class="push-bottom-20">
        <h4>How would you rate your driver?</h4>
        <div id="driver_rating" name="driver_rating" class="starrr" data-rating="0"></div>
    </div>
    <h4>Would you like to tip your driver?</h4>
    <p>Did your driver do a good job? Show your appreciation here!</p>
    <span>$</span> <input type="textbox" id="tip_amount" name="tip_amount" class="tip_box textbox align-center" placeholder="Enter a tip amount here..." value="2.00" />
    <hr>
    <label>
        <textarea id="order_feedback" name="order_feedback" class="textarea block wid-100" placeholder="Enter your service and driver feedback here..."></textarea>
    </label>
    <br />
    <button type="submit" >Submit</button>
</form>