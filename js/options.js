/**
 * Initialize jQuery
 * 
 * Provides functions to toggle visibility and/or properties of elements.
 * 
 * @author nrekow
 * 
 * @returns void
 */

jQuery(function() {
	
	/**
	 * Toggles the visibilty of an element in relation
	 * to a status of another element.
	 * 
	 * @returns false
	 */
	function toggleVisibility(tag) {
		if(jQuery('input[name=' + tag + '_flag]').is(':checked')) {
			jQuery('#' + tag + '_options').show();
		} else {
			jQuery('#' + tag + '_options').hide();
		}
		
		return false;
	}
	
	
	/**
	 * Toggles a property of an element between true and false
	 * in relation to the status of another element.
	 * 
	 * @returns false
	 */
	function toggleProperty(trigger_id, toggle_id, property) {
		if (jQuery(trigger_id).is(':checked')) {
			jQuery(toggle_id).prop(property, false);
			jQuery(toggle_id).removeProp(property);
		} else {
			jQuery(toggle_id).prop(property, true);
		}
		
		return false;
	}

	
	// Event handlers
	jQuery('.flag').change(function() {
		var id = this.id;
		id = id.substr(0, id.indexOf('_flag'));
		toggleVisibility(id);
	});
	
	jQuery("input[name=http_security_x_frame_options]").change(function() {
		toggleProperty('#http_security_x_frame_allow_from', '#http_security_x_frame_origin', 'disabled');
	});

	jQuery('#http_security_htaccess_flag').change(function() {
		toggleProperty('#http_security_htaccess_flag', '#htaccess', 'disabled');
	});

	
	// Initially check the status of these elements and set their properties.
	toggleVisibility('http_security_sts');
	toggleVisibility('http_security_pkp');
	toggleVisibility('http_security_expect_ct');
	toggleVisibility('http_security_x_frame');
	toggleProperty('#http_security_x_frame_allow_from', '#http_security_x_frame_origin', 'disabled');
	toggleVisibility('http_security_csp');
});
