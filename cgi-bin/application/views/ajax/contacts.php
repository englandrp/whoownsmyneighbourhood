<?php

$output = array();
$output['details'] = '
			<h2>Community contacts</h2>
			<p>A community contact is anyone who wants to get involved with discussions around this land, the buildings on it, or how it could be used.</p>
			<p>If you want the latest information it might be quicker to ask the community contacts rather than the council.</p>
			<p>Anyone can be a community contact for this plot - and they can join or leave whenever that suits them. </p>
			<p>Council officers are welcome to become community contacts for land, buildings or activities they are responsible for.</p>
			<p>A daily compilation of any comments that are added to this page are emailed to all the community contacts.</p>
			<p>Personal contact details of community contacts are not shared with anybody at all, not even the other community contacts. </p>
			<p>If community contacts want to get in touch with each other please do so through this site and arrange to share details through another channel if necessary.</p>
			<p><strong>Report a problem:</strong> <a href="http://www.kirklees.gov.uk/you-kmc/kmc-formsindex/reportit/locality.asp" target="_blank">to Kirklees Council</a></p>';
if ($plot[0]->current_owner != '')
{
	$output['details'] .= '<p><strong>Current owner:</strong> ' . $plot[0]->current_owner . '</p>' . "\n";
}
if ($plot[0]->address != '')
{
	$output['details'] .= '<p><strong>Address:</strong> ' . $plot[0]->address . '</p>' . "\n";
}
if ($plot[0]->contact != '')
{
	$output['details'] .= '<p><strong>Contact:</strong> ' . $plot[0]->contact . '</p>' . "\n";
}

$output['addendum'] = '
			<p id="js_community_contacts">' . $contactcount . '</p>
            <p class="hiddenalert" id="js_my_contact_alert"></p>
            <p><a href="#" id="js_my_contact_link">' . $contactstatus . '</a></p>
            <h2>Send a message to the community contacts</h2>
            <p class="hiddenalert" id="js_contact_msg_alert"></p>
            <form method="post" action="#">
                <p><textarea name="message" id="js_contact_msg"></textarea></p>
                <p><input type="submit" class="submit" name="action" value="Send your message" id="js_contact_msg_submit" /></p>
            </form>';
            
if (is_array($orgs) && count($orgs) > 0) 
{
	$output['addendum'] .= '<h3>Local organisations</h3><ul>';
	foreach($orgs as $liorg)
	{
		$li = unserialize($liorg->msg);
		$output['addendum'] .= '<li><a href="';
		if (substr($li[1], 0, 7) != 'http://') $output['addendum'] .= 'http://';
		$output['addendum'] .= $li[1] . '" target="_blank">' . $li[0]. '</a></li>' . "\n";
	}
	$output['addendum'] .= '</ul>';
}
echo json_encode($output);
