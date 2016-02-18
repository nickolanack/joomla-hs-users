<?php
/**
 *
 * The author is Hayato Sugimoto. https://hs-shelf.com
 *
 * @version		1.0.2
 * @package     Joomla.site
 * @subpackage  com_hs_users
 * @copyright   Copyright (C) 2012 Hayato Sugimoto. All rights reserved.
 * @license     GNU / GPL version 2 or later
 */
defined('_JEXEC') or die();

// require_once JPATH_COMPONENT.'/controller.php';

/**
 * Profile controller class for Users.
 *
 * @package Joomla.Site
 * @subpackage com_hs_users
 */
class Hs_usersControllerAuthentications extends JControllerLegacy {

    function endpoint() {
        jimport('hs.user.lib.Hybrid.Auth');
        jimport('hs.user.lib.Hybrid.Endpoint');
        // require_once (JPATH_COMPONENT_SITE . '/lib/Hybrid/Auth.php');
        // require_once (JPATH_COMPONENT_SITE . '/lib/Hybrid/Endpoint.php');
        
        Hybrid_Endpoint::process();
    }

    /*
     * New connection
     *
     *
     *
     */
    public function authenticatewith() {
        // check token
        JSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));
        
        $params = JComponentHelper::getParams('com_hs_users');
        
        $app = JFactory::getApplication();
        
        //
        $user = JFactory::getUser();
        
        $returnUrlRaw = JRequest::getVar('return', null);
        $returnUrl = JUri::base();
        if (isset($returnUrlRaw)) {
            $returnUrl = base64_decode($returnUrlRaw);
        }
        
        file_put_contents(__DIR__ . '/.log', $returnUrl . "\n\n", FILE_APPEND);
        
        if (! $user->guest) {
            $msg = JText::_('COM_HS_USERS_ERROR_YOU_ALREADY_LOGINED');
            
            file_put_contents(__DIR__ . '/.log', 'redirect guest' . "\n\n", FILE_APPEND);
            $app->redirect(JRoute::_($returnUrl), $msg);
            return;
        }
        
        // require_once JPATH_COMPONENT_SITE . '/helpers/config.php';
        // require_once JPATH_COMPONENT_SITE . '/lib/Hybrid/Auth.php';
        jimport('hs.user.lib.hybrid_config');
        jimport('hs.user.lib.Hybrid.Auth');
        
        try {
            $hybridauth_config = HsUserHybridConfig::getConfig();
            $providers = HsUserHybridConfig::getActiveProviders();
            
            // TODO CHECK PROVIDER NAME
            $provider = JRequest::getVar('provider');
            if (! in_array($provider, $providers)) {
                JError::raiseWarning(404, JText::_('COM_HS_USERS_ERROR_PROVIDER_WAS_NOT_FOUND'));
                return false;
            }
            
            // create an instance for Hybridauth with the configuration file path as parameter
            $hybridauth = new Hybrid_Auth($hybridauth_config);
            
            $options = array();
            // FIXME
            // Exception for OpenID
            if ($provider == 'openid') {
                $opi = JRequest::getVar('identifier', null);
                
                if (empty($opi) || mb_strlen($opi) < 0) {
                    JError::raiseWarning(404, JText::_('COM_HS_USERS_ERROR_OPENID_IDENTIFIER_WAS_NOT_FOUND'));
                    return false;
                }
                $options['openid_identifier'] = $params->get('openid_identifier', null);
                
                // FIXME get openid identifier??
                /*
                 * if(empty($options['openid_identifier']) || mb_strlen($options['openid_identifier'])<1){
                 * JError::raiseWarning(404, JText::_('COM_HS_USERS_ERROR_OPENID_IDENTIFIER_WAS_NOT_ENTERED'));
                 * return false;
                 * }
                 */
            }
            
            // try to authenticate the selected $provider
            $adapter = $hybridauth->authenticate($provider, $options);
            
            // $user_profile = $adapter->getUserProfile();
            // grab the user profile
            $user_profile = $adapter->getUserProfile();
        } catch (Exception $e) {
            // Display the recived error
            switch ($e->getCode()) {
                case 0:
                    $error = "COM_HS_USERS_ERROR_HYBRID_AUTH_UNSPECIFIED";
                    break;
                case 1:
                    $error = "COM_HS_USERS_ERROR_HYBRID_AUTH_CONFIGURATION";
                    break;
                case 2:
                    $error = "COM_HS_USERS_ERROR_HYBRID_AUTH_INVALID_PROVIDER";
                    break;
                case 3:
                    $error = "COM_HS_USERS_ERROR_HYBRID_AUTH_UNKNOWN_PROVIDER";
                    break;
                case 4:
                    $error = "COM_HS_USERS_ERROR_HYBRID_AUTH_MISSING_CREDENTIALS";
                    break;
                case 5:
                    $error = "COM_HS_USERS_ERROR_HYBRID_AUTH_FAILED_AUTH";
                    break;
                case 6:
                    $error = "COM_HS_USERS_ERROR_HYBRID_AUTH_FAILED_USER_PROFILE";
                    // $adapter -> logout();
                    break;
                case 7:
                    $error = "COM_HS_USERS_ERROR_HYBRID_AUTH_NO_CONNECTION";
                    // $adapter -> logout();
                    break;
            }
            

            JError::raiseWarning(404, JText::_($error.$e->getMessage()));
            return false;
        }
        
        // load user and authentication models, we will need them...
        $modelAuthentication = $this->getModel("Authentication");
        $modelUser = $this->getModel("User");
        
        // 1 - check if user already have authenticated using this provider before
        $user_id = $modelAuthentication->find_by_provider_uid($provider, $user_profile->identifier);
        
        // 2 - if authentication exists in the database, then we set the user as connected and redirect him to his profile page
        if ($user_id > 0) {
            
            // check the database is up-to-date
            $modelAuthentication->checkUserProfileIsUpToDate($user_profile);
            
            // login
            $modelAuthentication->login($user_id);
            
            file_put_contents(__DIR__ . '/.log', 'redirect login' . "\n\n", FILE_APPEND);
            $app->redirect($returnUrl);
            return;
        }
        
        // checker whether user is existing user or not
        $isNewUser = true;
        
        // if user is not a new user, the var has user info
        $user_info = null;
        
        // 3 - else, here lets check if the user email we got from the provider already exists in our database ( for this example the email is UNIQUE for each user )
        // if authentication does not exist, but the email address returned by the provider does exist in database,
        // then we tell the user that the email is already in use
        // but, its up to you if you want to associate the authentification with the user having the adresse email in the database
        
        // Added option to allow user to login who already have joomla account
        if ($user_profile->email) {
            $user_info = $modelUser->find_by_email($user_profile->email);
            
            // $user_info has something value if the user is already have an account
            if (isset($user_info)) {
                // check multiple login is allowed
                $allowMultipleLogin = (int) $params->get('multiple_connection', 1);
                // if the option is not allowed multiple login. then echo error
                if ($allowMultipleLogin === 0) {
                    $msg = JText::_('COM_HS_USERS_ERROR_DUPLICATED_EMAIL_WAS_FOUND');
                    $this->setRedirect('index.php?option=com_users&view=login', $msg);
                    return;
                }
                
                // add social info to existed user account
                // $modelUser->
                $isNewUser = false;
            }
        }
        
        // 4 - if authentication does not exist and email is not in use, then we create a new user
        $d = new stdClass();
        
        $d->provider = $provider;
        $d->provider_uid = $user_profile->identifier;
        $d->email = $user_profile->email;
        $d->first_name = $user_profile->firstName;
        $d->last_name = $user_profile->lastName;
        $d->display_name = $user_profile->displayName;
        $d->website_url = $user_profile->webSiteURL;
        $d->profile_url = $user_profile->profileURL;
        $d->photo_url = $user_profile->photoURL;
        $d->description = $user_profile->description;
        $d->gender = $user_profile->gender;
        $d->language = $user_profile->language;
        $d->age = $user_profile->age;
        $d->birth_day = $user_profile->birthDay;
        $d->birth_month = $user_profile->birthMonth;
        $d->birth_year = $user_profile->birthYear;
        $d->country = $user_profile->country;
        $d->created_at = JFactory::getDate()->toSql();
        
        // check user is new user or not
        if (isset($user_info) && $isNewUser === false) {
            $d->id = $user_info->id;
            $d->name = $user_info->name;
            $d->user_name = $user_info->username;
        }
        
        $modelUser->prepareRegistration($d);
        
        // save user image
        if (isset($d->photo_url) && mb_strlen($d->photo_url) > 0) {
            $modelSetting = $this->getModel('setting');
            $modelSetting->uploadImage($d->photo_url);
        }
        
        file_put_contents(__DIR__ . '/.log', json_encode(array($user_profile,  __FILE__.' '.__LINE__)) . "\n\n", FILE_APPEND);
        
        // if the email address and display name are given from auth, then skip the registration form
        // e.g. Facebook
        if (mb_strlen($d->email) > 0 && mb_strlen($d->display_name) > 0) {
            
            // check duplicated user name when the user is new user
            if ($isNewUser) {
                $ck = $modelUser->checkUserName();
                // if duplicated user name is found. echo error
                if ($ck === false) {
                    JError::raiseWarning(404, JText::_($modelUser->getError()));
                    return false;
                }
            }
            
            // get password
            $password = $modelUser->getPassword();
            if ($password == false) {
                JError::raiseWarning(404, JText::_($modelUser->getError()));
                return false;
            }
            
            // load language file of com_users
            $lang = JFactory::getLanguage();
            $lang->load('com_users');
            
            $errorMsg = 'SAVE ERROR';
            $ck = true;
            if ($isNewUser) {
                
                // start com_users registration stream
                require_once JPATH_SITE . '/components/com_users/models/registration.php';
                // get model
                $jmodelRegistration = $this->getModel('Registration', 'UsersModel');
                $ck = $jmodelRegistration->register(
                    array(
                        'password1' => $password,
                        'password2' => $password
                    ));
                
                if ($ck == false) {
                    $errorMsg = $jmodelRegistration->getError();
                }
            } else {}
            
            if ($ck === false) {
                JError::raiseWarning(404, $errorMsg);
                return false;
            }
            
            // TODO Should check returned data?
            // if($ck!==false){
            // login
            $forceLogin = (int) $params->get('force_login', 1);
            
            if (isset($forceLogin)) {
                $user_id = $app->getUserState('com_hs_users.user_id', null);
                
                if (isset($user_id)) {
                    
                    // login
                    $ck = $modelAuthentication->login($user_id);
                    file_put_contents(__DIR__ . '/.log', 'no redirect force login' . "\n\n", FILE_APPEND);
                    $app->redirect($returnUrl);
                    return;
                    if ($ck === false) {
                        JError::raiseWarning(404, $modelAuthentication->getError());
                        return false;
                    }
                }
            }
            
            // clear session data
            $app->setUserState('com_hs_users.user_id', null);
            
            // }
            file_put_contents(__DIR__ . '/.log', 'redirect user' . "\n\n", FILE_APPEND);
            $app->redirect($returnUrl);
            return;
        }
        
        // if the email address is not passed from auth, go to registration form
        // e.g. twitter
        $link = 'index.php?option=com_users&view=registration';
        if (isset($returnUrl)) {
            $link .= '&return=' . base64_encode($returnUrl);
        }
        file_put_contents(__DIR__ . '/.log', 'redirect to register' . "\n\n", FILE_APPEND);
        JFactory::getApplication()->redirect($link);
        
        return;
    }

    /**
     * Provide Login function only for authentication....
     */
    private function login($user_id) {
        return;
    }
}
