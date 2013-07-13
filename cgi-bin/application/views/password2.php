	<div id="main">
		<div class="wide_screen">
            <div class="left_screen">
				<h2>Forgotten your password? <span class="codicil">[Step two of two]</span></h2>
				<p>We have sent a 6 letter code to your email address. Enter it below to activate your account.</p>
                <?php echo validation_errors(); ?>
<?php
    if (is_array($sign_in_errors))
    {
        foreach($sign_in_errors as $error)
        {
            echo '                <p class="alert">' . $error . '</p>' . "\n";
        }
    }
?>
				<form method="post" action="/password" name="newpasswordform">
					<fieldset>
						<label for="idcheckcode" class="define">Code: </label><input type="text" name="checkcode" id="idcheckcode" class="textinput uc_code" />
					</fieldset>
					<fieldset>
						<label for="register" class="define">&nbsp;</label>
						<input type="submit" class="submit" name="registerbutton" value="Activate account">
					</fieldset>
					<input type="hidden" name="register" value="confirm" />
					<input type="hidden" name="email" value="<?php echo $email; ?>" />
				</form>
                <p>&nbsp;</p>
			</div>
            <div class="right_screen">
                <p>&nbsp;</p>
            </div>
        </div>
	</div><!-- #main -->
