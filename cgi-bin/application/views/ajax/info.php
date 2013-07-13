<?php
$output = array();
if ($plot[0]->owner == 'council') $output['details'] = '			<p><strong>Ownership:</strong> this plot of land is owned by Kirklees Council</p>';
elseif ($plot[0]->owner == 'private') $output['details'] = '			<p><strong>Ownership:</strong> this is not land owned by Kirklees Council. These details have been supplied by the public.</p>';
if ($plot[0]->lr_title != '') $output['details'] .= '<p><strong>Land Registry Title:</strong> ' . $plot[0]->lr_title . '</p>';

if ($plot[0]->deed_no != '') $output['details'] .= '<p><strong>Deed:</strong> ' . $plot[0]->deed_no . '</p>' . "\n";
if ($plot[0]->location != '') $output['details'] .= '<p><strong>Location:</strong> ' . $plot[0]->location . '</p>' . "\n";
if ($plot[0]->timestamp != '') $output['details'] .= '<p><strong>Date:</strong> ' . date('jS F Y', $plot[0]->timestamp) . '</p>' . "\n";
if ($plot[0]->nature != '') $output['details'] .= '<p><strong>Nature of document:</strong> ' . $plot[0]->nature . '</p>' . "\n";
if ($plot[0]->grid_ref != '') $output['details'] .= '<p><strong>Grid reference:</strong> ' . $plot[0]->grid_ref . '</p>' . "\n";
if ($plot[0]->description != '') $output['details'] .= '<p><strong>Description:</strong> ' . $plot[0]->description . '</p>' . "\n";
if ($plot[0]->deed_no == '' && $plot[0]->location == '' && $plot[0]->nature == '')
{
    $output['details'] .= '<p>This plot is part of the Ramsden Estate, which was bought by the former Huddersfield Corporation in 1920, when Huddersfield became known as "The town that bought itself". The area of land included in the Ramsden Estate is extremely large, so it is recorded a bit differently from the other plots of land that Kirklees Council owns. For example, we don’t have individual plot descriptions for land that is part of the Ramsden Estate.</p>' . "\n";
    //$output['details'] .= '<p>Find out more about <a href="/blog/the-town-that-bought-itself">The town that bought itself</a></p>' . "\n";
    $output['details'] .= '<p><strong>Add more information:</strong> click on "Contribute" and tell us what you know about this plot of land</p>' . "\n";
}
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

$output['addendum'] = '';
if ($plot[0]->website != '') $output['addendum'] .= '<p><a href="' . $plot[0]->website . '" target="_blank"><strong>Land and Property FAQs</strong></a></p>' . "\n";
$output['addendum'] .= '					<p><strong>Land ownership issues:</strong> any questions on this subject should be emailed to the Terrier team at dps@kirklees.gov.uk</p>' . "\n";
$output['addendum'] .= '					<p><a href="https://www.landregistry.gov.uk/wps/portal/Property_Search" target="_blank">Land Registry Property Search</a></p>' . "\n";
echo json_encode($output);
			