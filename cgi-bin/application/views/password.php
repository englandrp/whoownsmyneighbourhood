	<div id="main">
		<div class="wide_screen">
            <div class="left_screen">
            <h2>Forgotten your password? <span class="codicil">[Step one of two]</span></h2>
            <p>If you're already registered but you've forgotten your password, enter your email address and your new password and we'll send you an activation code by text.</p>
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
                    <label for="idemail" class="define">Email address: </label><input type="text" name="email" id="idemail" class="textinput" value="<?php echo $email; ?>" />
                </fieldset>
                <fieldset>
                    <label for="idpassword" class="define">New password: </label><input type="password" name="password" id="idpassword" class="textinput" value="<?php echo $password; ?>" />
                </fieldset>
                <fieldset>
                    <label for="idconfirmpassword" class="define">Confirm new password: </label><input type="password" name="confirmpassword" id="idpassword" class="textinput" value="<?php echo $confirmpassword; ?>" />
                </fieldset>
                <fieldset>
                    <label for="register" class="define">&nbsp;</label><input type="submit" class="submit" name="registerbutton" id="register" value="Create new password">
                </fieldset>
                <input type="hidden" name="register" value="getnewpassword" />
                <p>&nbsp;</p>
            </form>
			</div>
            <div class="right_screen">
                <p><a href="/signin">Sign in here</a></p>
            </div>
        </div>
	</div><!-- #main -->
