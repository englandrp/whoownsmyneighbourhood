<?php
    header("Content-type: text/xml");
    echo '<?xml version="1.0" encoding="ISO-8859-1" ?>' . "\n";
    echo '<?xml-stylesheet title="xsl_stylesheet" type="text/xsl" href="/x/xsl/rss.xsl"?>' . "\n";
    echo '<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
    echo '	<channel>' . "\n";
    echo '		<title>' . $ward[0]->w_name . ' RSS feed from Who Owns My Neighbourhood?</title>' . "\n";
    echo '		<link>http://whoownsmyneighbourhood.org.uk/</link>' . "\n";
    echo '		<description>Who Owns My Neighbourhood? RSS feed about ' . htmlspecialchars(ucfirst($ward[0]->w_name)) . '</description>' . "\n";
    echo '		<language>en-gb</language>' . "\n";
    echo '		<copyright>Copyright: whoownsmyneighbourhood.org.uk ' . date('Y', time()) . '</copyright>' . "\n";
    echo '		<atom:link href="http://whoownsmyneighbourhood.org.uk/rss/' . $ward[0]->w_code . '" rel="self" type="application/rss+xml" />' . "\n";
    if (is_array($aggregates) && count($aggregates) > 0) {
        foreach ($aggregates as $agg) {
            echo '		<item>' . "\n";
            echo '			<title>' . stripslashes(htmlspecialchars($agg->content)) . '</title>' . "\n";
            //$id = "_" . substr(preg_replace('/[^a-z0-9]/', '', strtolower(htmlspecialchars($value->comment))), 0, 8);
            echo '			<link>http://whoownsmyneighbourhood.org.uk' . $agg->plot_url . '</link>' . "\n";
            echo '			<guid>http://whoownsmyneighbourhood.org.uk' . $agg->plot_url . '</guid>' . "\n";
            echo '			<description>' . $agg->content;
			if ($agg->timestamp > 0) echo ': ' . date('jS F Y, g:i a', $agg->timestamp);
			echo '</description>' . "\n";
            echo '		</item>' . "\n";
        }
    }
    echo '	</channel>' . "\n";
    echo '</rss>' . "\n";
