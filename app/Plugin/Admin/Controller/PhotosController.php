<?php
/**
 * Photos Controller
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property Photo $Photo
 */
class PhotosController extends AdminAppController{
    
    public $components = array('RequestHandler');
    public $helpers = array('Time', 'Js'=>'JQuery');
    
    /* For search */
    public $presetVars = array();
    
    public function beforeFilter() {
        parent::beforeFilter();
        if($this->action == 'upload'){
            $this->Security->csrfUseOnce = false;
        }
    }
    /**
     * index method
     *
     * @return void
     */
    public function index(){
        $this->Photo->recursive = 0;
        $this->paginate = array(
            'limit'=>8,
            'contain' => array('Album'=> array('fields'=> array('id', 'permission_id'))),
            'fields'=>array('id', 'name', 'title', 'file_path', 'file_name', 'created', 'published'),
        );
        $this->set('photos', $this->paginate());
        $this->set('title_for_layout', __('Photos'));
    }
    
    public function search(){
        $this->Prg = $this->Components->load('Search.Prg');
        $this->Photo->Behaviors->attach('Search.Searchable');
        $this->Prg->commonProcess();
        
        $this->paginate = array(
            'conditions' => $this->Photo->parseCriteria($this->passedArgs),
            'limit' => 12,
            'fields' => array('id', 'title', 'file_name', 'file_path', 'taken'),
            'contain' => array('User'=>array('fields'=>array('id', 'username')),
                               'Album'=> array('fields'=> array('id', 'permission_id'))),
        );
        /* This is to populate the data back in the form */
        $this->request->data['Photo'] = $this->passedArgs;
        $this->Photo->Album->recursive = -1;
        $this->Photo->Album->Behaviors->attach('Tree');
        $this->set('albums', $this->Photo->Album->generateTreeList());
        $this->set('photos', $this->paginate());
        $this->set('title_for_layout', __('Search Photos'));
    }
    /**
     * view method
     *
     * @param string $id
     * @return void
     */
    public function view($id = null, $refresh_cache = false){
        $this->Photo->recursive = -1;
        if($refresh_cache){
            $this->set('rotated', true);
        }
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        
        if(!is_int($id)){
            throw new NotFoundException(__('Invalid Photo'));
        }
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
        $this->Photo->id = $id;
        $neighbors = $this->Photo->find(
            'neighbors', 
             array(
                 'conditions'=>
                    array(
                        'album_id'=>$photo['Photo']['album_id'],
                    ), 
                 'fields' => 'id', 
                 'value' => (int)$id)
        );
        if(empty($neighbors['prev']['Photo']['id'])){
            $last = $this->Photo->find('first', 
                array(
                    'conditions'=>array(
                        'id !='=>$id,
                        'album_id'=>$photo['Photo']['album_id'],
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
                    ),
                    'fields'=>array('id'),
                    'order'=>array('id'),
                )
            );
            if($first){
                $neighbors['next']['Photo']['id'] = $first['Photo']['id'];
            }
        }
        $this->set('photo', $photo);
        $this->set('neighbors', $neighbors);
        $this->set('title_for_layout', __('View').' :: '.h($photo['Photo']['name']));
    }
    public function details($id=null){
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
                    'Photo.id'=>$id,
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
     * slideshow method
     *
     * @param string $id
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
                )
            )
        );
        if(!$album){
            throw new NotFoundException(__('Photo not found'));
        }
        $photos = $this->Photo->find('all', 
            array(
                'conditions'=>array('Photo.album_id'=>(int)$album['Album']['id']),
                'fields'=>array('id', 'title', 'file_name', 'file_path'),
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
     * colorbox method
     *
     * @param string $id
     * @return void
     */
    public function colorbox($album_id){
        $id = (int)filter_var($album_id, FILTER_SANITIZE_NUMBER_INT);
        $this->Photo->recursive = -1;
        $this->Photo->Album->recursive = -1;
        $this->Photo->Album->id = $id;
        $album = $this->Photo->Album->read(array('id', 'name'));
        if(!$album){
            throw new NotFoundException(__('Photo not found'));
        }
        $photos = $this->Photo->find('all', 
            array(
                'conditions'=>array('Photo.album_id'=>(int)$album['Album']['id']),
                'fields'=>array('id', 'title', 'file_name', 'file_path'),
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
     * full method
     *
     * @param string $id
     * @return void
     */
    public function full($id, $download = 0){
        $download = intval($download);
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
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
            'name'      => pathinfo(h($photo['Photo']['name']), PATHINFO_FILENAME),
            'download'  => (bool)$download,
            'extension' => pathinfo($photo['Photo']['file_name'], PATHINFO_EXTENSION),
            'path'      => Configure::read('X2.Dir.P').DS.$photo['Photo']['file_path'].DS.Configure::read('X2.Dir.O').DS
        );
        $this->set($params);
    }
    /**
     * upload method
     *
     * @return void
     */
    public function upload($album_id = null){
        if(!empty($album_id)){
            $album_id = (int)filter_var($album_id, FILTER_SANITIZE_NUMBER_INT);
        }
        $this->Photo->Behaviors->attach('Admin.Image');
        if(empty($this->request->data['Photo']['album_id']) && !empty($album_id)){
            $this->request->data['Photo']['album_id'] = $album_id;
        }
        if($this->request->is('post')){
            $this->response->vary('accept');
            if($this->RequestHandler->accepts('json')){
                $this->response->type('json');
            }
            else{
                $this->response->type('text');
            }
            $this->Photo->Album->id = (int)$this->request->data['Photo']['album_id'];
            $album = $this->Photo->Album->read(array('id', 'photo_id'));
            if(!$album){
                $file = new stdClass();
                $file->error = __('Invalid Album');
                print json_encode(array($file));
                exit;
            }
            $this->layout = false;
            $this->autoRender = false;
            $this->Photo->create();
            $this->request->data['Photo']['user_id'] = $this->Auth->user('id');
            $file = $this->Photo->upload($this->request->data);
            /* Grab the ID before it gets reset from the read() call */
            $id = $this->Photo->id;
            $cover_photo = $this->Photo->read('id', $album['Album']['photo_id']);
            if(empty($album['Album']['photo_id']) || $cover_photo === false){
                $this->Photo->Album->save(array('Album'=>array('photo_id' => $id)));
            }
            $output = json_encode(array($file));
            print $output; 
            exit;
        }
        $this->Photo->Album->Behaviors->attach('Tree');
        $this->set('albums', $this->Photo->Album->generateTreeList());
        $this->set('title_for_layout', __('Upload Photos'));
    }

    /**
     * edit method
     *
     * @param string $id
     * @return void
     */
    public function edit($id){
        $this->Photo->contain(
            array(
                'Album' => array(
                    'fields' => array('id', 'name')
                ),
                'Metadata' => array('fields'=> array('id', 'photo_id')),
            )
        );
        $this->Photo->recursive = -1;
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $this->Photo->id = $id;
        if(!$this->Photo->exists()){
            throw new NotFoundException(__('Invalid photo'));
        }
        if($this->request->is('post') || $this->request->is('put')){
            if($this->Photo->save($this->request->data)){
                $this->setFlash(__('The photo has been saved'));
                $this->redirect(array('action' => 'index'));
            }
            else{
                $this->setFlash(__('The photo could not be saved. Please, try again.'));
            }
        }
        else{
            $photo = $this->Photo->read(null, $id);
            $this->request->data = $photo;
        }
        $albums = $this->Photo->Album->find('list');
        $this->set('albums', $albums);
        $this->set('photo', $photo);
        $this->set('title_for_layout', __('Edit').' :: '.h($this->request->data['Photo']['name']));
    }

    /**
     * delete method
     *
     * @param string $id
     * @return void
     */
    public function delete($id){
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $this->Photo->Behaviors->attach('Admin.Image');
        if(!$this->request->is('post') && !$this->request->is('delete')){
            throw new MethodNotAllowedException();
        }
        $this->Photo->id = $id;
        if(!$this->Photo->exists()){
            throw new NotFoundException(__('Invalid Photo'));
        }
        $removed = $this->Photo->remove($id);
        if($this->request->is('ajax')){
            $this->layout = false;
            $this->autoRender = false;
            echo json_encode($removed);
            exit();
        }
        else{
            if($removed){
                $this->setFlash(__('Photo successfully deleted'));
            }
            else{
                $this->setFlash(__('Could not delete Photo. Please try again'), 'bad');
            }
            $this->redirect($this->referer()); 
        }
    }
    
    public function cover($id){
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $this->Photo->id = $id;
        $this->Photo->contain('Album');
        $photo = $this->Photo->find('first', 
            array(
                'conditions'=>array('Photo.id'=>(int)$id),
                'fields'=>array('Album.id, Album.photo_id'),
            )
        );
        if($photo){
            if($photo['Album']['photo_id'] != $id){
                $photo['Album']['photo_id'] = (int)$id;
                if($this->Photo->Album->save($photo)){
                    $this->setFlash(__('Album Cover Updated'));
                }
                else{
                    $this->setFlash(__('There was an error. Please try again'), 'bad');
                }
            }
            else{
                $this->setFlash(__('No Changes were made'), 'info');
            }
            $this->redirect($this->referer());
        }
        else{
            throw new NotFoundException(__('Invalid Photo'));
        }
    }
    public function rotate($id, $direction = 'c'){
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException();
        }
        $this->Photo->Behaviors->attach('Admin.Image');
        
        if($direction != 'c' && $direction != 'cc'){
            $this->setFlash(__('Invalid Direction'), 'bad');
            $this->redirect($this->referer());
        }
        $this->Photo->recursive = -1;
        $count = $this->Photo->find('count', array('conditions'=>array('id'=>$id)));
        if($count > 0){
            if($this->Photo->rotate($id, $direction)){
                $this->setFlash(__('Photo Rotated'));
            }
            else{
                $this->setFlash(__('Photo could not be rotated. Please try again'), 'warning');
            }
        }
        else{
            $this->setFlash(__('Invalid Photo'), 'bad');
        }
        $this->redirect(array('action'=>'view', $id, true));
    }
    public function crop($id){
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        $this->Photo->recursive = -1;
        $this->layout = 'crop';
        $this->Photo->id = $id;
        if(!$this->Photo->exists()){
            throw new NotFoundException(__('Invalid photo'));
        }
        if($this->request->is('post')){
            $this->Photo->Behaviors->attach('Admin.Image');
            $x = (int)filter_var($this->request->data['Photo']['x'], FILTER_SANITIZE_NUMBER_INT);
            $y = (int)filter_var($this->request->data['Photo']['y'], FILTER_SANITIZE_NUMBER_INT);
            $w = (int)filter_var($this->request->data['Photo']['w'], FILTER_SANITIZE_NUMBER_INT);
            $h = (int)filter_var($this->request->data['Photo']['h'], FILTER_SANITIZE_NUMBER_INT);

            if($this->Photo->crop($id, $x, $y, $w, $h)){
                $this->setFlash(__('Photo Cropped'));
            }
            else{
                $this->setFlash(__('Photo could not be cropped.'), 'bad');
            }
        }
        $photo = $this->Photo->find('first', array(
                'conditions'=>array(
                    'Photo.id'=>$id,
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
        $this->set('photo', $photo);
        $this->set('title_for_layout', __('Crop').' :: '.h($photo['Photo']['name']));
    }
    public function publish($id, $unpublish = false){
        $id = (int)filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        if(!$this->request->is('post')){
            throw new MethodNotAllowedException();
        }
        if($id){
            $this->Photo->id = $id;
            if($unpublish){
                if($this->Photo->saveField('published', 0)){
                    $this->setFlash(__('Photo Unpublished'));
                }
                else{
                    $this->setFlash(__('Could Not Unpublish Photo'), 'bad');
                }
            }
            else{
                if($this->Photo->saveField('published', 1)){
                    $this->setFlash(__('Photo Published'));
                }
                else{
                    $this->setFlash(__('Could Not Publish Photo'), 'bad');
                }
            }
        }
        $this->redirect($this->referer());
    }
    public function duplicates(){
        $this->Photo->recursive = -1;
        $this->paginate = array(
            'fields'=>array('id', 'name', 'title', 'created', 'file_path', 'file_name', 'count(id) as cnt', 'hash'),
            'group' =>array('hash HAVING count(id) > 1'),
            'order' =>array('cnt'),
        );
        $this->set('duplicates', $this->paginate());
    }
    public function identical($hash){
        $this->Photo->recursive = -1;
        $photos = $this->Photo->findAllByHash($hash);
        $this->set('photos', $photos);
    }
}