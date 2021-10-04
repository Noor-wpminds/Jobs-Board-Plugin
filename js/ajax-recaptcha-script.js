	// Perform AJAX recaptcha on form submit
	var onReturnCallback = function(response) { 
		action = 'amb_recaptcha_ajax';
		var recaptcha = jQuery('#g-recaptcha-response').val();	
		jQuery.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajax_auth_object.ajaxurl,
			data: {
				'action': action,
				'recaptcha': recaptcha
			},
			success: function (data) {
				if(data.status=='success'){
					jQuery('p.status').text(data.message);
				}else{
					jQuery('p.status').text(data.message);	
				}			
			}
		});
	}