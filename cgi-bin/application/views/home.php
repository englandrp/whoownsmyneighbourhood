<script type="text/javascript">
	$(document).ready(function() {
		<?php 
		if ($postvalues['easting'] != '' && $postvalues['northing'] != '')
		{
			echo "        initHome('', " . $postvalues['easting']  . ", " . $postvalues['northing'] . ");\n";
		}
		else
		{
			echo "        initHome('" . $postvalues['postcode'] . "', '', '');\n";
		}
        
		?>
    });
</script>
	<div id="main">
        <div class="od_content">
            <p id="site_mission">Find out who owns places near you, and help take responsibility for land, buildings and activities in your neighbourhood. This site features <a href="/contact">Kirklees land ownership data</a> only at the moment.</p>
            <form method="post" action="#" id="postcodeform">
                <p><span><b>Enter postcode or postcode sector (eg HD1):</b>&nbsp;</span>
                <input type="text" class="uc_code" name="postcode" value="" id="pcode" size="14" maxlength="10" />
                <?php
				if (is_array($wards) && count($wards) > 0)
				{
					echo '                <select name="ward" id="js_ward">' . "\n";
                	echo '                <option value="">...or choose an area</option>' . "\n";
                	echo '';
                	foreach ($wards as $ward)
                	{
                		echo '				<option value="' . $ward->w_centre_x . '/' . $ward->w_centre_y . '">';
                		echo $ward->w_name . '</option>' . "\n";
                	}
                	echo '					</select>' . "\n";
				}
                ?>
                <input type="submit" class="vanilla" name="submit" id="pcodesubmit" value="Go" /></p>
            </form>
            <div id="map_key">
                <form class="formblock" action="/" method="post">
					<input type="submit" class="vanilla" name="submit" value="&laquo; Redraw map" />
					<input type="hidden" name="js_easting" id="js_easting" value="" />
					<input type="hidden" name="js_northing" id="js_northing" value="" />        
				</form>
				<form class="formblock" action="/place" method="post">
					<input type="submit" class="vanilla" name="submit" value="Add a place" />
					<input type="hidden" name="easting" id="js_place_easting" value="" />
					<input type="hidden" name="northing" id="js_place_northing" value="" />        
				</form>
				<span class="right">Council owned land <img src="/x/img/plot.png" alt="Council owned land" /> </span>
			</div>
            <div class="map_holder">
                <div id="mapdiv" class="map homemap">

					<p id="photo_caption"><i>Berry Brow</i> by Alison Munday</p>
				</div>
				<div id="aggregator">
				<?php
				if (is_array($aggregates) && count($aggregates) > 0)
				{
					echo '					<h2>What\'s happening near you</h2>' . "\n";
					foreach ($aggregates as $_agg)
					{
						echo '					<p><a href="' . $_agg->plot_url . '">' . $_agg->content . '</a></p>' . "\n";
					}
				}
				?>
				</div>
            </div>
	    </div>
	</div><!-- #main -->
