<?php
$output = array();
$output['details'] = '			<div class="message_roster" id="js_accordion">
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
                    <br />Add their website details below.';
if (count($orgs) > 0)
{
	$output['details'] .= '<a href="#localorganisations">' . count($orgs) . ' added so far.</a>';
}    
$output['details'] .= '</p>
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
';

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


echo json_encode($output);
			






					