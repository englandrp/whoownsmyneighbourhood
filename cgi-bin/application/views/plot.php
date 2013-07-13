<script type="text/javascript">
	$(document).ready(function() {
	<?php
		if ($plot[0]->owner == 'council') echo '		initPlot();' . "\n";
		elseif ($plot[0]->owner == 'private') echo '		initPlace();' . "\n";
	?>
    });
</script>
	<div id="main">
	<div class="od_content">
        <form method="post" action="/" id="postcodeform">
            <p><span><strong>Enter postcode or postcode sector (eg HD1):</strong>&nbsp;</span>
            <input type="text" id="pcode" name="postcode" size="14" maxlength="10" value="<?php echo $postcode; ?>" />
            <input type="submit" class="vanilla" name="submit" id="pcodesubmit" value="Go" /></p>
        </form>
        <div id="map_key">
			<form action="/" method="post" class="formblock">
				<input type="submit" class="vanilla" name="submit" value="&laquo; View all local plots" />
				<input type="hidden" name="js_plot_id" id="js_plot_id" value="<?php echo $plot[0]->id; ?>" />
				<input type="hidden" name="js_easting" id="js_easting" value="<?php echo $plot[0]->east; ?>" />
				<input type="hidden" name="js_northing" id="js_northing" value="<?php echo $plot[0]->north; ?>" />        
			</form>
			<span class="right">Council owned land <img src="/x/img/plot.png" alt="Council owned land" /> </span>
		</div>
        <div class="map_holder">
            <div id="map" class="map"></div>
        </div>
		<div class="wide_screen">
			<ul class="plot_nav"> 
				<li><a class="off" id="js_info">Info</a></li> 
				<li><a class="off" id="js_contacts">Contacts</a></li> 
				<li><a class="on" id="js_contribute">Contribute</a></li> 
				<li><a class="off" id="js_updates">Updates</a></li> 
			</ul> 
			<div class="left_screen"> 
                <div id="plot_details">
                
					<div class="message_roster" id="js_accordion">
						<h2><a>Local news</a> <span class="toggler">open</span></h2>
						<div class="accordion-content">
							<p>What are the latest goings on on this plot of land?</p>
							<p class="hiddenalert" id="js_localnews_alert"></p>
							<form method="post" action="#">
								<p><textarea name="localnews" id="js_localnews"></textarea></p>
								<p><input type="submit" class="submit" name="action" value="Add your news" id="js_localnews_submit" /></p>
							</form>
						</div>
						<h2><a>Local names</a> <span class="toggler">open</span></h2>
						<div class="accordion-content">
							<p>Does this plot of land have a local name?</p>
							<p class="hiddenalert" id="js_localname_alert"></p>
							<form method="post" action="#">
								<p><textarea name="localname" id="js_localname"></textarea></p>
								<p><input type="submit" class="submit" name="action" value="Add a local name" id="js_localname_submit" /></p>
							</form>
						</div>
						<h2><a>Local history</a> <span class="toggler">open</span></h2>
						<div class="accordion-content">
							<p>Do you have particular memories about things that have happened on this plot of land?</p>
							<p class="hiddenalert" id="js_localhistory_alert"></p>
							<form method="post" action="#">
								<p><textarea name="localhistory" id="js_localhistory"></textarea></p>
								<p><input type="submit" class="submit" name="action" value="Add your memories" id="js_localhistory_submit" /></p>
							</form>
						</div>
						<h2><a>Local organisations</a> <span class="toggler">open</span></h2>
						<div class="accordion-content">
							<p>Are there any relevant local organisations for this plot of land?
							<br />Add their website details below.
							<?php
							if (count($orgs) > 0)
							{
								echo '<a href="#localorganisations">' . count($orgs) . ' added so far.</a>';
							}
							?>
							</p>
							<p class="hiddenalert" id="js_localorg_alert"></p>
							<form method="post" action="#">
								<p>Name of organisation<br />
									<input type="text" class="wide_input" name="localorgname" id="js_localorg_name" /></p>
								<p>Web address<br />
									<input type="text" class="wide_input" name="localorgurl" id="js_localorg_url" /></p>
								<p><input type="submit" class="submit" name="action" value="Add organisation details" id="js_localorg_submit" /></p>
							</form>
							
						</div>
					</div>                
                <!--
                <p><strong>Ownership:</strong> this plot of land is owned by Kirklees Council</p>
                <?php
                var_dump($history);
				if ($plot[0]->lr_title != '') echo '<p><strong>Land Registry Title:</strong> ' . $plot[0]->lr_title . '</p>' . "\n";
				if ($plot[0]->deed_no != '') echo '<p><strong>Deed:</strong> ' . $plot[0]->deed_no . '</p>' . "\n";
				if ($plot[0]->location != '') echo '<p><strong>Location:</strong> ' . $plot[0]->location . '</p>' . "\n";
				if ($plot[0]->timestamp != '') echo '<p><strong>Date:</strong> ' . date('jS F Y', $plot[0]->timestamp) . '</p>' . "\n";
				if ($plot[0]->nature != '') echo '<p><strong>Nature of document:</strong> ' . $plot[0]->nature . '</p>' . "\n";
				if ($plot[0]->grid_ref != '') echo '<p><strong>Grid reference:</strong> ' . $plot[0]->grid_ref . '</p>' . "\n";
				
				if ($plot[0]->deed_no == '' && $plot[0]->location == '' && $plot[0]->nature == '')
				{
					echo '<p>This plot is part of the Ramsden Estate, which was bought by the former Huddersfield Corporation in 1920, when Huddersfield became known as "The town that bought itself". The area of land included in the Ramsden Estate is extremely large, so it is recorded a bit differently from the other plots of land that Kirklees Council owns. For example, we don’t have individual plot descriptions for land that is part of the Ramsden Estate.</p>' . "\n";
					//echo '<p>Find out more about <a href="/blog/the-town-that-bought-itself">The town that bought itself</a></p>' . "\n";
					echo '<p><strong>Add more information:</strong> click on "Contribute" and tell us what you know about this plot of land</p>' . "\n";
				}
				?>
				-->
				</div> 
			</div> 
			<div class="right_screen">
                <div id="plot_addendum">
<?php
	$output['addendum'] = '			<h3>Local news</h3>' . "\n";
	$output['addendum'] .= '					<ul id="js_local_news_list">';

    if (is_array($news) && count($news) > 0)
    {
        foreach($news as $linews)
        {
            $output['addendum'] .= '						<li>' . nl2br($linews->msg);
            $output['addendum'] .= '<br /><span class="quietly">' . date('jS F Y, H:i', $linews->timestamp) . '</span></li>' . "\n";
        }
    }
    else
    {
        $output['addendum'] .= '						<li>Be the first to add local news about this plot</li>' . "\n";
    }
	$output['addendum'] .= '					</ul>';


	$output['addendum'] .= '			<h3>Local names</h3>' . "\n";
	$output['addendum'] .= '					<ul id="js_local_name_list">';
    if (is_array($localname) && count($localname) > 0)
    {
        foreach($localname as $liln)
        {
            $output['addendum'] .= '						<li>' . nl2br($liln->msg);
            $output['addendum'] .= '<br /><span class="quietly">' . date('jS F Y, H:i', $liln->timestamp) . '</span></li>' . "\n";
        }
    }
    else
    {
        $output['addendum'] .= '						<li>Be the first to send in a local name for this plot</li>' . "\n";
    }
	$output['addendum'] .= '				</ul>';


	$output['addendum'] .= '			<h3>Local history</h3>' . "\n";
	$output['addendum'] .= '				<ul id="js_local_history_list">';
    if (is_array($history) && count($history) > 0)
    {
        foreach($history as $lih)
        {
            $output['addendum'] .= '						<li>' . nl2br($lih->msg);
            $output['addendum'] .= '<br /><span class="quietly">' . date('jS F Y, H:i', $lih->timestamp) . '</span></li>' . "\n";
        }
    }
    else
    {
        $output['addendum'] .= '					<li>Be the first to add local historical details about this plot</li>' . "\n";
    }
	$output['addendum'] .= '				</ul>';


	$output['addendum'] .= '			<h3 id="localorganisations">Local organisations</h3>' . "\n";
	$output['addendum'] .= '				<ul id="js_local_org_list">';
    if (is_array($orgs) && count($orgs) > 0)
    {
        foreach($orgs as $liorg)
        {
            $li = unserialize($liorg->msg);
            $output['addendum'] .= '						<li><a href="';
            if (substr($li[1], 0, 7) != 'http://') $output['addendum'] .= 'http://';
            $output['addendum'] .= $li[1] . '" target="_blank">' . $li[0]. '</a></li>' . "\n";
        }
    }
    else
    {
        $output['addendum'] .= '						<li>Be the first to add details about a local organisation</li>' . "\n";
    }

	$output['addendum'] .= '					</ul>';
echo $output['addendum'];
?>
                <!--
                <?php
                $output['addendum'] = '';
				if ($plot[0]->website != '') echo '<p><a href="' . $plot[0]->website . '" target="_blank"><strong>Land and Property FAQs</strong></a></p>' . "\n";
				echo '<p><strong>Land ownership issues:</strong> any questions on this subject should be emailed to the Terrier team at dps@kirklees.gov.uk</p>' . "\n";
				?>
				-->
				</div>
            </div>
        </div>
	</div>
	</div>

