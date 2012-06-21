<?php
/**
 * Setting Model
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 */
App::uses('AdminAppModel', 'Admin.Model');

class Setting extends AdminAppModel{

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate; 
    
    public function getSettings(){
        $config = Cache::read('app', 'configuration');
        if(!$config){
            $config = $this->find('all', array(
                'fields'=> array('section','name', 'value', 'type'),
            ));
            Cache::write('app', $config, 'configuration');
        }
        return $config;
    }
    public function afterSave($created) {
        Cache::clear(false, 'configuration');
        return parent::afterSave($created);
    }
    public function __construct($id = false, $table = null, $ds = null){
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'name' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                    'message' => __('Name cannot be empty'),
                ),
            ),
            'value' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                    'message' => __('Value cannot be empty'),
                ),
            ),
            'section' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                    'message' => __('Section cannot be empty'),
                ),
            ),
            'type' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                    'message' => __('Type cannot be empty'),
                ),
            ),
        );
    }
}