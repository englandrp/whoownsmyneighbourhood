<?php

#var_dump($plots);

$otpt = '{ "type": "FeatureCollection", "features": [';
$firstPlot = true;
if (is_array($plots))
{
    foreach($plots as $plot)
    {
        $bits = explode("\n", $plot->al_coords);
        #var_dump($bits);
        if ( ! $firstPlot) $otpt .= ',';
        $otpt .= '{ "type": "Feature", "properties":{"plot_id":"' . $plot->id . '","lr_title":"' . $plot->lr_title . '","descr":"' . htmlspecialchars($plot->location) . '"},"geometry": {"type": "Polygon", "coordinates": [[';
        $firstBit = true;
        foreach ($bits as $bit)
        {
            if (strlen(trim($bit)) > 0)
            {
                if ( ! $firstBit) $otpt .= ',';
                $otpt .= '[' . $bit . ']';
                $firstBit = false;
            }
        }
        
        $otpt .= ']]} }';
        $firstPlot = false;
    }
}
$otpt .= ']}';
echo $otpt;



