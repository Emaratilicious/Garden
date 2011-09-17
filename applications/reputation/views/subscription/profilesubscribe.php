<?php if (!defined('APPLICATION')) exit(); ?>

<h1><?php echo T('Subscription') .' &ldquo;'. Gdn_Format::Text($this->Subscription->Name) .'&rdquo;'; ?></h1>
<div class="Subscribe">

<p><?php echo Gdn_Format::Text($this->Subscription->Body); ?></p>

<?php echo Anchor('Purchase Now', $this->PaymentLink, 'BigButton PurchaseSubscription'); ?>

</div>