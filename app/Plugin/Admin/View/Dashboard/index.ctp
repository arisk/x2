<?php
/**
* @author Aris Karageorgos <aris@deepspacehosting.com>
* @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
* @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
* @version $x2.version$
* @package x2
*/
?>
<?php $url = $this->X2->photoUrl(); ?>
<?php $this->extend('/Layouts/two'); ?>
<?php $this->start('left'); ?>
<?php echo $this->element('admin_navigation'); ?>
<?php $this->end(); ?>
<h2><?php echo __('Dashboard'); ?></h2>
<div class="row">
    <div class="span3">
        <div class="well menu">
            <div class="dashboard-widget">
                <h4><?php echo __('Recent Albums'); ?></h4>
                <ul>
                <?php foreach($albums as $album): ?>
                    <li><?php echo $this->Html->link($album['Album']['name'], 
                            array('controller'=>'albums', 'action'=>'view', h($album['Album']['id']))) ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="span4">
        <div class="well menu">
            <div class="dashboard-widget">
                <h4><?php echo __('Recent Photos'); ?></h4>
                <ul class="thumbnails">
                <?php foreach($photos as $photo): ?>
                    <?php $image_url = h($url.$photo['Photo']['file_path'].'/'.
                            Configure::read('X2.Dir.XS').'/'.$photo['Photo']['file_name']); ?>
                    <li>
                        <?php 
                        echo $this->Html->image($image_url, 
                            array(
                                'alt' => h($photo['Photo']['title']),
                                'class' => 'thumbnail',
                                'url' => array('controller' => 'photos', 'action' => 'view', h($photo['Photo']['id']))
                            )
                        );
                        ?>
                    </li>
                <?php endforeach; ?>   
                </ul>
            </div>
        </div>
    </div>
    <div class="span3">
        <div class="well menu">
            <div class="dashboard-widget">
                <h4><?php echo __('Recent Content'); ?></h4>
                <ul>
                <?php foreach($pages as $page): ?>
                    <li><?php echo $this->Html->link($page['Page']['title'], 
                            array('controller'=>'pages', 'action'=>'view', h($page['Page']['id']))); ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>