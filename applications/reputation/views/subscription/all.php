<?php if (!defined('APPLICATION')) exit(); ?>

<h1><?php echo T('Manage Subscriptions'); ?></h1>
<div class="Info">
   <?php echo Anchor(T('Add Subscription'), 'reputation/subscription/manage', 'Popup SmallButton'); ?>
</div>
<table id="Users" class="AltColumns">
   <thead>
      <tr>
         <th><?php echo T('Name'); ?></th>
         <th class="Alt"><?php echo T('Description'); ?></th>
         <th><?php echo T('Role to Grant'); ?></th>
         <th class="Alt"><?php echo T('Current'); ?></th>
         <th><?php echo T('Total'); ?></th>
         <th class="Alt"><?php echo T('Options'); ?></th>
      </tr>
   </thead>
   <tbody>
      <?php
      if ($this->SubscriptionData && $this->SubscriptionData->NumRows() > 0) :
         include($this->FetchViewLocation('subscriptions'));
      else :
         echo '<tr><td colspan="' . (CheckPermission('Reputation.Subscriptions.Manage') ? '7' : '6') . '">' . T('No subscriptions yet.') . '</td></tr>';
      endif; 
      ?>
   </tbody>
</table>