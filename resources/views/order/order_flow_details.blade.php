<h2 class="align-center">Review your order details</h2>
<form method="POST" action="" id="submit-order-details" class="form_fill">

    <label class="block">
        <div>Location</div>
        <textarea name="order_location" class="dashbox_textbox wid-100" placeholder="Where are you looking to get delivery from?">{{$order->location}}</textarea>
    </label>

    <div class="label">Items</div>

    <div class="order-items">
        @forelse($order->parseOrderSummary() as $key => $item)
            @if (!$item == '')
                <div class="order-item-block-parsed">
                    <div class="wid-100" data-val="order_item_{{$key + 1}}">
                         <input type="text" value="{{$item}}"name="order_items[]" readonly />
                    </div>
                    <div class="cell-right">
                        <a class="remove-order-item"><i class="fa fa-minus-circle"></i></a>
                    </div>
                </div>
            @endif
            @empty
        @endforelse

        <div class="order-item-block">
            <div class="wid-100">
                <input name="order_items[]" class="textbox wid-100 push-bottom-10" placeholder="Item Description" />
            </div>
            <div>
                <button class="btn-alt add-order-item"><i class="fa fa-plus-circle"></i></button>
            </div>
        </div>
    </div>

    <div class="push-top-40 align-center">
        <input type="submit" class="button button_lrg resp_expand" id="button-order" name="submit" value="Next Step" />
    </div>
</form>
