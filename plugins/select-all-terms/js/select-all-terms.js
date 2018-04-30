/**
 * Adds a button to select all / de-select all hierarchy terms under taxonomy hierarchy metabox
 */

window.SAT_LABELS = window.SAT_LABELS || {};

(function( $ ) {
	
	$(document).ready(function() {
		
		//add buttons
		$(".categorydiv").each( function() {
			
			var tax_id = $(this).attr('id');
			var tax_name = tax_id.replace("taxonomy-", "");
			$('#'+ tax_name +'-adder')
				.prepend('<p><input type="button" data-tsl-tax="'+ tax_name +'" class="button tsl-select" value="'+ SAT_LABELS.select +'"><input type="button" data-tsl-tax="'+ tax_name +'" class="button tsl-deselect" value="'+ SAT_LABELS.deselect +'"></p>');
			
		});
		
		//add trigger to Select all
		$(".tsl-select").click( function() {
			var tax = $(this).attr('data-tsl-tax');
			$('#' + tax + 'checklist').find("input[type='checkbox']").prop('checked', true);
			
		});
		
		//add trigger to deSelect all
		$(".tsl-deselect").click( function() {
			var tax = $(this).attr('data-tsl-tax');
			$('#' + tax + 'checklist').find("input[type='checkbox']").prop('checked', false);
			
		});
		
	
	}); // document ready end
 
}(jQuery));
