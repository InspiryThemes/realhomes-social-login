(function($){
	
	$('.rsl-provider').on('click', function(e){

		var $provider_btn = $(this);
		var $provider     = $(this).data('provider');
		var $msg_wrap     = $('.rsl-ajax-message');

        $.ajax({
            type: 'POST',
            url: rsl_data.ajax_url,
            dataType: 'json',
            data: {
                'action' : 'rsl_'+ $provider +'_oauth_url'
            },
            beforeSend: function( ) {
				$provider_btn.addClass('in-progress');
				$msg_wrap.removeClass('error');
				$msg_wrap.text('');
            },
            complete: function(){
				$provider_btn.removeClass('in-progress');
            },
            success: function (response) {
               if(response.success){
				$msg_wrap.text(response.message);
				 window.location.replace(response.oauth_url);
			   } else {
				   $msg_wrap.addClass('error');
				   $msg_wrap.text(response.message);
			   }
            },
            error: function(error) {
				$msg_wrap.addClass('error');
				$msg_wrap.text(error);
            }
        });
	});

})(jQuery);