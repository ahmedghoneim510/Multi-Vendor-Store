<div class="cart-items">
    <a href="javascript:void(0)" class="main-btn">
        <i class="lni lni-cart "></i>
        <span class="total-items">{{$items->count()}} </span>
    </a>
    <!-- Shopping Item -->
    <div class="shopping-item">
        <div class="dropdown-cart-header">
            <span>{{$items->count()}}</span>
            <a href="{{route('cart.index')}}">View Cart</a>
        </div>
        <ul class="shopping-list">
            @foreach($items as $item)
            <li>
                <a href="javascript:void(0)" class="remove" title="Remove this item "><i class="lni lni-close remove-item" data-id="{{$item->id}}"></i></a>
                <div class="cart-img-head">
                    <a class="cart-img" href="{{route('products.show',$item->product->slug)}}"><img
                            src="{{asset($item->product->image_url)}}" alt="#"></a>
                </div>

                <div class="content">
                    <h4><a href="{{route('products.show',$item->product->slug)}}">{{$item->product->name}}</a></h4>
                    <p class="quantity">{{$item->quantity}}x - <span class="amount">{{Currency::formatCurrency( $item->product->price * $item->quantity)}}</span></p>
                </div>
            </li>
            @endforeach
        </ul>
        <div class="bottom">
            <div class="total">
                <span>Total</span>
                <span class="total-amount">{{Currency::formatCurrency( $total)}}</span>
            </div>
            <div class="button">
                <a href="checkout.html" class="btn animate">Checkout</a>
            </div>
        </div>
    </div>
    <!--/ End Shopping Item -->
    @push('scripts')
        <script>
            // Using an immediately-invoked function expression to avoid global scope pollution
            (function ($) {
                // Event handler for the change event on elements with the class 'item-quantity'

                $('.remove-item').on('click', function (e) {
                    const itemId = $(this).data('id');

                    // AJAX request to delete the item from the cart
                    $.ajax({
                        url: `/cart/${itemId}`, // Using template literals for string interpolation
                        method: 'delete',
                        data: {
                            _token: csrf_token
                        },
                        success: (response) => {
                            // Removing the item from the DOM on successful deletion
                            $(`#${itemId}`).remove();
                            location.reload();
                        },
                        error: (error) => {
                            console.error("Error deleting item:", error);
                        }
                    });
                });
            })(jQuery);
        </script>
    @endpush
</div>
