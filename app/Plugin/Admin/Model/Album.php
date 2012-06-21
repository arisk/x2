<?php
/**
 * Album Model
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property Perm $Perm
 * @property Photo $Photo
 * @property Album $ParentAlbum
 * @property Album $ChildAlbum
 * @property Photo $Photo
 */
App::uses('AdminAppModel', 'Admin.Model');

class Album extends AdminAppModel {

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'name';

    /**
     * Behaviors
     * @var array
     */
    public $actsAs = array('Tree', 'Containable');

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
        'Perm' => array(
            'className' => 'Admin.Perm',
            'foreignKey' => 'permission_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'CoverPhoto' => array(
            'className' => 'Admin.Photo',
            'foreignKey' => 'photo_id',
            'conditions' => '',
            'fields' => array('id', 'file_name', 'file_path', 'title'),
            'order' => ''
        ),
        'ParentAlbum' => array(
            'className' => 'Admin.Album',
            'foreignKey' => 'parent_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasMany associations
     *
     * @var array
     */
    public $hasMany = array(
        'ChildAlbum' => array(
            'className' => 'Admin.Album',
            'foreignKey' => 'parent_id',
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
        'Photo' => array(
            'className' => 'Admin.Photo',
            'foreignKey' => 'album_id',
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
            'parent_id' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                    'message' => __('Parent id must be numeric'),
                    'allowEmpty' => true,
                ),
            ),
            'permission_id' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                    'message' => __('Please select a permission from the list'),
                ),
            ),
            'photo_id' => array(
                'numeric' => array(
                    'rule' => array('numeric'),
                    'message' => __('Photo id must be numeric'),
                    'allowEmpty' => true,
                ),
            ),
            'name' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                    'message' => __('Please enter the name for this album'),
                ),
                'unique' => array(
                    'rule' => array('isUnique'),
                    'message' => __('That album name already exists. Please choose another'),
                    'on'=>'create',
                ),
            ),
            'slug' => array(
                'unique' => array(
                    'rule' => array('isUnique'),
                    'message' => __('Slug must be unique. Please choose another'),
                    'on'=>'create',
                ),
            ),
        ); 
    }
}