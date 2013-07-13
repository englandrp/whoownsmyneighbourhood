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
                    

    if ($this->userlogin->logged_in)
    {
        echo '			<a href="/signout">Sign out</a>' . "\n";
    }
    elseif ($_COOKIE['user'] == 'registered')
    {
        echo '			Already registered? <a href="/signin" class="boss">Sign in</a> | Want to take part? <a href="/register">Sign up</a>' . "\n";
    }
    else
    {
        echo '			Already registered? <a href="/signin">Sign in</a> | Want to take part? <a href="/register" class="boss">Sign up</a>' . "\n";
    }
?>
                </div>
			</div><!-- #branding -->
			<div id="access" role="navigation">
			  	<div class="skip-link screen-reader-text"><a href="#content" title="Skip to content">Skip to content</a></div>
                <div class="menu">
                    <ul>
                        <li<?php echo ($nav == 'home') ? ' class="current_page_item"' : ''; ?>>
                            <a href="/">Home</a>
                        </li>
                        <li<?php echo ($nav == 'mypage') ? ' class="current_page_item"' : ''; ?>>
                            <a href="/mypage">My Page</a>
                        </li>
                        <li<?php echo ($nav == 'about') ? ' class="current_page_item"' : ''; ?>>
                            <a href="/about">About</a>
                        </li>
                        <li>
                            <a href="/blog/">Blog</a>
                        </li>
                        <li<?php echo ($nav == 'rss') ? ' class="current_page_item"' : ''; ?>>
                            <a href="/rss/">RSS</a>
                        </li>
                        <li<?php echo ($nav == 'contact') ? ' class="current_page_item"' : ''; ?>>
                            <a href="/contact">Contact us</a>
                        </li>
                        <li<?php echo ($nav == 'help') ? ' class="current_page_item"' : ''; ?>>
                            <a href="/help">Help</a>
                        </li>
                    </ul>
                </div>
			</div><!-- #access -->
		</div><!-- #masthead -->
	</div><!-- #header -->
