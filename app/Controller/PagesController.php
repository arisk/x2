<?php
/**
 * Pages Controller. 
 * 
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
 * @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
 * @version $x2.version$
 * @package x2
 * 
 * @property Page $Page
 */
class PagesController extends AppController{
    /**
     * Allow all actions 
     * @return void
     */
    public function beforeFilter(){
        parent::beforeFilter();
        $this->Auth->allow('*');
    }
    /**
     * @todo Add a setting in the DB for how many pages should be paginated
     * Paginates all Pages
     *
     * @return void
     */    
    public function index(){
        $this->paginate = array(
            'conditions'=>array('published'=>true),
            'limit'=>5,
            'fields'=>array('id', 'title', 'html', 'slug'),
        );
        $this->set('pages', $this->paginate('Page'));
        $this->set('title_for_layout', __('Pages'));
    }
    /**
     * This is the home page action. It's very simple showing 1 Post on the front page
     * and paginating the rest. I've made this simple so people can customize it and do 
     * whatever they want with their homepage.
     *
     * @return void
     */
    public function home(){
        $recent = $this->Page->find('all', 
            array(
                'conditions'=>array('published'=>true),
                'fields' => array('id', 'slug', 'title'),
                'order'=>array('created'=>'DESC'),
                'limit'=>10,
            )
        );
        $this->paginate = array(
            'limit'=>1,
            'conditions'=>array('promoted'=>true, 'published'=>true),
            'fields'=>array('id', 'title', 'html', 'slug'),
        );
        $this->set('pages', $this->paginate('Page'));
        $this->set('recent', $recent);
        $this->set('title_for_layout', __('Home'));
    }
    /**
     * Shows a page. The slug is for SEO
     *
     * @param string $slug
     * @return void
     */
    public function view($slug = null){
        if(empty($slug)){
            throw new NotFoundException(__('Page Not Found'));
        }
        $slug = filter_var($slug, FILTER_SANITIZE_STRING);
        $page = $this->Page->find('first', array('conditions'=>array('slug'=>$slug, 'published'=>true)));
        if(!$page){
            throw new NotFoundException(__('Page Not Found'));
        }
        $recent = $this->Page->find('all', array(
            'conditions'=>array('published'=>true),
            'fields' => array('id', 'slug', 'title'),
            'order'=>array('created'=>'DESC'),
            'limit'=>5,
        ));
        $this->set('page', $page);
        $this->set('recent', $recent);
        $this->set('title_for_layout', h($page['Page']['title']));
    }
    public function requirements(){
        $this->set('title_for_layout', __('Requirements'));
    }
    /**
     * Simple Cake blackhole method.
     * @see http://book.cakephp.org/2.0/en/core-libraries/components/security-component.html#handling-blackhole-callbacks
     * @return void
     */
    public function blackhole(){
        throw new BadRequestException(__('The form you tried to submit has expired. Please go back, refresh the page and try again'));
    }
}