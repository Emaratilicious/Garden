<?php if (!defined('APPLICATION')) exit();
$Action = (isset($this->Subscription)) ? 'Edit' : 'Add';
$this->Title($Action . ' ' . T('a Subscription')); ?>

<div id="SubscriptionForm">
   <h1><?php echo $Action . ' '. T('a Subscription'); ?></h1>
   <?php
   echo $this->Form->Open();
   echo $this->Form->Errors();
   
   echo '<p>'.$this->Form->Label('Name');
   echo $this->Form->Input('Name').'</p>';
   
   echo '<p>'.$this->Form->Label('Description', 'Body');
   echo $this->Form->TextBox('Body', array('MultiLine' => TRUE)).'</p>';
   
   echo '<p>'.$this->Form->Label('Role to Grant', 'RoleID');
   echo $this->Form->DropDown('RoleID', $this->Roles, array('TextField' => 'Name', 'ValueField' => 'RoleID')).'</p>';
   
   $ButtonAction = (isset($this->Subscription)) ? 'Update' : 'Add';
   echo $this->Form->Close($ButtonAction . ' Subscription');
   ?>
</div>