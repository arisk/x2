<?php
/**
 * Page Model
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 */
class Page extends AppModel{

    /**
     * Display field
     *
     * @var string
     */
    public $displayField = 'title';

    /**
     * Validation rules
     *
     * @var array
     */
    public $validate;
    
    public $actsAs = array(
        'Translate' => array(
            'title', 'html',
        )
    );
    
    public function __construct($id = false, $table = null, $ds = null){
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'title' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                    'message' => __('Title cannot by empty'),
                    'allowEmpty' => false,
                    'required' => true,
                ),
            ),
            'html' => array(
                'notempty' => array(
                    'rule' => array('notempty'),
                    'message' => __('HTML Field cannot be empty'),
                    'allowEmpty' => false,
                    'required' => true,
                ),
            ),
        );
    }
}