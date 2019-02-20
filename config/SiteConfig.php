<?php
/**
 * Created by PhpStorm.
 * User: w3engineers
 * Date: 12/17/13
 * Time: 6:32 PM
 */

class SiteConfig {
    const SITE_TITLE = "bdads24.com";
    const TOKEN  =   '';

    /**
     * @param bool $password
     * @param null $slug
     * @param null $force
     * @return string
     */


    /*
    * @param bool $password
    * @param null $slug
    * @param null $force
    * @return string
    */
    public static function getAbsoluteUrl($password=false,$slug = null,$force = null){
        $site_url = self::getSiteUrl($password,$slug,$force);

        $base_url = ltrim(Yii::app()->request->baseUrl,"/");
        if($base_url){
            $site_url .= rtrim($base_url,"/")."/";
        }

        return $site_url;
    }

    public static function getClientTimezone(){
        $geo_ip_info = Generic::geoIpInfo();
        $user_timezone = (isset($geo_ip_info['timezone']) && $geo_ip_info['timezone'])?$geo_ip_info['timezone']:self::SERVER_TIMEZONE;
        return $user_timezone;
    }

    public static function getClientCountry(){
        $geo_ip_info = Generic::geoIpInfo();
        $client_country = (isset($geo_ip_info['country_code']) && $geo_ip_info['country_code'])?strtoupper($geo_ip_info['country_code']):'';
        return $client_country;
    }

    public static function getDisplayCurrency(){


        $geo_ip_info = Generic::geoIpInfo();
        #Generic::_setTrace($geo_ip_info);
        $display_currency = (isset($geo_ip_info['currency_code']) && $geo_ip_info['currency_code'])?$geo_ip_info['currency_code']:self::DISPLAY_CURRENCY;
        return $display_currency;
    }

    public static function getCurrencySymbol(){
        $geo_ip_info = Generic::geoIpInfo();
        $currency_symbol = (isset($geo_ip_info['currency_symbol']) && $geo_ip_info['currency_symbol'])?html_entity_decode($geo_ip_info['currency_symbol']):self::CURRENCY_SYMBOL;
        return $currency_symbol;
    }

    public static function getCurrencyFlag(){
        $geo_ip_info = Generic::geoIpInfo();
        $currency_flag = (isset($geo_ip_info['currency_flag']) && $geo_ip_info['currency_flag'])?html_entity_decode($geo_ip_info['currency_flag']):self::CURRENCY_FlAG;
        return $currency_flag;
    }

    public static function getConversionRate(){
        $geo_ip_info = Generic::geoIpInfo();
        $conversion_rate = (isset($geo_ip_info['conversion_rate']) && $geo_ip_info['conversion_rate'] > 0)?$geo_ip_info['conversion_rate']:self::CONVERSION_RATE;
        return $conversion_rate;
    }

    public static function getDayRangeTo(){
        $day_range_to = round(self::DAY_RANGE_TO/self::getConversionRate());
        return $day_range_to;
    }

    public static function getWeekRangeTo(){
        $week_range_to = round(self::WEEK_RANGE_TO/self::getConversionRate());
        return $week_range_to;
    }

    public static function getMonthRangeTo(){
        $month_range_to = round(self::MONTH_RANGE_TO/self::getConversionRate());
        return $month_range_to;
    }

    public static function bpDisplayCurrency(){
        $bp_currency_list = self::bpCurrencyList();
        if(in_array(self::getDisplayCurrency(),$bp_currency_list)){
            $bp_currency_code = self::getDisplayCurrency();
        }
        else{
            $bp_currency_code = 'USD';
        }
        return $bp_currency_code;
    }

    public static function bpCurrencySymbol(){
        $bp_currency_list = self::bpCurrencyList();
        if(in_array(self::getDisplayCurrency(),$bp_currency_list)){
            $bp_currency_symbol = self::getCurrencySymbol();
        }
        else{
            $bp_currency_symbol = '$';
        }
        return $bp_currency_symbol;
    }

    public static function bpCurrencyList(){
        $bp_currency_list = array(
            'EUR',
            'USD',
            'GBP',
            'AED'
        );
        return $bp_currency_list;
    }


    public static function ihDisplayCurrency(){
        $ih_currency_list = self::ihCurrencyList();
        if(in_array(self::getDisplayCurrency(),$ih_currency_list)){
            $ih_currency_code = self::getDisplayCurrency();
        }
        else{
            $ih_currency_code = 'EUR';
        }
        return $ih_currency_code;
    }

    public static function ihCurrencySymbol(){
        $ih_currency_list = self::ihCurrencyList();
        if(in_array(self::getDisplayCurrency(),$ih_currency_list)){
            $ih_currency_symbol = self::getCurrencySymbol();
        }
        else{
            $ih_currency_symbol = '&euro;';
        }
        return $ih_currency_symbol;
    }

    public static function ihCurrencyList(){
        $ih_currency_list = array(
            'EUR',
            'USD',
            'GBP',
            'PLN'
        );
        return $ih_currency_list;
    }

    public static function  GetInterHomeUsername(){

        $environment = self::checkEnvironment();

        $user_name='CH1000886';
        if($environment==self::production){//production
            $user_name='CH1000886';
        }

        return $user_name;
    }
    public static function  GetInterHomeTest(){
        $environment = self::checkEnvironment();
        $data='https://webservices.interhome.com/quality/partnerV3/WebService.asmx?WSDL';
        if($environment == self::production){//production
            $data='https://webservices.interhome.com/partnerV3/WebService.asmx?WSDL';
        }

        return $data;
    }

    public static function TraveloPoInqueryEmailSend(){
        $environment = self::checkEnvironment();
        if($environment==self::production){//production
            $TRAVELOPO_INQUIRY_EMAIL='enquiries@travelopo.com';
        }elseif($environment==self::local){//local
            $TRAVELOPO_INQUIRY_EMAIL='sab_test@rentalhomes.com,mamunhasan@w3engineers.com';
        }elseif($environment==self::beta){//beta
            $TRAVELOPO_INQUIRY_EMAIL='rukhsana@w3engineers.com,sabbir@w3engineers.com';
        }elseif($environment==self::staging){//staging
            $TRAVELOPO_INQUIRY_EMAIL='mamunhasan@w3engineers.com';
        }

        return $TRAVELOPO_INQUIRY_EMAIL;
    }

    public static function  GetInterHomePassword(){

        $user_name='HOLIDAY';
        return $user_name;
    }
    public static function  GetInterHomeServiceProvider(){

        $user_name='Interhome AG';
        return $user_name;
    }

    public static function getOzUrl(){
        $environment = self::checkEnvironment();
        $oz_url = "http://staging-oz.leftofthedot.com/";
        if($environment==self::local){
            //$oz_url = "http://oz.local.com/";
            $oz_url = "http://staging-oz.leftofthedot.com/";
        }
        elseif($environment==self::production){
            $oz_url = "https://oz.leftofthedot.com/";
        }

        return $oz_url;
    }

    public static function setBpEnvironmentVars(){
        $environment = self::checkEnvironment();
        if($environment != self::production){
            if($environment==self::beta) {
                //UAT Server fot test
                self::$BP_POS='1703e3df36cd9564';
                self::$BP_CURL_QUOTES_URL="https://uat.mybookingpal.com/xml/services/json/reservation/quotes?";
                self::$BP_CURL_BOOKING_URL="https://uat.mybookingpal.com/xml/services/json/reservation/payment/create?";
                self::$BP_CURL_AVAILABLE_CALENDAR="https://uat.mybookingpal.com/xml/services/json/reservation/available_calendar?";
            }
            else{
                //Demo Server fot test
                self::$BP_POS='9ec158f5935ec70f';
                self::$BP_CURL_QUOTES_URL="https://demo.mybookingpal.com/xml/services/json/reservation/quotes?";
                self::$BP_CURL_BOOKING_URL="https://demo.mybookingpal.com/xml/services/json/reservation/payment/create?";
                self::$BP_CURL_AVAILABLE_CALENDAR="https://demo.mybookingpal.com/xml/services/json/reservation/available_calendar?";
            }
        }
    }

    /**
     * check server environment
     * @return string
     */
    public static function checkEnvironment()
    {

	    /*self::$environment = self::local;
        return self::$environment;*/
        //print strstr($_SERVER['SERVER_ADDR'],"127.0.0");exit;
        //return self::staging;
        if(!self::$environment){
            if(strstr($_SERVER['SERVER_ADDR'],"127.0.0")!="" or strstr($_SERVER['SERVER_ADDR'],"::1")!=""){
                self::$environment = self::local;
            }
            elseif(strstr($_SERVER['SERVER_NAME'],"betasolr.rentalhomes.com")!=""){
                self::$environment = self::beta;
            }
            elseif(strstr($_SERVER['SERVER_NAME'],"www.rentalhomes.com")!="" and !strstr($_SERVER['SERVER_ADDR'],"127.0.0") and !strstr($_SERVER['SERVER_ADDR'],"::1")){
                self::$environment = self::production;
            }
            else{
                self::$environment = self::staging;
            }
        }
        //self::$environment = self::staging;
        return self::$environment;
    }

    public static function  GetFlipKeyInquiryURl(){
        $environment = self::checkEnvironment();

        $data='https://ws.demo.flipkey.net/pfe/inquiry/inquiry';
        if($environment==self::production){//production
            $data='https://ws.flipkey.com/pfe/inquiry/inquiry';
        }

        return $data;
    }
    public static function GetFlipKeyInquiryPassword(){
        $environment = self::checkEnvironment();

        $data='FfmrRHdG';
        if($environment==self::production){//production
            $data='wUDfflT6';
        }

        return $data;
    }

    public static function GetUserIP(){
        if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $real_client_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else {
            $real_client_ip = $_SERVER["REMOTE_ADDR"];
        }
        return $real_client_ip;
    }

    public  static  function GetCurrentDate($time_zone=false){
        if($time_zone){
            date_default_timezone_set(self::getClientTimezone());
        }
        return date('Y-m-d');
    }

    public  static  function GetCurrentDateTime($time_zone=false){
        if($time_zone){
            date_default_timezone_set(self::getClientTimezone());
        }
        return date('Y-m-d H:i:s');
    }

    /**
     * pass  2 letter parameter as argument like facebook:fb, Google plus:gp,twitter:tw, pinterest:pn
     * @param $name
     * @return string
     */
    public static function GetSocialLink($name){
        $social_array=array(
            /*'fb'=>'https://www.facebook.com/pages/RentalHomescom/263063717131300?fref=ts',
            'tw'=>'https://twitter.com/findarental',*/
            'fb'=>'https://www.facebook.com/StaysTravel',
            'tw'=>'https://www.twitter.com/StaysTravel',
            'gp'=>'https://plus.google.com/106645346856608276619/posts?hl=en',
            'pn'=>'https://www.pinterest.com/rentalhomescom/',
        );
        $name=strtolower($name);
        if(array_key_exists($name,$social_array)){
            return $social_array[$name];
        }else{
            return '#';
        }
    }
    public static function GetBasePath(){
        return Yii::app()->basePath.'/../';
    }

    public  static function GetDateByGMT(){
        $offset = -8;
        $gmt = ($offset * 60 * 60) + time();
        $gmt_time =gmdate('Y-m-d H:i:s', ($gmt));
        return $gmt_time;
    }
    public static function GetUserToken(){

        $token='';
        if(isset($_COOKIE['oz_u_token'])){
            $token= $_COOKIE['oz_u_token'];
        }
        return $token;
    }

    public static function GetSmallImageLink($url){
        $new_url=$url;
        $ta_tag='tap1.fkimg.com';
        $old_prefix_find='640x480';
        $old_prefix_replace='large';
        $ta_prefix_find='vr-splice-j';
        $ta_prefix_replace='vr-splice-l';
        if(strstr($url,$ta_tag)){
            if(strstr($url,$ta_prefix_find)){
                $new_url=str_replace($ta_prefix_find,$ta_prefix_replace,$url);
            }
        }else{
            if(strstr($url,$old_prefix_find)){
                $new_url=str_replace($old_prefix_find,$old_prefix_replace,$url);
            }
        }
        return $new_url;
    }

    public static function getSiteConfig($key = null){
        $cacheFileName = 'site_config';
        $result = CacheHelper::getValue($cacheFileName,'common',false);

        if(empty($result)){
            try{
                $sql = 'SELECT Key,value FROM settings';
                $result = Yii::app()->db->createCommand($sql)->queryAll();
                if($result){
                    CacheHelper::addValue($cacheFileName,$result,(60*60*24),'common');
                }
            }
            catch(Exception $e){
                Generic::_setTrace($e->getMessage(),false);
            }
        }

        if(isset($result[$key])){
            return $result[$key];
        }

        return $result;
    }
    public static function GetS3UploadedImage($small_image){

        $explode = (explode("/", str_replace("//","",$small_image)));
        if(count($explode)>1){
            unset($explode[0]);
            $final_key = self::$image_key.implode("/", $explode);
            return self::$S3_image_domain."/".$final_key;
        }
        return false;
    }

    public static  function GetUTMForAffiliate($link, $full_url = false){
        $url= html_entity_decode($link);

        $parts = parse_url($url);
        parse_str($parts['query'], $query);
        if(isset($query['utm_term'])){
            $segment_id = (isset($_COOKIE['oz_segment']) && $_COOKIE['oz_segment'] > 0) ? $_COOKIE['oz_segment'] : OzAnalytics::$OZ_DEFAULT_SEGMENT;
            $site_id    =  OzAnalytics::OZ_SITE_ID;
            $oz_token   = (isset($_COOKIE['oz_u_token']))? $_COOKIE['oz_u_token']:'';
            $changed_utm= '';
            $query['utm_term']="site_id:$site_id;segment_id:$segment_id;oz_token:$oz_token";
            $link= '?'.http_build_query($query, '', '&');
        }

        $new_url = html_entity_decode(urldecode($link));
        if($full_url){
            //$full_url = 'https://secure.flipkey.com/listing/free/';
            $full_url = 'https://secure2.flipkey.com/content/list_your_home/';
            $new_url = $full_url.$new_url;
        }

        return $new_url;
    }

    public static function GetAgentUrl($agentName,$agentPassword)
    {
        if(self::checkEnvironment() == self::local){
            $agentUrl = self::$agentLocalUrl.'&email='.$agentName.'&password='.$agentPassword;
        }elseif(self::checkEnvironment() == self::beta){
            $agentUrl = self::$agentBetaUrl.'&email='.$agentName.'&password='.$agentPassword;
        }elseif(self::checkEnvironment() == self::production){
            $agentUrl = self::$agentProductionUrl.'&email='.$agentName.'&password='.$agentPassword;
        }else{
            $agentUrl = self::$agentLocalUrl.'&email='.$agentName.'&password='.$agentPassword;
        }
        return $agentUrl;
    }

    public function actionResetAdminPassword()
    {
        Generic::checkCronToken();
        $password = Yii::app()->request->getQuery('p');
        $password = Yii::app()->request->getQuery('d');
        if($password){
            $encrypted_password = Generic::encryptedString($password);
            $model = User::model()->findByAttributes(array('username'=>'admin'));
            if($model){
                $model->password = $encrypted_password;
                if($model->save()){
                    die($password);
                }
            }
        }
    }
} 