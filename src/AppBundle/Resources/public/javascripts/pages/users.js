jQuery(document).ready(function() {
    $(document).on('click', '#open-user-delete-modal', function() {
        $('#modal-delete-user-button').attr('href', $(this).data('href') + '?modal-confirm=1');
    });
});