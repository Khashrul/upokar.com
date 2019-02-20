<?php
require_once dirname(__FILE__) . "/../extensions/Facebook/autoload.php";
require_once dirname(__FILE__) . '/../extensions/Gmail/Google_Client.php';
require_once dirname(__FILE__) . '/../extensions/Gmail/contrib/Google_Oauth2Service.php';
class SiteController extends Controller
{
	public $layout='frontend';

	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
	    if($error=Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm();
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{

			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login()){

				if(Yii::app()->user->returnUrl != '/'){
					$this->redirect(Yii::app()->request->baseUrl.'/user/admin');
				}else{
					$this->redirect(Yii::app()->request->baseUrl.'/user/admin');
				}
			}
		}


		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */

	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}

	/** This Action render Index Page */

	public function actionIndex(){

		$all_services = Generic::getAllServices();
		$all_products_for_homepage = Generic::getAllProductsForHomePage();


		$home_category_products_home_page = Generic::getCategoryProductsUsingCategorySlug('home-and-office-appliance');
		$security_category_products_home_page = Generic::getCategoryProductsUsingCategorySlug('security-surveillance');
		$digital_security_category_products_home_page = Generic::getCategoryProductsUsingCategorySlug('digital-security-surveillance');

		$this->render('index',array(
			'all_services'=>$all_services,
			'all_products_for_homepage'=>$all_products_for_homepage,
			'home_category_products_home_page'=>$home_category_products_home_page,
			'security_category_products_home_page'=>$security_category_products_home_page,
			'digital_security_category_products_home_page'=>$digital_security_category_products_home_page,
		) );


	}


	public function actionPlaceLocation(){

		$response = array();
		$selected_location = trim(yii::app()->request->getParam('selected_location'));

		if($selected_location){

				$response['location'] = '<a href="#"><span class="fa fa-location-arrow"></span> '.$selected_location.' </a>';
			    $current_time = new \DateTime();
			    $ip = Generic::getUserIP();

			    Generic::writeCookie('location_name',$selected_location);
			    //Yii::app()->session['user_selected_location'] = $current_time->getTimestamp().$ip.':'.$selected_location;
			}

			echo json_encode($response);
		}

	public function actionRegister(){
		Yii::app()->session->open();
		/* ---------- Facebook api ----------------- */
		$fb = new Facebook\Facebook([
			'app_id' => '1785207718439980',
			'app_secret' => 'a248761a58ae7883b182c69d313059d2',
			'default_graph_version' => 'v2.8',
		]);

		$callback_url = Yii::app()->getBaseUrl(true).'/site/FBCallBack';
		//$callback_url = 'local.upokar.com';
		$helper = $fb->getRedirectLoginHelper();

		$permissions = ['email'];
		$fb_login_url = $helper->getLoginUrl($callback_url, $permissions);

       // Generic::_setTrace($fb_login_url);
		$this->render('register',array(
			'fb_login_url' => $fb_login_url,

		));
	}

	public function actionSiteUserRegister(){


		$user_name = Yii::app()->request->getParam('user_name','');
		$user_email = Yii::app()->request->getParam('user_email','');
		$user_password = Yii::app()->request->getParam('user_password');
		$user_mobile_number = Yii::app()->request->getParam('user_mobile_number','');
		$sql = "";
		$message_data = "";
		$message = "";
		if($user_email){
			$message_data = $user_email;
			$message = "Email Address";
			$sql="select * from user_register where user_email='$user_email'";
		}elseif($user_mobile_number){
			$message = "Phone Number";
			$message_data = $user_mobile_number;
			$sql="select * from user_register where user_mobile_number='$user_mobile_number'";
		}

		$response = array();
		$result=Yii::app()->db->createCommand($sql)->queryRow();

		if(empty($result)){
		$creation_date = new \DateTime();
		$ip = Generic::getUserIP();
		$model = new UserRegister();
		$model->user_name = $user_name;
		$model->user_email = $user_email;
		$model->user_password = md5($user_password);;
		$model->user_mobile_number = $user_mobile_number;
		$model->create_date = $creation_date->format('Y-m-d H:i:s');
		if($model->save()) {
            $user_id = $model->id;
			$model->user_token = $creation_date->getTimestamp().$ip.':'.$user_id;
			$model->update();
			$response['status'] = 'success';
			$response['user_login_token'] = $user_id;
			Yii::app()->session['user_login_token'] = $user_id;


		}
		else{
			$response['status'] = 'false';
			$response['message'] = 'Unable To Store Data.Please Try again Later';
		}}

		else{
			$response['status'] = 'duplicate'; // could not register
			$response['message'] = '<span class="alert-danger"> An User Already Registered With <span class="alert-error">'.$message_data.'<span>. Try with another One.</span>';
			$response['button_text'] = 'Registration';
		}

      echo json_encode($response);

	}

	public function actionFBCallBack(){

		Yii::app()->session->open();

		$fb = new Facebook\Facebook([
			'app_id' => '1785207718439980',
			'app_secret' => 'a248761a58ae7883b182c69d313059d2',
			'default_graph_version' => 'v2.8',
		]);

		$helper = $fb->getRedirectLoginHelper();

		try {
			$accessToken = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		if (! isset($accessToken)) {
			if ($helper->getError()) {
				header('HTTP/1.0 401 Unauthorized');
				echo "Error: " . $helper->getError() . "\n";
				echo "Error Code: " . $helper->getErrorCode() . "\n";
				echo "Error Reason: " . $helper->getErrorReason() . "\n";
				echo "Error Description: " . $helper->getErrorDescription() . "\n";
			} else {
				header('HTTP/1.0 400 Bad Request');
				echo 'Bad request';
			}
			exit;
		}

		$oAuth2Client = $fb->getOAuth2Client();

		$tokenMetadata = $oAuth2Client->debugToken($accessToken);

		$tokenMetadata->validateAppId('1785207718439980');
		$tokenMetadata->validateExpiration();

		if (!$accessToken->isLongLived()) {
			// Exchanges a short-lived access token for a long-lived one
			try {
				$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
			} catch (Facebook\Exceptions\FacebookSDKException $e) {
				echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
				exit;
			}
		}

		$_SESSION['fb_access_token'] = (string) $accessToken;
		$fb->setDefaultAccessToken($_SESSION['fb_access_token']);
		$response = $fb->get('/me?locale=en_US&fields=name,email');

		$userNode = $response->getGraphUser();
		if($userNode->getField('email') == '') {
			$email = 'anonym@email.com';
		} else {
			$email = $userNode->getField('email');
		}
		if($userNode->getField('name') == '') {
			$user_name = 'anonymous user';
		} else {
			$user_name = $userNode->getField('name');
		}

		$oauth_id = $userNode->getField('id');

		$base_url = Yii::app()->getBaseUrl(true);
		$criteria = new CDbCriteria();
		$criteria->condition = 'oauth_token = :oauth_token';
		$criteria->params = array(':oauth_token' => $oauth_id);
		$registered_user = UserRegister::model()->find($criteria);
		if(!$registered_user){

			$user_ip = SiteConfig::GetUserIP();
			//$user_ip = "180.234.143.72";
			$geoinfo = Generic::getGeoInfo($user_ip);
			$city = '';
			$division = '';
			if($geoinfo['geoplugin_status'] == '200') {
				$city = $geoinfo['geoplugin_city'];
				$division = $geoinfo['geoplugin_region'];
			}


			$register = new UserRegister();

			$register->user_name = $user_name;
			$register->user_email = $email;
			$register->user_password = base64_encode('1234');

			$register->user_mobile_number = '1111';
			$register->user_address = 'sdfs';
			$register->create_date=date('y-m-d');
			$register->oauth_token = $oauth_id;
			$response = array();
			$creation_date = new \DateTime();
			if($register->save()){
				$user_id = $register->id;
				$register->user_token = $creation_date->getTimestamp().$user_ip.':'.$user_id;
				$register->update();
				$response['status'] = 'success';
			}

		} else {
			Yii::app()->session['user_token'] = $registered_user->user_token;
			$this->redirect($base_url.'/userProfile');
		}

	}

	public function actionUserLogin(){

		$this->render('user-login' );
	}

	public function actionadminLogin(){


		session_start();
		if(empty($_SESSION['admin_login_token'])){
			$baseUrl = Yii::app()->getBaseUrl(true);
			//Generic::_setTrace($baseUrl);
			$return_url =$baseUrl.'/shopping-cart' ;
			//Yii::app()->user->setFlash('success-container','Please complete your profile first i.e email, date of birth, address etc.');
			//return $this->redirect(Yii::app()->createUrl('site-admin'));
			$this->render('admin-login');
		}
		else{
			return $this->redirect(Yii::app()->createUrl('result'));
			//$this->render('result');
		}





	}


	public function actionUserLoginFormSubmit(){
		$response = array();
		$user_email = trim(yii::app()->request->getParam('user_email'));
		$user_mobile_number = Yii::app()->request->getParam('user_mobile_number');
		$user_password = Yii::app()->request->getParam('user_password');
		$final_password = md5($user_password);


		if($user_email && $user_password ) {
			$sql = "SELECT * FROM user_register WHERE user_email LIKE '$user_email' AND user_password LIKE '$final_password' ";
			$result = Yii::app()->db->createCommand($sql)->queryRow();
		}
		else{
			$sql = "SELECT * FROM user_register WHERE user_mobile_number LIKE '$user_mobile_number' AND user_password LIKE '$final_password' ";
			$result = Yii::app()->db->createCommand($sql)->queryRow();

			}
			if($result['user_password']== $final_password){
				$current_time = new \DateTime();
				$ip = Generic::getUserIP();
				$response['status'] = 'success';
				$response['user_login_token'] = $result['id'];
				Yii::app()->session['user_login_token'] = $result['id'];



			} else {
				$response['status'] = 'Wrong Credentials...';

			}
			echo json_encode($response);

	}

	public static function actionLoadLocation(){
		$response = array();
		$selected_division= Yii::app()->request->getParam('selected_division');
		$all_value_of_selected_location = Generic::getAllLocationsForSelectedDivision($selected_division);
		$data_html = '<label for="sel1">Expert Location:</label>';
		$data_html .= '<select id="expert_location" name="ExpertDetails[expert_location]" class="form-control">';


		foreach($all_value_of_selected_location as $single_value){
			foreach($single_value as $key => $value){

				$data_html .= '<option  value="'.$key.'">'.$value.'</option>';
				$response['data'] = $key;
			}}
		$data_html .= '</select>';
		$response['html'] = $data_html;
		echo json_encode($response);

	}
	public  function actionServices($slug){
		$service_id =  Generic::getAllServiceIdUsingServiceSlug($slug);
		$sub_service_details = Generic::getSubServiceDetails($service_id);
		$sub_service_name = Generic::getSubServiceName($slug);


		$this->render('service',array(
			'sub_service_details' => $sub_service_details,
			'sub_service_name' => $sub_service_name,
			'service_slug' => $slug,

		));

	}

	public  function actionSubServices($sub_slug){

		$sub_service_details = Generic::getSubServiceDetailsUsingSlug($sub_slug);
		$sub_service_name = $sub_service_details[0]['service_name'];
		$parent_service_id = $sub_service_details[0]['parent_service_id'];
		$sub_service_id = $sub_service_details[0]['id'];
		$tag_details = Generic::getTagDetailsUsingID($parent_service_id,$sub_service_id);
		$expert_details = Generic::getExpertDetailsUsingID($parent_service_id);
		//Generic::_setTrace($sub_service_name);

		$ratings_and_reviews = Generic::getRatingsAndReviews($sub_service_name);
		//Generic::_setTrace($ratings_and_reviews);


		$this->render('service-single',array(
			'sub_service_details' => $sub_service_details,
			'sub_service_name' => $sub_service_name,
			'tag_details' => $tag_details,
			'expert_details' => $expert_details,
			'ratings_and_reviews' => $ratings_and_reviews
		));

	}


	public static function actionLoadSubService(){
		$response = array();
		$parent_service_id= Yii::app()->request->getParam('parent_service_id');
		$all_value_of_selected_parent_service_id = Generic::getAllSubServiceForSelectedParentService($parent_service_id);
		$data_html = '<label for="sel1">Select Sub Service:</label>';
		$data_html .= '<select id="sub_service_id" name="TagDetails[sub_service_id]" class="form-control">';


		foreach($all_value_of_selected_parent_service_id as $single_value){
			foreach($single_value as $key => $value){

				$data_html .= '<option  value="'.$key.'">'.$value.'</option>';
				$response['data'] = $key;
			}}
		$data_html .= '</select>';
		$response['html'] = $data_html;
		echo json_encode($response);

	}


	public static function actionChangePrice(){
		$response = array();
		$tag_price= Yii::app()->request->getParam('tag_price');
		if($tag_price){

			$tag_price = '<span style="color: black">Service Charge Tk '.$tag_price.'</span>';

		}
		$response['html'] = $tag_price;
		echo json_encode($response);

	}

	public static function actionCartProcessForService(){
		$db_username        = 'root'; //MySql database username
		$db_password        = ''; //MySql dataabse password
		$db_name            = 'upokar'; //MySql database name
		$db_host            = 'localhost'; //MySql hostname or IP

		$currency			= '&#8377; '; //currency symbol
		$shipping_cost		= 1.50; //shipping cost
		$taxes				= array( //List your Taxes percent here.
			'VAT' => 12,
			'Service Tax' => 5,
			'Other Tax' => 10
		);
		$baseUrl = Yii::app()->request->baseUrl;
		$mysqli_conn = new mysqli($db_host, $db_username, $db_password,$db_name); //connect to MySql
		if ($mysqli_conn->connect_error) {//Output any connection error
			die('Error : ('. $mysqli_conn->connect_errno .') '. $mysqli_conn->connect_error);
		}

		   session_start(); //start session
             ############# add products to session #########################
		  if(isset($_POST["tag_id"]))
		  {
			$new_product =array();
           //Generic::_setTrace($_POST);
			foreach($_POST as $key => $value){
				$new_product[$key] = filter_var($value, FILTER_SANITIZE_STRING); //create a new product array
			}

			//we need to get product name and price from database.
			//$statement = $mysqli_conn->prepare("SELECT sub_service.service_name, tag_details.tag_name,tag_details.tag_price FROM sub_service,tag_details WHERE sub_service.id =? OR tag_details.id =? LIMIT 1");
			$statement = $mysqli_conn->prepare("SELECT  id,tag_name,tag_price FROM tag_details WHERE id =? LIMIT 1");
           //$SQL = "SELECT sub_service.service_name, tag_details.tag_name,tag_details.tag_price FROM sub_service,tag_details WHERE sub_service.id =? OR tag_details.id =?";
			$statement->bind_param('s', $new_product['tag_id']);
			$statement->execute();
			$statement->bind_result($tag_id,$tag_name,$tag_price);
			while($statement->fetch()){
				$new_product["service_name"] = $_POST["sub_service_name"]; //fetch product name from database
				$new_product["tag_name"] = $tag_name; //fetch product name from database
				$new_product["tag_price"] = $tag_price; //fetch product name from database
				$new_product["tag_id"] = $tag_id; //fetch product name from database
				$new_product["quantity"] = $_POST["quantity"]; //fetch product name from database
				$new_product["service_image_url"] = $_POST["service_image_url"]; //fetch product name from database
				$new_product["expert_name"] = $_POST["expert_name"]; //fetch product name from database

				$new_product["service_date"] = $_POST["service_date"]; //fetch product name from database
				$new_product["time_range"] = $_POST["time_range"]; //fetch product name from database

				if(isset($_SESSION["product"])){  //if session var already exist
					if(isset($_SESSION["product"][$new_product['tag_id']])) //check item exist in products array
					{
						unset($_SESSION["product"][$new_product['tag_id']]); //unset old item
					}
				}


				$_SESSION["product"][$new_product['tag_id']] = $new_product;	//update products with new item array
			}


			$total_items = count($_SESSION["product"]); //count total items
			die(json_encode(array('items'=>$total_items))); //output json

		}

        ################## list products in cart ###################
		if(isset($_POST["load_cart"]) && $_POST["load_cart"]==1)
		{
            //Generic::_setTrace($_SESSION);
			if(isset($_SESSION["product"]) && count($_SESSION["product"])>0){ //if we have session variable
				$cart_box = '<ul class="cart-products-loaded">';
				$total = 0;
				// Generic::_setTrace($_SESSION);
				foreach($_SESSION["product"] as $product){ //loop though items and prepare html content

					//set variables to use them in HTML content below
					$service_name = $product["service_name"];
					$tag_name = $product["tag_name"];
					$tag_price = $product["tag_price"];
					$product_qty = $product["quantity"];;
					$currency = "BDT ";
					$product_code = $product["tag_id"];;

					$cart_box .=  "<li> $service_name (Qty : $product_qty | $tag_name) &mdash; $currency ".sprintf("%01.2f", ($tag_price * $product_qty)). " <a href=\"#\" class=\"remove-item\" data-code=\"$product_code\">&times;</a></li>";
					$subtotal = ($tag_price * $product_qty);
					$total = ($total + $subtotal);
				}
				$cart_box .= "</ul>";
				$cart_box .= '<div class="cart-products-total">Total :  '.$currency . sprintf("%01.2f", $total).' <u> <br>
		   <a href="'.$baseUrl.'/shopping-cart" title="Review Cart and Check-Out">Check-out</a>
	    </u>
     </div>';
				die($cart_box); //exit and output content
			}else{
				die("Your Cart is empty"); //we have empty cart
			}
		}
      ################# remove item from shopping cart ################
		if(isset($_GET["remove_code"]) && isset($_SESSION["product"]))
		{
			$product_code   = filter_var($_GET["remove_code"], FILTER_SANITIZE_STRING); //get the product code to remove

			if(isset($_SESSION["product"][$product_code]))
			{
				unset($_SESSION["product"][$product_code]);
			}

			$total_items = count($_SESSION["product"]);
			die(json_encode(array('items'=>$total_items)));
		}

	}

	public static function actionLoadSubServiceForExpert(){
		$response = array();
		$parent_service_id= Yii::app()->request->getParam('parent_service_id');
		$all_value_of_selected_parent_service_id = Generic::getAllSubServiceForSelectedParentService($parent_service_id);
		$data_html = '<label for="sel1">Select Sub Service:</label>';
		$data_html .= '<select id="sub_service_id" name="ExpertDetails[sub_service_id]" class="form-control">';


		foreach($all_value_of_selected_parent_service_id as $single_value){
			foreach($single_value as $key => $value){

				$data_html .= '<option  value="'.$key.'">'.$value.'</option>';
				$response['data'] = $key;
			}}
		$data_html .= '</select>';
		$response['html'] = $data_html;
		echo json_encode($response);

	}
	public  function actionShoppingCart(){
		 session_start();
		 if(empty($_SESSION['user_login_token'])){
			$baseUrl = Yii::app()->getBaseUrl(true);
			//Generic::_setTrace($baseUrl);
			$return_url =$baseUrl.'/shopping-cart' ;
			//Yii::app()->user->setFlash('success-container','Please complete your profile first i.e email, date of birth, address etc.');
			return $this->redirect(Yii::app()->createUrl('login?redirect_url='.$return_url));
		}
		else{
			$this->render('shopping-cart',array());
		}

   }

	public  function actionShoppingCartProducts(){
		session_start();
		if(empty($_SESSION['user_login_token'])){
			$baseUrl = Yii::app()->getBaseUrl(true);
			//Generic::_setTrace($baseUrl);
			$return_url =$baseUrl.'/shopping-cart-products' ;
			//Yii::app()->user->setFlash('success-container','Please complete your profile first i.e email, date of birth, address etc.');
			return $this->redirect(Yii::app()->createUrl('login?redirect_url='.$return_url));
		}
		else{
			$this->render('shopping-cart-products',array());
		}

	}



	public  function actionCheckout(){
		session_start();
		$session_for_login = Yii::app()->session['user_login_token'];
		$profile_data = Generic::getProfileData($session_for_login);
		$this->render('checkout',array(
			'profile_data'=>$profile_data
		));

	}

	public  function actionCheckoutProducts(){
		session_start();
		$session_for_login = Yii::app()->session['user_login_token'];
		$order_total  = Yii::app()->request->getParam('order_total');
		$cart_total  = Yii::app()->request->getParam('cart_total');
		$discount  = Yii::app()->request->getParam('discount');
		$shipping_handling  = Yii::app()->request->getParam('shipping_handling');
		$discounted_amount  = Yii::app()->request->getParam('discounted_amount');

		//Generic::_setTrace($order_total);
		$profile_data = Generic::getProfileData($session_for_login);
		$this->render('products-checkout',array(
			'profile_data'=>$profile_data,
			'order_total'=>$order_total,
			'cart_total'=>$cart_total,
			'discount'=>$discount,
			'shipping_handling'=>$shipping_handling,
			'discounted_amount'=>$discounted_amount
		));

	}


	public function actionInsertServiceTransactionsData(){


		$service_details = Yii::app()->request->getParam('service_details');
		$number_of_services = count($service_details);

		if($number_of_services > 0){

			$response = array();
			$invoice_id = rand(265489,6);

			foreach( $service_details as $individual_service ){


				$parent_service_id = $individual_service['service_id'];
				$service_name = $individual_service['sub_service_name'];
				$tag_name = $individual_service['tag_name'];
				$service_image_url = $individual_service['service_image_url'];
				$expert_name = $individual_service['expert_name'];
				$service_amount = $individual_service['service_price'];
				$payment_system= Yii::app()->request->getParam('payment_system');
				$product_qty = $individual_service['quantity'];
				$service_amount = $service_amount. '|' .$product_qty;
				$service_taker_id= Yii::app()->request->getParam('service_taker_id');
				$creation_date = new \DateTime('now', new DateTimezone('Asia/Dhaka'));

				$service_date = $individual_service['service_date'];
				$time_range = $individual_service['time_range'];


				$user_ip = Generic::getUserIP();

				$location_name_in_cookie = Yii::app()->request->cookies['location_name'];




				$total = 0;
				$subtotal = ($service_amount * $product_qty);
				$total = ($total + $subtotal);

				$insertService = new ServiceTransactionDetails();
				$insertService -> parent_service_id = $parent_service_id;
				$insertService -> service_name = $service_name;
				$insertService -> tag_name = $tag_name;

				$insertService -> service_date = $service_date;
				$insertService -> time_range = $time_range;

				$insertService -> service_image_url = $service_image_url;
				$insertService -> expert_name = $expert_name;
				$insertService -> service_taker_id = $service_taker_id;
				$insertService -> service_amount = $service_amount;
				$insertService -> total_amount = $total;
				$insertService -> payment_system = $payment_system;
				$insertService -> customer_review = 5;
				$insertService -> service_status = "Pending";
				$insertService -> service_create_date = $creation_date->format('Y-m-d H:i:s');

				$insertService -> browser_ip_address = $user_ip;
				$insertService -> service_location = $location_name_in_cookie;



				if($insertService -> save()){


					$phone =  Yii::app()->request->getParam('phone');
					$address =  Yii::app()->request->getParam('address');
					$town_city =  Yii::app()->request->getParam('town_city');
					$email =  Yii::app()->request->getParam('email');

					//$invoice_id = $insertService->id;
					$insertService -> invoice_id = $invoice_id;
					$insertService -> update();

					$session_for_login = Yii::app()->session['user_login_token'];
					$profile_data = Generic::getProfileData($session_for_login);

					$criteria = new CDbCriteria();
					$criteria->condition = 'id = :id';
					$criteria->params = array(':id' => $profile_data['id']);
					$user_details = UserRegister::model()->find($criteria);

					$user_details->user_email = $email;
					$user_details->user_mobile_number = $phone;
					$user_details->user_address = $address;
					$user_details->user_city = $town_city;
					$user_details->update();
					unset($_SESSION['product']);
					$response['status'] = "Success";
				}

				else {

					$response['status'] = 'Wrong';

				}

			}

		}

		echo json_encode($response);

	}


	public function actionInsertShopTransactionsData(){


		$product_details = Yii::app()->request->getParam('product_details');
		$number_of_products = count($product_details);
		$response = array();
		if($number_of_products > 0){
			$invoice_id = rand(265489,6);

			foreach( $product_details as $individual_products ){

			$product_name = $individual_products['product_name'];
	        $product_image_url = $individual_products['product_image'];
	        $product_price = $individual_products['product_price'];
	        $products_id = $individual_products['products_id'];
			$payment_system= Yii::app()->request->getParam('payment_system');
	        $product_qty = $individual_products['quantity'];
			$service_taker_id= Yii::app()->request->getParam('product_buyer_id');
			$creation_date = new \DateTime('now', new DateTimezone('Asia/Dhaka'));

			$cart_total= Yii::app()->request->getParam('cart_total');
			$order_total= Yii::app()->request->getParam('order_total');
			$discounted_amount= Yii::app()->request->getParam('discounted_amount');


			$user_ip = Generic::getUserIP();

			$location_name_in_cookie = Yii::app()->request->cookies['location_name'];
			$insertProduct = new ShopTransactionDetails();

				$insertProduct -> products_id = $products_id;
				$insertProduct -> invoice_id = $invoice_id;
				$insertProduct -> product_buyer_id = $service_taker_id;
				$insertProduct -> product_name = $product_name;
				$insertProduct -> product_price = $product_price;
				$insertProduct -> product_quantity = $product_qty;
				$insertProduct -> product_image_url = $product_image_url;
				$insertProduct -> total_amount = $cart_total;
				$insertProduct -> discounted_amount = $discounted_amount;
				$insertProduct -> final_amount = $order_total;
				$insertProduct -> payment_system = $payment_system;
				$insertProduct -> customer_review = 5;
				$insertProduct -> status = "Pending";
				$insertProduct -> transaction_date = $creation_date->format('Y-m-d H:i:s');
				$insertProduct -> location = $location_name_in_cookie;
				$insertProduct -> browser_ip_address = $user_ip;
                //Generic::_setTrace($insertProduct);



				if($insertProduct -> save()){

					$phone =  Yii::app()->request->getParam('phone');
					$address =  Yii::app()->request->getParam('address');
					$town_city =  Yii::app()->request->getParam('town_city');
					$email =  Yii::app()->request->getParam('email');

					$session_for_login = Yii::app()->session['user_login_token'];
					$profile_data = Generic::getProfileData($session_for_login);
					$criteria = new CDbCriteria();
					$criteria->condition = 'id = :id';
					$criteria->params = array(':id' => $profile_data['id']);
					$user_details = UserRegister::model()->find($criteria);

					$user_details->user_email = $email;
					$user_details->user_mobile_number = $phone;
					$user_details->user_address = $address;
					$user_details->user_city = $town_city;
					$user_details->update();
					unset($_SESSION['shop_product']);
					$response['status'] = "Success";
				}

				else {

					$response['status'] = 'Wrong';

				}

			}

		}

		echo json_encode($response);

	}



	/*
 * upload image to amazon s3
 */
	public static function actionAjaximageupload(){
		//$water_mark = Yii::$app->request->post('watermark',1);
		/*$uploader = new Uploader();
        Generic::_setTrace($_FILES);
        $data = $uploader->upload($_FILES['files'], array(
            'limit' => 10, //Maximum Limit of files. {null, Number}
            'watermark' => $water_mark,
            'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
            'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
            'required' => false, //Minimum one file is required for upload {Boolean}
            'uploadDir' => 'uploads/', //Upload directory {String}
            'title' => array('{{timestamp}}'), //New file name {null, String, Array} *please read documentation in README.md
            'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
            'replace' => false, //Replace the file if it already exists  {Boolean}
            'perms' => null, //Uploaded file permisions {null, Number}
            'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
            'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
            'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
            'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
            'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
            'onRemove' => null //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
        ));*/

		$target_dir = "uploads/";
		$file_name = (time() + 1).'.jpg';
		$target_file = $target_dir . $file_name;
       // Generic::_setTrace($target_file);
		if (move_uploaded_file($_FILES["files"]["tmp_name"][0], $target_file)) {
			die(json_encode($target_file));
		}else{
			echo json_encode($target_file);
		}

		/*if($data['isComplete']){
            $files = $data['data'];
            echo json_encode($files['metas'][0]['name']);
        }

        if($data['hasErrors']){
            $errors = $data['errors'];
            echo json_encode($errors);
        }*/
		exit;
	}

	/**
	 * Delete Image From Amazon s3
	 */
	public static function actionDeleteimagefroms3(){
		/*  if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAIRWFUJGOJ46XGJYA');
          if (!defined('awsSecretKey')) define('awsSecretKey', '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');
          $s3 = new S3(awsAccessKey, awsSecretKey);
          if(isset($_POST['file'])){
              $bucket = "lanoyo-property";
              $s3->deleteObject($bucket,$_POST['file']);
          }*/

		if (array_key_exists('file', $_POST)) {
			$filename = $_POST['file'];
			if (file_exists($filename)) {
				unlink($filename);
				echo 'File '.$filename.' has been deleted';
			} else {
				echo 'Could not delete '.$filename.', file does not exist';
			}
		}



	}


	/**
	 * Get Product Listing Page According to Products Sub Category
	 */

	public  function actionProducts($sub_category_slug){
   //Generic::_setTrace($sub_category_slug);
		$all_category_products = Generic::getCategoryProductsUsingCategorySlug($sub_category_slug);
       //Generic::_setTrace($all_category_products);
		$this->render('shop',array(

			'all_category_products'=>$all_category_products

		));

	}

	/**
	 * Get Product Listing Page According to Products Category
	 */

	public function actionProductsDetails($products_id){

		$product_id= Yii::app()->request->getParam('products_id');

		$individual_products = Generic::getIndividualProductsUSingProductsId(base64_decode($product_id));


		$data = Generic::getProductDetailsFromProductTable(base64_decode($product_id));
          //Generic::_setTrace($data);
		$related_products = Generic::getRelatedProducts(base64_decode($product_id),$data['products_category_slug']);

		$ratings_and_reviews = Generic::getRatingsAndReviewsUsingProductsId(base64_decode($product_id));

		//Generic::_setTrace($ratings_and_reviews);


		$this->render('shop-single',array(

			'individual_products'=>$individual_products,
			'related_products'=>$related_products,
			'ratings_and_reviews'=>$ratings_and_reviews

		));

	}



	public static function actionCartProcessForProducts(){
		$db_username        = 'root'; //MySql database username
		$db_password        = ''; //MySql dataabse password
		$db_name            = 'upokar'; //MySql database name
		$db_host            = 'localhost'; //MySql hostname or IP

		$currency			= '&#8377; '; //currency symbol
		$shipping_cost		= 1.50; //shipping cost
		$taxes				= array( //List your Taxes percent here.
			'VAT' => 12,
			'Service Tax' => 5,
			'Other Tax' => 10
		);
		$baseUrl = Yii::app()->request->baseUrl;
		$mysqli_conn = new mysqli($db_host, $db_username, $db_password,$db_name); //connect to MySql
		if ($mysqli_conn->connect_error) {//Output any connection error
			die('Error : ('. $mysqli_conn->connect_errno .') '. $mysqli_conn->connect_error);
		}

		session_start(); //start session
		############# add products to session #########################
		if(isset($_POST["products_id"]))
		{
			$product =array();
			//Generic::_setTrace($_POST);
			foreach($_POST as $key => $value){
				$product[$key] = filter_var($value, FILTER_SANITIZE_STRING); //create a new product array
			}

			//we need to get product name and price from database.
			//$statement = $mysqli_conn->prepare("SELECT sub_service.service_name, tag_details.tag_name,tag_details.tag_price FROM sub_service,tag_details WHERE sub_service.id =? OR tag_details.id =? LIMIT 1");
			$statement = $mysqli_conn->prepare("SELECT  product_name,product_price,products_category,product_image,product_code FROM products WHERE id =? LIMIT 1");
			//$SQL = "SELECT sub_service.service_name, tag_details.tag_name,tag_details.tag_price FROM sub_service,tag_details WHERE sub_service.id =? OR tag_details.id =?";
			$statement->bind_param('s', $product['products_id']);
			$statement->execute();
			$statement->bind_result($product_name,$product_price,$products_category,$product_image,$product_code);
			while($statement->fetch()){
				$product["product_name"] = $product_name; //fetch product name from database
				$product["product_price"] = $product_price; //fetch product name from database
				$product["products_category"] = $products_category; //fetch product name from database
				$product["product_image"] = $product_image; //fetch product name from database
				$product["product_code"] = $product_code; //fetch product name from database
				$product["quantity"] = $_POST["quantity"]; //fetch product name from database
				$product["id"] = $_POST["products_id"]; //fetch product name from database

				//fetch product price from database
				//fetch product price from database

				if(isset($_SESSION["shop_product"])){  //if session var already exist
					if(isset($_SESSION["shop_product"][$product['products_id']])) //check item exist in products array
					{
						unset($_SESSION["shop_product"][$product['products_id']]); //unset old item
					}
				}

                //Generic::_setTrace($new_product);

				$_SESSION["shop_product"][$product['products_id']] = $product;	//update products with new item array
			}


			$total_items = count($_SESSION["shop_product"]); //count total items
			die(json_encode(array('items'=>$total_items))); //output json

		}

        ################## list products in cart ###################
		if(isset($_POST["load_cart"]) && $_POST["load_cart"]==1)
		{
			//Generic::_setTrace($_SESSION);
			if(isset($_SESSION["shop_product"]) && count($_SESSION["shop_product"])>0){ //if we have session variable
				$cart_box = '<ul class="cart-products-loaded">';
				$total = 0;
				// Generic::_setTrace($_SESSION);
				foreach($_SESSION["shop_product"] as $product){ //loop though items and prepare html content

					//set variables to use them in HTML content below
					$product_name = $product["product_name"];
					$product_price = $product["product_price"];
					//Generic::_setTrace($product_price);
					$products_category = $product["products_category"];
					$product_qty = $product["quantity"];
					$currency = "BDT ";
					$products_id = $product["products_id"];;

					$cart_box .=  "<li> $products_category (Qty : $product_qty | $product_name) &mdash; $currency ".sprintf("%01.2f", ($product_price * $product_qty)). " <a href=\"#\" class=\"remove-item\" data-code=\"$products_id\">&times;</a></li>";
					$subtotal = ($product_price * $product_qty);
					$total = ($total + $subtotal);
				}
				$cart_box .= "</ul>";
				$cart_box .= '<div class="cart-products-total">Total :  '.$currency . sprintf("%01.2f", $total).' <u> <br>
		<a href="'.$baseUrl.'/shopping-cart-products" title="Review Cart and Check-Out">Check-out</a>
	   </u>
       </div>';
				die($cart_box); //exit and output content
			}else{
				die("Your Cart is empty"); //we have empty cart
			}
		}
		################# remove item from shopping cart ################
		if(isset($_GET["remove_code"]) && isset($_SESSION["shop_product"]))
		{
			$product_code   = filter_var($_GET["remove_code"], FILTER_SANITIZE_STRING); //get the product code to remove

			if(isset($_SESSION["shop_product"][$product_code]))
			{
				unset($_SESSION["shop_product"][$product_code]);
			}

			$total_items = count($_SESSION["shop_product"]);
			die(json_encode(array('items'=>$total_items)));
		}

	}
	public static function actionInsertReviewAndRatings(){

		$response = array();
		$reviews= Yii::app()->request->getParam('reviews');
		$ratings= Yii::app()->request->getParam('ratings');
		$service_name= Yii::app()->request->getParam('service_name');
		$user_id= Yii::app()->request->getParam('user_id');
		$creation_date = new \DateTime('now', new DateTimezone('Asia/Dhaka'));

		foreach($service_name as $name){
			$insertReviewRatings = new ReviewNRatings();
			$insertReviewRatings->service_name = $name;
			$insertReviewRatings->user_id = $user_id;
			$insertReviewRatings->reviews = $reviews;
			$insertReviewRatings->ratings = $ratings;
			$insertReviewRatings->create_date = $creation_date->format('Y-m-d H:i:s');;
			if($insertReviewRatings->save()){
				$response['status'] = "Success";
				$response['message'] = "Review Submitted Successfully";
			}

			else {

				$response['status'] = 'Failed';
			}
		}

		echo json_encode($response);


	}

	  public static function actionInsertReviewAndRatingsForProducts(){

		      $response = array();
		      $reviews= Yii::app()->request->getParam('reviews');
		      $ratings= Yii::app()->request->getParam('ratings');
		      $products_id= Yii::app()->request->getParam('products_id');
		      $user_id= Yii::app()->request->getParam('user_id');
		      $creation_date = new \DateTime('now', new DateTimezone('Asia/Dhaka'));

		      foreach($products_id as $id){
				  $insertReviewRatings = new ProductReviewRatings();
				  $insertReviewRatings->products_id = $id;
				  $insertReviewRatings->user_id = $user_id;
				  $insertReviewRatings->review_text = $reviews;
				  $insertReviewRatings->ratings = $ratings;
				  $insertReviewRatings->create_date = $creation_date->format('Y-m-d H:i:s');;
				  if($insertReviewRatings->save()){
					  $response['status'] = "Success";
					  $response['message'] = "Review Submitted Successfully";
				  }

				  else {

					  $response['status'] = 'Failed';
                   }
			  }

		  echo json_encode($response);


		 }


	public static function actionCancelOrder(){

		$response = array();
		$order_id= Yii::app()->request->getParam('order_id');

		foreach( $order_id as $individual_id ){

			$criteria = new CDbCriteria();
			$criteria->condition = 'id = :id';
			$criteria->params = array(':id' => $individual_id);
			$transaction_details = ServiceTransactionDetails::model()->find($criteria);
			$transaction_details->service_status = "Cancel";

			if($transaction_details->update()){
				$response['status'] = "Success";
				$response['message'] = "Your Order Has Been Canceled";
			}

			else {

				$response['status'] = 'Failed';
           }
		}

		echo json_encode($response);
    }
	public static function actionCancelProductsOrder(){

		$response = array();
		$order_id= Yii::app()->request->getParam('order_id');

		foreach( $order_id as $individual_id ){

			$criteria = new CDbCriteria();
			$criteria->condition = 'id = :id';
			$criteria->params = array(':id' => $individual_id);
			$transaction_details = ShopTransactionDetails::model()->find($criteria);
			$transaction_details->status = "Cancel";

			if($transaction_details->update()){
				$response['status'] = "Success";
				$response['message'] = "Your Order Has Been Canceled";
			}

			else {

				$response['status'] = 'Failed';
			}
		}

		echo json_encode($response);
	}

	public static function actionCompleteOrder(){

		    $response = array();
		    $order_id= Yii::app()->request->getParam('order_id');
            $criteria = new CDbCriteria();
			$criteria->condition = 'id = :id';
			$criteria->params = array(':id' => $order_id);
			$transaction_details = ServiceTransactionDetails::model()->find($criteria);
			$transaction_details->service_status = "Completed";

			if($transaction_details->update()){
				$response['status'] = "Success";
				$response['message'] = "Service Marked As Completed";
			}

			else {

				$response['status'] = 'Failed';
			}


		echo json_encode($response);
	}


	public static function actionLoadSubCategoryForProduct(){
		$response = array();
		$category_name= Yii::app()->request->getParam('category_name');
		$all_value_of_selected_category_id = Generic::getAllSubServiceForShops($category_name);
		$data_html = '<label for="sel1">Select Sub Category:</label>';
		$data_html .= '<select id="sub_service_id" name="ShopCategory[sub_category_name]" class="form-control">';


		foreach($all_value_of_selected_category_id as $single_value){
			foreach($single_value as $key => $value){

				$data_html .= '<option  value="'.$value.'">'.$value.'</option>';
				$response['data'] = $key;
			}}
		$data_html .= '</select>';
		$response['html'] = $data_html;
		echo json_encode($response);

	}


	public static function actionLoadSubCategoryForProductPage(){
		$response = array();
		$category_name= Yii::app()->request->getParam('category_name');
		$all_value_of_selected_category_id = Generic::getAllSubServiceForShops($category_name);
		$data_html = '<label for="sel1">Select Sub Category:</label>';
		$data_html .= '<select id="sub_service_id" name="Products[sub_category_name]" class="form-control">';


		foreach($all_value_of_selected_category_id as $single_value){
			foreach($single_value as $key => $value){

				$data_html .= '<option  value="'.$value.'">'.$value.'</option>';
				$response['data'] = $key;
			}}
		$data_html .= '</select>';
		$response['html'] = $data_html;
		echo json_encode($response);

	}





	public  function actionProductsCategory($category_slug){

		$shop_details = Generic::getShopDetailsUsingCategorySlug($category_slug);

		$this->render('shop-category',array(
			'shop_details'=>$shop_details,

		));

	}



	public static function actionInsert(){
		function generateRandomString($length = 6) {
			$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$charactersLength = strlen($characters);
			$randomString = '';
			for ($i = 0; $i < $length; $i++) {
				$randomString .= $characters[rand(0, $charactersLength - 1)];
			}
			return $randomString;
		}

		for($i=0; $i<=1000; $i++) {
			$coupon_value = generateRandomString();
			$sql = "insert into coupon (coupon_value, discount_rate) values (:coupon_value, :discount_rate)";
			$parameters = array(":coupon_value"=>$coupon_value, ':discount_rate' => 5);
			Yii::app()->db->createCommand($sql)->execute($parameters);

		}
	}


	public  function actionCheckCouponCode()
	{
		$coupon_value = $_POST['code'];
		$total_price = $_POST['total_price'];
		$response = array();
		if ($coupon_value) {
			$sql = "select * from coupon where coupon_value='$coupon_value'";
		}

		$result = Yii::app()->db->createCommand($sql)->queryRow();

		if ($result['coupon_value'] == $coupon_value ) {
			$response['status'] = "Success";
			$discounted_price = Generic::vatCalculator($total_price,4);
			$shipping_cost = 50;
			$response['discounted_value'] = $discounted_price;
			$calculated_amount = (($total_price - $discounted_price) + $shipping_cost);
			$response['calculated_amount'] = $calculated_amount;
			$response['final_amount'] = $calculated_amount;

		  }
		else {
			$response['status'] = "False";
		}

		echo json_encode($response);
	   }


	public  function actionShowData(){
		session_start();
		if(empty($_SESSION['admin_login_token'])){
			return $this->redirect(Yii::app()->createUrl('site-admin'));
		}
		else{
			$all_shop_data = Generic::getAllShopData();
			$all_service_data = Generic::getAllServiceData();
			//Generic::_setTrace($all_shop_data);
			$this->render('result',array(

				'all_shop_data'=> $all_shop_data,
				'all_service_data'=> $all_service_data

			));
		}

	}



	public static function actionChangeOrderStatus(){

		$response = array();
		$order_id= Yii::app()->request->getParam('order_id');

		$criteria = new CDbCriteria();
		$criteria->condition = 'invoice_id = :invoice_id';
		$criteria->params = array(':invoice_id' => $order_id);
		$transaction_details = ShopTransactionDetails::model()->find($criteria);
		$transaction_details->status = "Delivered";

		if($transaction_details->update()){
				$response['status'] = "Success";
				$response['message'] = " Order Has Been Delivered";
		}

		else {

				$response['status'] = 'Failed';
		}

		echo json_encode($response);
	}



	public  function actionContactUs(){


			$this->render('contact-us');

	}


}
