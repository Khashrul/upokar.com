<?php
/**
 * Created by PhpStorm.
 * User: KHASHRUL
 * Date: 1/15/2018
 * Time: 10:57 AM
 */



$baseUrl = Yii::app()->getBaseUrl(true);
$redirect_url = Yii::app()->request->getParam('redirect_url');

?>



<body>

<div id="login-box">
    <div class="left">
        <h1>User Login</h1>
        <form id="user_login_form" name="user_login-form"  action="javascript:void(0)" method="post" >
            <div id="error_personal"></div>
            <input type="hidden" name="redirect_url" id="redirect_url" value="<?=$redirect_url?>">
            <input type="text" name="user_email_phone" id="user_email_phone" placeholder="E-mail / Mobile Number" />
        <input type="password" name="user_password" id="user_password" placeholder="Password" />

            <div align="left" id="signup_status_personal"></div>
        <button style="background-color: orange" class="btn primary-btn login-submit-btn" type="submit" name="signup_submit" ><i class="fa fa-sign-in"></i> Login with us</button>

        </form>
        <br>
        <a href="<?=$baseUrl?>/register">Not Any Account? Please Register</a>
    </div>

    <div class="right">
        <span class="loginwith">Login with<br />social network</span>
        <a href=""><button class="social-signin facebook">Login with facebook</button></a>

        <button class="social-signin google">Login with Google+</button>
    </div>
    <div class="or">OR</div>
</div>



</body>

<style>
    .info {margin-bottom: 10px; border: 1px solid #999; padding:6px 17px 8px; font: bold 12px verdana;-moz-box-shadow: 0 0 5px #888; -webkit-box-shadow: 0 0 5px#888;box-shadow: 0 0 5px #888;text-shadow: 2px 2px 2px #ccc;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;font-family:Verdana, Geneva, sans-serif; font-size:11px; line-height:20px;font-weight:normal;color: black;background: #BDE5F8;}


</style>

