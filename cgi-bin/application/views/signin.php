<script type="text/javascript">
	$(document).ready(function() {
        initSignin();
    });
</script>
	<div id="main">
		<div class="wide_screen">
            <div class="left_screen">
                    <h2>Sign in</h2>
<?php
    $this->load->view('frags/signin');
?>
            </div>
            <div class="right_screen">
                <p><a href="/password">Forgotten your password?</a></p>
                <p>New to our website? <a href="/register">Sign up here</a></p>
            </div>
        </div>
	</div><!-- #main -->
