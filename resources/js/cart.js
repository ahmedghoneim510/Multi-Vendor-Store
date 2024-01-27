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
            }
        });
    });
})(jQuery);
