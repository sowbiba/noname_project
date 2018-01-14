jQuery(document).ready(function() {
    $(document).on('click', '#open-product-delete-modal', function() {
        $('#modal-delete-product-button').attr('href', $(this).data('href') + '?modal-confirm=1');
    });
});