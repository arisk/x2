<?php
/**
 * Permission Model
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property Album $Album
 */
App::uses('AdminAppModel', 'Admin.Model');

class Perm extends AdminAppModel{

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';
    public $useTable = 'permissions';
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
        'Album' => array(
            'className' => 'Admin.Album',
            'foreignKey' => 'permission_id',
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
    public function __construct($id = false, $table = null, $ds = null){
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'name' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                    'message' => __('Name cannot be empty'),
                ),
            ),
        );
    }
}