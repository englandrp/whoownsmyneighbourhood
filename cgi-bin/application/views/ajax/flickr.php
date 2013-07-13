<?php

    if (is_array($flickr_images)  && count($flickr_images) > 0)
    {
        shuffle($flickr_images);
        echo '				<h2>Images from www.flickr.com</h2>' . "\n";
        echo '				<hr />' . "\n";
        echo '				<p>' . "\n";
        $i = 0;
        $imgLimit = count($flickr_images);
        while ($i < $imgLimit)
        {
            $image = $flickr_images[$i];
            echo '				<a href="' . $image['link'] . '" title="';
            # most of this is to do with absent or overlong alt tags...
            echo 'From www.flickr.com';
            if ($image['alt'] != '')
            {
                if (strlen($image['alt']) > 100) echo " - " . substr($image['alt'], 0, 97) . '...';
                else echo " - " . $image['alt'];
            }
            echo '"><img src="' . $image['thmb_src'] . '" alt="';
            if ($image['alt'] == '') echo ucfirst($page_name) . ' image from www.flickr.com';
            else echo $image['alt'];
            echo '" /></a>' . "\n";
            $i++;
        }
        echo '				</p>' . "\n";
        echo '				<hr />' . "\n";
        echo '				<p>This product uses the Flickr API but is not endorsed or certified by Flickr.</p>' . "\n";
    }
