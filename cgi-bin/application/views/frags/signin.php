<?php
    if ($cookiestring != '')
    {
        echo '            <p class="alert">' . $cookiestring . '</p>' . "\n";
    }
?>
    				<p>If you're already registered, sign in using the boxes below:</p>
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
                    <form action="/signin" method="post" name="sign_in">
                        <fieldset>
                            <label for="email" class="define">Email address: </label><input type="text" tabindex="1" id="email" name="email" class="textinput" />
                        </fieldset>
                        <fieldset>
                            <label for="password" class="define">Password: </label><input type="password" tabindex="2" id="password" name="password" class="textinput" />
                        </fieldset>
                        <fieldset>
                            <label class="define">&nbsp;</label>
                            Enter the jumbled letters shown here:<br />
                            <a href="#" class="js_refresh" title="change letters">
                                <img src="/captcha.png" alt="captcha" id="js_captcha" />
                            </a>
                            <a href="#" class="js_refresh">change letters</a>
                        </fieldset>
                        <fieldset>
                            <label for="captcha" class="define">Jumbled letters: </label><input type="text" tabindex="3" id="captcha" name="captcha" class="textinput inputfield uc_code" />
                        </fieldset>
                        <!--
                        <fieldset>
                            <label class="define">&nbsp;</label><input type="checkbox" name="remember" id="remember" value="Y"
<?php
#if ( ! isset($remember) || $remember == 'Y') echo ' checked="checked"';
?>
 /><label class="summat" for="remember"> Stay signed in</label>
                        </fieldset>
                        -->
                        <fieldset>
                            <label for="signin" class="define">&nbsp;</label><input type="submit" class="submit" tabindex="4" name="signinbutton" id="signin" value="sign in">
                        </fieldset>
                        <input type="hidden" name="sign_in" value="sign in" />
                        <p>&nbsp;</p>
                    </form>
