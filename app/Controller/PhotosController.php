<?php
/**
 * Photos Controller. 
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property Photo $Photo
 */
App::uses('AppController', 'Controller');

class PhotosController extends AppController{
    /**
     * Components array
     */
    public $components = array('RequestHandler');
    /**
     * Helpers array
     */
    public $helpers = array('Time', 'Js'=>'JQuery');
    
    /** 
     * This variable needs to be declared for search to work
     */
    public $presetVars = array();
    /**
     * Allow all actions 
     * @return void
     */ 
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('*');
    }
    /**
     * @todo Setting in the DB for pagination limit
     * Show all Photos paginated. Not very useful but whatever.
     *
     * @return void
     */
    public function index(){
        $this->Photo->recursive = 0;
        $this->paginate = array(
            'limit'=>12,
            'contain' => array('Album'=> array('fields'=> array('id', 'permission_id'))),
            'conditions' => array(
                'Photo.published' => true,
                'Album.permission_id >=' => $this->getPermission(),
            ),
            'fields'=>array('id', 'title', 'file_path', 'file_name', 'taken'),
        );
        $this->set('photos', $this->paginate());
        $this->set('title_for_layout', __('Photos'));
    }
    /**
     * Search function. This uses the CakeDC Search plugin.
     * @see https://github.com/CakeDC/search
     *
     * @return void
     */
    public function search(){
        $this->Prg = $this->Components->load('Search.Prg');
        $this->Photo->Behaviors->attach('Search.Searchable');
        $this->Prg->commonProcess();
        
        $this->paginate = array(
            'conditions' => array_merge(
                    array(
                        'Album.permission_id >=' => $this->getPermission(),
                        'Photo.published'=>true,
                    ), 
                    $this->Photo->parseCriteria($this->passedArgs)),
            'limit' => 12,
            'fields' => array('id', 'title', 'file_name', 'file_path', 'taken'),
            'contain' => array('User'=>array('fields'=>array('id', 'username')),
                               'Album'=> array('fields'=> array('id', 'permission_id'))),
        );
        /* This is to populate the data back in the form */
        $this->request->data['Photo'] = $this->passedArgs;
        $this->Photo->Album->recursive = -1;
        $this->Photo->Album->Behaviors->attach('Tree');
        $this->set('albums', $this->Photo->Album->generateTreeList(array('Album.permission_id >=' => $this->getPermission())));
        $this->set('photos', $this->paginate());
        $this->set('title_for_layout', __('Search Photos'));
    }
    /**
     * Load a Photo. Last accessed time and views are updated.
     *
     * @param string $id
     * @return void
     */
    public function view($id){
        $this->Photo->recursive = -1;
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $this->Photo->contain(
            array(
                'Album'=>array(
                    'fields'=>array('id', 'name')
                ),  
            )
        );
        $photo = $this->Photo->find('first', array(
                'conditions'=>array(
                    'Photo.id'=>(int)$id,
                    'Photo.published' => true,
                    'Album.permission_id >=' => $this->getPermission(),
                ),
                'fields' => array(
                    'id', 'album_id', 'name', 'title', 'created', 
                    'width', 'height', 'file_path', 'file_name', 'views', 'modified',
                ),
            )
        );
        if(!$photo){
            throw new NotFoundException(__('Invalid Photo'));
        }
        /* This ID needs to be set for neighbors to work */
        $this->Photo->id = $id;
        $neighbors = $this->Photo->find(
            'neighbors', 
             array(
                 'conditions'=>
                    array(
                        'album_id'=>$photo['Photo']['album_id'],
                        'published'=>true,
                    ), 
                 'fields' => 'id', 
                 'value' => $id)
        );
        if(empty($neighbors['prev']['Photo']['id'])){
            $last = $this->Photo->find('first', 
                array(
                    'conditions'=>array(
                        'id !='=>$id,
                        'album_id'=>$photo['Photo']['album_id'],
                        'published'=>true,
                    ),
                    'fields'=>array('id'),
                    'order'=>array('id'=>'DESC'),
                )
            );
            if($last){
                $neighbors['prev']['Photo']['id'] = $last['Photo']['id'];
            }
        }
        if(empty($neighbors['next']['Photo']['id'])){
            $first = $this->Photo->find('first', 
                array(
                    'conditions'=>array(
                        'id !='=>$id,
                        'album_id'=>$photo['Photo']['album_id'],
                        'published'=>true,
                    ),
                    'fields'=>array('id'),
                    'order'=>array('id'),
                )
            );
            if($first){
                $neighbors['next']['Photo']['id'] = $first['Photo']['id'];
            }
        }
        $data = array();
        $data['Photo']['views'] = ++$photo['Photo']['views'];
        $data['Photo']['last_viewed'] = date( 'Y-m-d H:i:s');
        $data['Photo']['modified'] = $photo['Photo']['modified'];
        $this->Photo->save($data, null, array('views', 'last_viewed'));
        $this->set('photo', $photo);
        $this->set('neighbors', $neighbors);
        $this->set('title_for_layout', __('View').' :: '.h($photo['Photo']['name']));
    }
    /**
     * @todo Add DB config to stop showing location
     * Load details of a Photo. Last accessed time and views are not updated.
     *
     * @param integer $id
     * @return void
     */
    public function details($id){
        if(empty($id)){
            throw new NotFoundException(__('Invalid Photo'));
        }
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $photo = $this->Photo->find('first', 
            array(
               'fields'=>array('id', 'name', 'title', 'created', 'file_path', 'file_name', 
                   'views', 'type', 'size', 'width', 'height', 'taken', 'last_viewed', 'location'),
               'contain'=>array(
                    'Album' => array(
                        'fields' => array('id', 'name', 'description', 'created')
                    ),
                    'Metadata' => array('fields'=> array('photo_id', 'data')),
                ),
                'conditions'=>array(
                    'Photo.id'=>(int)$id,
                    'Photo.published' => true,
                    'Album.permission_id >=' => $this->getPermission(),
                ),
            )
        );
        if(!$photo){
            throw new NotFoundException(__('Invalid Photo'));
        }
        $this->set('photo', $photo);
        $this->set('title_for_layout', __('Details').' :: '.h($photo['Photo']['name']));
    }
    /**
     * Show a slideshow starting with the photo ID provided
     *
     * @param integer $id Initial active Slideshow Photo
     * @return void
     */
    public function slideshow($id){
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $this->Photo->recursive = -1;
        $this->Photo->id = $id;
        $photo = $this->Photo->read(array('album_id'));
        $this->Photo->Album->recursive = -1;
        $album = $this->Photo->Album->find('first', 
            array('conditions'=> 
                array(
                    'Album.id'=>$photo['Photo']['album_id'],
                    'Album.permission_id >=' => $this->getPermission(),
                )
            )
        );
        if(!$album){
            throw new NotFoundException(__('Photo not found'));
        }
        $photos = $this->Photo->find('all', 
            array(
                'conditions'=>array(
                    'Photo.album_id'=>(int)$album['Album']['id'],
                    'Photo.published' => true,
                ),
                'fields'=>array('id', 'title', 'file_name', 'file_path', 'lwidth'),
            )
        );
        if(!$photos){
            throw new NotFoundException(__('Photo not found'));
        }
        $this->set('album', $album);
        $this->set('photos', $photos);
        $this->set('id', $id);
        $this->set('title_for_layout', h($album['Album']['name']).' :: '.__('Slideshow'));
    }
    /**
     * Colorbox method.
     *
     * @param string $album_id ID of the album to display a colorbox for
     * @return void
     */
    public function colorbox($album_id){
        $id = (int)filter_var($album_id, FILTER_SANITIZE_NUMBER_INT);
        $this->Photo->recursive = -1;
        $this->Photo->Album->recursive = -1;
        $this->Photo->Album->id = $id;
        $album = $this->Photo->Album->find('first', 
            array(
                'conditions'=>array(
                    'id'=>$id,
                    'permission_id >='=>$this->getPermission(),
                ), 
                'fields'=>array('id', 'name')
            )
        );
        if(!$album){
            throw new NotFoundException(__('Photo not found'));
        }
        $photos = $this->Photo->find('all', 
            array(
                'conditions'=>array(
                    'Photo.album_id'=>(int)$album['Album']['id'],
                    'Photo.published' => true,
                ),
                'fields'=>array('id', 'title', 'file_name', 'file_path', 'taken'),
            )
        );
        if(!$photos){
            throw new NotFoundException(__('Photo not found'));
        }
        $this->set('album', $album);
        $this->set('photos', $photos);
        $this->set('title_for_layout', h($album['Album']['name']).' :: '.__('Slideshow'));
    }
    /**
     * Retrieve the original Photo. Can be shown in the browser or downloaded.
     *
     * @param string $id ID of the Photo
     * @param integer $download Whether to download the Photo or just show it in the browser
     * @return void
     */
    public function full($id, $download = 0){
        $download = intval($download);
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if(!Configure::read('X2.Photo.Allow_Downloads')){
            throw new ForbiddenException(__('Permission Denied'));
        }
        if(!is_int($id)){
            throw new NotFoundException(__('Invalid Photo'));
        }
        $this->Photo->contain(
            array(
                'Album'=>array(
                    'fields'=>array('id', 'permission_id')
                ),
            )
        );
        $photo = $this->Photo->find('first', array(
                'conditions'=>array(
                    'Photo.id'=>(int)$id,
                    'Photo.published' => true,
                    'Album.permission_id >=' => $this->getPermission(),
                ),
                'fields' => array(
                    'id', 'album_id', 'name', 'file_path', 'file_name', 'type',
                ),
            )
        );
        if(!$photo){
            throw new NotFoundException(__('Invalid Photo'));
        }
        $this->viewClass = 'Media';
        // Download Image
        $params = array(
            'id'        => $photo['Photo']['file_name'],
            'name'      => pathinfo($photo['Photo']['name'], PATHINFO_FILENAME),
            'download'  => (bool)$download,
            'extension' => pathinfo($photo['Photo']['file_name'], PATHINFO_EXTENSION),
            'path'      => Configure::read('X2.Dir.P').DS.$photo['Photo']['file_path'].DS.Configure::read('X2.Dir.O').DS
        );
        $this->set($params);
    }
}