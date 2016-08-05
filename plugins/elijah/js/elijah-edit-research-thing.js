/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function(){
//	jQuery('#elijah-edit-researc-thing').validate();
	jQuery('.hierarchical-reveal-source').change(function(){
		var grandparent = jQuery(this).parent().parent();
		if(this.checked){
			grandparent.children('.hierarchical-reveal-destination-area').show();
		} else {
			grandparent.children('.hierarchical-reveal-destination-area').hide();
			grandparent.find( '.hierarchical-reveal-source').prop( 'checked', false );
		}
	})
});