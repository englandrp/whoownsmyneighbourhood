			<p><strong>Ownership:</strong> this plot of land is owned by Kirklees Council</p>
<?php
if ($plot[0]->lr_title != '') echo '                <p><strong>Land Registry Title:</strong> ' . $plot[0]->lr_title . '</p>' . "\n";
if ($plot[0]->deed_no != '') echo '                <p><strong>Deed:</strong> ' . $plot[0]->deed_no . '</p>' . "\n";
if ($plot[0]->location != '') echo '                <p><strong>Location:</strong> ' . $plot[0]->location . '</p>' . "\n";
if ($plot[0]->timestamp != '') echo '                <p><strong>Date:</strong> ' . date('jS F Y', $plot[0]->timestamp) . '</p>' . "\n";
if ($plot[0]->nature != '') echo '                <p><strong>Nature of document:</strong> ' . $plot[0]->nature . '</p>' . "\n";
if ($plot[0]->grid_ref != '') echo '                <p><strong>Grid reference:</strong> ' . $plot[0]->grid_ref . '</p>' . "\n";
if ($plot[0]->website != '') echo '                <p><a href="' . $plot[0]->website . '" target="_blank"><strong>Land and Property FAQs</strong></a></p>' . "\n";
if ($plot[0]->deed_no == '' && $plot[0]->location == '' && $plot[0]->nature == '')
{
    echo '                <p>At the moment there isn\'t any more information because this plot was bought from the Ramsden Estate in 1920, when Huddersfield was known as The Town That Bought Itself.</p>' . "\n";
    echo '                <p><strong>Add more information:</strong> click on "Contribute" and tell us what you know about this plot of land</p>' . "\n";
}
?>
			<p><strong>Land ownership issues:</strong> any questions on this subject should be emailed to the Terrier team at dps@kirklees.gov.uk</p>