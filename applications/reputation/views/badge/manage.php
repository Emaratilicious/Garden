<?php if (!defined('APPLICATION')) exit();
$Action = (isset($this->Badge)) ? 'Edit' : 'Add';
$this->Title($Action . ' ' . T('an Badge')); ?>

<div id="BadgeForm">
   <h1><?php echo $Action . ' '. T('an Badge'); ?></h1>
   <?php
   echo $this->Form->Open();
   echo $this->Form->Errors();
   
   echo '<p>'.$this->Form->Label('Name');
   echo $this->Form->Input('Name').'</p>';
      
   echo '<p>'.$this->Form->Label('Description', 'Body');
   echo $this->Form->TextBox('Body', array('MultiLine' => TRUE)).'</p>';
   
   //echo $this->Form->Label('Points');
   //echo $this->Form->Input('Points');
   
   $ButtonAction = (isset($this->Badge)) ? 'Update' : 'Add';
   echo $this->Form->Close($ButtonAction . ' Badge');
   ?>
</div>