	<div id="main">
		<div class="wide_screen">
            <div class="left_screen">
                <h2>My Page</h2>
<?php

    if (is_array($places) && count($places) > 0)
    {
        echo '                <h3>Places I have added</h3>' . "\n";
		foreach($places as $place)
		{
			//echo '                <p><a href="/plot/' . $place->id . '">' . $place->location . '</a> | ';
			echo '                <p><a href="/landreg/unknown/' . $place->id . '">' . $place->location . '</a> | ';
			echo '<a href="/place/' . $place->id . '">Edit</a></p>' . "\n";
		}
    }

    if (is_array($ccontacts))
    {
        echo '                <h3>Plots where I\'m a community contact</h3>' . "\n";
        if (count($ccontacts) == 0)
        {
            echo '                <p> - none - </p>' . "\n";
        }
        else
        {
            foreach($ccontacts as $_ccon)
            {
                if ($_ccon->lr_title == '')
                {
					echo '                <p><a href="/landreg/unknown/' . $_ccon->id . '">' . $_ccon->location . '</a></p>' . "\n";
                }
                else
                {
					echo '                <p><a href="/landreg/' . $_ccon->lr_title;
					if ($_ccon->lr_subtitle != '1') echo '/' . $_ccon->lr_subtitle;
					echo '">' . $_ccon->lr_title;
					if ($_ccon->lr_subtitle != '1') echo '/' . $_ccon->lr_subtitle;
					echo '</a></p>' . "\n";
				}
            }
        }
    }

    if (is_array($c_msgs))
    {
        echo '                <h3>Plots where I\'ve sent a message to the community contacts</h3>' . "\n";
        if (count($c_msgs) == 0)
        {
            echo '                <p> - none - </p>' . "\n";
        }
        else
        {
            foreach($c_msgs as $_c_msg)
            {
                if ($_c_msg->lr_title == '')
                {
					echo '                <p><a href="/landreg/unknown/' . $_c_msg->id . '">' . $_ccon->location . '</a></p>' . "\n";
                }
                else
                {
					echo '                <p><a href="/landreg/' . $_c_msg->lr_title;
					if ($_c_msg->lr_subtitle != '1') echo '/' . $_c_msg->lr_subtitle;
					echo '">' . $_c_msg->lr_title;
					if ($_c_msg->lr_subtitle != '1') echo '/' . $_c_msg->lr_subtitle;
					echo '</a>' . "\n";
					echo '              <br />"' . $_c_msg->msg . '"';
					echo '              <br />' . date('jS M Y H:i', $_c_msg->timestamp);
					echo '</p>' . "\n";
				}
            }
        }
    }

    if (is_array($contribs))
    {
        echo '                <h3>Plots where I have added a contribution</h3>' . "\n";
        if (count($contribs) == 0)
        {
            echo '                <p> - none - </p>' . "\n";
        }
        else
        {
            foreach($contribs as $_contrib)
            {
                if ($_contrib->lr_title == '')
                {
					echo '                <p><a href="/landreg/unknown/' . $_contrib->id . '">' . $_contrib->location . '</a></p>' . "\n";
                }
                else
                {
					echo '                <p><a href="/landreg/' . $_contrib->lr_title;
					if ($_contrib->lr_subtitle != '1') echo '/' . $_contrib->lr_subtitle;
					echo '">' . $_contrib->lr_title;
					if ($_contrib->lr_subtitle != '1') echo '/' . $_contrib->lr_subtitle;
					echo '</a></p>' . "\n";
				}
            }
        }
    }
?>
			</div>
            <div class="right_screen">
                <p>&nbsp;</p>
            </div>
        </div>
	</div><!-- #main -->
