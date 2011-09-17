<?php if (!defined('APPLICATION')) exit(); ?>

<div id="UserSubscriptionForm">
   <h1><?php echo T('Delete Subscription') .  ': '. $this->Subscription->Name; ?></h1>
   <p><?php echo T('Are you sure you want to delete this subscription? This is irreversible and will revoke the subscription from all users who have purchased it.'); ?></p>
   <?php
   echo $this->Form->Open();
   echo $this->Form->Close('Yes, Delete Subscription');
   ?>
</div>