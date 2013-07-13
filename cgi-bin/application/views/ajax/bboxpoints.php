<?php

//var_dump($plots);

$otpt = '{ "type": "FeatureCollection", "features": [';
$firstPlot = true;
if (is_array($plots))
{
	$i = 0;
    foreach($plots as $plot)
    {
        $bits = explode("\n", $plot->al_coords);

        if ( ! $firstPlot) $otpt .= ',';
        

        if ($plot->owner == 'council') 
        {
			if ($plot->lr_title == '')
			{
				$url = '/plot/' . $plot->id;
			}
			else
			{
				if ($plot->lr_subtitle == '1') $url = '/landreg/' . $plot->lr_title;
				else $url = '/landreg/' . $plot->lr_title . '/' . $plot->lr_subtitle;
			}
			//$otpt .= '{ "type": "Feature", "properties":{"plot_id":"' . $plot->id . '","lr_title":"' . $plot->lr_title . '","descr":"' . htmlspecialchars($plot->location) . '", "owner":"council"},"geometry": {"type": "Polygon", "coordinates": [[';
			$otpt .= '{ "type": "Feature", "properties":{"plot_id":"' . $plot->id . '","url":"' . $url . '","descr":"' . htmlspecialchars($plot->location) . '", "owner":"council"},"geometry": {"type": "Polygon", "coordinates": [[';
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
        elseif ($plot->owner == 'private') 
        {
        	$url = '/landreg/unknown/' . $plot->id;
			$otpt .= '{ "type": "Feature", "properties":{"plot_id":"' . $plot->id . '","url":"' . $url . '","descr":"' . htmlspecialchars($plot->location) . '", "owner":"private"},"geometry": {"type": "Point", "coordinates": ';
			//$otpt .= '{ "type": "Feature", "properties":{"plot_id":"' . $plot->id . '","lr_title":"' . $plot->lr_title . '","descr":"' . htmlspecialchars($plot->location) . '", "owner":"private"},"geometry": {"type": "Point", "coordinates": ';
			$firstBit = true;
			foreach ($bits as $bit)
			{
				if (strlen(trim($bit)) > 0)
				{
					if ($firstBit)
					{
						$otpt .= '[' . $bit . ']';
					}
					
					$firstBit = false;
				}
			}
			$otpt .= '}}';
        	$firstPlot = false;
        }
        
        

        $i++;
    }
}
$otpt .= ']}';
echo $otpt;



