<?php
/**
 * Albums Controller. 
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property Album $Album
 */

App::uses('AppController', 'Controller');
App::uses('Sanitize', 'Utility');
App::uses('Inflector', 'Utility');

class AlbumsController extends AppController{

    public $components = array('RequestHandler');
    public $helpers = array('Cache', 'Html', 'Form', 'Js' => 'Jquery', 'Time');
    public $cacheAction = array(
        //'index' => 3600,
    );
    /**
     * Allow all actions 
     * @return void
     */
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('*');
    }

    /**
     * Show paginated albums
     * @param integer $parent
     * @return void
     */
    public function index($parent = null){
        $conditions = array();
        $conditions['Album.permission_id >='] = $this->getPermission();
        if(!empty($parent)){
            $parent = Sanitize::paranoid($parent);
            $conditions['Album.parent_id'] = $parent;
        }
        elseif(Configure::read('X2.Album.Show_Children') !== true){
            $conditions['Album.parent_id'] = null;
        }
        $this->Album->recursive = 0;
        $this->paginate = array(
            'contain' => array('CoverPhoto'),
            'limit' => 15,
            'order' => 'Album.id',
            'conditions' => $conditions,
            'fields' => array('id','name','created','permission_id', 'parent_id', 'photo_id'),
        );
        $this->set('albums', $this->paginate());
        $this->set('title_for_layout', __('Albums'));
    }

    /**
     * view method
     *
     * @param integer $id Id of album to be shown
     * @param string $slug future param for SEO
     * @return void
     */
    public function view($id, $slug = null){
        $this->autoRender = false;
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $this->Album->Photo->recursive = -1;
        $this->paginate = array('Photo' =>
            array(
                'limit' => 15,
                'fields' => array('id', 'title', 'file_path', 'file_name', 'views', 'taken'),
            )
        );
        $album = $this->Album->find('first', array(
            'contain' => array(
                'CoverPhoto', 
                'ChildAlbum'=>array(
                    'fields'=>array('id', 'name'), 
                    'conditions'=>array(
                        'ChildAlbum.permission_id >=' => $this->getPermission(),
                    ),
                 ),
            ),
            'fields'=> array('id', 'name', 'description', 'created'), 
            'conditions' => array(
                'Album.id' => (int) $id,
                'Album.permission_id >=' => $this->getPermission())
             )
        );
        if(!$album){
            throw new NotFoundException(__('Invalid Album'));
        }
        $this->set('album', $album);
        $photos = $this->paginate('Photo', array('album_id' => $album['Album']['id'], 'Photo.published'=>true));
        $this->set('photos', $photos);
        $this->set('title_for_layout', __('Album').' :: '.h($album['Album']['name']));
        if(isset($this->request->params['named']['t']) && $this->request->params['named']['t'] == 'embedded'){
            $this->render();
        }
        elseif(Configure::read('X2.Photo.Render_Colorbox')){
            $this->render('/Photos/colorbox');
        }
        else{
            $this->render();
        }
    }
    /**
     * Blank method. All processing starts from the client side with AJAX.
     *
     * @return void
     */
    public function tree(){
        $this->set('title_for_layout', __('Album Tree'));
    }
    /**
     * Get Children of the given album
     *
     * @param integer $id Id of album to find the children of
     * @return void
     */
    public function children($id = null){
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $this->Album->Behaviors->load('Tree', array('scope'=>'Album.permission_id >='.$this->getPermission()));
        $this->Album->recursive = -1;
        $this->autoRender = false;
        $this->autoLayout = false;

        if($id === null || is_numeric($id)){

            $output = array();

            if(isset($this->request->query['key']) && is_numeric($this->request->query['key'])){
                $id = $this->request->query['key'];
            }
            foreach($this->Album->children($id, true, array('id', 'name')) as $child){
                if($this->Album->childCount($child['Album']['id'], true)){
                    $output[] = array(
                        'title' => $child['Album']['name'],
                        'isFolder' => true,
                        'key' => $child['Album']['id'],
                        'isLazy' => true,
                    );
                }
                else{
                    $output[] = array(
                        'title' => $child['Album']['name'],
                        'key' => $child['Album']['id'],
                        'href' => Router::url(array('controller'=>'albums', 'action'=>'view', $child['Album']['id']), true),
                    );
                }
            }
            $this->response->type('json');
            $this->response->body(json_encode($output));
            $this->response->send();
            exit();
        }
    }
}