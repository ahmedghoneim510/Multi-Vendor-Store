<x-front-layout title="Cart">
    <x-slot:breadcrumb>
        <!-- Start Breadcrumbs -->
        <div class="breadcrumbs">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="breadcrumbs-content">
                            <h1 class="page-title">Cart</h1>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <ul class="breadcrumb-nav">
                            <li><a href="{{ route('home') }}"><i class="lni lni-home"></i> Home</a></li>
                            <li><a href="{{ route('products.index') }}">Shop</a></li>
                            <li>Cart</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Breadcrumbs -->
    </x-slot:breadcrumb>


    <!-- Shopping Cart -->
    <div class="shopping-cart section">
        <div class="container">
            <div class="cart-list-head">
                <!-- Cart List Title -->
                <div class="cart-list-title">
                    <div class="row">
                        <div class="col-lg-1 col-md-1 col-12">

                        </div>
                        <div class="col-lg-4 col-md-3 col-12">
                            <p>Product Name</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-12">
                            <p>Quantity</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-12">
                            <p>Subtotal</p>
                        </div>
                        <div class="col-lg-2 col-md-2 col-12">
                            <p>Discount</p>
                        </div>
                        <div class="col-lg-1 col-md-2 col-12">
                            <p>Remove</p>
                        </div>
                    </div>
                </div>
                <!-- End Cart List Title -->
                @foreach ($cart->get() as $item)
                    <!-- Cart Single List list -->
                    <div class="cart-single-list" id="{{$item->id??''}}">
                        <div class="row align-items-center">
                            <div class="col-lg-1 col-md-1 col-12">
                                <a href="{{ route('products.show',$item->product->slug) }}">
                                    <img src="{{ $item->product->image_url }}" alt="#"></a>
                            </div>
                            <div class="col-lg-4 col-md-3 col-12">
                                <h5 class="product-name"><a href="{{ route('products.show',$item->product->slug ) }}">
                                        {{ $item->product->name }}</a></h5>
                                <p class="product-des">
                                    <span><em>Type:</em> Mirrorless</span>
                                    <span><em>Color:</em> Black</span>
                                </p>
                            </div>
                            <div class="col-lg-2 col-md-2 col-12">
                                <div class="count-input">
                                    <input class="form-control item-quantity" data-id="{{$item->id}}" value="{{ $item->quantity }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-12">
                                <p> {{Currency::formatCurrency( $item->quantity * $item->product->price)}}</p>
                            </div>
                            <div class="col-lg-2 col-md-2 col-12">
                                <p>{{Currency::formatCurrency(0)}}</p>
                            </div>
                            <div class="col-lg-1 col-md-2 col-12">
                                <a class="remove-item" data-id="{{$item->id}}" href=""><i class="lni lni-close"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- End Single List list -->
                @endforeach

            </div>
            <div class="row">
                <div class="col-12">
                    <!-- Total Amount -->
                    <div class="total-amount">
                        <div class="row">
                            <div class="col-lg-8 col-md-6 col-12">
                                <div class="left">
                                    <div class="coupon">
                                        <form action="#" target="_blank">
                                            <input name="Coupon" placeholder="Enter Your Coupon">
                                            <div class="button">
                                                <button class="btn">Apply Coupon</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-12">
                                <div class="right">
                                    <ul>
                                        <li>Cart Subtotal<span>{{\App\Helpers\Currency::formatCurrency( $cart->total())}}</span></li>
                                        <li>Shipping<span>Free</span></li>
                                        <li>You Save<span>$29.00</span></li>
                                        <li class="last">You Pay<span>$2531.00</span></li>
                                    </ul>
                                    <div class="button">
                                        <a href="{{route('checkout')}}" class="btn">Checkout</a>
                                        <a href="{{route('home')}}" class="btn btn-alt">Continue shopping</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/ End Total Amount -->
                </div>
            </div>
        </div>
    </div>
    <!--/ End Shopping Cart -->
    @push('scripts')
        <script>
            // Assigning the CSRF token to a JavaScript variable
            const csrf_token = "{{ csrf_token() }}";
        </script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <!-- Include your cart.js file if needed -->
        {{--<script src="{{ asset('assets/js/cart.js') }}"></script>--}}
        {{--<script src="js/cart.js"></script>--}}

        <script>
            // Using an immediately-invoked function expression to avoid global scope pollution
            (function ($) {
                // Event handler for the change event on elements with the class 'item-quantity'
                $('.item-quantity').on('change', function (e) {
                    // AJAX request to update the cart on quantity change
                    $.ajax({
                        url: "/cart/" + $(this).data('id'), // Appending the data-id to the URL
                        method: 'put',
                        data: {
                            quantity: $(this).val(), // New quantity value
                            _token: csrf_token // CSRF token for security
                        },
                        success: (response) => {
                            // Reload the page after the AJAX request is successful
                            location.reload();
                        },
                        error: (error) => {
                            console.error("Error updating quantity:", error);
                        }
                    });
                });

                // Event handler for item removal
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
                            // Reload the page after the AJAX request is successful
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
{{--    @vite(['resource/js/cart.js'])--}}
</x-front-layout>
