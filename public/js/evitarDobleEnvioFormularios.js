$('form').submit(function (event) {
    if ($(this).hasClass('submitted')) {
            event.preventDefault();
    }
    else {
        $(this).find('.spin:submit').html('<i class="fa fa-spinner fa-spin"></i>');
        $(this).find('.spin:submit').attr('disabled', true);
        
        $(this).addClass('submitted');
    }
});