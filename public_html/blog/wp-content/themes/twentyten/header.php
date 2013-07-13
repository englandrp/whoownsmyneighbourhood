<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	#$site_description = get_bloginfo( 'description', 'display' );
	#if ( $site_description && ( is_home() || is_front_page() ) )
		#echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
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

<body <?php body_class(); ?>>
<div id="wrapper" class="hfeed">
	<div id="header">
		<div id="masthead">
			<div id="branding" role="banner">
				<h1 id="site-title">
					<span>
						<a href="/" title="Who Owns My Neighbourhood?" rel="home">Who Owns My Neighbourhood?</a>
					</span>
				</h1>
				<div id="site-description">
                <?php
                if (isset($_COOKIE['PHPSESSID'])) echo '			<a href="/signout">Sign out</a>' . "\n";
                elseif ($_COOKIE['user'] == 'registered') echo '			Already registered? <a href="/signin" class="boss">Sign in</a> | Want to take part? <a href="/register">Sign up</a>' . "\n";
                else echo '			Already registered? <a href="/signin">Sign in</a> | Want to take part? <a href="/register" class="boss">Sign up</a>' . "\n";
                ?>
                </div>
			</div><!-- #branding -->

			<div id="access" role="navigation">
			  	<div class="skip-link screen-reader-text"><a href="#content" title="Skip to content">Skip to content</a></div>
                <div class="menu">
                    <ul>
                        <li>
                            <a href="/">Home</a>
                        </li>
                        <li>
                            <a href="/mypage">My Page</a>
                        </li>
                        <li>
                            <a href="/about">About</a>
                        </li>
                        <li class="current_page_item">
                            <a href="/blog/">Blog</a>
                        </li>
                        <li>
                            <a href="/contact">Contact us</a>
                        </li>
                        <li>
                            <a href="/help">Help</a>
                        </li>
                    </ul>
                </div>
			</div><!-- #access -->
		</div><!-- #masthead -->
	</div><!-- #header -->

	<div id="main">
