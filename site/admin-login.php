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
        <h1>Admin Login</h1>
        <form id="admin_login_form" name="admin_login_form"  action="javascript:void(0)" method="post" >
            <div id="error_personal"></div>
            <input type="hidden" name="redirect_url" id="redirect_url" value="<?=$redirect_url?>">
            <input type="text" name="admin_email" id="admin_email" placeholder="E-mail" />
        <input type="password" name="admin_password" id="admin_password" placeholder="Password" />

            <div align="left" id="signup_status_admin"></div>
        <button style="background-color: orange" class="btn primary-btn login-submit-btn-admin" type="submit" name="admin_signup_submit" ><i class="fa fa-sign-in"></i> Login with us</button>

        </form>
        <br>

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

