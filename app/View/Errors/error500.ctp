<h2><?php echo $name; ?></h2>
<p class="error">
    <strong><?php echo __('Error'); ?>: </strong>
    <?php echo __('An Internal Error Has Occurred.'); ?>
</p>
<?php
if(Configure::read('debug') > 0):
    echo $this->element('exception_stack_trace');
endif;
?>
