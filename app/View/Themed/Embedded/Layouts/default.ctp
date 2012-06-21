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
        <title>X2 :: <?php echo $title_for_layout ?></title>
        <?php
        echo $this->Html->meta('icon');
        echo $this->fetch('meta');
        echo $this->Html->css(array('bootstrap.min', 'bootstrap-responsive.min', 'style'));
        echo $this->fetch('css');
        echo $this->Html->script('jquery.min');
        ?>
    </head>
    <body>
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
            <div id="content" class="row">
                <?php echo $this->fetch('content'); ?>
            </div>
        </div>
        <?php echo $this->element('sql_dump'); ?>
        <?php echo $this->Html->script('bootstrap.min'); ?>
        <?php echo $this->fetch('script'); ?>
    </body>
</html>