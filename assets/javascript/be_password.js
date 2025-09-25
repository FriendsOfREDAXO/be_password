var BePassHandler = (function () {
    var H = {};

    H.showForm = function () {
        var url = 'index.php?be_password_request=/default/form';
        var form;
        if ('FORM' === this.tagName) {
            form = this;
        } else {
            form = undefined;
        }
        if (undefined === form) {
            $.get(url, function (dat) {
                $("#rex-form-login").html(updateFormElements(dat));
                applyHandlers("#rex-js-page-main .has-handler");
            });
        } else {
            $.post(url, $(form).serialize(), function (dat) {
                $(".rex-js-login-message").remove();
                $("#rex-form-login").html(updateFormElements(dat));
                applyHandlers("#rex-js-page-main .has-handler");
            });
        }
        return false;
    };


    H.showReset = function (args) {
        var token;
        if (undefined === args.token) {
            token = $(this).attr('data-token');
        } else {
            token = args.token;
        }
        var url = 'index.php?be_password_request=/default/reset' +
            '&token=' + encodeURIComponent(token);
        var form;
        if ('FORM' === this.tagName) {
            form = this;
        } else {
            form = undefined;
        }
        if (undefined === form) {
            $.get(url, function (dat) {
                $("#rex-form-login").html(updateFormElements(dat));
                applyHandlers("#rex-js-page-main .has-handler");
            });
        } else {
            var pw = $(form).find('input[name="password"]').val();
            var csrfToken = $(form).find('input[name="_csrf_token"]').val();
            var postData = {pw: pw};
            if (csrfToken) {
                postData._csrf_token = csrfToken;
            }
            $.post(url, postData, function (dat) {
                $("#rex-form-login").html(updateFormElements(dat));
                applyHandlers("#rex-js-page-main .has-handler");
            });
        }
        return false;
    };

    return H;

})();
$(function () {
    var token_name = 'be_password_reset_token=';
    if (-1 !== location.href.indexOf(token_name)) {
        var a = location.search.substr(1).split('&');
        var token;
        for (var i = 0; i < a.length; i++) {
            if (0 === a[i].indexOf(token_name)) {
                token = a[i].replace(token_name, '');
            }
        }
        BePassHandler.showReset({token: token});
    } else {
        $.get('index.php?be_password_request=/default/index', function (dat) {
            $("#rex-js-page-main .btn-toolbar").append(dat);
            applyHandlers("#be_password_forgotten.has-handler");
        });
    }
});

function updateFormElements(content) {
    content = $(content);
    // select branding element on current login form and inject into our form
    content.find(".panel-body").prepend($(".rex-branding", "#rex-form-login"));
    // if there is no panel heading (R 5.12+), we don’t use ours either
    if (!$(".panel-heading", "#rex-form-login").length) {
        content.find(".panel-heading").remove();
    }
    return content;
}

function applyHandlers(selector) {
    var $ = jQuery; // Innerhalb von WP sonst nicht bekannt
    if (undefined == selector) {
        selector = ".has-handler";
    }
    $(selector).each(function () {
        var handler = $(this).attr('data-handler');
        if (-1 != handler.indexOf(',')) {
            var aHandler = handler.split(',');
        } else {
            var aHandler = [handler]
        }
        for (var i = 0; i < aHandler.length; i++) {
            var h = aHandler[i];
            var a = h.split(':');
            if (undefined != window[a[1]] && undefined !== window[a[1]][a[2]]) {
                // if jquery-mobile is loaded, click to vclick events
                if ('click' == a[0] && $.mobile) {
                    a[0] = 'vclick';
                }
                $(this).bind(a[0], window[a[1]][a[2]]);
            } else {
                console.log('Error applyHandlers: ' + aHandler[i] + ' Handler or function not found');
            }
        }
    });
}
