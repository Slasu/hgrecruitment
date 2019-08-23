jQuery(document).ready(function() {

    jQuery('.hgquiz--question__checkboxMark').on('click touchend', function() {
        if( jQuery(this).parent().data('is-final') !== undefined && jQuery(this).parent().data('is-final') === true)
        {
            let isChecked = jQuery('input[type="checkbox"]', this).is(':checked');

            jQuery.each(jQuery(this).parent().siblings(), function(i, elem) {
                if( isChecked ) {
                    jQuery(elem).addClass('answer--disabled');
                    jQuery('input[type="checkbox"]', elem)
                        .attr('disabled', true)
                        .prop('checked', false);
                } else {
                    jQuery(elem).removeClass('answer--disabled');
                    jQuery('input[type="checkbox"]', elem).removeAttr('disabled', true);
                }
            })
        }
    });

    jQuery('#HgQuizFormSubmit').on('click touchend', function() {
        event.preventDefault();

        var isAtLeastOneChecked = false;
        jQuery.each(jQuery('.hgquiz--question__single input[type="checkbox"]'), function(i, elem) {
            if(jQuery(elem).is(':checked'))
                isAtLeastOneChecked = true;
        });

        if( !isAtLeastOneChecked ) {
            jQuery('#HgQuizErrMsg').html(`<p>At least one answer must be selected!</p>`);
            return;
        }

        jQuery('#HgQuizForm').css('display', 'none');
        jQuery('#HgQuizAjaxLoader').css('display', 'block');

        let formData = jQuery('#HgQuizForm').serialize();
        let quizId = jQuery('#HgQuizForm').data('quiz-id');

        jQuery.post(
            ajaxurl,
            {
                'action': 'HgQuizFormSubmit',
                'data': formData,
                'quizId': quizId,
            },
            function(response) {
                let responseObj = JSON.parse(response);
                if( responseObj.status == "success" ) {
                    WHCreateCookie('HgQuizSubmittedForm'+quizId, 'submitted', 7);
                    jQuery('#HgQuizSuccessMsg').html(`<p>Thank you for submitting the form!</p>`);
                } else {
                    jQuery('#HgQuizErrMsg').html(`<p>responseObj.msg</p>`);
                }
                jQuery('#HgQuizAjaxLoader').css('display', 'none');
            }
        )
    });

});

function WHCreateCookie(name, value, days) {
    var date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    var expires = "; expires=" + date.toGMTString();
    document.cookie = name + "=" + value + expires + "; path=/";
}
function WHReadCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ')
            c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0)
            return c.substring(nameEQ.length, c.length);
    }
    return false;
}
