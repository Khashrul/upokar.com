<?php
/**
 * Created by PhpStorm.
 * User: Sabuj
 * Date: 7/24/15
 * Time: 10:31 AM
 */

class Generic {
  public static function getResponse(){
      $param = array();
      $search = Yii::app()->request->getParam('search');
      $page = Yii::app()->request->getParam('page');
      $param['search'] = $search;
      $param['page'] = $page;
      return $param;
  }

  public static function _setTrace($param, $debug=true){
      if(is_string($param)){
          print "$param";
      }else{
          print "<pre>";
          print_r($param);
          print "<pre>";
      }
      print"<hr/>";
      if($debug){
          exit();
      }
  }

    /*
  * get visitor ip address
  * @return string $ip
  */
    public static function getUserIP()
    {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        return $ip;
    }




    public static function validateUploadImage($model, $attribute){

            $imageInstance = CUploadedFile::getInstance($model, $attribute);
            return $imageInstance;
        }

    public static function uploadImage($imageInstance, $image_name, $image_path, $width=0, $height=0)
    {
        if($imageInstance and ($imageInstance->getExtensionName())){

            $allowed_image_type = self::getAllowedImage();
            $image_type = $imageInstance->getExtensionName();
            if(!in_array($image_type, $allowed_image_type)){

                return 'Invalid image type';
            }
            $base_path = Yii::app()->basePath."/../uploaded/";
            $image_name = $image_name.".".$image_type;

            $image_thumb = 'thumb-'.$image_name;

            $image_base = $base_path.$image_path."/";
            self::createDirectory($image_base);
            if($imageInstance->saveAs($image_base.$image_name)){
                //Create thumb for grid view
                try{
                    self::thumbGenerator($image_base.$image_name, $image_base.$image_thumb, 85, 75);
                }catch (Exception $ex){}
                if($width > 0){
                    try{
                        self::thumbGenerator($image_base.$image_name, $image_base.$image_name, $width, $height);
                    }
                    catch(Exception $ex){
                        @unlink($image_base.$image_name);
                        Yii::app()->user->setFlash("error", "Image Upload failed! <br />".$ex->getMessage());
                        return false;
                    }
                }
                return $image_name;
            }
        }
        return false;
    }

    public static function getAllowedImage(){
        $image_extensions = array('jpg', 'jpeg', 'png', 'gif','JPG');
        return $image_extensions;
    }

    public static function thumbGenerator($source, $destination,$width=220,$height=180){
        $thumbnail_width = $width;
        $thumbnail_height = $height;
        $thumb_beforeword = "thumb";
        $arr_image_details = getimagesize($source); // pass id to thumb name
        $original_width = $arr_image_details[0];
        $original_height = $arr_image_details[1];
        if ($original_width > $original_height) {
            $new_width = $thumbnail_width;
            $new_height = intval($original_height * $new_width / $original_width);
        } else {
            $new_height = $thumbnail_height;
            $new_width = intval($original_width * $new_height / $original_height);
        }
        $dest_x = intval(($thumbnail_width - $new_width) / 2);
        $dest_y = intval(($thumbnail_height - $new_height) / 2);
        if ($arr_image_details[2] == 1) {
            $imgt = "ImageGIF";
            $imgcreatefrom = "ImageCreateFromGIF";
        }
        elseif ($arr_image_details[2] == 2) {
            $imgt = "ImageJPEG";
            $imgcreatefrom = "ImageCreateFromJPEG";
        }
        elseif ($arr_image_details[2] == 3) {
            $imgt = "ImagePNG";
            $imgcreatefrom = "ImageCreateFromPNG";
        }
        if (isset($imgt)) {
            $old_image = $imgcreatefrom($source);
            $new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
            imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
            return $imgt($new_image, $destination);
        }
    }

    public static function createDirectory($path){
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }
    public static function showImage($folder, $image, $title = '', $thumb = true, $width = 80, $height = 80){
            $image = '<img width="'.$width.'" height="'.$height.'" title="'.$title.'" src="'.self::getImagePath($folder, $image, $thumb).'" alt="'.$title.'" />';
            return $image;
    }

      public static function getImagePath($folder, $image, $thumb = false){
        if($folder) $folder = $folder . '/';
       
             $file = $thumb ? 'thumb-'.$image : $image;
       
            $base_path = Yii::app()->request->getBaseUrl(true)."/uploaded/";
            $path = $base_path.$folder.$file;
          
            return $path;
        
    }
       public static function deleteThumb($thumb, $folder = ''){
        if($folder) $folder = $folder . '/';
            $base_path = Yii::app()->basePath."/../uploaded/";
            $image_base = $base_path.$folder.'/'.$thumb;
            $image_thumb = $base_path.$folder.'/thumb-'.$thumb;
            @unlink(trim($image_base));
            @unlink(trim($image_thumb));
        }

      public static function slugToUrl($slug){
        $replacement = array(',', ' ', '\'', '"', '`', '$', '~', '!', '@', '#', '%', '^', '(', ')', '{', '}', '[', ']', '<', '>', '.', '\\', ';', '?', '*', '=', '|', ':', '/');
        $url = strtolower(str_replace($replacement, '-', $slug));

        $url = str_replace('&', '-and-', $url);

        $url = preg_replace('/[\-]+/', '-', $url);

        $url = trim($url, ' -');

        return $url;
    }


    public static function getAllowedFile(){
        $file_extensions = array('jpg', 'jpeg', 'png', 'gif','zip','pdf','msword');
        return $file_extensions;
    }


    public static function sendMail($content, $subject, $to, $from="payallportal.com <support@payallportal.com>", $debug = false,$cc=false, $bcc = false ,$style='')
    {

        if (isset($content) && gettype($content) == 'array') {
            $content = json_encode($content);
        }

        $body = "<!DOCTYPE html>
                <html>
                <head>
                    <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                    <meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0' />
                    <title>$subject</title>
                    <!-- Facebook sharing information tags -->
                    <meta property='og:title' content='' />
                    <style type='text/css'>
                        body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none;}

                         table {border-spacing:0;}
                        table td {border-collapse:collapse;}
                        p {margin:0; padding:0; margin-bottom:0;}
                        body, #body_style {width:100% !important; min-height:1000px;  background-color:#f0f0f0; }
                        /* Target mobile devices. */
                        /* @media only screen and (max-device-width: 639px) { */
                        @media only screen and (max-width: 639px) {
                            body[yahoo] .hide {display:none !important;}
                            body[yahoo] .table {width:320px !important;}
                            body[yahoo] .innertable {width:280px !important;}
                            /* Resize hero image at smaller screen sizes. */
                            body[yahoo] .heroimage {width:280px !important; height:100px !important;}
                            /* Resize page shadow at smaller screen sizes. */
                            body[yahoo] .shadow {width:280px !important; height:4px !important;}
                            /* Collapse cells at smaller screen sizes. */
                            body[yahoo] .collapse-cell {width:320px !important;}
                            /* Range social icons left at smaller screen sizes. */
                            body[yahoo] .social-media img {float:left !important; margin:0 1em 0 0 !important;}
                        }
                        /* Target tablet devices. */
                        /* @media only screen and (min-device-width: 640px) and (max-device-width: 1024px) { */
                        @media only screen and (min-width: 640px) and (max-width: 1024px) {
                        }
                        img {display:block; border:none; outline:none; text-decoration:none;}
                        /* Remove spacing around Outlook 07, 10 tables */
                        table {border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;}
                    </style>
                    $style
                </head>
                <body style='width:650px !important; margin:0 auto; height:600px; padding:25px 15px !important; color: #222; line-height:27px;'  yahoo='fix'>
                    <div id='body_style'>
                         $content
                    </div>
                </body>
                </html>";


        // To send HTML mail, the Content-type header must be set
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: ' . $from . "\r\n";
        if ($cc) {
            $headers .= 'Cc: ' . $cc . " \r\n";
        }
        if ($bcc) {
            $headers .= 'Bcc: ' . $bcc . " \r\n";
        }


        // sending mail
        if (@mail($to, $subject, $body, $headers, '-f support@bdads24.com')) {

            return true;
        }
       return false;
    }

    public static function getAllMerchantName(){
        $all_merchant = array();

        try{

            $sql='SELECT DISTINCT( merchant_name) AS merchant_name FROM tbl_merchant_register WHERE group_id != 1';
            $connection=Yii::app()->db;
            $command=$connection->createCommand($sql);
            $all_merchant=$command->queryAll();

       /*   $all_merchant = TblMerchantRegister::model()->findAll(array(
                'select'=>'merchant_name',
                'distinct'=>true,
            ));*/

        }catch (Exception $ex){
            Generic::_setTrace($ex->getMessage());
        }

        return $all_merchant;
    }

   public static function getMerchantIdFromMerchantName($merchant_name){
       $result = Yii::app()->db->createCommand()
           ->select('merchant_id')
           ->from('tbl_merchant_register')
           ->where('merchant_name=:merchant_name', array(':merchant_name' => $merchant_name))
           ->queryAll();

       return $result;

   }

    public static function getGeoInfo($user_ip){
       // $user_ip = '';
        $geoinfo= unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$user_ip));
        if(!$geoinfo){
            return '';
        }
        return $geoinfo;
    }

    public static function writeCookie($cookie_name, $data, $merge = true, $expire_hours = 24, $force_empty = false){
        if($merge){
            $old_data = array();
            if(isset(Yii::app()->request->cookies[$cookie_name]) && Yii::app()->request->cookies[$cookie_name]){
                $old_data = @unserialize(Yii::app()->request->cookies[$cookie_name]);
            }

            if(is_array($old_data) && !empty($old_data)){
                $cookie_data = array_merge($old_data,$data);
            }
            else{
                $cookie_data = $data;
            }
        }
        else{
            $cookie_data = $data;
        }

        if($force_empty || !empty($cookie_data)){
            if(is_array($cookie_data)){
                $cookie_data = serialize($cookie_data);
            }

            $cookie = new CHttpCookie($cookie_name, $cookie_data);
            if($expire_hours > 0){
                $expire = 60*60*$expire_hours;
                $cookie->expire = time()+$expire;
                $cookie->path = "/";
            }
            else{
                $cookie->expire = 0;
            }
            Yii::app()->request->cookies[$cookie_name] = $cookie;
        }
    }

    public static function isAllowedImageExtensions($extension){
        $image_extensions = self::getAllowedImage();
        $extension_to_lower = mb_strtolower($extension, 'UTF-8');
        return in_array($extension_to_lower, $image_extensions);
    }

    public static function getAllLocations(){
          return array(
         'select_division' => 'Select Division',
         'khulna'=>'Khulna',
         'dhaka'=>'Dhaka',
         'rajshahi'=>'Rajshahi',
         'barishal'=>'Barishal',
         'sylhet'=>'Sylhet'

     );
    }
    public static function getAllLocationsForSelectedDivision($selected_division)
    {

        $ad_location_list = array(
            'khulna' => array(
                'Jessore' => 'Jessore',
                'Bagerhat' => 'Bagerhat',
            ),

            'dhaka' => array(
                'Dhaka' => 'Dhaka',
                'Gopalgonj' => 'Gopalgonj',

            ),
            'rajshahi' => array(
                'Rajshahi' => 'Rajshahi',

            ),
        );
        $selected_location = array();
        foreach ($ad_location_list as $key => $value) {
            if ($key === $selected_division) {
                $selected_location[] = $value;
            }
        }

        return $selected_location;

    }


    public static function getAllSubServiceForSelectedParentService($parent_service_id)
    {
       // Generic::_setTrace($parent_service_id);
        $service_list = array(
            '1' => array(
                '1' => 'Desktop Software Installation / Up-gradation Service',
                '2' => 'Desktop Hardware Related Services',
                '3' => 'Laptop/Note Book Software Related Solutions',
                '4' => 'Laptop/Notebook Hardware Related Solutions',
                '5' => 'Virus Removal & Cleanup',
                '6' => 'Printer and Scanner Setup',
                '7' => 'Computer Tune-up',
                '8' => 'WiFi and Network Connectivity',
            ),

            '2' => array(
                '1' => 'IPS/UPS Installation and Servicing ',
                '2' => 'LCD/LED TV Servicing',
                '3' => 'Doorbell Servicing',
                '4' => 'Doorbell Servicing',

            ),
            '3' => array(
                '1' => 'CCTV Camera Installation',
                '2' => 'CCTV Camera Repair/Replacement',
                '3' => 'CCTV Camera Online View Configuration',
                '4' => 'Access Control Installation',
                '5' => 'Fire and Alarming System',


            ),
        );
        //Generic::_setTrace($service_list);
        $selected_service = array();
        foreach ($service_list as $key => $value) {

            if ($key == $parent_service_id) {

                $selected_service[] = $value;

            }

        }

        return $selected_service;

    }


    public static function getAllParentServices(){

        return array(
            '0' => 'Select Services',
            '1'=>'Computer Services',
            '2'=>'Home & Office Appliance',
            '3'=>'Digital Security Surveillance',
        );
    }

    public static function getAllProductsCategory(){

        return array(

            '0'=>'Power And Electronics',
            '1'=>'Security And Safety',
            '2'=>'Computer And Networking',
            '3'=>'Smart Gadgets',
        );
    }



    public static function getAllSubServiceForShops($category_name)
    {

        $sub_category_list = array(
            'Power And Electronics' => array(
                '1' => 'Lighting',
                '2' => 'Switching',
                '3' => 'Power Cables',
                '4' => 'Small Appliances',
                '5' => 'Kitchen Appliances',
                '6' => 'Fan And Cooler',
                '7' => 'Tools And Others',

            ),

            'Security And Safety' => array(
                '1' => 'CCTV Camera',
                '2' => 'Fire And Alarming System',
                '3' => 'Time Attendance And Access Control',
                '4' => 'X-Ray And Baggage Scanner',
                '5' => 'Multi-Video Door Phone',
                '6' => 'PABX And IP Phone System',
                '7' => 'Tools And Accessories',

            ),
            'Computer And Networking' => array(
                '1' => 'Mouse And Keyboard',
                '2' => 'Switch And Router',
                '3' => 'Pendrive And Harddisk',
                '4' => 'Headphone And Speaker',
                '5' => 'Software And Security',
                '6' => 'Office Equipment',
                '7' => 'Power And Others',


            ),
            'Smart Gadgets' => array(
                '1' => 'Smart Watch',
                '2' => 'Backpack',
                '3' => 'Mobile Accessories',
                '4' => 'Mens World',
                '5' => 'Womens World',

            ),
        );

        $selected_category = array();
        foreach ($sub_category_list as $key => $value) {

            if ($key == $category_name) {
                $selected_category[] = $value;
            }
        }

        return $selected_category;

    }




    public static function getProfileData($token)
    {
        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('user_register')
            ->where('id=:id', array(':id' => $token))
            ->queryRow();

        return $result;
    }

   public static function getAllServices(){
       $criteria = new CDbCriteria();
       $criteria->select = '*';
       $result = Yii::app()->db->commandBuilder->createFindCommand(Services::model()->tableName(), $criteria)->queryAll();
       return $result;
   }

    public static function getAllServiceIdUsingServiceSlug($service_slug){
        $arr = array ('1' => 'computer-services', '2' => 'home-and-office-appliance','3' => 'digital-security-survellience' );
        $key = array_search ($service_slug, $arr);
        return $key;
    }

    public static function getSubServiceDetails($service_id)
    {

        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('sub_service')
            ->where('parent_service_id=:parent_service_id', array(':parent_service_id' => $service_id))
            ->queryAll();

        return $result;
    }
    public static function getSubServiceName($slug)
    {
        $arr = array ('Computer Services' => 'computer-services', 'Home & Office Appliance' => 'home-and-office-appliance','Digital Security Survellience' => 'digital-security-survellience' );
        $name = array_search ($slug, $arr);
        return $name;

    }
    public static function getSubServiceId($service_id)
    {
        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('sub_service')
            ->where('parent_service_id=:parent_service_id', array(':parent_service_id' => $service_id))
            ->queryRow();

        return $result;
    }

    public static function getSubServiceDetailsUsingSlug($sub_slug)
    {
        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('sub_service')
            ->where('sub_service_slug=:sub_service_slug', array(':sub_service_slug' => $sub_slug))
            ->queryAll();

        return $result;
    }

   public static function getTagDetailsUsingID($parent_service_id,$sub_service_id){

       $result = Yii::app()->db->createCommand()
           ->select('*')
           ->from('tag_details')
           ->where('parent_service_id=:parent_service_id', array(':parent_service_id' => $parent_service_id))
           ->andWhere('sub_service_id=:sub_service_id',array(':sub_service_id' => $sub_service_id))
           ->queryAll();

       return $result;

   }


    public static function getExpertDetailsUsingID($parent_service_id){

        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('expert_details')
            ->where('parent_service_id=:parent_service_id', array(':parent_service_id' => $parent_service_id))

            ->queryAll();

        return $result;

    }


   public static function getServiceDetailsUsingProfileId($service_taker_id,$status=""){
       $connection = Yii::app()->db;
       $data_command = $connection->createCommand()
         ->select('*')
         ->from('service_transaction_details')
         ->where('service_taker_id=:service_taker_id', array(':service_taker_id' => $service_taker_id))
         ->order('id DESC');
         if($status){
             $data_command->andWhere('service_status = :service_status', array(':service_status' => $status));
         }

       $data_result = $data_command->queryAll();
       return $data_result;

 }


    public static function getServiceDetailsUsingInvoiceId($invoice_id){

        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('service_transaction_details')
            ->where('invoice_id=:invoice_id', array(':invoice_id' => $invoice_id))
            ->queryAll();

        return $result;

    }

    public static function getProductDetailsUsingInvoiceId($invoice_id){

        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('shop_transaction_details')
            ->where('invoice_id=:invoice_id', array(':invoice_id' => $invoice_id))
            ->queryAll();

        return $result;

    }

    public static function getAllProductsForHomePage(){

        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('products')
            ->queryAll();

        return $result;

    }


    public static function getCategoryProductsUSingCategorySlug($sub_category_slug){

        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('products')
            ->where('sub_category_slug=:sub_category_slug', array(':sub_category_slug' => $sub_category_slug))
            ->queryAll();

        return $result;

    }



    public static function getIndividualProductsUSingProductsId($product_id){

        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('products')
            ->where('id=:id', array(':id' => $product_id))
            ->queryAll();

        return $result;

    }


    public static function getServiceDetailsUsingServiceId($service_id){

        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('service_transaction_details')
            ->where('id=:id', array(':id' => $service_id))
            ->queryAll();

        return $result;

    }

    public static function vatCalculator($price,$vat){

            $price_with_vat=0;
            //$price_with_vat = $price + ($vat*($price/100));
            $price_with_vat =  ($vat*($price/100));
            $price_with_vat = round($price_with_vat, 2);
            return $price_with_vat;


    }


    public static function convertNumber($number)
    {
        list($integer, $fraction) = explode(".", (string) $number);

        $output = "";

        if ($integer{0} == "-")
        {
            $output = "Negative ";
            $integer    = ltrim($integer, "-");
        }
        else if ($integer{0} == "+")
        {
            $output = "Positive ";
            $integer    = ltrim($integer, "+");
        }

        if ($integer{0} == "0")
        {
            $output .= "Zero";
        }
        else
        {
            $integer = str_pad($integer, 36, "0", STR_PAD_LEFT);
            $group   = rtrim(chunk_split($integer, 3, " "), " ");
            $groups  = explode(" ", $group);

            $groups2 = array();
            foreach ($groups as $g)
            {
                $groups2[] = Generic::convertThreeDigit($g{0}, $g{1}, $g{2});
            }

            for ($z = 0; $z < count($groups2); $z++)
            {
                if ($groups2[$z] != "")
                {
                    $output .= $groups2[$z] . Generic::convertGroup(11 - $z) . (
                        $z < 11
                        && !array_search('', array_slice($groups2, $z + 1, -1))
                        && $groups2[11] != ''
                        && $groups[11]{0} == '0'
                            ? " And "
                            : ", "
                        );
                }
            }

            $output = rtrim($output, ", ");
        }

        if ($fraction > 0)
        {
            $output .= " Point";
            for ($i = 0; $i < strlen($fraction); $i++)
            {
                $output .= " " . Generic::convertDigit($fraction{$i});
            }
        }

        return $output;
    }

    public static function convertGroup($index)
    {
        switch ($index)
        {
            case 11:
                return " Decillion";
            case 10:
                return " Nonillion";
            case 9:
                return " Octillion";
            case 8:
                return " Septillion";
            case 7:
                return " Sextillion";
            case 6:
                return " Quintrillion";
            case 5:
                return " Quadrillion";
            case 4:
                return " Trillion";
            case 3:
                return " Billion";
            case 2:
                return " Million";
            case 1:
                return " Thousand";
            case 0:
                return "";
        }
    }

    public static function convertThreeDigit($digit1, $digit2, $digit3)
    {
        $buffer = "";

        if ($digit1 == "0" && $digit2 == "0" && $digit3 == "0")
        {
            return "";
        }

        if ($digit1 != "0")
        {
            $buffer .= Generic::convertDigit($digit1) . " Hundred";
            if ($digit2 != "0" || $digit3 != "0")
            {
                $buffer .= " And ";
            }
        }

        if ($digit2 != "0")
        {
            $buffer .= Generic::convertTwoDigit($digit2, $digit3);
        }
        else if ($digit3 != "0")
        {
            $buffer .= Generic::convertDigit($digit3);
        }

        return $buffer;
    }

    public static function convertTwoDigit($digit1, $digit2)
    {
        if ($digit2 == "0")
        {
            switch ($digit1)
            {
                case "1":
                    return "Ten";
                case "2":
                    return "Twenty";
                case "3":
                    return "Thirty";
                case "4":
                    return "Forty";
                case "5":
                    return "Fifty";
                case "6":
                    return "Sixty";
                case "7":
                    return "Seventy";
                case "8":
                    return "Eighty";
                case "9":
                    return "Ninety";
            }
        } else if ($digit1 == "1")
        {
            switch ($digit2)
            {
                case "1":
                    return "Eleven";
                case "2":
                    return "Twelve";
                case "3":
                    return "Thirteen";
                case "4":
                    return "Fourteen";
                case "5":
                    return "Fifteen";
                case "6":
                    return "Sixteen";
                case "7":
                    return "Seventeen";
                case "8":
                    return "Eighteen";
                case "9":
                    return "Nineteen";
            }
        } else
        {
            $temp = Generic::convertDigit($digit2);
            switch ($digit1)
            {
                case "2":
                    return "Twenty-$temp";
                case "3":
                    return "Thirty-$temp";
                case "4":
                    return "Forty-$temp";
                case "5":
                    return "Fifty-$temp";
                case "6":
                    return "Sixty-$temp";
                case "7":
                    return "Seventy-$temp";
                case "8":
                    return "Eighty-$temp";
                case "9":
                    return "Ninety-$temp";
            }
        }
    }

    public static function convertDigit($digit)
    {
        switch ($digit)
        {
            case "0":
                return "Zero";
            case "1":
                return "One";
            case "2":
                return "Two";
            case "3":
                return "Three";
            case "4":
                return "Four";
            case "5":
                return "Five";
            case "6":
                return "Six";
            case "7":
                return "Seven";
            case "8":
                return "Eight";
            case "9":
                return "Nine";
        }
    }


    public static function getRatingsAndReviews($service_name){


        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('review_n_ratings')
            ->where('service_name=:service_name', array(':service_name' => $service_name))
            ->order('id DESC')

            ->queryAll();

        return $result;

    }


    public static function getRatingsAndReviewsUsingProductsId($product_id){


        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('product_review_ratings')
            ->where('products_id=:products_id', array(':products_id' => $product_id))
            ->order('id DESC')

            ->queryAll();

        return $result;

    }







    public static function getCategoryProductsUsingCategorySlugs($category_slug,$limit = 1){


        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('products')
            ->where('products_category_slug=:products_category_slug', array(':products_category_slug' => $category_slug))
            ->limit($limit)
            ->queryAll();

        return $result;

    }


    public static function getRelatedProducts($products_id, $products_category_slug)
    {
        //Generic::_setTrace($products_id,false);
       // Generic::_setTrace($products_category_slug);

        $table = "products";
        $connection = Yii::app()->db;
        $data_command = $connection->createCommand()
            ->select("*")
            ->from($table)
            ->where('products_category_slug=:products_category_slug', array(':products_category_slug' => $products_category_slug))
            ->limit(6)
            ->andwhere(array('not in', 'id', $products_id));

        $data_result = $data_command->queryAll();
        return $data_result;
    }


    /*
 * function getAdDetailsFromAdTable
 * $param int ad_id
 * @return result
 */
    public static function getProductDetailsFromProductTable($products_id)
    {
        $connection = Yii::app()->db;;
        $data_result = $connection->createCommand()
            ->select("*")
            ->from('products')
            ->where('id=:id', array(':id' => $products_id))
            ->queryRow();

        return $data_result;
    }

    public static function getExpertProfileData($token)
    {
        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('expert_details')
            ->where('id=:id', array(':id' => $token))
            ->queryRow();

        return $result;
    }
    public static function getExpertExpertise($parent_service_id)
    {
        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('services')
            ->where('id=:id', array(':id' => $parent_service_id))
            ->queryRow();

        return $result;
    }

    public static function getAllTypeServiceUsingExpertName($expert_name,$service_status)
    {
       // $service_status = "Pending";
        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('service_transaction_details')
            ->where('expert_name=:expert_name', array(':expert_name' => $expert_name))
            ->andWhere('service_status=:service_status',array(':service_status' => $service_status))
            ->queryAll();

        return $result;
    }


    public static function getNextServiceId($current_service_id,$expert_name)
    {
        $service_status = "Pending";
        $result = Yii::app()->db->createCommand()
            ->select('id')
            ->from('service_transaction_details')
            ->where('expert_name=:expert_name', array(':expert_name' => $expert_name))
            ->andWhere('service_status=:service_status',array(':service_status' => $service_status))
            ->andWhere('id > :id',array(':id' => $current_service_id))
            ->queryRow();

        return $result;
    }


    public static function getExpertDetailsUsingExpertID($id)
    {

        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('expert_details')
            ->where('id=:id', array(':id' => $id))
            ->queryRow();

        return $result;
    }


    public static function getShopDetailsUsingCategorySlug($category_slug)
    {
        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('shop_category')
            ->where('category_slug=:category_slug', array(':category_slug' => $category_slug))
            ->queryAll();

        return $result;
    }


    public static function getProductTransactionDetailsUsingProfileId($service_taker_id,$status=""){

        $invoice_id_array = Yii::app()->db->createCommand()
            ->selectDistinct('invoice_id')
            ->from('shop_transaction_details')
            ->where('product_buyer_id=:product_buyer_id', array(':product_buyer_id' => $service_taker_id))
            ->queryAll();
          //Generic::_setTrace($invoice_id_array);

        $data_result=array();

        foreach($invoice_id_array as  $single_array){

             $connection = Yii::app()->db;
             $data_command = $connection->createCommand()
                 ->select('*')
                 ->from('shop_transaction_details')
                 ->where('product_buyer_id=:product_buyer_id', array(':product_buyer_id' => $service_taker_id))
                 ->andWhere('invoice_id=:invoice_id',array(':invoice_id' => $single_array['invoice_id']))
                 ->order('id DESC');
             if($status){
                 $data_command->andWhere('status = :status', array(':status' => $status));
             }

             $result = $data_command->queryRow();

            array_push($data_result,$result);


        }


        return $data_result;

    }

    public static function getAllShopData()
    {

        $invoice_id_array = Yii::app()->db->createCommand()
            ->selectDistinct('invoice_id')
            ->from('shop_transaction_details')
            ->queryAll();


        $data_result=array();


        foreach($invoice_id_array as  $single_array){
            //Generic::_setTrace($single_array);

            $connection = Yii::app()->db;
            $data_command = $connection->createCommand()
                ->select('*')
                ->from('shop_transaction_details')
                ->Where('invoice_id=:invoice_id',array(':invoice_id' => $single_array['invoice_id']))
                ->order('id DESC');

            $result = $data_command->queryRow();

            array_push($data_result,$result);


        }


        return $data_result;
    }

    public static function getAllServiceData()
    {
        $result = Yii::app()->db->createCommand()
            ->select('*')
            ->from('service_transaction_details')
            ->order('id DESC')
            ->queryAll();

       // Generic::_setTrace($result);
        return $result;
    }





}