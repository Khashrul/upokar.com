<?php

class ProfileController extends Controller
{
    public $layout='frontend';


	public function actionUserProfile()
	{
        $session_for_login = Yii::app()->session['user_login_token'];
        $profile_data = Generic::getProfileData($session_for_login);


		$this->render('user-profile',array('profile_data' => $profile_data));
	}



    public function actionUserServiceHistory()
    {
        $session_for_login = Yii::app()->session['user_login_token'];
        $profile_data = Generic::getProfileData($session_for_login);
        $service_details = Generic::getServiceDetailsUsingProfileId($profile_data['id']);

          //Generic::_setTrace($service_details);


        $this->render('service-history',array(
            'profile_data' => $profile_data,
            'service_details'=> $service_details
        ));
    }



    public function actionUserRecentServices()
    {
        $session_for_login = Yii::app()->session['user_login_token'];
        $profile_data = Generic::getProfileData($session_for_login);
        $service_details = Generic::getServiceDetailsUsingProfileId($profile_data['id'],"Pending");

       // Generic::_setTrace($service_details);


        $this->render('recent-services',array(
            'profile_data' => $profile_data,
            'service_details'=> $service_details
        ));
    }


    public function actionServicesDetails()
    {
        $invoice_id = base64_decode(Yii::app()->request->getParam('InvoiceId'));
        $session_for_login = Yii::app()->session['user_login_token'];
        $profile_data = Generic::getProfileData($session_for_login);
        $service_details = Generic::getServiceDetailsUsingInvoiceId($invoice_id);
        //Generic::_setTrace($service_details);
        $this->render('my-order-details',array(
            'profile_data' => $profile_data,
            'service_details'=> $service_details
        ));
    }


    public function actionYourProfile()
	{
		$is_logged_in = $session =  Yii::app()->session['user_token'];
		if(!$is_logged_in){
			$this->render('sign-in');
		}
		$token = Yii::app()->session['user_token'];
		$profile_data = Generic::getProfileData($token);
		$login_type = isset($profile_data['register_type']) ? $profile_data['register_type'] : '';
		$enterprise_name = isset($profile_data['enterprise_name']) ? $profile_data['enterprise_name'] : '';
		if(isset($login_type) && $login_type == 'personal'){
			$this->render('personal-profile');
		}
		elseif(isset($login_type) && $login_type == 'business'){
			$this->render('business-profile',array('enterprise_name'=>$enterprise_name));
		}

	}

	public function actionList()
	{
		$this->render('index_list');
	}


	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Register']))
		{
			$model->attributes=$_POST['Register'];
			if($model->save())
				$this->redirect(array('myprofile','id'=>$model->id));
		}

		$this->render('myprofile',array(
			'model'=>$model,
		));
	}

	public function loadModel($id)
	{
		$model=Register::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	public function actionYourDashboard($country_code = '')
	{
        $requested_country = Generic::checkValidCountryRequest($country_code);

        if($country_code && !$requested_country){
            return ;
        }
        $country_details = Generic::checkForStoredCountry();

        $currency = '';
        $currency_sign = '';
        if (!$country_code) {
            if ($country_details) {
                $currency_sign = $country_details->currency_sign;
                $currency = Generic::currency_convert(100,'BDT',$country_details->currency);
            }
        } else {
            $currency = Generic::currency_convert(100,'BDT',$requested_country['currency']);
            $currency_sign = $requested_country['currency_sign'];
        }
        $currency_rate = Generic::Getfloat($currency);

        $user_details = (object) $this->checkUserDetails();
        $criteria = new CDbCriteria();
        $criteria->condition = 'user_id = :user_id and active = :active';
        $criteria->params = array(':user_id' => $user_details->profile_data['id'],':active' => 1);
        $store_details = Estore::model()->find($criteria);
        $subscription_details = Generic::getSubscriptionDetailsFromUserID($store_details->user_id);

        $info_array = array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,
            'subscription_details' => $subscription_details,
            'currency_rate' => $currency_rate,
            'currency_sign' => $currency_sign,
            'country_code' => $country_code
        );


        $render_page = 'dashboard';
        $service_config = '';
        if($user_details->register_type == 'business' && $user_details->business_status == 'approved') {
            #If not skipped
            $info_array['remarks'] = 0;
            #else
            if(empty($store_details->categories) || empty($store_details->banner) || empty($store_details->logo) || empty($store_details->slogan)){
                $info_array['remarks'] = 1;
                $this->actionUpdateEStore();
                return;
            }
            if($subscription_details){
                $plan_details_array = Generic::getPlanDetailsArray($subscription_details->id);
                $user_ads_array = Generic::getUserAdsArray($user_details->profile_data['id']);
                $info_array['plan_details_array'] = $plan_details_array;
                $info_array['user_ads_array'] = $user_ads_array;
            }
            else{
                $info_array['plan_details_array'] = '';
                $info_array['user_ads_array'] = '';
            }
        } else if($user_details->register_type == 'business' && $user_details->business_status == 'no_store'){
            $render_page = 'create-e-store';
        } else if($user_details->register_type == 'promotion') {
            $render_page = 'dashboard_service';
            $info_array['service_config'] =  Generic::getServiceConfiguration();
        }


        if($user_details->service_status != 'no_service'){
            $info_array['service_status'] = $user_details->service_status;
            $info_array['service'] = Service::model()->findByPk($user_details->service_id);
            $info_array['service_plan'] = Service_config::model()->findByPk($info_array['service']->plan_id);
        }
        $this->render($render_page, $info_array);
	}

	public function actionMyAds()
	{
        $user_details = (object) $this->checkUserDetails();

        $this->render('my-ads',array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url
        ));
	}

	public function actionMessages($message_type = '')
	{
        $user_details = (object) $this->checkUserDetails();

        $Criteria = new CDbCriteria();
        $Criteria->condition = "user_token = :user_token";
        $Criteria->params = array(':user_token'=>Yii::app()->session['user_token']);
        $logged_user = Register::model()->find($Criteria);

        $criteria = new CDbCriteria();
        $criteria->condition = 'receiver_id = :receiver_id and seen = :seen';
        $criteria->params = array(':receiver_id' => $logged_user->id,':seen' => 0);
        Notification_message::model()->updateAll(array('seen' => '1'),$criteria);

        $messages = Generic::getAllMessageOfUser($logged_user->id,$message_type);
		$this->render('messages',array(
		    'messages' => $messages,
            'base_url' => Yii::app()->request->getBaseUrl(true),
            'message_type' => $message_type,
            'word_count' => 10,
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url
        ));
	}

	public function actionListFavouriteAds()
	{
        $user_details = (object) $this->checkUserDetails();

        $criteria = new CDbCriteria();
        $criteria->condition = "user_token = :user_token";
        $criteria->params = array(':user_token'=>Yii::app()->session['user_token']);
        $loggedin_user = Register::model()->find($criteria);
        $loggedin_user_id = $loggedin_user->id;

        $criteria = new CDbCriteria();
        $criteria->condition = 'receiver_id = :receiver_id and seen = :seen';
        $criteria->params = array(':receiver_id' => $loggedin_user_id,':seen' => 0);
        Notification_favorite::model()->updateAll(array('seen' => '1'),$criteria);

        $criteria = new CDbCriteria();
        $criteria->condition = 'user_id = :user_id and active = :active';
        $criteria->params = array(':user_id' => $loggedin_user_id,':active' => 1);
        $my_ads = Ads::model()->findAll($criteria);

        $favorite_array = array();
        $favorites = Favorites::model()->findAll();
        foreach ($favorites as $favorite) {
            $favorite_array[] = explode(',',$favorite->ad_id);
        }

        $all_favorite_ads = Generic::getAllFavoritesAdsFromDB();
        $favorite_ads = array();
        $favorite_ads_counter = array();
        foreach ($my_ads as $ad) {
            if(in_array($ad->id,$all_favorite_ads)) {
                $favorite_ads[] = $ad;
                $favorite_counter = 0;
                foreach($favorite_array as $single_favorite){
                    if(in_array($ad->id,$single_favorite)) {
                        $favorite_counter++;
                    }
                }
                $favorite_ads_counter[] = $favorite_counter;
            }
        }

        $option_array = array(
            'w' =>'269',
            'h' =>'262',
            'g'=>'center',
            'r' => '0'
        );
		$this->render('favourite-ads',array(
		    'favorite_ads' => $favorite_ads,
            'favorite_ads_counter' => $favorite_ads_counter,
            'option_array' => $option_array,
            'baseUrl' => Yii::app()->request->getBaseUrl(true),
            'word_count' => 10,
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url
        ));
	}

	public function actionMySearches()
	{
        $criteria = new CDbCriteria();
        $criteria->condition = "user_token = :user_token";
        $criteria->params = array(':user_token'=>Yii::app()->session['user_token']);
        $logged_user = Register::model()->find($criteria);
        $logged_user_id = $logged_user->id;

        $criteria = new CDbCriteria();
        $criteria->condition = 'receiver_id = :receiver_id and seen = :seen';
        $criteria->params = array(':receiver_id' => $logged_user_id,':seen' => 0);
        Notification_search::model()->updateAll(array('seen' => '1'),$criteria);

		$this->render('my-searches');
	}

	public function actionSettings()
	{
        $user_details = (object) $this->checkUserDetails();
        $info_array = array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,

        );
        if($user_details->register_type == 'promotion') {
            $render_page = 'dashboard_service';
            $info_array['service_config'] =  Generic::getServiceConfiguration();
        }


        if($user_details->service_status != 'no_service'){
            $info_array['service_status'] = $user_details->service_status;
            $info_array['service'] = Service::model()->findByPk($user_details->service_id);
            $info_array['service_plan'] = Service_config::model()->findByPk($info_array['service']->plan_id);
        }
		$this->render('settings',$info_array);
	}

	public function actionCreateEStore($country_code = '')
	{
        $requested_country = Generic::checkValidCountryRequest($country_code);

        if($country_code && !$requested_country){
            return ;
        }
        $country_details = Generic::checkForStoredCountry();

        $currency = '';
        $currency_sign = '';
        if (!$country_code) {
            if ($country_details) {
                $currency_sign = $country_details->currency_sign;
                $currency = Generic::currency_convert(100,'BDT',$country_details->currency);
            }
        } else {
            $currency = Generic::currency_convert(100,'BDT',$requested_country['currency']);
            $currency_sign = $requested_country['currency_sign'];
        }
        $currency_rate = Generic::Getfloat($currency);

        $user_details = (object) $this->checkUserDetails();
		$this->render('create-e-store',array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,
            'currency_rate' => $currency_rate,
            'currency_sign' => $currency_sign,
            'country_code' => $country_code
        ));
	}

    public function actionUpdateEStore()
    {
        $user_details = (object) $this->checkUserDetails();
        $store_details = Estore::model()->findByAttributes(array('user_id'=>$user_details->profile_data['id']));
        $subscription_details = Subscription_plan::model()->findByAttributes(array('user_id'=>$user_details->profile_data['id']));
        if(empty($store_details->categories) || empty($store_details->banner) || empty($store_details->logo) || empty($store_details->slogan)){
            if(!$subscription_details->additional_service){
                $this->render('update-skipped-e-store', array(
                    'user_name' => $user_details->user_name,
                    'register_type' => $user_details->register_type,
                    'profile_data' => $user_details->profile_data,
                    'sidebar_type' => $user_details->sidebar_type,
                    'business_status' => $user_details->business_status,
                    'store_url' => $user_details->store_url,
                    'store_details' => $store_details,
                ));
            }
            else{
                $this->render('update-skipped-e-store-with-service', array(
                    'user_name' => $user_details->user_name,
                    'register_type' => $user_details->register_type,
                    'profile_data' => $user_details->profile_data,
                    'sidebar_type' => $user_details->sidebar_type,
                    'business_status' => $user_details->business_status,
                    'store_url' => $user_details->store_url,
                    'store_details' => $store_details,
                ));
            }
        }
        else {
            $this->render('update-e-store', array(
                'user_name' => $user_details->user_name,
                'register_type' => $user_details->register_type,
                'profile_data' => $user_details->profile_data,
                'sidebar_type' => $user_details->sidebar_type,
                'business_status' => $user_details->business_status,
                'store_url' => $user_details->store_url,
                'store_details' => $store_details,
            ));
        }
    }

	public function actionAddAds()
	{
        $user_details = (object) $this->checkUserDetails();


        $store_owner_status = Generic::checkStoreOwnerStatus($user_details->profile_data['id']);
        if(!$store_owner_status['active']){
            $this->redirect($user_details->base_url.'/my-profile/dashboard');

        }
        $store_owner_service_status = Generic::checkStoreOwnerServiceStatus($user_details->profile_data['id']);
        if(!$store_owner_service_status['status']){
            $this->redirect($user_details->base_url.'/my-profile/change-plan');
        }

        $subscription_details = Generic::getSubscriptionDetailsFromUserID($user_details->profile_data['id']);
        $plan_details_array = array();
        $user_ads_array = array();
        if($subscription_details){
            $plan_details_array = Generic::getPlanDetailsArray($subscription_details->id);
            $user_ads_array = Generic::getUserAdsArray($user_details->profile_data['id']);
        }

        $this->render('add-ads',array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,
            'plan_details_array' => $plan_details_array,
            'user_ads_array' => $user_ads_array
        ));
	}

    public function actionChangePlan()
    {
        $user_details = (object) $this->checkUserDetails();
        $subscription_status = 'Expired';
        $ad_post_service = 'Not Taken';
        $plan_settings = Yii::app()->params['planSettings'];

        $criteria = new CDbCriteria();
        $criteria->condition = 'user_id = :user_id';
        $criteria->params = array(':user_id' => $user_details->profile_data['id']);
        $subscription_details = Subscription_plan::model()->find($criteria);
        $plan_details_array = array();
        $user_ads_array = array();
        if($subscription_details){
            $plan_details_array = Generic::getPlanDetailsArray($subscription_details->id);
            $user_ads_array = Generic::getUserAdsArray($user_details->profile_data['id']);
            if($subscription_details->status) {
                $subscription_status = 'Active';
            }

            if($user_ads_array) {
                if($user_ads_array['user_total_ad_count'] >= $plan_details_array['ad_count']){
                    $subscription_status = 'Exceeded';
                }
            }
            if($subscription_details->additional_service){
                $ad_post_service = 'Taken';
            }
        }

        $plan_details = array();
        $plan_name = '';

        switch ($subscription_details->plan_type){
            case 1:
                $plan_details = $plan_settings['standard'];
                $plan_name = 'Standard';
                break;
            case 2:
                $plan_details = $plan_settings['silver'];
                $plan_name = 'Silver';
                break;
            case 3:
                $plan_details = $plan_settings['platinum'];
                $plan_name = 'Platinum';
                break;

        }

        $this->render('change-plan',array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,
            'plan_details_array' => $plan_details_array,
            'user_ads_array' => $user_ads_array,
            'subscription_status' => $subscription_status,
            'plan_name' => $plan_name,
            'plan_details' => $plan_details,
            'ad_post_service' => $ad_post_service
        ));

    }

    /*
     * sends request to support@bdads24.com for Ad Post Service
     */
    public function actionSendRequestForAdPostService(){
        $plan_name = Yii::app()->request->getParam('plan_name','');
        $user_id = Yii::app()->request->getParam('user_id','');
        $plan_status = Yii::app()->request->getParam('plan_status','');

        $user_details = Register::model()->findByPk($user_id);

        $message_line_spacing_single = '<br>';
        $message_line_spacing_double = '<br><br>';
        $to_email = Yii::app()->params['adminEmail'];
        $subject = 'Request for Ad Post Service';
        $message = '<h3>Following registered business requested for Ad Post Service with following details:</h3>';
        $message .= $message_line_spacing_double . '<p><strong>Business Name</strong>: ' . $user_details->enterprise_name . '</p>';
        $message .= $message_line_spacing_double . '<p><strong>Contact Person Name</strong>: ' . $user_details->user_name . '</p>';
        $message .= $message_line_spacing_single . '<p><strong>Contact Person Email</strong>: ' . $user_details->email . '</p>';
        $message .= $message_line_spacing_single . '<p><strong>Contact Person Phone</strong>: ' . $user_details->phone_number . '</p>';
        $message .= $message_line_spacing_single . '<p><strong>Service Plan</strong>: ' . $plan_name . '</p>';
        $message .= $message_line_spacing_single . '<p><strong>Service Status</strong>: ' . $plan_status . '</p>';

        if(Generic::sendMail($message, $subject,$to_email)){
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failure';
        }

        echo json_encode($response);
    }

    /*
     * sends request to support@bdads24.com to change subscription plan
     */
    public function actionSendRequestForChangePlan(){
        $plan_name = Yii::app()->request->getParam('plan_name','');
        $user_id = Yii::app()->request->getParam('user_id','');
        $plan_status = Yii::app()->request->getParam('plan_status','');
        $ad_post_service = Yii::app()->request->getParam('ad_post_service','');

        $user_details = Register::model()->findByPk($user_id);

        $message_line_spacing_single = '<br>';
        $message_line_spacing_double = '<br><br>';
        $to_email = Yii::app()->params['adminEmail'];
        $subject = 'Request for Changing Subscription Plan';
        $message = '<h3>Following registered business requested for changing subscription plan with following details:</h3>';
        $message .= $message_line_spacing_double . '<p><strong>Business Name</strong>: ' . $user_details->enterprise_name . '</p>';
        $message .= $message_line_spacing_double . '<p><strong>Contact Person Name</strong>: ' . $user_details->user_name . '</p>';
        $message .= $message_line_spacing_single . '<p><strong>Contact Person Email</strong>: ' . $user_details->email . '</p>';
        $message .= $message_line_spacing_single . '<p><strong>Contact Person Phone</strong>: ' . $user_details->phone_number . '</p>';
        $message .= $message_line_spacing_single . '<p><strong>Service Plan</strong>: ' . $plan_name . '</p>';
        $message .= $message_line_spacing_single . '<p><strong>Service Status</strong>: ' . $plan_status . '</p>';
        $message .= $message_line_spacing_single . '<p><strong>Ad Post Service Taken</strong>: ' . $ad_post_service . '</p>';

        if(Generic::sendMail($message, $subject,$to_email)){
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failure';
        }

        echo json_encode($response);
    }



	public function actionDiscountOffer()
	{
		$this->render('discount-offer');
	}

	public function actionSpecialAds()
	{
		$this->render('special-ads');
	}

	public function actionViewMyEStore()
	{
		$this->render('view-my-e-store');
	}

    /**
     * delete message from profile
     */
    public function actionDeleteMessage()
    {
        $message_id = Yii::app()->request->getParam('message_id',0);
        $message_type = Yii::app()->request->getParam('message_type','');
        if(!$message_id) {
            return false;
        }
        if($message_type =='sent') {
            $message = Message_sent::model()->findByPk($message_id);
        } else {
            $message = Message::model()->findByPk($message_id);
        }
        if($message->delete()) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failure';
        }
        echo json_encode($response);
    }

    /**
     * starr message
     */
    public function actionStarrMessage()
    {
        $message_id = Yii::app()->request->getParam('message_id',0);
        $message_type = Yii::app()->request->getParam('message_type','');
        if(!$message_id) {
            return false;
        }
        if($message_type == 'sent') {
            $message = Message_sent::model()->findByPk($message_id);
        } else {
            $message = Message::model()->findByPk($message_id);
        }
        $message->is_starred = $message->is_starred ? 0 : 1;
        if($message->save()) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failure';
        }
        echo json_encode($response);
    }

    /**
     * get full message details
     */
    public function actionGetMessageDetails()
    {
        $message_id = Yii::app()->request->getParam('message_id',0);
        $message_type = Yii::app()->request->getParam('message_type','');
        if(!$message_id) {
            return false;
        }
        if($message_type == 'sent') {
            $message = Message_sent::model()->findByPk($message_id);
        } else {
            $message = Message::model()->findByPk($message_id);
        }
        if($message) {
            $message->read_status = 1;
            $message->save();
            $response['status'] = 'success';
            $response['html'] = $message->details;
            if($message_type == 'sent') {
                $response['html_reply_button'] = '';
            } else {
                $response['html_hidden'] = '<input type="hidden" name="sender_id" id="sender_id" value="'.$message->receiver.'" />
<input type="hidden" name="receiver_id" id="receiver_id" value="'.$message->registered_sender.'" />
<input type="hidden" name="ad_id" id="receiver_id" value="'.$message->ad_id.'" />
<input type="hidden" name="reply_of" id="receiver_id" value="'.$message->id.'" />';
                $response['html_reply_button'] = '<button id="reply_btn" class="btn btn-primary" onclick="showReplyMessage()">Reply</button>';
            }

        } else {
            $response['status'] = 'failure';
            $response['html'] = '';
        }
        echo json_encode($response);
    }

    /**
     * get Sender Details
     */
    public function actionGetSenderDetails()
    {
        $message_id = Yii::app()->request->getParam('message_id',0);
        $message_type = Yii::app()->request->getParam('message_type','');
        if(!$message_id) {
            return false;
        }
        if($message_type =='sent') {
            $message = Message_sent::model()->findByPk($message_id);
            $sender_details = Register::model()->findByPk($message->receiver);
            $response['status'] = 'success';
            $response['html'] = '<div class="col-sm-6">
                        <img src="'.$sender_details->image.'" alt="'.$sender_details->user_name.'">
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <label class="col-sm-3 label-title">Name</label>
                            <div class="col-sm-9">'.$sender_details->user_name.'</div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 label-title">Email</label>
                            <div class="col-sm-9">'.$sender_details->email.'</div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 label-title">Phone</label>
                            <div class="col-sm-9">'.$sender_details->phone_number.'</div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 label-title">Address</label>
                            <div class="col-sm-9">'.$sender_details->address.' '.$sender_details->district.'</div>
                        </div>
                    </div>';
        } else {
            $message = Message::model()->findByPk($message_id);
            if($message->registered_sender) {
                $sender_details = Register::model()->findByPk($message->registered_sender);
                $response['status'] = 'success';
                $response['html'] = '<div class="col-sm-6">
                        <img src="'.$sender_details->image.'" alt="'.$sender_details->user_name.'">
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <label class="col-sm-3 label-title">Name</label>
                            <div class="col-sm-9">'.$sender_details->user_name.'</div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 label-title">Email</label>
                            <div class="col-sm-9">'.$sender_details->email.'</div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 label-title">Phone</label>
                            <div class="col-sm-9">'.$sender_details->phone_number.'</div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 label-title">Address</label>
                            <div class="col-sm-9">'.$sender_details->address.' '.$sender_details->district.'</div>
                        </div>
                    </div>';
            } else {
                $response['status'] = 'success';
                $response['html'] = '<div class="col-sm-6">
                        <img src="'.Yii::app()->request->getBaseUrl(true).'/images/user.jpg" alt="'.$message->sender_name.'">
                    </div>
                    <div class="col-sm-6">
                        <div class="row">
                            <label class="col-sm-3 label-title">Name</label>
                            <div class="col-sm-9">'.$message->sender_name.'</div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 label-title">Email</label>
                            <div class="col-sm-9">'.$message->sender_email.'</div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 label-title">Phone</label>
                            <div class="col-sm-9">'.$message->sender_phone.'</div>
                        </div>
                    </div>';
            }
        }

        echo json_encode($response);
    }

    /*
     * saves a reply message
     */
    public function actionSaveMessage(){
        $sender_id = Yii::app()->request->getParam('sender_id');
        $receiver_id = Yii::app()->request->getParam('receiver_id');
        $ad_id = Yii::app()->request->getParam('ad_id');
        $reply_details = Yii::app()->request->getParam('reply_details');
        $reply_of = Yii::app()->request->getParam('reply_of');
        $sender_details = Register::model()->findByPk($sender_id);
        $name = $sender_details->user_name;
        $email = $sender_details->email;
        $phone = $sender_details->phone_number;
        $current_time = new \DateTime();

        $reply_message = new Message();
        $reply_message->registered_sender = $sender_id;
        $reply_message->receiver = $receiver_id;
        $reply_message->sender_name = $name;
        $reply_message->sender_email = $email;
        $reply_message->sender_phone = $phone;
        $reply_message->ad_id = $ad_id;
        $reply_message->details = $reply_details;
        $reply_message->read_status = 0;
        $reply_message->is_starred = 0;
        $reply_message->reply_of = $reply_of;
        $reply_message->create_date = $current_time->format('Y-m-d H:i:s');
        $reply_message->save();

        $message_notification = new Notification_message();
        $message_notification->receiver_id = $receiver_id;
        $message_notification->sender_id = $sender_id;
        $message_notification->create_date = $current_time->format('Y-m-d H:i:s');
        $message_notification->save();

        $reply_message_own_copy = new Message_sent();
        $reply_message_own_copy->registered_sender = $sender_id;
        $reply_message_own_copy->receiver = $receiver_id;
        $reply_message_own_copy->sender_name = $name;
        $reply_message_own_copy->sender_email = $email;
        $reply_message_own_copy->sender_phone = $phone;
        $reply_message_own_copy->ad_id = $ad_id;
        $reply_message_own_copy->details = $reply_details;
        $reply_message_own_copy->read_status = 0;
        $reply_message_own_copy->is_starred = 0;
        $reply_message_own_copy->reply_of = $reply_of;
        $reply_message_own_copy->create_date = $current_time->format('Y-m-d H:i:s');
        $reply_message_own_copy->save();

        $this->redirect(Yii::app()->request->getBaseUrl(true).'/my-profile/messages');
    }

    public function actionGetMessageNotification(){
        $user_token = Yii::app()->request->getParam('user_token');
        $Criteria = new CDbCriteria();
        $Criteria->condition = "user_token = :user_token";
        $Criteria->params = array(':user_token'=>$user_token);
        $loggedin_user = Register::model()->find($Criteria);

        $criteria = new CDbCriteria();
        $criteria->condition = 'receiver_id = :receiver_id and seen = :seen';
        $criteria->params = array(':receiver_id' => $loggedin_user->id,':seen' => 0);
        $message_notifications = Notification_message::model()->findAll($criteria);
        $response['status'] = 'success';
        $response['notification_count'] = count($message_notifications);
        echo json_encode($response);
    }

    public function actionGetFavoriteNotification(){
        $user_token = Yii::app()->request->getParam('user_token');
        $Criteria = new CDbCriteria();
        $Criteria->condition = "user_token = :user_token";
        $Criteria->params = array(':user_token'=>$user_token);
        $loggedin_user = Register::model()->find($Criteria);

        $criteria = new CDbCriteria();
        $criteria->condition = 'receiver_id = :receiver_id and seen = :seen';
        $criteria->params = array(':receiver_id' => $loggedin_user->id,':seen' => 0);
        $message_notifications = Notification_favorite::model()->findAll($criteria);
        $response['status'] = 'success';
        $response['notification_count'] = count($message_notifications);
        echo json_encode($response);
    }

    public function actionGetSearchNotification(){
        $user_token = Yii::app()->request->getParam('user_token');
        $Criteria = new CDbCriteria();
        $Criteria->condition = "user_token = :user_token";
        $Criteria->params = array(':user_token'=>$user_token);
        $loggedin_user = Register::model()->find($Criteria);

        $criteria = new CDbCriteria();
        $criteria->condition = 'receiver_id = :receiver_id and seen = :seen';
        $criteria->params = array(':receiver_id' => $loggedin_user->id,':seen' => 0);
        $message_notifications = Notification_search::model()->findAll($criteria);
        $response['status'] = 'success';
        $response['notification_count'] = count($message_notifications);
        echo json_encode($response);
    }

    public function actionGetAlertNotification(){
        $user_token = Yii::app()->request->getParam('user_token');
        $Criteria = new CDbCriteria();
        $Criteria->condition = "user_token = :user_token";
        $Criteria->params = array(':user_token'=>$user_token);
        $loggedin_user = Register::model()->find($Criteria);

        $criteria = new CDbCriteria();
        $criteria->condition = 'receiver_id = :receiver_id and seen = :seen';
        $criteria->params = array(':receiver_id' => $loggedin_user->id,':seen' => 0);
        $message_notifications = Notification_alert::model()->findAll($criteria);
        $response['status'] = 'success';
        $response['notification_count'] = count($message_notifications);
        echo json_encode($response);
    }

    /*
     * get Ad details from ad id
     */
    public function actionGetAdDetailsForProfile(){
        $opt = array(
            'w' =>'400',
            'h' =>'280',
            'g'=>'center',
            'r' => '0'
        );
        $opt_small = array(
            'w' =>'100',
            'h' =>'90',
            'g'=>'center',
            'r' => '0'
        );
        $ad_id = Yii::app()->request->getParam('ad_id','');
        $ad_details = Ads::model()->findByPk($ad_id);
        $ad_owner = Register::model()->findByPk($ad_details->user_id);

        $criteria = new CDbCriteria();
        $criteria->condition = 'category_id = :category_id and ad_id = :ad_id';
        $criteria->params = array(':category_id' => $ad_details->category_id,':ad_id' => $ad_details->id);
        $ad_meta_all = Ad_meta::model()->findAll($criteria);
        $ad_meta = array();
        if(!$ad_details) {
            $response['status'] = 'failure';
        } else {
            $response['status'] = 'success';
            $response['ad_id'] = $ad_details->id;
            $response['ad_title'] = $ad_details->title;

            $images = json_decode($ad_details->image_url);
            $counter = 0;

            /* ------------------ image block -------------------- */
            $image_block = '';
            $image_block .= '<div class="col-xs-12 col-md-12">
<img src="'.ImageHelper::cloudinary($images[0],$opt).'" alt="Featured Image" class="img-responsive">
                </div><div class="col-xs-12 col-md-12 ad_small_images" style="margin-top:10px"><ul>';
            foreach( $images as $image )
            {
                if($counter){
                    $image_block .= '<li style="float: left;margin-right: 5px"><img src="'.ImageHelper::cloudinary($image,$opt_small).'" alt="small image" class="img-responsive"></li>';
                }
                $counter++;
            }
            $image_block .= '</ul></div>';

            /* ------------------ Short description block -------------------- */
            $short_desc_block = '';
            $short_desc_block .= '<h4>Short Information</h4>';

            if($ad_details->ad_condition == 1){
                $short_desc_block .= '<p><strong>Condition: </strong>New</p>';
            } else {
                $short_desc_block .= '<p><strong>Condition: </strong>Used</p>';
            }
            foreach ($ad_meta_all as $ad_meta) {
                $short_desc_block .= '<p><strong>'.ucwords(str_replace("_"," ",$ad_meta->field_name)).':</strong>'.$ad_meta->field_value.'</p>';
            }

            if ($ad_details->price_type == 1){
                $short_desc_block .= '<p><strong>Price Type: </strong>Fixed</p>';
            } else {
                $short_desc_block .= '<p><strong>Price Type: </strong>Negotiable</p>';
            }

            if($ad_details->active) {
                $ad_status_icon = '<p><strong>Status: </strong><span class="verified-tag" title="Verified">Verified</span></p>';
            } else {
                $ad_status_icon = '<p><strong>Status: </strong><span class="unverified-tag" title="Pending">Pending</span></p>';
            }

            $response['ad_image'] = $image_block;
            if($ad_details->show_price) {
                $response['ad_price'] = '&#2547; ' . $ad_details->price;
            } else {
                $response['ad_price'] = '';
            }
            $response['ad_discount'] = $ad_details->discount;
            $response['ad_short_details'] = $short_desc_block.$ad_status_icon;
            $response['ad_status'] = $ad_details->active;
            $response['ad_details'] = '<h4>Description</h4><p style="text-align: justify;">'.$ad_details->description.'</p><br>';
        }
        echo json_encode($response);
    }

    public function actionNotifications(){

        $user_details = (object) $this->checkUserDetails();

        $Criteria = new CDbCriteria();
        $Criteria->condition = "user_token = :user_token";
        $Criteria->params = array(':user_token'=>Yii::app()->session['user_token']);
        $logged_user = Register::model()->find($Criteria);

        $criteria = new CDbCriteria();
        $criteria->condition = 'receiver_id = :receiver_id';
        $criteria->params = array(':receiver_id' => $logged_user->id);
        $criteria->order='id DESC';
        $messages = Notification_alert::model()->findAll($criteria);
        Notification_alert::model()->updateAll(array('seen' => '1'),$criteria);

        $info_array = array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,
            'base_url' => Yii::app()->request->getBaseUrl(true),
            'messages' => $messages,
            'word_count' => 50
        );
        if($user_details->register_type == 'promotion') {
            $render_page = 'dashboard_service';
            $info_array['service_config'] =  Generic::getServiceConfiguration();
        }


        if($user_details->service_status != 'no_service'){
            $info_array['service_status'] = $user_details->service_status;
            $info_array['service'] = Service::model()->findByPk($user_details->service_id);
            $info_array['service_plan'] = Service_config::model()->findByPk($info_array['service']->plan_id);
        }

        $this->render('notifications',$info_array);
    }

    public function actionGetNotificationDetails(){
        $message_id = Yii::app()->request->getParam('message_id',0);
        if(!$message_id) {
            return false;
        }

        $message = Notification_alert::model()->findByPk($message_id);

        if($message) {
            $response['status'] = 'success';
            $response['html'] = $message->details;
        } else {
            $response['status'] = 'failure';
            $response['html'] = '';
        }
        echo json_encode($response);
    }


    /**
     * delete notification from profile
     */
    public function actionDeleteAlertNotification()
    {
        $message_id = Yii::app()->request->getParam('message_id',0);
        if(!$message_id) {
            return false;
        }

        $message = Notification_alert::model()->findByPk($message_id);

        if($message->delete()) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failure';
        }
        echo json_encode($response);
    }

       public function actionLoadMoreAds()
        {
           $response = array();
           $page_number = Yii::app()->request->getParam('page');
           $user_id = Yii::app()->request->getParam('user_id');
           $limit = 5;
           $offset = (($page_number-1) * $limit);
           $opt = array(
                'w' =>'130',
                'h' =>'120',
                'g'=>'center',
                 'r' => '0'
              );
            $ads = Generic::getAdDetailsFromAddTable($user_id,$limit,$offset);
            $baseUrl = Yii::app()->getBaseUrl(true);
            $loaded_html = "";
                foreach($ads as $ad){
                    $ad_date_block = "";
                    $ad_without_date_block = "";
                            $ad_id = $ad['id'];
                            $ad_views = Generic::getTotalAdView($ad_id);
                            $view_count = array_sum(array_column($ad_views, 'view_count'));
                            $expire_date = new \DateTime($ad['expire_date']);
                            $ad_create_date = new \DateTime($ad['expire_date']);
                            $ad_create_date->modify('1 month ago');
                            $images = json_decode($ad['image_url']);

                   if($ad['expire_date']!= NULL){
                       $ad_date_block = ' <li><i class="fa fa-clock-o"></i>From:'. $ad_create_date->format('d M') .' - To: '.$expire_date->format('d M').' </li>
                                            <li><i class="fa fa-eye"></i>'.$view_count.'</li>';

                   }

                   if($ad['expire_date'] != NULL && $ad['active']== 1){

                       $ad_without_date_block = ' <div class="verified-tag" style="font-size: 14px; padding-left: 0px;" title="Verified"><i class="fa fa-check" aria-hidden="true"></i> Verified</div>';
                   }else{
                       $ad_without_date_block = '<div class="unverified-tag" style="font-size: 14px;" title="Pending"><i class="fa fa-cog" aria-hidden="true"></i> Pending</div>';

                   }



                    $loaded_html .= '<div class="items-list">
                                <article class="item-spot">
                                <a href="javascript:void(0)" class="imgBg tc" data-item="'. $ad['id'].'">
                                    <img src="'.ImageHelper::cloudinary($images[0],$opt).'" alt="dummy data">
                                </a>
                                <div class="item-content">
                                    <header>
                                        <h6><a href="javascript:void(0)" class="tc" data-item="'.$ad['id'].'">'.$ad['title'].'</a></h6>
                                        <ul class="item-info">
                                           '.$ad_date_block.'
                                            <div class="price-tag">BDT '.$ad['price'].'</div>
                                           '.$ad_without_date_block.'
                                        </ul>
                                    </header>

                                    <div class="item-admin-actions text-center">
                                        <ul>
                                            <li><a class="tc" title="View" href="javascript:void(0)" onclick="showAdPreviewModal('.$ad['id'].')" data-item="'.$ad['id'].'"><i class="adicon-eye"></i></a></li>
                                            <li><a class="tc6-hover" title="Edit" href="'.$baseUrl."/update-ad?ad_id=".urlencode(base64_encode($ad['id'])).'"><i class="adicon-edit"></i></a></li>
                                            <li><a id="tc1-hover" title="Request Hot Ad" href="javascript:void(0);" onclick="set_ad_id('.$ad['id'].')"><i class="adicon-star-circle"></i></a></li>
                                            <li><a class="tc12-hover" title="Delete" href="javascript:void(0);" onclick="deleteItem(' . $ad['id'] . ',\'' .$ad['user_id'].'\')"><i class="adicon-recyclebin"></i></a></li>
                                        </ul>
                                    </div>

                                </div>
                            </article>
                       </div>';
                              }

            $response['html'] = html_entity_decode($loaded_html);
            echo json_encode($response);


        }


    public function actionSendRequestAfterPlanChosen(){
        $plan_name = Yii::app()->request->getParam('plan_name');
        $user_id = Yii::app()->request->getParam('user_id');

        if(isset($plan_name) && isset($user_id)) {
            $user_details = Register::model()->findByPk($user_id);
            $activation_link = Yii::app()->getBaseUrl(true).'/estore-creation-from-support?token='.urlencode(base64_encode(json_encode(array('user_id' => md5($user_details->id),'plan_name' => $plan_name))));
            $message_line_spacing_single = '<br>';
            $message_line_spacing_double = '<br><br>';
            $to_email = Yii::app()->params['adminEmail'];
            $subject = 'Request for opening an estore';
            $message = '<h3>Following registered business requested for an estore with following details:</h3>';
            $message .= $message_line_spacing_double . '<p><strong>Business Name</strong>: ' . $user_details->enterprise_name . '</p>';
            $message .= $message_line_spacing_double . '<p><strong>Contact Person Name</strong>: ' . $user_details->user_name . '</p>';
            $message .= $message_line_spacing_single . '<p><strong>Contact Person Email</strong>: ' . $user_details->email . '</p>';
            $message .= $message_line_spacing_single . '<p><strong>Contact Person Phone</strong>: ' . $user_details->phone_number . '</p>';
            $message .= $message_line_spacing_single . '<p><strong>Service Plan</strong>: ' . $plan_name . '</p>';
            $message .= $message_line_spacing_single . '<p><strong>Ad Post Service</strong>: Taken</p>';
            $message .= $message_line_spacing_double.'<p>You can follow the link below to create EStore:</p>';
            $message .= $message_line_spacing_single.'<a href="'.$activation_link.'" target="_blank">'.$activation_link.'</a>';

            Generic::sendMail($message, $subject,$to_email);
            $response['status'] = 'success';
            echo json_encode($response);
        }
    }

    /*
     * Show thank you page for choosing plan
     */
    public function actionShowThanksMessage(){
        $user_details = (object) $this->checkUserDetails();
        $this->render('thankyou',array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url
        ));
    }

    private function checkUserDetails($check_session = true){
        $token = Yii::app()->session['user_token'];
        $base_url = Yii::app()->request->getBaseUrl(true);
        if(!$token) {
            $this->redirect($base_url.'/sign-in');
        }
        $profile_data = Generic::getProfileData($token);
        if(!$profile_data) {
            $this->redirect($base_url.'/sign-in');
        }

        $user_name = isset($profile_data['user_name']) ? $profile_data['user_name'] : '';
        $register_type = isset($profile_data['register_type']) ? $profile_data['register_type'] : '';
        $category_details = Category::model()->findByPk($profile_data['business_category_id']);

        $sidebar_type = '../elements/profile_sidebar';

        $current_time = new \DateTime();
        $store_url = '';
        $business_status = '';
        $store_details = '';
        if ($register_type == 'business') {
            $sidebar_type = '../elements/business_sidebar';
            $store_url = '';
            $criteria = new CDbCriteria();
            $criteria->condition = 'user_id = :user_id';
            $criteria->params = array(':user_id' => $profile_data['id']);
            $store_details = Estore::model()->find($criteria);

            if(empty($store_details->categories) || empty($store_details->banner) || empty($store_details->logo) || empty($store_details->slogan)){
                $sidebar_type = '../elements/service_sidebar';
            }

            if(!$store_details){
                $business_status = 'no_store';
            } else {
                if (!$store_details->active) {
                    $business_status = 'pending';

//                    if($check_session) {
//                        $criteria = new CDbCriteria();
//                        $criteria->condition = 'user_id = :user_id and payment_status = :payment_status';
//                        $criteria->params = array(':user_id' => $profile_data['id'], ':payment_status' => 0);
//                        $criteria->order = 'id desc';
//                        $criteria->limit = 1;
//                        $payment_history = PaymentHistory::model()->find($criteria);
//                        if ($payment_history && $payment_history->payment_method == 1) {
//                            Estore::model()->deleteByPk($payment_history->store_id);
//                            Generic::deleteEstoreDetails($payment_history->store_id);
//                            $payment_history->payment_status = 2;
//                            $payment_history->decline_reason = 'Payment processing Cancelled';
//                            $payment_history->update();
//                            $business_status = 'no_store';
//                        }
//                    }

                } else {
                    $criteria = new CDbCriteria();
                    $criteria->condition = 'estore_id = :estore_id';
                    $criteria->params = array(':estore_id' => $store_details->id);
                    $business_plan = Subscription_plan::model()->find($criteria);
                    if ($business_plan->status) {
                        $business_status = 'approved';
                    } else {
                        $business_status = 'expired';
                    }
                    $store_url = $base_url . '/e-store/' . $store_details->url_alias;
                }
            }
        } else if($register_type == 'promotion'){
            $sidebar_type = '../elements/service_sidebar';
        }

        $data_result = array(
            'profile_data' => $profile_data,
            'register_type' => $register_type,
            'user_name' => $user_name,
            'sidebar_type' => $sidebar_type,
            'business_status' => $business_status,
            'store_url' => $store_url,
            'base_url' => $base_url,

        );

        if($register_type == 'business' && $store_details){
            $data_result['estore_id']  = $store_details->id;
        }

        /* ------- Check if the user has a request for  service ----------- */
        $criteria = new CDbCriteria();
        $criteria->condition = 'user_id = :user_id';
        $criteria->params = array(':user_id' => $profile_data['id']);
        $service_requested = Service::model()->find($criteria);
        if($service_requested){
            if($service_requested->active) {
                $data_result['service_status'] = 'approved';
                $data_result['service_plan_id'] = $service_requested->plan_id;
                $data_result['service_id'] = $service_requested->id;
            } else {
                $data_result['service_status'] = 'pending';
                $data_result['service_plan_id'] = $service_requested->plan_id;
                $data_result['service_id'] = $service_requested->id;
            }
        } else {
            $data_result['service_status'] = 'no_service';
        }

        return $data_result;
    }


    public function actionOrderManagement(){
        $user_details = (object) $this->checkUserDetails();

        $criteria = new CDbCriteria();
        $criteria->condition = 'estore_id = :estore_id';
        $criteria->params = array(':estore_id' => $user_details->estore_id);
        $order_data = EstoreOrder::model()->findAll($criteria);



        $this->render('order-management',array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,
            'order_data' => $order_data
        ));

    }


    /*
     *  method for saving service request from service provider dashboard
     * @plan_type integer
     * @request_type integer
     * @return json response
     */
    public function actionSendServiceRequest(){
        $plan_type = Yii::app()->request->getParam('plan','');
        $request_type = Yii::app()->request->getParam('request_type','');
        $token = Yii::app()->session['user_token'];
        if($token == ''){
            $response['status'] = 'failure';
            $response['msg'] = 'You are not authorized';
            echo json_encode($response);
            return;
        }
        $criteria = new CDbCriteria();
        $criteria->condition = "user_token = :user_token";
        $criteria->params = array(':user_token'=>$token);
        $logged_user = Register::model()->find($criteria);

        $criteria = new CDbCriteria();
        $criteria->condition = 'business_category_id = :business_category_id';
        $criteria->params = array(':business_category_id' => $logged_user->business_category_id);
        $service = Service_config::model()->find($criteria);
        if(!$service){
            $response['status'] = 'failure';
            $response['msg'] = 'No Service available';
            echo json_encode($response);
            return;
        }

        $current_date = new \DateTime();
        $promotion_type = '';
        if($request_type == 1){
            $promotion_type = 'banner';
        } else if($request_type == 2){
            $promotion_type = 'website link';
        } else if($request_type == 3){
            $promotion_type = 'promotion page';
        }
        $activation_link = '';
        try{
            $service_request = new Service_request();
            $service_request->user_id = $logged_user->id;
            $service_request->plan_type = $plan_type;
            $service_request->request_type = $request_type;
            $service_request->service_id = $service->id;
            $service_request->status = 0;
            $service_request->create_date = $current_date->format('Y-m-d H:i:s');
            if($service_request->save()){
                // send mail to support
                $activation_link = Yii::app()->getBaseUrl(true).'/promotion-banner-creation-from-support?token='.urlencode(base64_encode(json_encode(array('user_id' => md5($logged_user->id),'request_type' => $request_type,'plan_name' => $plan_type))));
                $message_line_spacing_single = '<br>';
                $message_line_spacing_double = '<br><br>';
                $to_email = Yii::app()->params['adminEmail'];
                $subject = 'Request for service promotion';
                $message = '<h3>Following registered service provider requested for '.$promotion_type.' with following details:</h3>';
                $message .= $message_line_spacing_double . '<p><strong>Business Name</strong>: ' . $logged_user->enterprise_name . '</p>';
                $message .= $message_line_spacing_double . '<p><strong>Contact Person Name</strong>: ' . $logged_user->user_name . '</p>';
                $message .= $message_line_spacing_single . '<p><strong>Contact Person Email</strong>: ' . $logged_user->email . '</p>';
                $message .= $message_line_spacing_single . '<p><strong>Contact Person Phone</strong>: ' . $logged_user->phone_number . '</p>';
                $message .= $message_line_spacing_single . '<p><strong>Service Plan</strong>: ' . $plan_type . '</p>';
                $message .= $message_line_spacing_double.'<p>You can follow the link below to create EStore:</p>';
                $message .= $message_line_spacing_single.'<a href="'.$activation_link.'" target="_blank">'.$activation_link.'</a>';

                Generic::sendMail($message, $subject,$to_email);
            }
        } catch (Exception $exception){
            $response['status'] = 'failure';
            $response['msg'] = 'You have an error: '.$exception;
            echo json_encode($response);
            return;
        }
        $response['status'] = 'success';
        $response['msg'] = 'We received your request. Please wait while we approve your request accordingly.';
        $response['activation_link'] = $activation_link;
        echo json_encode($response);
    }

    public function actionBusinessPlan(){
        $currency_rate = Yii::app()->request->getParam('currency_rate');

        $id = Yii::app()->request->getParam('selected_value');
        $business_plan_details = Generic::getBusinessPlanDetails($id);
        $response['status'] = 'success';
        $response['name'] = $business_plan_details['name'];
        $response['price'] = $business_plan_details['price']/100 * $currency_rate;
        $response['duration'] = $business_plan_details['duration'];
        $response['details'] = $business_plan_details['details'];
        $response['id'] = $business_plan_details['id'];
        echo json_encode($response);
    }


    public  function actionBusinessInformation(){
        $user_details = (object) $this->checkUserDetails();

        $pricing_plan_id = Yii::app()->request->getParam('pricing_plan_id');
        $ad_post_service = Yii::app()->request->getParam('ad_post_service');

        $this->render('business-information',array(
            'pricing_plan_id' => $pricing_plan_id,
            'ad_post_service' => $ad_post_service,
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url
        ));
    }

    public  function actionBusinessInformationWithPostService(){
        $user_details = (object) $this->checkUserDetails();

        $pricing_plan_id = Yii::app()->request->getParam('pricing_plan_id');
        $ad_post_service = Yii::app()->request->getParam('ad_post_service');

        $this->render('business-information-with-post-service',array(
            'pricing_plan_id' => $pricing_plan_id,
            'ad_post_service' => $ad_post_service,
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url
        ));
    }

    public function actionPaymentSelection()
    {
        unset(Yii::app()->session['successIndicator']);
        unset(Yii::app()->session['orderID']);

        $user_details = (object)$this->checkUserDetails();
        $pricing_plan_id = '';
        $promotion_request_type = '';
        $request_for = Yii::app()->request->getParam('request_for');
        $business_information_data = array();
        $pricing_plan_details = array();

        if(!strcmp($request_for,'service_promotion')){
            $pricing_plan_id = Yii::app()->request->getParam('promotion_plan');
            $promotion_request_type = Yii::app()->request->getParam('request_type');
            $business_information_data['user_id'] = $user_details->profile_data['id'];
            $business_information_data['company_name'] = $user_details->profile_data['enterprise_name'];

            $url_alias = str_replace(" ","-",$user_details->profile_data['enterprise_name']);

            $criteria = new CDbCriteria();
            $criteria->condition = 'url_alias = :url_alias';
            $criteria->params = array(':url_alias' => $url_alias);
            $existing_service = Service::model()->find($criteria);
            if($existing_service){
                $date = new \DateTime();
                $url_alias = $url_alias.'-'.$date->getTimestamp();
            }
            $business_information_data['company_url_alias'] = $url_alias;
            $business_information_data['about_service'] = Yii::app()->request->getParam('about_service');
            $business_information_data['contact_details'] = Yii::app()->request->getParam('contact_details');
            $business_information_data['service_documents'] = Yii::app()->request->getParam('service_documents');
            $business_information_data['additional_comment'] = Yii::app()->request->getParam('additional_comment');
            $business_information_data['facebook_link'] = Yii::app()->request->getParam('facebook_link');
            $business_information_data['twitter_link'] = Yii::app()->request->getParam('twitter_link');
            $business_information_data['linkedin_link'] = Yii::app()->request->getParam('linkedin_link');
            $business_information_data['google_plus_link'] = Yii::app()->request->getParam('google_plus_link');
            $promotion_config = Service_config::model()->findByPk($pricing_plan_id);
            $pricing_plan_details['name'] = $promotion_config->name;
            $pricing_plan_details['duration'] = $promotion_config->duration.' month';
            $pricing_plan_details['price'] = $promotion_config->price;

        } else {
            $pricing_plan_id = Yii::app()->request->getParam('pricing_plan_id');
            $business_information_data['user_id'] = $user_details->profile_data['id'];
            $business_information_data['company_name'] = $user_details->profile_data['enterprise_name'];
            $url_alias = str_replace(" ","-",$user_details->profile_data['enterprise_name']);

            $criteria = new CDbCriteria();
            $criteria->condition = 'url_alias = :url_alias';
            $criteria->params = array(':url_alias' => $url_alias);
            $existing_estore = Estore::model()->find($criteria);
            if($existing_estore){
                $date = new \DateTime();
                $url_alias = $url_alias.'-'.$date->getTimestamp();
            }

            $business_information_data['company_url_alias'] = $url_alias;
            $business_information_data['company_slogan'] = Yii::app()->request->getParam('slogan');
            $business_information_data['logo_image'] = Yii::app()->request->getParam('logo_image');
            $business_information_data['banner_image'] = Yii::app()->request->getParam('banner_image');
            $business_information_data['sub_banner'] = Yii::app()->request->getParam('sub_banner');
            $business_information_data['category'] = Yii::app()->request->getParam('category');
            $business_information_data['about_us'] = Yii::app()->request->getParam('about_us');
            $business_information_data['contact_us'] = Yii::app()->request->getParam('contact_us');
            $business_information_data['comment'] = Yii::app()->request->getParam('comment');
            $business_information_data['product_details'] = Yii::app()->request->getParam('product_details');
            $business_information_data['product_images'] = Yii::app()->request->getParam('product_images');

            $pricing_plan_details = Generic::getBusinessPlanDetails($pricing_plan_id);
        }


        $ad_post_service = Yii::app()->request->getParam('ad_post_service');
        $this->render('payment', array(
            'pricing_plan_id' => $pricing_plan_id,
            'promotion_request_type' => $promotion_request_type,
            'ad_post_service' => $ad_post_service,
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,
            'pricing_plan_details' => $pricing_plan_details,
            'request_for' => $request_for,
            'business_information_data' => json_encode($business_information_data)
        ));
    }

    public function actionTransactionStatus(){
        $user_details = (object)$this->checkUserDetails();
        $business_info_data = Yii::app()->request->getParam('business_information_data','');
        $individual_ad_information = Yii::app()->request->getParam('individual_ad_information','');
        $request_for = Yii::app()->request->getParam('request_for','');
        $promotion_request_type = Yii::app()->request->getParam('promotion_request_type','');
        if($user_details->register_type != 'personal'){
            if(!isset(Yii::app()->session['orderID']) && $business_info_data == ''){
                $this->redirect(Yii::app()->createUrl('my-profile/dashboard'));
            }
        }

        $pricing_plan_id = Yii::app()->request->getParam('pricing_plan_id');
        $ad_post_service = Yii::app()->request->getParam('ad_post_service');
        $payment_id = Yii::app()->request->getParam('payment');

        $seller_id = Yii::app()->request->getParam('seller_id','');
        $deposit_amount = Yii::app()->request->getParam('advanced_payment','');

        $criteria = new CDbCriteria();
        $criteria->condition = 'saler_id = :saler_id';
        $criteria->params = array(':saler_id' => $seller_id);
        $existing_seller = Saler_info::model()->find($criteria);

        $payment_status = 'failure';
        $failure_reason = '';

        $payment_details = '';
        $success_indicator = '';
        $order_id = '';
        if(isset(Yii::app()->session['orderID'])) {
            $order_id = Yii::app()->session['orderID'];

//            $criteria = new CDbCriteria();
//            $criteria->condition = 'transaction_id = :order_id';
//            $criteria->params = array(':order_id' => $order_id);
//            $payment_details = PaymentHistory::model()->find($criteria);
            $payment_id = 1;
        }
        if($payment_id == 3 && $existing_seller){
            if($user_details->register_type != 'personal'){
                if($request_for == 'service_promotion'){
                    $payment_details = $this->createServicePromotion($business_info_data,$pricing_plan_id,$payment_id,$seller_id,$deposit_amount,'',$request_for);
                } else {
                    $payment_details = $this->createEstoreFromProfile($business_info_data,$pricing_plan_id,$ad_post_service,$payment_id,$seller_id,$deposit_amount);
                }

            } else {
                $payment_details = $this->saveIndividualPaidAd($individual_ad_information,$payment_id,$seller_id,$deposit_amount);
            }
            $payment_details->payment_status = 1;
            $payment_details->update();
            $payment_status = 'success';
        } else if($payment_id == 1){
            /* Todo: credit card payment actions will go here */
            $result_indicator = Yii::app()->request->getParam('resultIndicator');
            $success_indicator = Yii::app()->session['successIndicator'];

            if(strcmp($result_indicator, $success_indicator) == 0){
                $payment_status = 'success';

                if($user_details->register_type != 'personal'){
                    $criteria = new CDbCriteria();
                    $criteria->condition = 'transaction_id = :order_id';
                    $criteria->params = array(':order_id' => $order_id);
                    $payment_details = PaymentHistory::model()->find($criteria);
                    if(!is_null($payment_details->service_promotion_id)){

                        $service = Service::model()->findByPk($payment_details->service_promotion_id);

                        $payment_details->payment_status = 1;
                        $payment_details->update();

                        $service_plan_details = Service_config::model()->findByPk($service->plan_id);
                        $pricing_details_service = array();
                        $pricing_details_service['price'] = $service_plan_details->price;
                        $pricing_details_service["name"] = $service_plan_details->name;
                        $pricing_details_service["duration"] = $service_plan_details->duration.' month';

                        Generic::sendInvoice($payment_details->user_id,$payment_details,$pricing_details_service,$service);
                        //$this->sendEstoreRequestToSupport($service,$service_plan);
                    } else {

                        $registered_user_data = Yii::app()->session['registered_user_data'];
                        $ad_post_service = Yii::app()->session['ad_post_service'];
                        $pricing_plan_id = Yii::app()->session['pricing_plan_id'];

                        $pricing_details = Generic::getBusinessPlanDetails($pricing_plan_id);

                        $configArray = array();
                        include dirname(__FILE__) . '/../extensions/card-processor/api_lib.php';
                        include dirname(__FILE__) . '/../extensions/card-processor/configuration.php';
                        include dirname(__FILE__) . '/../extensions/card-processor/connection.php';


                        $merchantObj = new Merchant($configArray);

                        $parserObj = new Parser($merchantObj);

                        $requestUrl = $parserObj->FormRequestUrl($merchantObj);

                        $request_assoc_array = array("apiOperation"=>"RETRIEVE_ORDER",
                            "order.id"=>$order_id
                        );

                        $request = $parserObj->ParseRequest($merchantObj, $request_assoc_array);
                        $response = $parserObj->SendTransaction($merchantObj, $request);

                        $new_api_lib = new api_lib;
                        $parsed_array = $new_api_lib->parse_from_nvp($response);
                        $card_info = $parsed_array['sourceOfFunds.provided.card.number'];

                        $payment_details = $this->createEstoreFromProfile($registered_user_data,$pricing_plan_id,$ad_post_service,$payment_id,'',0,'',$card_info);
                        $service_plan = Subscription_plan::model()->findByPk($payment_details->subscription_id);
                        $store = Estore::model()->findByPk($payment_details->store_id);

                        Generic::sendInvoice($payment_details->user_id,$payment_details,$pricing_details,$service_plan);
                        $this->sendEstoreRequestToSupport($store,$service_plan);
                    }

                } else {
                    $criteria = new CDbCriteria();
                    $criteria->condition = 'transaction_id = :order_id';
                    $criteria->params = array(':order_id' => $order_id);
                    $payment_details = PaymentHistory::model()->find($criteria);
                    $payment_details->payment_status = 1;
                    $payment_details->update();
                    $pricing_details['price'] = $payment_details->payment_amount;
                    Generic::sendInvoice($payment_details->user_id,$payment_details,$pricing_details,'');
                }

            }

            unset(Yii::app()->session['successIndicator']);
            unset(Yii::app()->session['orderID']);
            unset(Yii::app()->session['registered_user_data']);
            unset(Yii::app()->session['ad_post_service']);
            unset(Yii::app()->session['pricing_plan_id']);

        } else if($payment_id == 2){

            $uploaded_bank_receipt = '';
            if(isset($_FILES['bank_receipt'])){
                $imageName = time() + 1;
                if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAIRWFUJGOJ46XGJYA');
                if (!defined('awsSecretKey')) define('awsSecretKey', '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');
                $s3 = new S3(awsAccessKey, awsSecretKey);
                $image_type = explode('.',$_FILES['bank_receipt']['name']);
                $image = $_FILES['bank_receipt']['tmp_name'];

                $image_name = $imageName.".".$image_type[1];


                if($imageSaveName = $s3->putObjectFile($image,"ad-dwit-a",$image_name,S3::ACL_PUBLIC_READ)){
                    $uploaded_bank_receipt = "http://ad-dwit-a.s3.amazonaws.com/".$image_name;
                }
            }

            if($user_details->register_type != 'personal'){
                if($request_for == 'service_promotion'){
                    $payment_details = $this->createServicePromotion($business_info_data,$pricing_plan_id,$payment_id,'',0,$uploaded_bank_receipt,$request_for);
                } else {
                    $payment_details = $this->createEstoreFromProfile($business_info_data,$pricing_plan_id,$ad_post_service,$payment_id,'',0,$uploaded_bank_receipt);
                }
            } else {
                $payment_details = $this->saveIndividualPaidAd($individual_ad_information,$payment_id,'',0,$uploaded_bank_receipt);
            }
            $payment_status = 'success';
        }

        if(!$existing_seller){
            $failure_reason = 'Seller Id did not match';
        }

        if($individual_ad_information != '') {
            $this->redirect(Yii::app()->createUrl('success?ad_id='.base64_encode($payment_details->ad_id)));
        }

        if($payment_status == 'success') {
            $estore_url = '';
            if($request_for == 'service_promotion'){
                $service_details = Service::model()->findByPk($payment_details->service_promotion_id);
                $estore_url = $user_details->base_url . '/service/' . $service_details->url_alias;
            } else {
                $estore_details = Estore::model()->findByPk($payment_details->store_id);
                $estore_url = $user_details->base_url . '/e-store/' . $estore_details->url_alias;
            }
            $this->render('transaction-success', array(
                'user_name' => $user_details->user_name,
                'register_type' => $user_details->register_type,
                'profile_data' => $user_details->profile_data,
                'sidebar_type' => $user_details->sidebar_type,
                'business_status' => $user_details->business_status,
                'store_url' => $estore_url,
                'pricing_plan_id' => $pricing_plan_id,
                'ad_post_service' => $ad_post_service,
                'payment_details' => $payment_details,
                'request_for' => $request_for
            ));
        } else {
            $this->render('transaction-failure', array(
                'user_name' => $user_details->user_name,
                'register_type' => $user_details->register_type,
                'profile_data' => $user_details->profile_data,
                'sidebar_type' => $user_details->sidebar_type,
                'business_status' => $user_details->business_status,
                'store_url' => $user_details->store_url,
                'pricing_plan_id' => $pricing_plan_id,
                'ad_post_service' => $ad_post_service,
                'failure_message' => $failure_reason
            ));
        }
    }


    /*
     * process failure credit card transaction
     */
    public function actionTransactionFailure(){

        $user_details = (object)$this->checkUserDetails(false);

        $order_id = Yii::app()->session['orderID'];
        $configArray = array();
        include dirname(__FILE__) . '/../extensions/card-processor/api_lib.php';
        include dirname(__FILE__) . '/../extensions/card-processor/configuration.php';
        include dirname(__FILE__) . '/../extensions/card-processor/connection.php';


        $merchantObj = new Merchant($configArray);

        $parserObj = new Parser($merchantObj);

        $requestUrl = $parserObj->FormRequestUrl($merchantObj);

        $request_assoc_array = array("apiOperation"=>"RETRIEVE_ORDER",
            "order.id"=>$order_id
        );

        $request = $parserObj->ParseRequest($merchantObj, $request_assoc_array);
        $response = $parserObj->SendTransaction($merchantObj, $request);

        $new_api_lib = new api_lib;
        $parsed_array = $new_api_lib->parse_from_nvp($response);
        $failure_reason = $parsed_array['transaction[0].response.acquirerMessage'];
        $card_info = $parsed_array['sourceOfFunds.provided.card.number'];

        $registered_user_data = json_decode(Yii::app()->session['registered_user_data']);
        $registered_user_details = Register::model()->findByPk($registered_user_data->user_id);
        $ad_post_service = Yii::app()->session['ad_post_service'];
        $pricing_plan_id = Yii::app()->session['pricing_plan_id'];

        $pricing_details = Generic::getBusinessPlanDetails($pricing_plan_id);

        // delete e-store from order id or delete ad
        if($registered_user_details->register_type == 'business'){
            if($failure_reason != '') {
                $payment_details = $this->savePaymentHistory($registered_user_details->id, $pricing_details, '', 1, '', 0, 2,'','','',$card_info);
                $payment_details->decline_reason = $failure_reason;
                $payment_details->update();
            }
        } else if($registered_user_details->register_type == 'promotion'){
            //Service::model()->deleteByPk($payment_details->service_promotion_id);
        } else {
            //Ads::model()->deleteByPk($payment_details->ad_id);
        }

        if($failure_reason == ''){
            $failure_reason = 'Credit Card processing cancelled';
        }
        unset(Yii::app()->session['successIndicator']);
        unset(Yii::app()->session['orderID']);
        unset(Yii::app()->session['registered_user_data']);
        unset(Yii::app()->session['ad_post_service']);
        unset(Yii::app()->session['pricing_plan_id']);

        $this->render('transaction-failure', array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,
            'failure_message' => $failure_reason
        ));

    }

    /*
     * process cancelled credit card transaction
     */
    public function actionTransactionCancel(){

        $user_details = (object)$this->checkUserDetails(false);

        $order_id = Yii::app()->session['orderID'];
        $configArray = array();
        include dirname(__FILE__) . '/../extensions/card-processor/api_lib.php';
        include dirname(__FILE__) . '/../extensions/card-processor/configuration.php';
        include dirname(__FILE__) . '/../extensions/card-processor/connection.php';


        $merchantObj = new Merchant($configArray);

        $parserObj = new Parser($merchantObj);

        $requestUrl = $parserObj->FormRequestUrl($merchantObj);

        $request_assoc_array = array("apiOperation"=>"RETRIEVE_ORDER",
            "order.id"=>$order_id
        );

        $request = $parserObj->ParseRequest($merchantObj, $request_assoc_array);
        $response = $parserObj->SendTransaction($merchantObj, $request);

        $new_api_lib = new api_lib;
        $parsed_array = $new_api_lib->parse_from_nvp($response);
        $failure_reason = $parsed_array['transaction[0].response.acquirerMessage'];
        $card_info = $parsed_array['sourceOfFunds.provided.card.number'];

        $registered_user_data = json_decode(Yii::app()->session['registered_user_data']);
        $registered_user_details = Register::model()->findByPk($registered_user_data->user_id);
        $ad_post_service = Yii::app()->session['ad_post_service'];
        $pricing_plan_id = Yii::app()->session['pricing_plan_id'];

        $pricing_details = Generic::getBusinessPlanDetails($pricing_plan_id);

        // delete e-store from order id or delete ad
        if($registered_user_details->register_type == 'business'){
            if($failure_reason != '') {
                $payment_details = $this->savePaymentHistory($registered_user_details->id, $pricing_details, '', 1, '', 0, 2,'','','',$card_info);
                $payment_details->decline_reason = $failure_reason;
                $payment_details->update();
            }
        } else if($registered_user_details->register_type == 'promotion'){
            //Service::model()->deleteByPk($payment_details->service_promotion_id);
        } else {
            //Ads::model()->deleteByPk($payment_details->ad_id);
        }

        if($failure_reason == ''){
            $failure_reason = 'Credit Card processing cancelled';
        }
        unset(Yii::app()->session['successIndicator']);
        unset(Yii::app()->session['orderID']);
        unset(Yii::app()->session['registered_user_data']);
        unset(Yii::app()->session['ad_post_service']);
        unset(Yii::app()->session['pricing_plan_id']);

        $this->render('transaction-failure', array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,
            'failure_message' => $failure_reason
        ));
    }

    public function actionFeedback(){
        $response = array();
        $payment_id = Yii::app()->request->getParam('payment_id');
        /*$feedback = Yii::app()->request->getParam('feedback');*/
        $rating = Yii::app()->request->getParam('rating');

        $payment = PaymentHistory::model()->findByPk($payment_id);
        $payment->comment = $rating;
        if($payment->update()){
            $response['status'] = 'success';
        }
        echo json_encode($response);
    }

    /*
     * create estore from profile data information
     * @param mixed $business_info_data
     */
    private function createEstoreFromProfile($business_info_data,$pricing_plan_id,$ad_post_service,$payment_id,$seller_id = '',$deposit_amount = 0,$bank_receipt = '',$card_info = ''){

        $business_data = json_decode($business_info_data);
        $pricing_details = Generic::getBusinessPlanDetails($pricing_plan_id);

        $user_id = $business_data->user_id;
        $company_name = $business_data->company_name;
        $url_alias = $business_data->company_url_alias;
        $company_slogan = $business_data->company_slogan;
        $company_logo = $business_data->logo_image;
        $image_urls = $business_data->banner_image;
        $image_urls_array = explode(',',substr($image_urls, 1));
        $image_url = json_encode($image_urls_array);
        $sub_banner = $business_data->sub_banner;
        $sub_banner_image_urls_array = explode(',',substr($sub_banner, 1));
        $sub_banner_image_url = json_encode($sub_banner_image_urls_array);
        $category = $business_data->category;
        $about_us = $business_data->about_us;
        $contact_us = $business_data->contact_us;
        $comment = $business_data->comment;
        $product_details = $business_data->product_details;
        $product_images = $business_data->product_images;

        $plan_id = $pricing_plan_id;
        $additional_service = $ad_post_service;
        $creation_date = new \DateTime();
        $current_date = new \DateTime();
        $country_details = Generic::checkForStoredCountry();
        $store = new Estore();
        $store->user_id = $user_id;
        $store->slogan = $company_slogan;
        $store->logo = $company_logo;
        $store->banner = $image_url;
        $store->sub_banner = $sub_banner_image_url;
        $store->categories = $category;
        $store->about_us = $about_us;
        $store->contact_us = $contact_us;
        $store->comment = $comment;
        $store->product_details = $product_details;
        $store->product_images = $product_images;
        $store->url_alias = $url_alias;
        $store->create_date = $creation_date->format('Y-m-d');
        if($payment_id == 3 || $payment_id == 1) {
            $store->active = 1;
        }
        if($country_details){
            $store->country_code = $country_details->sortname;
        }
        if($store->save()) {
            $service_plan = new Subscription_plan();
            $service_plan->user_id = $user_id;
            $service_plan->estore_id = $store->id;
            $service_plan->plan_type = $plan_id;
            $service_plan->additional_service = $additional_service;
            $service_plan->create_date = $creation_date->format('Y-m-d H:i:s');

            $subscription_plan_details = new Subscription_details();
            if($payment_id == 3){
                $service_plan->status = 1;
                $service_plan->activation_date = $current_date->format('Y-m-d H:i:s');
                if($pricing_details['duration'] == '3 months'){
                    $current_date->modify('3 month');
                    $service_plan->expiration_date = $current_date->format('Y-m-d H:i:s');
                } else if($pricing_details['duration'] == '6 months'){
                    $current_date->modify('6 month');
                    $service_plan->expiration_date = $current_date->format('Y-m-d H:i:s');
                } else if($pricing_details['duration'] == '12 months'){
                    $current_date->modify('12 month');
                    $service_plan->expiration_date = $current_date->format('Y-m-d H:i:s');
                }

                $subscription_plan_details = new Subscription_details();
                $subscription_plan_details->ad_count = $pricing_details['ad_count'];
                $subscription_plan_details->featured_ad_count = $pricing_details['featured_ad'];
                $subscription_plan_details->premium_ad_count = $pricing_details['premium_ad'];
                $subscription_plan_details->top_ad_count = $pricing_details['top_ad'];
            } else if($payment_id == 1){
                $service_plan->status = 1;
                $service_plan->activation_date = $current_date->format('Y-m-d H:i:s');
                if($pricing_details['duration'] == '3 months'){
                    $current_date->modify('3 month');
                    $service_plan->expiration_date = $current_date->format('Y-m-d H:i:s');
                } else if($pricing_details['duration'] == '6 months'){
                    $current_date->modify('6 month');
                    $service_plan->expiration_date = $current_date->format('Y-m-d H:i:s');
                } else if($pricing_details['duration'] == '12 months'){
                    $current_date->modify('12 month');
                    $service_plan->expiration_date = $current_date->format('Y-m-d H:i:s');
                }

                $subscription_plan_details = new Subscription_details();
                $subscription_plan_details->ad_count = $pricing_details['ad_count'];
                $subscription_plan_details->featured_ad_count = $pricing_details['featured_ad'];
                $subscription_plan_details->premium_ad_count = $pricing_details['premium_ad'];
                $subscription_plan_details->top_ad_count = $pricing_details['top_ad'];
            }
            $service_plan->save();

            if($payment_id == 3){
                $subscription_plan_details->plan_id = $service_plan->id;
                $subscription_plan_details->save();

                $payment_details = $this->savePaymentHistory($user_id,$pricing_details,$service_plan,$payment_id,$seller_id,$deposit_amount,1,'','','','');
                Generic::sendInvoice($user_id,$payment_details,$pricing_details,$service_plan);
                $this->sendEstoreRequestToSupport($store,$service_plan);
            } else if($payment_id == 1){
                $subscription_plan_details->plan_id = $service_plan->id;
                $subscription_plan_details->save();

                $payment_details = $this->savePaymentHistory($user_id,$pricing_details,$service_plan,$payment_id,'',0,1,'','','',$card_info);
            }else {
                $subscription_plan_details->plan_id = $service_plan->id;
                $subscription_plan_details->save();

                $payment_details = $this->savePaymentHistory($user_id,$pricing_details,$service_plan,$payment_id,$seller_id,$deposit_amount,0,$bank_receipt,'','','');
                Generic::sendInvoice($user_id,$payment_details,$pricing_details,$service_plan);
                $this->sendEstoreRequestToSupport($store,$service_plan);
            }

            return $payment_details;
        }
    }

    /*
     * create service promotion
     * @param mixed $business_info_data
     */
    private function createServicePromotion($business_info_data,$pricing_plan_id,$payment_id,$seller_id = '',$deposit_amount = 0,$bank_receipt = '',$request_for){

        $business_data = json_decode($business_info_data);
        $service_plan_details = Service_config::model()->findByPk($pricing_plan_id);
        $pricing_details['price'] = $service_plan_details->price;
        $pricing_details["name"] = $service_plan_details->name;
        $pricing_details["duration"] = $service_plan_details->duration.' month';

        $user_id = $business_data->user_id;
        $company_name = $business_data->company_name;
        $url_alias = strtolower($business_data->company_url_alias);
        $about_service = $business_data->about_service;
        $contact_details = $business_data->contact_details;
        $service_documents = $business_data->service_documents;
        $service_document_array = explode(',',substr($service_documents, 1));
        $service_documents = json_encode($service_document_array);
        $additional_comment = $business_data->additional_comment;
        $facebook_link = $business_data->facebook_link;
        $twitter_link = $business_data->twitter_link;
        $linkedin_link = $business_data->linkedin_link;
        $google_plus_link = $business_data->google_plus_link;

        $plan_id = $pricing_plan_id;
        $creation_date = new \DateTime();
        $current_date = new \DateTime();
        $current_date->modify($pricing_details["duration"]);
        $country_details = Generic::checkForStoredCountry();
        $service = new Service();
        $service->user_id = $user_id;
        $service->plan_id = $pricing_plan_id;
        $service->about_us = $about_service;
        $service->contact_us = $contact_details;
        $service->documents = $service_documents;
        $service->additional_comments = $additional_comment;
        $service->facebook_link = $facebook_link;
        $service->twitter_link = $twitter_link;
        $service->linkedin_link = $linkedin_link;
        $service->google_plus_link = $google_plus_link;
        $service->url_alias = $url_alias;
        $service->create_date = $creation_date->format('Y-m-d H:i:s');
        $service->expire_date = $current_date->format('Y-m-d');

        $payment_details = '';

        if($country_details){
            $service->country_code = $country_details->sortname;
        }

        if($service->save()) {
            if($payment_id == 3){
                $payment_details = $this->savePaymentHistory($user_id,$pricing_details,$service,$payment_id,$seller_id,$deposit_amount,1,'','',$request_for);
                Generic::sendInvoice($user_id,$payment_details,$pricing_details,$service);
                //$this->sendEstoreRequestToSupport($store,$service_plan);
            } else if($payment_id == 1){
                $payment_details = $this->savePaymentHistory($user_id,$pricing_details,$service,$payment_id,'',0,0,'','',$request_for);
            }else {

                $payment_details = $this->savePaymentHistory($user_id,$pricing_details,$service,$payment_id,$seller_id,$deposit_amount,0,$bank_receipt,'',$request_for);
                Generic::sendInvoice($user_id,$payment_details,$pricing_details,$service);
                //$this->sendEstoreRequestToSupport($store,$service_plan);
            }
        }

        return $payment_details;

    }

    /*
     * save individual Ad and send invoice
     * @return object $payment_details
     */
    private function saveIndividualPaidAd($individual_ad_information,$payment_id,$seller_id = '',$deposit_amount = 0,$bank_receipt = '',$card_info = ''){

        $ad_info = get_object_vars(json_decode($individual_ad_information));
        if(!$ad_info){
            return false;
        }
        $user_details = Register::model()->findByPk($ad_info['user_id']);

        $country_details = Generic::checkForStoredCountry();

        $image_urls_array = explode(',',substr($ad_info['image_url'], 1));
        $creation_date = new \DateTime();
        $ad = new Ads();
        $ad->category_id = $ad_info['category_id'];
        $ad->user_id = $ad_info['user_id'];
        $ad->title = $ad_info['ad_title'];
        $ad->image_url = json_encode($image_urls_array);
        $ad->description = $ad_info['ad_description'];
        $ad->ad_condition = $ad_info['ad_condition'];
        $ad->price = $ad_info['ad_price'];
        $ad->price_end = $ad_info['price_end'];
        $ad->price_type = $ad_info['price_type'];
        $ad->discount = $ad_info['discount'];
        $ad->special_offer = $ad_info['special_offer'];
        $ad->create_date = $creation_date->format('Y-m-d H:i:s');
        $ad->update_date = $creation_date->format('Y-m-d H:i:s');
        $ad->is_paid = $ad_info['is_paid'];
        $ad->location = $user_details->district;
        $ad->show_price = $ad_info['show_price_option'];
        if($country_details){
            $ad->country_code = $country_details->sortname;
        }
        $custom_fields_array = array();
        foreach($ad_info['custom_fields_array'] as $custom_field_object){
            $custom_fields_array[] = array(
                'field_name' => $custom_field_object->field_name,
                'field_value' => $custom_field_object->field_value
            );
        }

        if($ad->save()) {
            $ad_id = $ad->id;
            $ad->ad_id = time().$ad_id;
            $ad->update();
            Generic::saveMetaData($ad_info['category_id'],$ad_id,$custom_fields_array);
        }
        $pricing_details['price'] =  Yii::app()->params['individual_paid_ad_price'];
        if($payment_id != 1){
            $payment_details = $this->savePaymentHistory($ad_info['user_id'],$pricing_details,'',$payment_id,$seller_id,$deposit_amount,1,$bank_receipt,$ad->id);
            Generic::sendInvoice($ad_info['user_id'],$payment_details,$pricing_details,'');
        } else {
            $payment_details = $this->savePaymentHistory($ad_info['user_id'],$pricing_details,'',$payment_id,$seller_id,$deposit_amount,0,$bank_receipt,$ad->id,'',$card_info);
        }
        return $payment_details;
    }

    private function sendEstoreRequestToSupport($store,$service_plan){
        $user_details = Register::model()->findByPk($store->user_id);
        $plan_details = Generic::getBusinessPlanDetails($service_plan->plan_type);

        $activation_link = Yii::app()->getBaseUrl(true).'/estore-creation-from-support?token='.urlencode(base64_encode(json_encode(array('user_id' => md5($user_details->id),'plan_id' => $service_plan->plan_type, 'store_id' => $store->id))));
        $message_line_spacing_single = '<br>';
        $message_line_spacing_double = '<br><br>';
        $to_email = Yii::app()->params['adminEmail'];
        $subject = 'Request for opening an estore';
        $message = '<h3>Following registered business requested for an estore with following details:</h3>';
        $message .= $message_line_spacing_double.'<p><strong>Business Name</strong>: '.$user_details->enterprise_name.'</p>';
        $message .= $message_line_spacing_double.'<p><strong>Contact Person Name</strong>: '.$user_details->user_name.'</p>';
        $message .= $message_line_spacing_single.'<p><strong>Business Url</strong>: '.$store->url_alias.'</p>';
        $message .= $message_line_spacing_single.'<p><strong>Contact Person Email</strong>: '.$user_details->email.'</p>';
        $message .= $message_line_spacing_single.'<p><strong>Contact Person Phone</strong>: '.$user_details->phone_number.'</p>';
        $message .= $message_line_spacing_single.'<p><strong>Choosen Plan</strong>: '.ucwords($plan_details['name'])." / ".$plan_details['duration'].'</p>';
        $message .= $message_line_spacing_double.'<p>You can activate the Estore from the link below:</p>';
        $message .= $message_line_spacing_single.'<a href="'.$activation_link.'" target="_blank">'.$activation_link.'</a>';

        Generic::sendMail($message,$subject,$to_email);
    }

    private function savePaymentHistory($user_id,$pricing_details,$service_plan,$payment_method,$seller_id,$deposit_amount,$payment_status = 0,$bank_receipt = '',$ad_id = '',$request_for = '',$card_info = ''){
        $create_date = new \DateTime();
        $user_details = Register::model()->findByPk($user_id);
        $payment_amount = $pricing_details['price'];


        $ref_id = $create_date->getTimestamp();
        $payment_history = new PaymentHistory();
        $payment_history->user_id = $user_id;
        if($service_plan != '') {
            if($request_for != 'service_promotion'){
                $payment_history->store_id = $service_plan->estore_id;
                $payment_history->plan_id = $pricing_details['id'];
                $payment_history->subscription_id = $service_plan->id;
                if($service_plan->additional_service){
                    /* Todo: if ad post service changes with plan, then we need to add ad_post_service column to business_config table */
                    $payment_amount += 1000;
                }
            } else {
                $payment_history->service_promotion_id = $service_plan->id;
            }

        }
        $due_amount = 0;
        if($payment_method == 3 && $deposit_amount) {
            $due_amount = $payment_amount - $deposit_amount;
            $payment_amount = $deposit_amount;
            $payment_status = 3;
        }
        $payment_history->invoice_id = $ref_id;
        $payment_history->payment_method = $payment_method;
        $payment_history->transaction_date = $create_date->format('Y-m-d H:i:s');
        $payment_history->payment_amount = $payment_amount;
        $payment_history->due_amount = $due_amount;
        $payment_history->payment_status = $payment_status;
        $payment_history->create_date = $create_date->format('Y-m-d H:i:s');
        $payment_history->referral_id = $user_details->referral_id;
        if($service_plan == ''){
            $payment_history->ad_id = $ad_id;
        }

        if($seller_id != '' && $payment_method == 3){
            $payment_history->saler_id = $seller_id;
            $payment_history->invoice_id = $ref_id;
        } else if($payment_method == 1){
            $payment_history->transaction_id = Yii::app()->session['orderID'];
            $payment_history->invoice_id = Yii::app()->session['orderID'];
        } else if($payment_method == 2){
            $payment_history->bank_receipt = $bank_receipt;
            $payment_history->invoice_id = $ref_id;
        }
        if($card_info != ''){
            $payment_history->card_info = $card_info;
        }

        if($payment_history->save()){

        }
        return $payment_history;
    }

    /*
     * process credit card payment request
     */
    public function actionPaymentProcessor(){
        $user_details = (object)$this->checkUserDetails();
        $pricing_plan_id = Yii::app()->request->getParam('pricing_plan_id');
        $request_for = Yii::app()->request->getParam('request_for');
        $ad_post_service = Yii::app()->request->getParam('ad_post_service');
        $payment_id = Yii::app()->request->getParam('payment','');
        $business_info_data = Yii::app()->request->getParam('business_information_data');
        $individual_ad_information = Yii::app()->request->getParam('individual_ad_information','');

        $order_amount  = 0;
        $order_id = '';
        $order_description = '';
        $order_details = array();
        if($payment_id){
            if($user_details->register_type != 'personal'){
                if($request_for == 'service_promotion'){
                    $payment_details = $this->createServicePromotion($business_info_data,$pricing_plan_id,$payment_id,'',0,'',$request_for);

                    $pricing_details = Service_config::model()->findByPk($pricing_plan_id);
                    $order_amount = $payment_details['payment_amount'];
                    $order_id = $payment_details['transaction_id'];
                    $order_description = 'Package: '.ucwords($pricing_details->name).' /'.$pricing_details->duration.' month';
                } else {
                    //$payment_details = $this->createEstoreFromProfile($business_info_data,$pricing_plan_id,$ad_post_service,$payment_id);

                    $pricing_details = Generic::getBusinessPlanDetails($pricing_plan_id);
                    $order_amount = $pricing_details['price'];
                    if($ad_post_service){
                        $order_amount += 1000;
                    }
                    $create_date = new \DateTime();
                    $order_id = $create_date->getTimestamp();
                    $order_description = 'Package: '.ucwords($pricing_details["name"]).' /'.$pricing_details["duration"];
                    $order_details['order_amount'] = $order_amount;
                    $order_details['order_id'] = $order_id;
                    $order_details['order_description'] = $order_description;
                    $order_details['order_currency'] = 'BDT';
                    $order_details['address'] = $user_details->profile_data['address'];
                    $order_details['city'] = $user_details->profile_data['district'];
                    $order_details['order_email'] = $user_details->profile_data['email'];
                }
            } else {
                $payment_details = $this->saveIndividualPaidAd($individual_ad_information,$payment_id);
                $ad_details = Ads::model()->findByPk($payment_details->ad_id);
                $order_amount = $payment_details['payment_amount'];
                $order_id = $payment_details['transaction_id'];
                $order_description = 'Individual Ad: '.ucwords($ad_details->title);
            }
        }



        //Generic::_setTrace($merchantID);

        $success_page_url = Yii::app()->getBaseUrl(true).'/my-profile/transaction-status';
        $cancel_page_url = Yii::app()->getBaseUrl(true).'/my-profile/transaction-cancel';
        $failure_page_url = Yii::app()->getBaseUrl(true).'/my-profile/transaction-failure';
        $order_details['success_page_url'] = $success_page_url;
        $order_details['cancel_page_url'] = $cancel_page_url;
        $order_details['failure_page_url'] = $failure_page_url;
        $alternate_link = Yii::app()->getBaseUrl(true).'/card-payment-processor.php';

        $this->render('payment-processor', array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,
            'pricing_plan_id' => $pricing_plan_id,
            'ad_post_service' => $ad_post_service,
            'order_amount' => $order_amount,
            'order_currency' => 'BDT',
            'order_description' => $order_description,
            'order_id' => $order_id,
            'business_info_data' => $business_info_data,
            'success_page_url' => $success_page_url,
            'cancel_page_url' => $cancel_page_url,
            'failure_page_url' => $failure_page_url,
            'alternate_link' => $alternate_link,
            'data_token' => base64_encode(json_encode($order_details))
        ));

    }

    /*
     * get service information
     */
    public function actionGetServiceInformation(){
        $user_details = (object)$this->checkUserDetails();
        $request_type = Yii::app()->request->getParam('request_type');
        $plan_config = Yii::app()->request->getParam('plan_config');

        $this->render('service-information', array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,
            'request_type' => $request_type,
            'plan_config' => $plan_config,

        ));
    }

    /*
     * manage banner service taken by a service provider
     */
    public function actionManageBanner(){
        $user_details = (object)$this->checkUserDetails();

        $info_array = array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,

        );
        if($user_details->register_type == 'promotion') {
            $render_page = 'dashboard_service';
            $info_array['service_config'] =  Generic::getServiceConfiguration();
        }


        $criteria = new CDbCriteria();
        $criteria->condition = 'user_id = :user_id';
        $criteria->params = array(':user_id' => $user_details->profile_data['id']);
        $banner_details = AdSpecial::model()->find($criteria);
        $info_array['banner_details'] = $banner_details;
        $info_array['banner_size_option'] = array('w' =>'500','g'=>'center','r' => '0','c' => 'pad');

        if($user_details->service_status != 'no_service'){
            $info_array['service_status'] = $user_details->service_status;
            $info_array['service'] = Service::model()->findByPk($user_details->service_id);
            $info_array['service_plan'] = Service_config::model()->findByPk($info_array['service']->plan_id);
        }

        $this->render('banner-management', $info_array);
    }

    /*
     * manage website service taken by a service provider
     */
    public function actionManageWebsite(){
        $user_details = (object)$this->checkUserDetails();
        $banner_link = Yii::app()->request->getParam('banner_url');
        $info_array = array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,

        );
        if($user_details->register_type == 'promotion') {
            $info_array['service_config'] =  Generic::getServiceConfiguration();
        }

        $criteria = new CDbCriteria();
        $criteria->condition = 'user_id = :user_id';
        $criteria->params = array(':user_id' => $user_details->profile_data['id']);
        $banner_details = AdSpecial::model()->find($criteria);
        $info_array['banner_details'] = $banner_details;
        $info_array['banner_size_option'] = array('w' =>'500','g'=>'center','r' => '0','c' => 'pad');


        if($user_details->service_status != 'no_service'){
            $info_array['service_status'] = $user_details->service_status;
            $info_array['service'] = Service::model()->findByPk($user_details->service_id);
            $info_array['service_plan'] = Service_config::model()->findByPk($info_array['service']->plan_id);
        }

        if(isset($banner_link)){
            $banner_details->banner_url = $banner_link;
            $banner_details->update();
            $info_array['message'] = 'Banner url updated successfully';
        }

        $this->render('website-management', $info_array);
    }

    /*
     * manage landing page service taken by a service provider
     */
    public function actionManageLandingPage(){
        $user_details = (object)$this->checkUserDetails();

        $info_array = array(
            'user_name' => $user_details->user_name,
            'register_type' => $user_details->register_type,
            'profile_data' => $user_details->profile_data,
            'sidebar_type' => $user_details->sidebar_type,
            'business_status' => $user_details->business_status,
            'store_url' => $user_details->store_url,

        );
        if($user_details->register_type == 'promotion') {
            $info_array['service_config'] =  Generic::getServiceConfiguration();
        }


        if($user_details->service_status != 'no_service'){
            $info_array['service_status'] = $user_details->service_status;
            $info_array['service'] = Service::model()->findByPk($user_details->service_id);
            $info_array['service_plan'] = Service_config::model()->findByPk($info_array['service']->plan_id);
        }

        $this->render('landing-page-management', $info_array);
    }

    /*
     * Save landing page for service promotion
     */
    public function actionSaveLandingPage(){

    }

    public function actionShowSession(){
        session_start();
        echo "sfdsd";
        echo "<pre>";
        print_r($_SESSION);
    }

    public function actionExpertLogin(){


        $this->render('expert-login');




    }


    public function actionExpertLoginFormSubmit(){
        $response = array();
        $user_email = trim(yii::app()->request->getParam('user_email'));
        $user_password = Yii::app()->request->getParam('user_password');
        $final_password = $user_password;


        if($user_email && $user_password ) {
            $sql = "SELECT * FROM expert_details WHERE expert_email LIKE '$user_email' AND password LIKE '$final_password' ";
            $result = Yii::app()->db->createCommand($sql)->queryRow();

        }

        if($result['password']== $final_password){
            $response['status'] = 'success';
            $response['expert_login_token'] = $result['id'];
            Yii::app()->session['expert_login_token'] = $result['id'];



        } else {
            $response['status'] = 'Wrong Credentials...';

        }
        echo json_encode($response);

    }

    public function actionExpertProfile()
    {
        $session_for_login = Yii::app()->session['expert_login_token'];
        $expert_profile_data = Generic::getExpertProfileData($session_for_login);


        $this->render('expert-profile',array('expert_profile_data' => $expert_profile_data));
    }

    public function actionExpertNewService()
    {
        $session_for_login = Yii::app()->session['expert_login_token'];
        $expert_profile_data = Generic::getExpertProfileData($session_for_login);
        $expert_name = $expert_profile_data['id'];
        $newService = Generic::getAllTypeServiceUsingExpertName($expert_name,"Pending");
        //Generic::_setTrace($expert_profile_data,false);
        //Generic::_setTrace($newService);


        $this->render('expert-new-services',array(
            'newService' => $newService,
            'expert_profile_data' => $expert_profile_data
        ));
    }



    public function actionExpertNewServiceDetails()
    {
        $invoice_id = base64_decode(Yii::app()->request->getParam('ServiceId'));
        $session_for_login = Yii::app()->session['expert_login_token'];
       //$profile_data = Generic::getExpertProfileData($session_for_login);
        $service_details = Generic::getServiceDetailsUsingServiceId($invoice_id);
        //Generic::_setTrace($service_details);
        //die;
        $profile_data = Generic::getProfileData($service_details[0]['service_taker_id']);
        $expert_name = $service_details[0]['expert_name'];


        $this->render('expert-order-details',array(
            'profile_data' => $profile_data,
            'service_details'=> $service_details,
            'expert'=> $expert_name
        ));
    }

    public function actionExpertCompletedService()
    {
        $session_for_login = Yii::app()->session['expert_login_token'];
        $expert_profile_data = Generic::getExpertProfileData($session_for_login);
        $expert_name = $expert_profile_data['id'];
        $newService = Generic::getAllTypeServiceUsingExpertName($expert_name,"Completed");
        //Generic::_setTrace($newService);


        $this->render('expert-completed-services',array(
            'newService' => $newService,
            'expert_profile_data' => $expert_profile_data
        ));
    }

    public function actionExpertCompletedServiceDetails()
    {
        $invoice_id = base64_decode(Yii::app()->request->getParam('ServiceId'));
        $session_for_login = Yii::app()->session['expert_login_token'];
        //$profile_data = Generic::getExpertProfileData($session_for_login);
        $service_details = Generic::getServiceDetailsUsingServiceId($invoice_id);
        //Generic::_setTrace($service_details);
        //die;
        $profile_data = Generic::getProfileData($service_details[0]['service_taker_id']);
        $expert_name = $service_details[0]['expert_name'];


        $this->render('expert-completed-order-details',array(
            'profile_data' => $profile_data,
            'service_details'=> $service_details,
            'expert'=> $expert_name
        ));
    }

    public function actionUserOrderHistory()
    {
        $session_for_login = Yii::app()->session['user_login_token'];
        $profile_data = Generic::getProfileData($session_for_login);
        $service_details = Generic::getServiceDetailsUsingProfileId($profile_data['id'],"Completed");

        $this->render('completed-services',array(
            'profile_data' => $profile_data,
            'service_details'=> $service_details
        ));
    }

    public function actionUserPurchaseItems()
    {
        $session_for_login = Yii::app()->session['user_login_token'];
        $profile_data = Generic::getProfileData($session_for_login);
        $shop_details = Generic::getProductTransactionDetailsUsingProfileId($profile_data['id'],"");
        //Generic::_setTrace(array_filter($shop_details));
        $shop_details = array_reverse($shop_details);
        $this->render('purchase-items',array(
            'profile_data' => $profile_data,
            'shop_details'=> array_filter($shop_details)
        ));
    }


    public function actionProductDetails()
    {
        $invoice_id = base64_decode(Yii::app()->request->getParam('InvoiceId'));
        $session_for_login = Yii::app()->session['user_login_token'];
       // $profile_data = Generic::getProfileData($session_for_login);
        $shop_details = Generic::getProductDetailsUsingInvoiceId($invoice_id);
        $profile_data = Generic::getProfileData($shop_details[0]['product_buyer_id']);
        $this->render('my-product-purchase-details',array(
            'profile_data' => $profile_data,
            'shop_details'=> $shop_details
        ));
    }

    public function actionAdminLoginFormSubmit(){
        $response = array();
        $user_email = trim(yii::app()->request->getParam('user_email'));
        $user_password = Yii::app()->request->getParam('user_password');
        $final_password =md5($user_password);


        if($user_email && $user_password ) {
            $sql = "SELECT * FROM user WHERE email LIKE '$user_email' AND password LIKE '$final_password' ";
            $result = Yii::app()->db->createCommand($sql)->queryRow();

        }

        // Generic::_setTrace($result);

        if($result['password']== $final_password){
            $response['status'] = 'success';
            $response['admin_login_token'] = $result['id'];
            Yii::app()->session['admin_login_token'] = $result['id'];



        } else {
            $response['status'] = 'Wrong Credentials...';

        }
        echo json_encode($response);

    }

}