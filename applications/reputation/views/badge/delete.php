<?php if (!defined('APPLICATION')) exit(); ?>

<div id="UserBadgeForm">
   <h1><?php echo T('Delete Badge') .  ': '. $this->Badge->Name; ?></h1>
   <p><?php echo T('Are you sure you want to delete this achievement? This is irreversible and will revoke the achievement from all users who have it.'); ?></p>
   <?php
   echo $this->Form->Open();
   echo $this->Form->Close('Yes, Delete Badge');
   ?>
</div>