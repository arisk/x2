<?php
/**
 * Users Controller. 
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property User $User
 */
App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('CakeEmail', 'Network/Email');

class UsersController extends AppController{
    
    /**
     * Components array
     */
    public $components = array('Email');
    
    /**
     * Helpers array
     */
    public $helpers = array('Cache', 'Html', 'Form', 'Js' => 'Jquery', 'Time');
    
    /**
     * Allow all actions 
     * @return void
     */
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('register', 'logout', 'login', 'forgot', 'reset');
    }
    /**
     * Login Function. Allows a user to login.
     *
     * @return void
     */
    function login(){
        /* Disable all caching */
        $this->response->disableCache();
        $this->set('title_for_layout', __('Login'));

        if($this->request->isPost()){
            $this->User->set($this->request->data);
            /* Do not validate unique usernames and min length for login */
            $options = array('remove' => 
                array(
                    'username' => array('unique', 'between'),
                    'password' => array('minLength'),
                )
            );
            if($this->User->validates($options)){
                if($this->Auth->login()){
                    $this->User->last_login($this->Auth->user('id'), $this->request->clientIp());
                    if($this->Session->check('Auth.redirect')){
                        $this->redirect($this->Auth->redirect());
                    }
                    else{
                        $this->redirect(array('plugin'=>null, 'controller'=>'albums', 'action'=>'index'));
                    }
                }
                else{
                    $this->setFlash(__('Invalid username or password. Please try again'), 'bad');
                }
            }
        }
        /* If the User is logged in redirect him back */
        if($this->Session->check('Auth.User')){
            if(!$this->Session->check('Message.bad')){
                $this->setFlash(__('You are already logged in!'), 'warning');
            }
            $this->redirect(array('plugin'=>null, 'controller'=>'pages', 'action'=>'home'));
        }
        $this->set('title_for_layout', __('Login'));
    }
    /**
     * Logout a User. Nothing special here
     *
     * @return void
     */
    function logout(){
        $this->setFlash(__('You have been successfully logged out.'));
        $this->redirect($this->Auth->logout());
    }

    /**
     * Allows for user registration if it has been enabled in the Admin area.
     * If it's the first user in the DB he becomes an admin automatically.
     *
     * @return void
     */
    public function register(){
        App::uses('Model', 'Setting');
        
        /* Is the registration system open? Still allow the first user to register. */
        if(!Configure::read('X2.User.Registration') && !Configure::read('X2.User.First')){
            throw new NotFoundException(__('Page Not Found'));
        }
        if($this->request->is('post')){
            $this->User->create();

            unset($this->request->data['User']['id']);

            $last_login = new DateTime();
            
            if(Configure::read('X2.User.First') === true){
                $this->request->data['User']['admin'] = true;
                $this->request->data['User']['active'] = true;
                $settings = new Setting();
                $first_user = $settings->find('first', array('conditions'=>array('section'=>'User', 'name'=>'First')));
                if(!$first_user){
                    throw new UnexpectedValueException(__('Your configuration table is wrong or corrupt'));
                }
                $settings->create();
                $settings->id = $first_user['Setting']['id'];
                $d = array('Setting'=>array('value'=>false));
                $settings->save($d, false);
            }
            else{
                $this->request->data['User']['admin'] = false;
                if(Configure::read('X2.User.Require_Approval')){
                    $this->request->data['User']['active'] = false;
                }
                else{
                    $this->request->data['User']['active'] = true;
                }
            }
            $this->request->data['User']['signup'] = $last_login->format('Y-m-d H:i:s');
            $this->request->data['User']['salt'] = Security::generateAuthKey();
            $this->request->data['User']['reset_key'] = Security::generateAuthKey();
            $this->request->data['User']['signup_ip'] =
                    $this->request->data['User']['last_login_ip'] =
                    $this->request->clientIp();
            $this->request->data['User']['last_login_user_agent'] =
                    $this->request->data['User']['signup_user_agent'] =
                    env('HTTP_USER_AGENT');;

            if($this->User->save($this->request->data)){
                $this->setFlash(__('Account created successfully. Please wait for approval'));
                $this->redirect(array('controller'=>'pages', 'action' => 'home'));
            }
            else{
                $this->setFlash(__('Account could not be created. Please, try again.'), 'bad');
            }
        }
        $this->set('title_for_layout', __('Register'));
    }

    /**
     * View your own user account. Simple find method.
     *
     * @return void
     */
    public function view(){
        $id = $this->Auth->user('id');
        $this->User->recursive = -1;
        $this->User->id = $id;
        if(!$this->User->exists()){
            throw new NotFoundException(__('Invalid user'));
        }
        $user = $this->User->find('first', 
            array(
                'conditions'=>array('id'=>$id, 'active'=>true),
                'fields'=>array('username','email','nickname','last_login'),
            )
        );
        $this->set('user', $user);
        $this->set('title_for_layout', __('View').' :: '.h($user['User']['username']));
    }

    /**
     * Edit your own user account.
     *
     * @return void
     */
    public function edit(){
        $this->User->recursive = -1;
        $id = $this->Auth->user('id');
        $this->User->id = $id;
        $user = $this->User->find('first', 
            array(
                'conditions'=>array('id'=>$id, 'active'=>true)
            )
        );       
        if(!$user){
            throw new NotFoundException(__('Invalid user'));
        }
        if($this->request->is('post') || $this->request->is('put')){
            if($this->User->save($this->request->data)){
                $this->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'view'));
            }
            else{
                $this->setFlash(__('The user could not be saved. Please, try again.'), 'bad');
            }
        }
        else{
            $this->request->data = $user;
        }
        $this->set('title_for_layout', __('Edit').' :: '.h($user['User']['username']));
    }

    /**
     * @todo Change to deactivate user in the DB or at least provide a setting for it.
     * Delete your own user account.
     *
     * @return void
     */
    public function delete(){
        $id = $this->Auth->user('id');
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        $user = $this->User->find('first', 
            array(
                'conditions'=>array('id'=>$id, 'active'=>true)
            )
        );       
        if(!$user){
            throw new NotFoundException(__('Invalid user'));
        }
        if($user->delete()){
            $this->setFlash(__('User deleted'));
            $this->redirect(array('action' => 'logout'));
        }
        $this->setFlash(__('User was not deleted'), 'bad');
        $this->redirect(array('action' => 'index'));
    }
    /**
     * Forgot Password function
     * 
     * @return void
     */
    public function forgot(){
        if($this->Auth->user('id')){
            $this->setFlash(__('You are already logged in!'), 'info');
            $this->redirect($this->referer());
        }
        if($this->request->is('post')){
            if(isset($this->request->data['User']['user'])){
                $user_or_email = filter_var($this->request->data['User']['user'], FILTER_SANITIZE_STRING);
            }
            $user = $this->User->findByEmailOrUsername($user_or_email, $user_or_email);
            if($user){
                if(!empty($_SERVER['SERVER_NAME'])){
                    $domain = $_SERVER['SERVER_NAME'];
                }
                else{
                    /* This could return something like co.uk instead of example.co.uk */
                    $domain = $this->request->domain();
                }
                $domain = 'nooclear.com';
                $url = Router::url(array('controller'=>'users', 'action'=>'reset', 
                    $user['User']['email'], $user['User']['reset_key']), true);
                $body = "Dear {$user['User']['username']},<br />";
                $body.= 'A request has been made to reset your password.<br />';
                $body.= 'Please follow the link below to reset your password.<br />';
                $body.= '<a href="'.$url.'">Reset Password</a><br />';
                $body.= 'If you did not make this request please ignore the link above and take additional measures to protect your application<br />';
                $from = 'X2@'.$domain;
                $email = new CakeEmail();
                $email->from(array($from => 'X2 Photo'))
                      ->to($user['User']['email'])
                      ->subject('X2 :: Password Reset Intructions')
                      ->send($body);
            }
            $this->setFlash(__('Password reset instructions have been sent to your email'));
        }
        $this->set('title_for_layout', __('Password Recovery'));
    }
    /**
     * Reset a forgotten password
     * 
     * @return void
     */    
    public function reset($email = null , $key = null){
        if($this->Auth->user('id')){
            $this->setFlash(__('You are already logged in!'), 'info');
            $this->redirect($this->referer());
        }
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        $key = filter_var($key, FILTER_SANITIZE_STRING);
        if(!empty($email) && !empty($key)){
            $user = $this->User->findByEmail($email);
            if($user && $user['User']['reset_key'] == $key){
                if($this->request->is('post')){
                    $this->User->id = $user['User']['id'];
                    $this->request->data['User']['id'] = $user['User']['id'];
                    $this->request->data['User']['salt'] = $user['User']['salt'];
                    $this->request->data['User']['reset_key'] = Security::generateAuthKey();
                    if($this->User->save($this->request->data)){
                        $this->setFlash(__('You have successfully changed your password'));
                        $this->redirect(array('action'=>'login'));
                    }
                }
            }
            else{
                $this->setFlash(__('Invalid credentials'), 'bad');
                $this->redirect(array('controller'=>'pages', 'action'=>'index'));
            }
        }
        $this->set('title_for_layout', __('Password Reset'));
    }
    /**
     * Change your own password
     * 
     * @return void
     */
    public function password(){
        $this->User->recursive = -1;
        if($this->request->is('post')){
            $id = $this->Auth->user('id');
            $this->User->id = $id;
            $user = $this->User->find('first', 
                array(
                    'conditions'=>array('id'=>$id),
                    'fields'=>array('id', 'password', 'salt'),
                )
            );
            $currentPassword = Security::hash(Security::hash(Security::hash(
                    $this->request->data['User']['currentPassword'], null, $user['User']['salt'])));
            if($user['User']['password'] != $currentPassword){
                $this->User->invalidate('currentPassword', __('Incorrect Password'));
                return;
            }
            $this->request->data['User']['salt'] = $user['User']['salt'];
            $this->User->set($this->request->data);
            if($this->User->validates()){
                if($this->User->save($this->request->data)){
                    $this->setFlash(__('Password Updated Successfully'));
                }
                else{
                    $this->setFlash(__('Error! Could not Update Password'), 'bad');
                }
            }
        }
        $this->set('title_for_layout', __('Password Reset'));
    }
}