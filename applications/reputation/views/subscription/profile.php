<?php if (!defined('APPLICATION')) exit(); ?>

<h1><?php echo T('My Subscriptions'); ?></h1>
<div class="Subscriptions">

<?php if ($this->MySubscriptionData && $this->MySubscriptionData->NumRows() > 0) : ?>
   
   <?php foreach ($this->MySubscriptionData as $this->Subscription) : ?>
      
      <?php if ($this->Subscription->CostData && $this->Subscription->CostData->NumRows() > 0) : # Must be purchasable ?>
      <?php include($this->FetchViewLocation('subscription')); ?>      
      <?php endif; ?>
      
   <?php endforeach; ?>
      
<?php elseif ($this->SubscriptionData && $this->SubscriptionData->NumRows() > 0) : ?>

   <p class="Empty"><?php echo T('You have no subscriptions yet.'); ?></p>
      
<?php endif; ?>

<h1><?php echo T('Available Subscriptions'); ?></h1>

<?php if ($this->SubscriptionData && $this->SubscriptionData->NumRows() > 0) : ?>
      
   <?php foreach ($this->SubscriptionData as $this->Subscription) : ?>
      
      <?php if ($this->Subscription->CostData && $this->Subscription->CostData->NumRows() > 0) : # Must be purchasable ?>
      <?php include($this->FetchViewLocation('subscription')); ?>
      <?php endif; ?>
   
   <?php endforeach; ?>

<?php else : ?>
   
   <p class="Empty"><?php echo T('No subscriptions are available yet.'); ?></p>

<?php endif; ?>

</div>