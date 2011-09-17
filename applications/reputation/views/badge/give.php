<?php if (!defined('APPLICATION')) exit();

$this->Title(T('Give an Badge')); ?>

<div id="UserBadgeForm">
   <h1><?php echo T('Give Badge') . ': ' . Gdn_Format::Text($this->Badge->Name); ?></h1>
   <p><?php echo Gdn_Format::Text($this->Badge->Body); ?></p>
   
   <?php
   echo $this->Form->Open();
   echo $this->Form->Errors();
   
   echo '<p>', $this->Form->Label('Recipients', 'To');
   echo $this->Form->TextBox('To', array('MultiLine' => TRUE, 'class' => 'MultiComplete')), '</p>';
   
   echo '<p>', $this->Form->Label('Reason (optional)', 'Reason');
   echo $this->Form->TextBox('Reason', array('MultiLine' => TRUE)), '</p>';
   
   echo Anchor('Cancel', 'achievement/'.$this->Badge->BadgeID);
   
   echo $this->Form->Close('Give Badge');
   ?>
</div>