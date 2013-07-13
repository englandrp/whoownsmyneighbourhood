	<div id="main">
		<div class="wide_screen">
            <div class="left_screen">
                <h2>RSS feeds</h2>
                <p><br />Use an RSS feed to keep up-to-date on events in your area</p>
<?php
	if (is_array($wards) && count($wards) > 0)
	{
		echo '                	<ul class="rss">'. "\n";
		foreach($wards as $ward)
		{
			echo '                	<li><a href="/rss/' . $ward->w_code . '">' . $ward->w_name . '</li>'. "\n";
		}
		echo '                	</ul>'. "\n";
	}
?>
			</div>
            <div class="right_screen">
            </div>
        </div>
	</div><!-- #main -->
