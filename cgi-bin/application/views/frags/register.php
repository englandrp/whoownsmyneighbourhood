<?php
    if ($cookiestring != '')
    {
        echo '            <p class="alert">' . $cookiestring . '</p>' . "\n";
    }
?>
            <p>New to the service? Enter your name, email address and a password below and we'll create your account. We'll then send a code to your email address so you can activate your account.</p>
            <?php echo validation_errors(); ?>
<?php
    if (is_array($sign_in_errors))
    {
        foreach($sign_in_errors as $error)
        {
            echo '                <p class="alert">' . $error . '</p>' . "\n";
        }
    }
    /*
					<fieldset>
						<label for="username" class="define">Your name: </label><input type="text" name="username" id="username" class="textinput" value="<?php echo $username; ?>" />
					</fieldset>
    */
?>
				<form method="post" action="/register" name="register">
					<fieldset>
						<label for="email" class="define">Email address: </label><input type="text" name="email" id="email" class="textinput" value="<?php echo $email; ?>" />
					</fieldset>
					<fieldset>
						<label for="password" class="define">Password: </label><input type="password" name="password" id="password" class="textinput" value="<?php echo $password; ?>" />
					</fieldset>
					<fieldset>
						<label for="confirmpassword" class="define">Confirm password: </label><input type="password" name="confirmpassword" id="confirmpassword" class="textinput" value="<?php echo $confirmpassword; ?>" />
					</fieldset>
                    <!--
			        <fieldset>
				        <label class="define">&nbsp;</label><input type="checkbox" name="confirm" id="y_confirm" value="Y" 
<?php
    #if ($confirm == 'Y') echo 'checked="checked" ';    
?>/><label for="y_confirm"> I agree to terms &amp; conditions</label>
			        </fieldset>

					<fieldset>
						<label class="define">&nbsp;</label><input type="checkbox" name="remember" id="remember" value="Y"
<?php
#if ( ! isset($remember) || $remember == 'Y') echo ' checked="checked"';
?>
 /><label for="remember"> Stay signed in</label>
					</fieldset>
                    -->
                    <fieldset>
						<label for="register" class="define">&nbsp;</label><input type="submit" class="submit" name="register" id="register" value="continue">
					</fieldset>
                </form>
                <p>&nbsp;</p>
