;(function ($) {
    "use strict";

    $(document).ready(function () {
        initCheckboxSwitch();
        initConfirm();
        initDatepicker();
        initMultiselects();
        initPagination();
        initSubmit();
        initToggleFilter();
        initConfirmForm();
        initPreventLosingFormData();
        initFieldsEmpty();
        initDisableAfterClick();
        removeLoader();
    });
})(jQuery);

function removeLoader()
{
    $('.loader').remove();
    $("body").css('overflow', 'auto');
}

function addLoader(text)
{
    text = typeof text !== 'undefined' ? text : 'Chargement en cours';

    var loader = $('<div/>', {
            'class': 'loader'
        }
    ).append(
        $('<div/>', {
            'class': 'spinner-text',
            'text': text
        })
    ).append(
        $('<div/>', {
            'class': 'spinner'
        }).append(
            $('<div/>', {
                'class': 'rect1'
            })
        ).append(
            ' '
        ).append(
            $('<div/>', {
                'class': 'rect2'
            })
        ).append(
            ' '
        ).append(
            $('<div/>', {
                'class': 'rect3'
            })
        ).append(
            ' '
        ).append(
            $('<div/>', {
                'class': 'rect4'
            })
        ).append(
            ' '
        ).append(
            $('<div/>', {
                'class': 'rect5'
            })
        )
    );

    $('body')
        .css('overflow', 'hidden')
        .append(loader)
    ;
}

function initFieldsEmpty()
{
    $('span.input-group-addon').next('input, textarea').keyup(function() {
        if ($(this).val() == '') {
            var color = 'color-orange';
            if ($(this).prop('required')) {
                color = 'color-red';
            }

            $(this).prev('span.input-group-addon').find('.fa').removeClass('color-green').addClass(color);
        } else {
            $(this).prev('span.input-group-addon').find('.fa').removeClass('color-red color-orange').addClass('color-green');
        }
    });
}

function initConfirm()
{
    $(document).on('click', 'a[data-confirm]', function(event) {
        var href = $(this).attr('href') + '?modal-confirm=1',
            callback = $(this).data('callback');

        $('#popin-confirm').find('.modal-body').html($(this).attr('data-confirm'));
        $('#dataConfirmOK').attr('href', href);
        $('#dataConfirmOK').data('callback', callback);


        if (undefined !== callback) {
            $('#dataConfirmOK').data('callback', callback);
        } else {
            $('#dataConfirmOK').removeData('callback');
        }

        $('#popin-confirm').modal({
            show: true,
            backdrop: 'static'
        });

        return false;
    });

    $('#dataConfirmOK').on('click', function(event) {
        var href = $(this).attr('href'),
            callback = $(this).data('callback');

        if (undefined !== callback) {
            event.preventDefault();

            window[callback](href);

            return false;
        }
    });

    // Set a max height to modals in order to be visible

    $('.modal').not('.modal-no-resize').on('shown.bs.modal', function () {
        setModalMaxHeight($(this));
    })

    // Resize height of modals when the window is resized

    $(window).resize(function() {
        var modal = $('.modal').not('.modal-no-resize');

        if (modal.length) {
            setModalMaxHeight(modal);
        }
    });
}
/**
 * Set a maximum height to a bootstrap modal which is not greather than window height
 *
 * @param modal {Object} The bootstrap modal as a JQuery object
 */
function setModalMaxHeight(modal)
{
    var modalBody = modal.find('.modal-body .tab-content');

    if (!modalBody.length) {
        modalBody = modal.find('.modal-body');
    }

    modalBody.css({
        'max-height': 500,
        'overflow-y': 'auto'
    });
}

function initSubmit()
{
    $('[data-submit]').click(function() {
        var formToSubmitFirst = $(this).attr('data-submit-ajax');

        if(typeof formToSubmitFirst !== 'undefined' && formToSubmitFirst !== false) {
            $.ajax({
                method : 'POST',
                url    : $('#' + formToSubmitFirst).attr('action'),
                data   : $('#' + formToSubmitFirst).serialize(),
                async: false
            });
        }

        $('#'+ $(this).data('submit')).submit();
    });
}

function initConfirmForm()
{
    var modals  = $('.modal.form-confirm');

    modals.each(function() {
        var modal = $(this),
            inputs = modal.find('input[required], textarea[required]'),
            submit = modal.find('button[type="submit"]'),
            closed = submit.data('closed'),
            disabled = submit.data('disabled');

        modals.on('hide.bs.modal', function() {
            var form = $(this).find('form');

            if (form.length) {
                form[0].reset();
            }
        });

        if (pfd.functions.issetVariable(closed)) {
            submit.on('click', function(event) {
                event.preventDefault();
                modals.modal('hide');

                return false;
            });
        }

        if (pfd.functions.issetVariable(disabled)) {
            if (!allElementsAreNotEmpty(inputs)) {
                submit.attr('disabled', 'disabled');
            }

            inputs.on('keyup change', function() {
                if (!allElementsAreNotEmpty(inputs)) {
                    submit.attr('disabled', 'disabled');
                } else {
                    submit.removeAttr('disabled');
                }
            });
        }
    });
}

function initPreventLosingFormData()
{
    window.onbeforeunload = preventLosingFormData;

    $('form').each(function() {
        var form = $(this);

        if (form.is('[data-form-notification-exit]') && form.attr('data-form-notification-exit')) {
            var initialForm = form.serialize();

            $('input, textarea, select', form).on('blur change', function () {
                var currentForm = form.serialize();
                initialForm !== currentForm ? $('body').addClass('form_changed') : $('body').removeClass('form_changed');
            });

            form.submit(function () {
                window.onbeforeunload = null;
            });
        }
    });
}

function preventLosingFormData()
{
    if($('body').hasClass('form_changed')) {
        return 'Des données ont été saisies.';
    }
}

function allElementsAreNotEmpty(elements)
{
    var counter = 0;

    elements.each(function() {
        if ("" == $(this).val()) {
            return;
        }

        counter++;
    });

    if (counter !== elements.length) {
        return false;
    }

    return true;
}

function initPagination()
{
    $('.pagination a').on('click', function(e) {
        if ($('.list-filter')[0]) {
            if ($('.list-filter').is(':hidden')) {
                window.location = $(this).attr('href') + '&filter_close';
            } else {
                window.location = $(this).attr('href').replace('filter_close', '');
            }

            return false;
        }
    });
}

function initDatepicker()
{
    $('.date_picker').each(function() {
        var startDate = '-3d';

        if ($(this).data('start-date')) {
            startDate = $(this).data('start-date');
        }
        $(this).datepicker({
            format: 'dd/mm/yyyy',
            language: 'fr-FR',
            startDate: startDate
        });
    });
}

function initMultiselects() {
    $.each($('.multiselect'), function() {
        initMultiselect($(this));
    });

    $(document).on('click', '.multiselect-deselect-all', function() {
        var select = $(this).siblings('select');

        if (select.attr('multiple')) {
            select.multiselect('deselectAll', false);
            select.multiselect('updateButtonText');
        } else {
            var opt_vals = [];
            $(select).children('option:selected').each(function() {
                opt_vals.push($(this).val());
            });

            select.multiselect('deselect', opt_vals);
            select.multiselect('select', '', true);
            select.multiselect('updateButtonText');
        }

        return false;
    });
}

function initMultiselect(element) {
    var multiselectFiltering = $(element).attr('data-multiselect-no-filtering') ? false : true;

    $(element).multiselect({
        maxHeight: 200,
        buttonClass: 'btn tip-top',
        enableFiltering: multiselectFiltering,
        enableCaseInsensitiveFiltering: multiselectFiltering,
        filterPlaceholder: 'Rechercher...',
        includeSelectAllOption: $(element).attr('data-multiselect-enable-select-all') && $(element).attr('data-multiselect-enable-select-all')=='true' ? true : false,
        selectAllText: $(element).attr('data-multiselect-select-all-text') ? $(element).attr('data-multiselect-select-all-text') : '',
        allSelectedText: $(element).attr('data-multiselect-all-selected-text') ? $(element).attr('data-multiselect-all-selected-text') : '',
        nonSelectedText: $(element).attr('data-multiselect-non-selected-text') ? $(element).attr('data-multiselect-non-selected-text') : '',
        onChange: function(option, checked, select) {
            $('.multiselect.dropdown-toggle').tooltip('fixTitle');
            if($(element).attr('data-multiselect-change-action')) {
                window[$(element).attr('data-multiselect-change-action')](option, checked, select);
            }
        },
        onDropdownShown: function() {
            var searchInput = $(this.$filter).find('.multiselect-search');

            if (typeof searchInput !== typeof undefined) {
                searchInput.focus();
            }
        }
    });

    //if (!$(element).attr('disabled') && $(element).attr('data-multiselect-deselect-all')) {
    //    $(element).after('<i class="fa fa-times multiselect-deselect-all"></i>');
    //}
}

function initToggleFilter() {

    var filter_button = '.filter-button',
        filter_block = '.list-filter';

    if($(filter_block).length > 0) {
        $(filter_button).on('click', function(evt) {
            var $toggler = $(this);

            if ($toggler.hasClass('btn-default')) {
                $(filter_block).slideUp(250, function(){
                    $toggler.removeClass('btn-default').addClass('btn-primary');
                    window.scrollTo(0, 0);
                });
            } else {
                $(filter_block).slideDown(250, function(){
                    $toggler.removeClass('btn-primary').addClass('btn-default');
                    window.scrollTo(0, 0);
                });
            }
        });
    }
}



function initCheckboxSwitch() {
    $("[data-action='checkbox-switch']").each(function() {
        var checkboxSwitch = $(this),
            callback = $(this).data('callback'),
            readOnly = undefined !== $(this).data('read-only') ? $(this).data('read-only') : false;

        checkboxSwitch.bootstrapSwitch({
            size: "mini",
            onColor: "success",
            offColor: "danger",
            onText: "&nbsp;&nbsp;&nbsp;",
            offText: "&nbsp;&nbsp;&nbsp;",
            readonly: readOnly,
            onSwitchChange: function(event, state) {
                if (undefined !== callback) {
                    event.preventDefault();
                    window[callback](checkboxSwitch, state);
                }
            },
            onInit: function(event, state) {
                checkboxSwitch.closest('.bootstrap-switch')
                    .attr('title', checkboxSwitch.attr('title'))
                    .tooltip({'placement': 'bottom', 'html' : true})
                    .parents('label').css('padding-left', '0px')
                ;
            }
        });
    });
}

function initDisableAfterClick()
{
    var els = $('[data-disable-after-click="true"]');

    els.each(function() {
        $(this).on('click', function() {
            $(this).addClass('disabled').siblings('[data-disable-after-click="true"]').addClass('disabled');
        });
    });
}

/**
 * Forms tools
 */

function strip(html)
{
    var tmp = document.createElement("DIV");
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || "";
}
