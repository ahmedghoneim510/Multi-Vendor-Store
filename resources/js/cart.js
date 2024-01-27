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
