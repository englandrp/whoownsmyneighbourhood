<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <meta charset="UTF-8" />
    <title><?php echo ($title == '') ? '' : $title . ' | '; ?>Who Owns My Neighbourhood?</title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo WEBSITE_URL; ?>/blog/wp-content/themes/twentyten/style.css" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php echo WEBSITE_URL; ?>/x/css/whoownsmyneighbourhood.css" />
<?php
    if (is_array($js))
    {
        echo '    <script type="text/javascript" src="/x/js/jquery-1.4.4.js"></script>' . "\n";
        foreach ($js as $js_file)
        {
            echo '    <script type="text/javascript" src="' . $js_file . '"></script>' . "\n";
        }
    }
?>
    <script type="text/javascript">
        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-20731064-1']);
        _gaq.push(['_trackPageview']);
        (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
        })();
    </script>
</head>
<body class="home blog">
    <div id="wrapper" class="hfeed">
