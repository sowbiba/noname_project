jQuery(document).ready(function() {
    $(document).on('click', '#open-product-type-delete-modal', function() {
        $('#modal-delete-product-type-button').attr('href', $(this).data('href') + '?modal-confirm=1');
    });
});