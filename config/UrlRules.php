<?php
/**
 * Created by PhpStorm.
 * User: Sabuj
 * Date: 8/11/15
 * Time: 12:11 AM
 */

class UrlRules {

        public static $urlManager = array(
                'urlFormat'=>'path',
                'showScriptName'=>false,
                'rules'=>array(
                    'register'=>'site/Register',



                    'login'=>'site/UserLogin',
                    'logout'=>'site/logout',
                    'user-profile/dashboard'=>'profile/UserProfile',
                    'user-profile/recent-order'=>'profile/UserRecentServices',
                    'user-profile/recent-order/ServiceDetails'=>'profile/ServicesDetails',
                    'user-profile/service-history'=>'profile/UserServiceHistory',
                    'user-profile/order-history'=>'profile/UserOrderHistory',
                    'user-profile/order-history/ServiceDetails'=>'profile/ServicesDetails',
                    'user-profile/purchase-items'=>'profile/UserPurchaseItems',
                    'user-profile/purchase-items/productDetails'=>'profile/ProductDetails',
                    'upokar-admin'=>'site/login',
                    'site-admin'=>'site/adminLogin',
                    'shopping-cart'=>'site/ShoppingCart',
                    'shopping-cart-products'=>'site/ShoppingCartProducts',
                    'checkout'=>'site/Checkout',
                    'products-checkout'=>'site/CheckoutProducts',
                    'insert'=>'site/Insert',
                    'result'=>'site/ShowData',
                    'expert-login'=>'profile/ExpertLogin',
                    'expert-profile/dashboard'=>'profile/ExpertProfile',
                    'expert-profile/new-service'=>'profile/ExpertNewService',
                    'expert-profile/new-service/service-details'=>'profile/ExpertNewServiceDetails',
                    'expert-profile/completed-service'=>'profile/ExpertCompletedService',
                    'expert-profile/completed-service/service-details'=>'profile/ExpertCompletedServiceDetails',
                    'shop-category/<category_slug>'=>'site/ProductsCategory',
                    'shop-category/<category_slug>/<sub_category_slug>'=>'site/Products',
                    'shops-products'=>'site/ProductsDetails',
                    'contact-us'=>'site/ContactUs',

                    '<controller:\w+>/<id:\d+>'=>'<controller>/view',
                    '<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
                    '<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

                   'shops/<products_category_slug>'=>'site/Products',
                    '<slug>'=>'site/Services',
                    '<slug>/<sub_slug>'=>'site/SubServices',

                ),
        );
} 