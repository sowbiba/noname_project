(function (window) {

    var tooltips = function() {};

    tooltips.prototype.initToolTips = function () {
        var tipTopOptions    = {container: '#content_container', 'html': true}, // default = top
            tipBottomOptions = {container: '#content_container', 'placement': 'bottom', 'html': true},
            tipRightOptions  = {container: '#content_container', 'placement': 'right', 'html': true},
            tipLeftOptions   = {container: '#content_container', 'placement': 'left', 'html': true};

        $('.tip').tooltip(tipTopOptions);
        $('.tip-top').tooltip(tipTopOptions);
        $('.tip-bottom').tooltip(tipBottomOptions);
        $('.tip-right').tooltip(tipRightOptions);
        $('.tip-left').tooltip(tipLeftOptions);
    };

    tooltips.prototype.initPopovers = function () {
        var popoverTopOptions    = {container: '#content_container'}, // default = top
            popoverBottomOptions = {container: '#content_container', 'placement': 'bottom'},
            popoverRightOptions  = {container: '#content_container', 'placement': 'right'},
            popoverLeftOptions   = {container: '#content_container', 'placement': 'left'};

        $('.popover').popover(popoverTopOptions);
        $('.popover-top').popover(popoverTopOptions);
        $('.popover-bottom').popover(popoverBottomOptions);
        $('.popover-right').popover(popoverRightOptions);
        $('.popover-left').popover(popoverLeftOptions);
    };

    if (typeof window.app == "undefined") {
        window.app = {};
    }


    window.app.tooltips = new tooltips();

}(window));
