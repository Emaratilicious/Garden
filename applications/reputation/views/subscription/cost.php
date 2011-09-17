<?php if (!defined('APPLICATION')) exit();
$Action = (isset($this->SubscriptionCost)) ? 'Edit' : 'Add';
$this->Title($Action . ' ' . T('a Subscription Cost')); ?>

<div id="SubscriptionCostForm">
   <h1><?php echo $Action . ' '. T('a Subscription Cost'); ?></h1>
   <?php
   echo $this->Form->Open();
   echo $this->Form->Errors();
   
   echo '<p>'.$this->Form->Label('Cost');
   echo $this->Form->Input('Cost').' USD</p>';
   
   echo '<p>'.$this->Form->Label('Duration', 'Interval');
   echo $this->Form->TextBox('Interval', array('class' => 'InputBox SmallInput'));
   echo $this->Form->DropDown('Unit', $this->Units).'</p>';
   
   $ButtonAction = (isset($this->SubscriptionCost)) ? 'Update' : 'Add';
   echo $this->Form->Close($ButtonAction . ' Subscription Cost');
?>