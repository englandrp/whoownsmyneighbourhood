<script type="text/javascript">

$(document).ready(function() {
	initPlaceEdit();
});

</script>
	<div id="main">
		<?php //var_dump($postvalues); ?>
		
		<div class="od_content">
			<form action="/place" method="post"> 
			<div class="edit_screen"> 
				<h2>Add a place</h2>
				<?php echo validation_errors(); ?>
			</div>
			<div class="edit_screen"> 
				<div class="left_screen">
					<p><strong>Location:</strong>
					<br /><input type="text" class="wide_input" name="location" value="<?php echo $postvalues['location']  ?>" /></p> 				
				</div>
				<div class="right_screen">
					<p><br />Enter the location of the place you want to add.</p> 
				</div>
			</div>
			<div class="edit_screen"> 
				<div class="left_screen">
					<p>Description:
					<br /><textarea name="description"><?php echo $postvalues['description']  ?></textarea></p> 
				</div>
				<div class="right_screen">
					<p><br />If you wish, give a brief description of where it is and what is there.</p> 
				</div>
			</div>
			<div class="edit_screen"> 
				<div class="left_screen">
					<div id="mapdiv" class="map editmap"> 
					</div> 
				</div>
				<div class="right_screen">
					<p>Use the map controls to get the circle into the right position on the map.</p> 
					<p>You can drag the map or the circle using to pan to the right place and the controls top left let you zoom in and out and move the map around.</p> 
				</div>
			</div>
			<hr />
			<div class="edit_screen"> 
				<div class="left_screen">
					<p>Land registry number:
					<br /><input type="text" class="wide_input uc_code" name="lr_title" value="<?php echo $postvalues['lr_title']  ?>" /></p> 
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
					<br /><textarea name="current_owner"><?php echo $postvalues['current_owner']  ?></textarea></p> 
				</div>
				<div class="right_screen">
					<p><br />Optional</p> 
				</div>
			</div>
			<div class="edit_screen"> 
				<div class="left_screen">
					<p>Address:
					<br /><textarea name="address"><?php echo $postvalues['address']  ?></textarea></p> 
				</div>
				<div class="right_screen">
					<p><br />Optional</p> 
				</div>
			</div>
			<div class="edit_screen"> 
				<div class="left_screen">
					<p>Contact details:
					<br /><textarea name="contact"><?php echo $postvalues['contact']  ?></textarea></p> 
				</div>
				<div class="right_screen">
					<p><br />Optional</p> 
				</div>
			</div>
			<div class="edit_screen"> 
				<div class="left_screen">
					<hr /> 
					<p><input type="submit" class="submit" name="action" value="Submit" /></p> 
					<input type="hidden" name="easting" id="js_easting" value="<?php echo $postvalues['easting']  ?>" /> 
					<input type="hidden" name="northing"id="js_northing" value="<?php echo $postvalues['northing']  ?>" /> 
				</div>
				<div class="right_screen">
				</div>
			</div>
			</form>
	    </div>
	</div><!-- #main -->
