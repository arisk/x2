<?php
/**
* @author Aris Karageorgos <aris@deepspacehosting.com>
* @copyright 2012 Aris Karageorgos (http://deepspacehosting.com)
* @license GPL V2 (http://www.gnu.org/licenses/gpl2.txt)
* @version $x2.version$
* @package x2
*/
?>
<?php echo $this->Html->doctype(); ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php echo $this->Html->charset(); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php echo Configure::read('X2.General.Name'); ?> :: <?php echo $title_for_layout ?></title>
        <?php
        echo $this->Html->meta('icon');
        echo $this->fetch('meta');
        echo $this->Html->css(array('bootstrap.min', 'bootstrap-responsive.min', 'style'));
        echo $this->fetch('css');
        echo $this->Html->script('jquery.min');
        ?>
        <script type="text/javascript">        
        $(function(){
            $('.dropdown-toggle').dropdown();
        });
        </script>
    </head>
    <body>
        <div class="navbar">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <?php echo $this->Html->link(Configure::read('X2.General.Name').' '.__('Admin'), 
                            array('plugin'=>'admin', 'controller'=>'dashboard', 'action'=>'index'), 
                            array('class'=>'brand')); ?>
                    <div class="nav-collapse">
                        <?php echo $this->element('admin_menu'); ?>
                        <?php if($this->action != 'search'): ?>
                            <?php echo $this->element('search'); ?>
                        <?php endif; ?>
                        <ul class="nav pull-right">
                            <li class="dropdown">
                                 <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                     <?php echo __('Language'); ?> <b class="caret"></b>
                                 </a>
                                <?php echo $this->I18n->flagSwitcher(
                                        array('class'=>'dropdown-menu', 'id'=>'language-switcher', 'appendName' => true)); ?>
                            </li>
                            <li>
                                <?php echo $this->Html->link(__('User View'), 
                                    array('plugin'=>null, 'controller'=>'pages', 'action'=>'home')); ?>
                            </li>
                            <li class="dropdown">
                                 <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                     <?php echo __('Manage'); ?> <b class="caret"></b>
                                 </a>
                                <ul class="dropdown-menu">
                                    <li><?php print $this->Html->link(__('Settings'), array('controller'=>'settings')); ?></li>
                                    <li><?php print $this->Html->link(__('Users'), array('controller'=>'users')); ?></li>
                                    <li><?php print $this->Html->link(__('Albums'), array('controller'=>'albums')); ?></li>
                                    <li><?php print $this->Html->link(__('Photos'), array('controller'=>'photos')); ?></li>
                                    <li><?php print $this->Html->link(__('Content'), array('controller'=>'pages')); ?></li>
                                </ul>
                            </li>
                            <li class="divider-vertical"></li>
                            <?php if($this->Session->check('Auth.User.id')): ?>
                            <li class="dropdown">
                                 <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                     <?php echo $this->Session->read('Auth.User.username'); ?> <b class="caret"></b>
                                 </a>
                                <ul class="dropdown-menu">
                                    <li><?php print $this->Html->link(__('Logout'), array('plugin'=>null, 'controller'=>'users', 'action'=>'logout')); ?></li>
                                    <li><?php print $this->Html->link(__('Account Details'), array('controller'=>'users', 'action'=>'view')); ?></li>
                                    <li><?php print $this->Html->link(__('Edit Account'), array('controller'=>'users', 'action'=>'edit')); ?></li>
                                    <li><?php print $this->Html->link(__('Change Password'), array('plugin' => null, 'controller'=>'users', 'action'=>'password')); ?></li>
                                </ul>
                            </li>
                            <?php elseif($this->request->params['action'] != 'login'): ?>
                                <li><?php echo $this->Html->link(__('Login'), array('controller'=>'users', 'action'=>'login')); ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <?php $message = $this->Session->read('Message'); ?>
            <?php if(!empty($message)): ?>
            <div class="row">
                <div class="span12">
                    <?php
                    if($this->Session->check('Message.good')){
                        $class='alert alert-success';
                    }
                    elseif($this->Session->check('Message.bad')){
                        $class='alert alert-error';
                    }
                    elseif($this->Session->check('Message.info')){
                        $class='alert alert-info';
                    }
                    else{
                        $class='alert alert-warning';
                    }
                    ?>
                    <div class="<?php echo $class; ?>">
                        <a class="close" data-dismiss="alert">×</a>
                        <?php echo $this->Session->flash('good'); ?>
                        <?php echo $this->Session->flash('bad'); ?>
                        <?php echo $this->Session->flash('info'); ?>
                        <?php echo $this->Session->flash('warning'); ?>
                    </div>
                </div>        
            </div>
            <?php endif; ?>
            <div id="breadcrumbs" class="row">
                <div class="span12">
                    <?php echo $this->Html->getCrumbs(' :: '); ?>
                </div>
            </div>
        </div>
        <div id="content">
            <?php echo $this->fetch('content'); ?>
        </div>
        <div class="container">
            <div id="footer" class="row">
                <div id="footer-text" class="span12">
                    <?php echo Configure::read('X2.General.Footer_Text'); ?>
                </div>
            </div>
        </div>
        <?php echo $this->element('sql_dump'); ?>
        <?php echo $this->Html->script(array('jquery.ui.min', 'bootstrap.min')); ?>
        <?php echo $this->fetch('script'); ?>
    </body>
</html>