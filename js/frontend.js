(function($){
	
	$('.rsl-provider').on('click', function(e){

		var $provider = $( this ).data('provider');

		console.log('rsl_'+ $provider +'_oauth_url');

        $.ajax({
            type: 'POST',
            url: rsl_data.ajax_url,
            dataType: 'json',
            data: {
                'action' : 'rsl_'+ $provider +'_oauth_url'
            },
            beforeSend: function( ) {
                console.log('sending...');
            },
            complete: function(){
                console.log('completed!');
            },
            success: function (response) {
               if(response.success){
				 window.location.replace(response.oauth_url);
			   } else {
				   console.log(response);
			   }
            },
            error: function(error) {
                console.log(error);
            }
        });
	});

})(jQuery);