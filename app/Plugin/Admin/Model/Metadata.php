<?php
/**
 * Metadata Model
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property Photo $Photo
 */
App::uses('AdminAppModel', 'Admin.Model');

class Metadata extends AdminAppModel{

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate;

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Photo' => array(
            'className' => 'Photo',
            'foreignKey' => 'photo_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public function __construct($id = false, $table = null, $ds = null){
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'photo_id' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                    'message' => __('Photo id must be numeric'),
                ),
            ),
            'data' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                    'message' => __('Data must not be empty'),
                ),
            ),
        );
    }
}