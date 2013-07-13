<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
	</div><!-- #main -->

	<div id="footer" role="contentinfo">
		<div class="addthis_toolbox addthis_default_style"> 
			<a class="addthis_button_facebook"></a> 
			<a class="addthis_button_twitter"></a> 
			<a class="addthis_button_myspace"></a> 
			<a class="addthis_button_stumbleupon"></a> 
			<a class="addthis_button_digg"></a> 
			<a class="addthis_button_google"></a> 
			<a class="addthis_button_reddit"></a> 
			<a class="addthis_button_favorites"></a> 
			<a class="addthis_button_email"></a> 
			<a class="addthis_button_print"></a> 
		</div> 
		<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4ce4fe9267294d05"></script> 
		<div id="colophon">

<?php
	/* A sidebar in the footer? Yep. You can can customize
	 * your footer with four columns of widgets.
	 */
	get_sidebar( 'footer' );
?>

			<div id="site-info">
				<a href="<?php echo WEBSITE_URL; ?>/" title="Who Owns My Neighbourhood?" rel="home">Who Owns My Neighbourhood?</a>
			</div><!-- #site-info -->
            <p class="addendum">
                This service has been brought to you by <a href="http://www.thumbprint.coop" target="_blank">Thumbprint Co-operative</a>
            </p>
            <p class="addendum">
                It has been produced as part of Kirklees Council's open data initiative in partnership with <a href="http://www.nesta.org.uk" target="_blank">NESTA</a>
            </p>

		</div><!-- #colophon -->
	</div><!-- #footer -->

</div><!-- #wrapper -->

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
