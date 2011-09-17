<?php if (!defined('APPLICATION')) exit(); ?>

<h1><?php echo T('Manage Subscription Costs'); ?></h1>

<div class="Info">
   <h2><?php echo Gdn_Format::Text($this->Subscription->Name); ?></h2>
   <p><?php echo Gdn_Format::Text($this->Subscription->Body); ?></p>
</div>

<div class="Info">
   <?php echo Anchor(T('Add Cost'), 'subscription/cost/'.$this->Subscription->SubscriptionID, 'Popup SmallButton'); ?>
</div>
<table id="Users" class="AltColumns">
   <thead>
      <tr>
         <th><?php echo T('Cost'); ?></th>
         <th class="Alt"><?php echo T('Length'); ?></th>
         <th><?php echo T('Options'); ?></th>
      </tr>
   </thead>
   <tbody>
      <?php
      if ($this->SubscriptionCostData && $this->SubscriptionCostData->NumRows() > 0) :
         include($this->FetchViewLocation('costs'));
      else :
         echo '<tr><td colspan="' . (CheckPermission('Reputation.Subscriptions.Manage') ? '7' : '6') . '">' . T('No costs yet.') . '</td></tr>';
      endif; 
      ?>
   </tbody>
</table>

<div class="Info">
   <p><?php echo Anchor(T('View All Subscriptions'), 'subscription/all'); ?></p>
</div>