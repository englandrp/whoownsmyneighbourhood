<script type="text/javascript">
	$(document).ready(function() {
        initPostcode('<?php echo $postcode; ?>');
    });
</script>
	<div id="main">
        <div class="od_content">
            <form method="post" action="/postcode/" id="postcodeform">
                <span><b>Enter postcode or postcode sector (eg HD1):</b>&nbsp;</span>
                <input type="text" id="pcode" name="postcode" size="14" maxlength="10" value="<?php echo $postcode; ?>" />
                <input type="submit" name="submit" id="pcodesubmit" value="Go" />
            </form>
            <div class="map_holder">
                <div id="mapdiv" class="map homemap"></div>
            </div>
	    </div><!-- #main -->
	</div><!-- #main -->
