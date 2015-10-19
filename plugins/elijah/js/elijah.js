
jQuery(document).ready(function(){
	jQuery('.start-research-strategy').click(function(event){
		parentDiv = jQuery(event.currentTarget).parents('.strategy-info');
		elijah_send_update('elijah_strategy_update',event.currentTarget.id);
		jQuery('.strategy-buttons',parentDiv).hide('fast');
		jQuery('.strategy-status-info',parentDiv).show('fast');
		jQuery('.strategy-skipped-area',parentDiv).hide('fast');
		event.preventDefault();
	});
	jQuery('.skip-research-strategy').click(function(event){
		parentDiv = jQuery(event.currentTarget).parents('.strategy-info');
		elijah_send_update('elijah_strategy_update',event.currentTarget.id);
		jQuery('.strategy-buttons',parentDiv).hide('fast');
		jQuery('.strategy-skipped-area',parentDiv).show('fast');
		event.preventDefault();
	});
	//updating started research strategies
	jQuery('.elijah-research-strategy-usefulness').change(jQuery.debounce(send_strategy_application_form,500));
	jQuery('.strategy-comments-area').keyup(jQuery.debounce(send_strategy_application_form,500));
        jQuery('.elijah-reveal').click(function() {
            var section_id = this.id;
            var section_id_to_reveal = section_id.replace('elijah-reveal-', '' );
            jQuery('#' + section_id_to_reveal ).toggle('fast');
        })
});

function send_strategy_application_form(){
	var url = elijah.ajaxurl;
	var form = jQuery(this).parents('form');
	var data = form.serialize()
	jQuery(".spinner", form).show();
	jQuery.post(url, data, function(response) {
		jQuery(".spinner").hide();
//		alert('got this from the server' + response );
	})
//	elijah_send_update( 'elijah_strategy_modified', );
}

function elijah_send_update(action,info_to_send){
	var data = {
		action: action,//'elijah_strategy_update',
		info_to_send: info_to_send,
		current_research_objective_id: elijah.current_research_objective_id
	};


	// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
	jQuery.post(elijah.ajaxurl, data, function(response) {

//		alert('Sent update. Got this from the server: ' + response);
	});
}