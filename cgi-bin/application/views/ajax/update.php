<?php

$output = array();
$output['details'] = '<p>We can send you weekly email updates whenever there are any changes for this plot.</p>';
$output['details'] .= '<p>We will only send an email if something happens, if it doesn\'t, you won\'t get anything from us.</p>';
$output['details'] .= '<p>We will send out updates whenever there is a new planning application for this plot or when more contributions have been added to this page.</p>';
$output['addendum'] = '
            <p class="hiddenalert" id="js_my_updates_alert"></p>
            <p><a href="#" id="js_my_updates_link">' . $newsletterstatus . '</a></p>';

echo json_encode($output);
