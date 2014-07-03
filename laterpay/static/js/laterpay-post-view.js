(function($) {$(document).ready(function() {

    var lpShowStatistic = function() {
            $('#statistics .bar').peity('bar', {
                delimiter   : ';',
                width       : 182,
                height      : 42,
                gap         : 1,
                fill        : function(value, index, array) {
                                var date        = new Date(),
                                    daysCount   = array.length,
                                    color       = '#999';
                                date.setDate(date.getDate() - (daysCount - index));
                                // highlight the last (current) day
                                if (index === (daysCount - 1))
                                    color = '#555';
                                // highlight Saturdays and Sundays
                                if (date.getDay() === 0 || date.getDay() === 6)
                                    color = '#c1c1c1';
                                return color;
                            }
            });
            $('#statistics .background-bar').peity('bar', {
                delimiter   : ';',
                width       : 182,
                height      : 42,
                gap         : 1,
                fill        : function() { return '#ddd'; }
            });
        },
        togglePreviewMode = function() {
            var $toggle = $('#preview-post-toggle');
            if ($toggle.prop('checked')) {
                $('#preview_post_hidden_input').val(1);
            } else {
                $('#preview_post_hidden_input').val(0);
            }
            makeAjaxRequest('plugin_mode');
        },
        makeAjaxRequest = function(form_id) {
            // plugin mode Ajax form
            $.post(
                lpVars.ajaxUrl,
                $('#' + form_id).serializeArray(),
                function(data) {
                    if(data && data.success) {
                        location.reload();
                    }
                },
                'json'
            );
        };

        lpShowStatistic();

        $('body').on('mousedown', '#preview-post-toggle', function(e) {
            togglePreviewMode();
        });

        $('body').on('mousedown', '.laterpay-purchase-link', function(e) {
            if( $(this).data('preview-as-visitor') ) {
                e.preventDefault();
                alert(lpVars.i18nAlert);
            }
        });

});}(jQuery));

// show LaterPay dialogs using the LaterPay dialog manager library
YUI().use('node', 'laterpay-dialog', 'laterpay-iframe', 'laterpay-easyxdm', function(Y) {
    var ppuContext  = {
                        showCloseBtn: true,
                        canSkipAddToInvoice: false
                    },
        dm          = new Y.LaterPay.DialogManager();

    dm.attachToLinks('.laterpay-purchase-link', ppuContext.showCloseBtn);
});
