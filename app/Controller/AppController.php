<?php
/**
 * Application Controller. 
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 */
App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');
App::uses('Sanitize', 'Utility');

class AppController extends Controller {
    const ADMIN = 1;
    const USER = 2;
    const PUB = 3;

    /**
     * Standard Components for all controllers 
     */
    public $components = array(
        'Session',
        'Cookie',
        'Auth',
        'Security',
    );
    /**
     * Standard Helpers for all controllers 
     */    
    public $helpers = array('Session', 'Html', 'Form', 'Menu', 'I18n.I18n');
    
    /**
     * Setup the application
     * @return void
     */
    public function beforeFilter() {
        /* Set the cookie name */
        $this->Cookie->name = 'X2_CONFIG';
        $this->_setupLanguage();
        $this->_setupAuth();
        
        /* Setup Theme */
        $theme = Configure::read('X2.General.Theme');
        if(!empty($theme)){
            $this->theme = $theme;
        }
        /* Disable caching for authenticated users */
        if($this->Auth->user('id')){
            $this->response->disableCache();
        }
        /* TODO: Remove before production */
        //header('Connection: Close');
        
        /* Set the hash globally */
        Security::setHash('sha512');
        
        $this->Security->blackHoleCallback = '_blackhole';
    }
    public function beforeRender(){
        parent::beforeRender();
        /* Set the theme on the fly before rendering */
        if(!empty($this->request->params['named']['t'])){
            $theme = Sanitize::paranoid($this->request->params['named']['t']);
            $this->theme = ucfirst($theme);
        }
    }
    /**
     * TODO: Implement a good blackhole method
     * 
     * @param string $type Type of error
     * @return void
     */
    public function _blackhole($type){
        switch($type){
            case 'csrf':
                $this->redirect(array('plugin'=>null, 'controller'=>'pages', 'action'=>'blackhole'));
                break;
            default:
                break;
        }
    }
    /* 
     * Return the permission of the current user. This function may be removed
     * 
     * @return integer Permission
     */
    protected function getPermission(){
        if($this->Auth->user('admin') === true){
            return self::ADMIN;
        }
        elseif($this->Auth->user('id')){
            return self::USER;
        }
        else{
            return self::PUB;
        }
    }
    /**
     * Check permission of user to a specific album 
     */
    protected function isPermitted($pid, $key = null){
        /* Admin users are permitted everything */
        if($this->Auth->user('admin') === true){
            return true;
        }
        
        /*
         * This was removed since we're not using Auth keys anymore
         * If the user has a key and the permission is not for admin only
        elseif(isset($this->request->params['named']['auth_key']) && $pid !== self::ADMIN &&
                $this->request->params['named']['auth_key'] == $key){
            return true;
        } 
         */
        /* If user permissions is required you must be logged in */
        elseif($pid == self::USER && $this->Auth->user('id')){
            return true;
        }
        /* If the public is allowed */
        elseif($pid == self::PUB){
            return true;
        }
        return false;
    }
    /*
     * This function was moved to the bootstrap file
    private function _setupConfig(){
        $model = new Setting();
        foreach($model->getConfig() as $c){
            $section = $c['Setting']['section'];
            $name = $c['Setting']['name'];
            $type = $c['Setting']['type'];
            if($type == 'checkbox'){
                $value = (bool) $c['Setting']['value'];
            }
            elseif($type == 'int' || $type == 'integer'){
                $value = (int) $c['Setting']['value'];
            }
            else{
                $value = $c['Setting']['value'];
            }
            Configure::write('X2.'.$section.'.'.$name, $value);
        }
    }
    */
    /**
     * Setup the language for the Application
     * 
     * @return void
     */
    private function _setupLanguage(){
        /* Language setup */
        if($this->Cookie->read('language') && !$this->Session->check('Config.language')) {
            $language = $this->Cookie->read('language');
            $this->Session->write('Config.language', $language);
            Configure::write('Config.language', $language);
        }
        elseif(isset($this->request->params['lang']) && 
                $this->request->params['lang'] != $this->Session->read('Config.language')) {
            $language = $this->request->params['lang'];
            $this->Session->write('Config.language', $language);
            $this->Cookie->write('language', $language, false, '30 days');
            Configure::write('Config.language', $language);
        }
    }
    /**
     * Setup Authentication and Authorization for the Application
     * 
     * @return void
     */    
    private function _setupAuth(){
        $this->Auth->loginRedirect = array('plugin'=>null, 'controller'=>'pages', 'action'=>'home');
        $this->Auth->logoutRedirect = array('plugin'=>null, 'controller'=>'users', 'action'=>'login');
        $this->Auth->authenticate = array('SaltForm' => array('scope'=>array('User.active' => 1)));
        $this->Auth->authorize = array('Controller');
        $this->Auth->flash = array(
            'element' => '',
            'key' => 'bad',
            'params' => array()
	);  
    }
    /**
     *  Convenience method for setting flash messages
     * 
     * @param string $message Message to set
     * @param string $key Flash key to use 
     * @return void
     */
    public function setFlash($message, $key='good'){
        $this->Session->setFlash($message, '', array(), $key);
    }
    /** 
     * Authorize users to do it all. All admin functions will go in the Admin plugin
     * 
     * @return boolean True or false depending on if the user is logged in or not.
     */
    public function isAuthorized($user){
        if (isset($user) && !empty($user)) {
            return true;
        }
        return false;
    }
}