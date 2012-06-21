<?php
/**
 * Photo Model
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property Album $Album
 * @property Metadata $Metadata
 */
App::uses('AppModel', 'Model');

class Photo extends AppModel{

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

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Album' => array(
            'className' => 'Album',
            'foreignKey' => 'album_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasOne = array(
        'Metadata' => array(
            'className' => 'Metadata',
            'foreignKey' => 'photo_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ),
    );
     
    public $actsAs = array('Containable');
    
    public $filterArgs = array(
        array('name' => 'title', 'type' => 'like'),
        array('name' => 'album_id', 'type' => 'value'),
        array('name' => 'username', 'type' => 'like', 'field' => 'User.username'),
    );
    public function __construct($id = false, $table = null, $ds = null){
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'album_id' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                    'message'=> __('You must select an Album'),
                ),
            ),
            'file_path' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                ),
            ),
            'file_name' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                ),
            ),
            'name' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                ),
            ),
            'type' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                ),
            ),
            'hash' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                ),
            ),
            'title' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                    'message' => __('Title cannot be empty'),
                ),
            ),
            'views' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                    'message' => __('Views must be a number'),
                ),
            ),
            'size' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                    'message' => __('Size must be a number'),
                ),
            ),
            'width' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                    'message' => __('Width must be a number'),
                ),
            ),
            'height' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                    'message' => __('Height must be a number'),
                ),
            ),
        );
    }
}