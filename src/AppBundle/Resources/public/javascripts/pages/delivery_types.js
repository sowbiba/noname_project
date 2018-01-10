jQuery(document).ready(function() {
    $(document).on('click', '#open-delivery-type-delete-modal', function() {
        $('#modal-delete-delivery-type-button').attr('href', $(this).data('href') + '?modal-confirm=1');
    });
});