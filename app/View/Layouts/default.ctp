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
        <script type="text/javascript">$(function(){$('.dropdown-toggle').dropdown();});</script>
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
                    <?php echo $this->Html->link(Configure::read('X2.General.Name'), 
                            array('plugin'=>null, 'controller'=>'pages', 'action'=>'home'), 
                            array('class'=>'brand')); ?>
                    <div class="nav-collapse">
                        <?php echo $this->element('menu'); ?>
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
                            <?php if($this->Session->read('Auth.User.admin') === true): ?>
                                <li><?php echo $this->Html->link(__('Admin'), 
                                        array('plugin'=>'admin', 'controller'=>'dashboard', 'action'=>'index')); ?></li>
                            <?php endif; ?>
                            <li class="divider-vertical"></li>
                            <?php if($this->Session->check('Auth.User.id')): ?>
                            <li class="dropdown">
                                 <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                     <?php echo $this->Session->read('Auth.User.username'); ?> <b class="caret"></b>
                                 </a>
                                <ul class="dropdown-menu">
                                    <li><?php print $this->Html->link(__('Logout'), array('controller'=>'users', 'action'=>'logout')); ?></li>
                                    <li><?php print $this->Html->link('Account Details', array('controller'=>'users', 'action'=>'view')); ?></li>
                                    <li><?php print $this->Html->link('Edit Account', array('controller'=>'users', 'action'=>'edit')); ?></li>
                                    <li><?php print $this->Html->link('Change Password', array('controller'=>'users', 'action'=>'password')); ?></li>
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
            <div id="content" class="row">
                <?php echo $this->fetch('content'); ?>
            </div>
            <div id="footer" class="row">
                <div id="footer-text" class="span12">
                    <?php echo Configure::read('X2.General.Footer_Text'); ?>
                </div>
            </div>
        </div>
        <?php echo $this->element('sql_dump'); ?>
        <?php echo $this->Html->script('bootstrap.min'); ?>
        <?php echo $this->fetch('script'); ?>
    </body>
</html>
<?php //var_dump($_SESSION); ?>
<?php //var_dump($_SERVER); ?>