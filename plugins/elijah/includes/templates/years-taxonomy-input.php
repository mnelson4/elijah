<div style='background-color:red'>
<select multiple="multiple" name="<?php echo $taxonomy->name;?>-years[]">
<?php foreach( $year_terms as $year_term_id => $year_name ) { ?>
	<option value="<?php echo $year_term_id;?>" <?php echo in_array( $year_term_id, $selected_terms ) ? 'selected="selected"' : ''?>><?php echo $year_name;?></option>
<?php } ?>
</select>
</div>