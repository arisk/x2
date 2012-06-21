<?php
/**
 * User Model
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 */
App::uses('AdminAppModel', 'Admin.Model');

class User extends AdminAppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'nickname';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate;

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'Photo' => array(
            'className' => 'Admin.Photo',
            'foreignKey' => 'user_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password']) && isset($this->data[$this->alias]['salt'])) {
            $this->data[$this->alias]['password'] = 
                    Security::hash(Security::hash(Security::hash($this->data[$this->alias]['password'], null,
                            $this->data[$this->alias]['salt'])));
        }
        return true;
    }
    public function confirmField($field, $confirmField){
        return array_pop($field) === $this->data[$this->alias][$confirmField];
    }
    /* Remove validators if they're not required */
    public function beforeValidate($options = array()) {
        if(isset($options['remove'])){
            foreach($options['remove'] as $key => $value){
                if(is_array($value)){
                    foreach($value as $v){
                        unset ($this->validate[$key][$v]);
                    }
                }
                else{
                    unset($this->validate[$key][$value]);
                }
            }
        }
        return parent::beforeValidate($options);
    }
    function last_login($id, $ip){        
        if(!is_numeric($id)){
            $this->_stop();
        }
        $this->id = (int)$id;
        $date = date('Y-m-d H:i:s');
        $this->set('last_login_ip', $ip);
        $this->set('last_login' , $date);
        $this->set('last_login_user_agent', env('HTTP_USER_AGENT'));
        $this->save(null, false, array('last_login_ip', 'last_login', 'last_login_user_agent'));
    }
    public function __construct($id = false, $table = null, $ds = null){
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'username' => array(
                'notempty' => array(
                    'rule' => array('notEmpty'),
                    'message' => __('Username cannot be empty'),
                    'on' => 'create',
                    'required' => true,
                ),
                'unique' => array(
                    'rule' => array('isUnique'),
                    'message' => __('That username already exists. Please choose another'),
                    'on'=>'create',
                ),
                'alphaNumeric' => array(
                    'rule' => 'alphaNumeric',
                    'message' => __('Username can consist of letters and numbers only'),
                ),
                'between' => array(
                    'rule' => array('between', 4, 15),
                    'message' => __('Username must be Between 5 and 15 characters'),
                )
            ),
            'email' => array(
                'email' => array(
                    'rule' => array('email'),
                    'message' => __('Please use a valid email'),
                ),
                'notempty' => array(
                    'rule' => array('notEmpty'),
                    'message'=> __('Email cannot be empty'),
                ),
                'unique' => array(
                    'rule' => 'isUnique',
                    'message' => __('The email you entered has already been registered'),
                    'on'=>'create'
                ),
            ),
            'password' => array(
                'notEmpty' => array(
                    'rule' => array('notEmpty'),
                    'message' => __('Password cannot be empty'),
                ),
                'minLength' => array(
                    'rule' => array('minLength', 6),
                    'message' => __('Password must be a minimum of 6 characters long'),
                ),
                'maxLength' => array(
                    'rule' => array('maxLength', 30),
                    'message' => __('Password must be a maximum of 30 characters long'),
                )
            ),
            'confirmPassword' => array(
                'notEmpty' => array(
                    'rule' => array('notEmpty'),
                    'message' => __('Please confirm your password'),
                ),
                'confirm' => array(
                    'rule' => array('confirmField', 'password'),
                    'message' => __('Passwords must match'),
                ),
                'minLength' => array(
                    'rule' => array('minLength', 6),
                    'message' => __('Password must be a minimum of 6 characters long'),
                ),
                'maxLength' => array(
                    'rule' => array('maxLength', 30),
                    'message' => __('Password must be a maximum of 30 characters long'),
                )
            ),
            'admin' => array(
                'boolean' => array(
                    'rule' => array('boolean'),
                ),
            ),
            'nickname' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                    'message' => __('Nickname cannot be empty'),
                ),
                'alphaNumeric' => array(
                    'rule' => 'alphaNumeric',
                    'message' => __('Nickname can consist of letters and numbers only'),
                ),
            ),
            'active' => array(
                'boolean' => array(
                    'rule' => array('boolean'),
                ),
            ),
            'reset_key' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                ),
            ),
        );
    }
}