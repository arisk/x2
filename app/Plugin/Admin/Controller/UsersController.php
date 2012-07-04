<?php
/**
 * Users Controller
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property User $User
 */
App::uses('Sanitize', 'Utility');

class UsersController extends AdminAppController{

    public $helpers = array('Cache', 'Html', 'Form', 'Js' => 'Jquery', 'Time');
    public $components = array('Email');

    /**
     * index method
     *
     * @return void
     */
    public function index(){
        $this->User->recursive = 0;
        $this->paginate = array(
            'limit'=>8,
            'fields'=>array('id', 'username', 'email', 'admin', 'active', 'last_login'),
        );
        $this->set('users', $this->paginate());
        $this->set('title_for_layout', __('User Management'));
    }
    /*
    public function add(){
        if($this->request->is('post')){
            $this->User->create();
            if($this->User->save($this->request->data)){
                $this->setFlash(__('The user has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else{
                $this->setFlash(__('The User could not be saved. Please, try again.'));
            }
        }
        $this->set('title_for_layout', __('Add User'));
    }
    */
    public function queue(){
        $this->paginate = array(
            'limit'=>8,
            'fields'=>array('id', 'username', 'email', 'nickname', 'active', 'last_login'),
        );
        $this->User->recursive = 0;
        $this->set('users', $this->paginate(null, array('active' => 0)));
        $this->set('title_for_layout', __('Approval Queue'));
    }

    public function approve($id = null){
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException();
        }
        if($id){
            $this->User->id = $id;
            if($this->User->saveField('active', 1)){
                $this->setFlash(__('User Activated'));
            }
            else{
                $this->setFlash(__('Could not Activate User'), 'bad');
            }
        }
        $this->redirect(array('action'=>'queue'));
    }

    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($id = null){
        if(empty($id)){
            $id = $this->Auth->user('id');
        }
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $this->User->id = $id;
        if(!$this->User->exists()){
            throw new NotFoundException(__('Invalid user'));
        }
        $user = $this->User->read(null, $id);
        $this->set('user', $user);
        $this->set('title_for_layout', __('View').' :: '.h($user['User']['username']));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($id = null){
        if(empty($id)){
            $id = $this->Auth->user('id');
        }
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $this->User->id = $id;
        $user = $this->User->read();
        if(!$user){
            throw new NotFoundException(__('Invalid user'));
        }
        if($this->request->is('post') || $this->request->is('put')){
            $options = array();
            if(empty($this->request->data['User']['password']) && empty($this->request->data['User']['confirmPassword'])){
                /* Do not validate usernames and passwords if they are empty */
                $options = array('remove' => 
                    array(
                        'password' => array('notEmpty', 'minLength', 'maxLength'),
                        'confirmPassword' => array('notEmpty', 'minLength', 'maxLength', 'confirm'),
                    )
                );
            }
            $this->request->data['User']['salt'] = $user['User']['salt'];
            $this->User->set($this->request->data);
            if($this->User->validates($options)){
                if($this->User->save(null, false)){
                    $this->setFlash(__('The user has been saved'));
                    $this->redirect(array('action' => 'index'));
                }
            }
            //var_dump($this->User->invalidFields());
            $this->setFlash(__('The user could not be saved. Please, try again.'), 'bad');
        }
        else{
            $user = $this->User->read(null, $id);
            unset($user['User']['password']);
            $this->request->data = $user;
        }
        $this->set('title_for_layout', __('View').' :: '.h($user['User']['username']));
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($id){
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        $user = $this->User->read(array('id', 'admin'));
        if(!$user){
            throw new NotFoundException(__('Invalid user'));
        }
        if($user['User']['admin'] == true){
            $this->setFlash(__('I cannot delete admin users for your own protection'), 'warning');
            $this->redirect(array('action' => 'index'));
        }
        elseif($this->User->delete()){
            $this->setFlash(__('User deleted'));
        }
        else{
            $this->setFlash(__('User was not deleted'), 'bad');
        }
        $this->redirect(array('action' => 'index'));
    }
    /**
     * activate method
     *
     * @param string $id
     * @return void
     */
    public function activate($id, $deactivate = false){
       
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException();
        }
        $this->User->id = $id;
        if(!$this->User->exists()){
            throw new NotFoundException(__('Invalid user'));
        }
        if($deactivate){
            $this->User->set('active', false);
            $this->setFlash(__('User Deactivated'));
        }
        else{
            $this->User->set('active', true);
            $this->setFlash(__('User Activated'));
        }
        if(!$this->User->save()){
            $this->setFlash(__('User Could not be activated/deactivated. Please try again'), 'bad');
        }
        $this->redirect(array('action' => 'index'));
    }
}