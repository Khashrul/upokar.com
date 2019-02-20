<?php
/**
 * Created by PhpStorm.
 * User: KHASHRUL
 * Date: 1/15/2018
 * Time: 10:57 AM
 */?>



<body>

<div id="login-box">
    <div style="display: block;padding-top: 345px;" id="error_personal"></div>
    <div class="left">
        <h1>User Registration</h1>

        <form id="user_registration_form" name="user_registration_form-form"  action="javascript:void(0)" method="post" >
        <input type="text" name="user_name" id="user_name" placeholder="Username" />
        <input type="text" name="user_email_phone" id="user_email_phone" placeholder="E-mail / Mobile Number" />
        <input type="password" name="user_password" id="user_password" placeholder="Password" />
        <input type="password" name="user_password2" id="user_password2" placeholder="Retype password" />
            <div align="left" id="signup_status_personal"></div>

            <button style="background-color: orange" class="btn primary-btn register-submit-btn" type="submit" name="register_submit" ><i class="fa fa-sign-in"></i> Register with us</button>
        </form>

    </div>

    <div class="right">
        <span class="loginwith">Registration with<br />social network</span>
        <a href="<?php echo $fb_login_url ?>"><button class="social-signin facebook">Registration with facebook</button></a>

        <button class="social-signin google">Registration with Google+</button>
    </div>
    <div class="or">OR</div>

</div>

</body>

<style>
    .info {margin-bottom: 10px; border: 1px solid #999; padding:6px 17px 8px; font: bold 12px verdana;-moz-box-shadow: 0 0 5px #888; -webkit-box-shadow: 0 0 5px#888;box-shadow: 0 0 5px #888;text-shadow: 2px 2px 2px #ccc;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;font-family:Verdana, Geneva, sans-serif; font-size:11px; line-height:20px;font-weight:normal;color: black;background: #BDE5F8;}
</style>

