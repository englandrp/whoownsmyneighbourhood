<script type="text/javascript">

$(document).ready(function() {
	initPlaceEdit();
});

</script>
	<div id="main">
		<?php //var_dump($postvalues); ?>
		
		<div class="od_content">
			<form action="/place/<?php echo $editvalues['plot_id']; ?>" method="post">
			<div class="edit_screen"> 
				<h2>Edit a place</h2>
				<?php echo validation_errors(); ?>
			</div>
			<div class="edit_screen"> 
				<div class="left_screen">
					<p><strong>Location:</strong>
					<br /><input type="text" class="wide_input" name="location" value="<?php echo $editvalues['location']  ?>" /></p> 				
				</div>
				<div class="right_screen">
					<p><br />Enter the location of the place you want to add.</p> 
				</div>
			</div>
			<div class="edit_screen"> 
				<div class="left_screen">
					<p>Description:
					<br /><textarea name="description"><?php echo $editvalues['description']; ?></textarea></p> 
				</div>
				<div class="right_screen">
					<p><br />If you wish, tell us a bit more about this place - describe what is there.</p> 
				</div>
			</div>
			<div class="edit_screen"> 
				<div class="left_screen">
					<div id="mapdiv" class="map editmap"> 
					</div> 
				</div>
				<div class="right_screen">
					<p>Use the map controls to get the circle into the right position on the map.</p> 
					<p>You can use the controls at the left of the map to zoom in and out and move the map around. You can also drag anywhere on the map (by clicking and holding down the mouse button) and you can drag the circle to move it to the right place.</p> 
				</div>
			</div>
			<hr />
			<div class="edit_screen"> 
				<div class="left_screen">
					<p>Land registry number:
					<br /><input type="text" class="wide_input uc_code" name="lr_title" value="<?php echo $editvalues['lr_title'];  ?>" /></p> 
				</div>
				<div class="right_screen">
					<p><br />Optional
						<br />When a property is registered, the Land Registry gives it a unique reference number. This number is called a 'Land Registry number' or ‘Title number’. If you don't know the number for this place, leave this box blank.
					</p>
				</div>
			</div>
			<div class="edit_screen"> 
				<div class="left_screen">
					<p>Current owner:
					<br /><textarea name="current_owner"><?php echo $editvalues['current_owner']  ?></textarea></p> 
				</div>
				<div class="right_screen">
					<p><br />Optional</p> 
				</div>
			</div>
			<div class="edit_screen"> 
				<div class="left_screen">
					<p>Address:
					<br /><textarea name="address"><?php echo $editvalues['address']  ?></textarea></p> 
				</div>
				<div class="right_screen">
					<p><br />Optional</p> 
				</div>
			</div>
			<div class="edit_screen"> 
				<div class="left_screen">
					<p>Contact details:
					<br /><textarea name="contact"><?php echo $editvalues['contact']  ?></textarea></p> 
				</div>
				<div class="right_screen">
					<p><br />Optional</p> 
				</div>
			</div>
			<div class="edit_screen"> 
				<div class="left_screen">
					<hr /> 
					<p><input type="submit" class="submit" name="action" value="Submit" /></p> 
					<input type="hidden" name="easting" id="js_easting" value="<?php echo $editvalues['easting']; ?>" /> 
					<input type="hidden" name="northing"id="js_northing" value="<?php echo $editvalues['northing']; ?>" /> 
				</div>
				<div class="right_screen">
				</div>
			</div>
			</form>			
	    </div>
	</div><!-- #main -->
