<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session(); ?>

<?php foreach ($this->SubscriptionData as $Subscription) : 
   $Alt = $Alt ? FALSE : TRUE;
   $AjaxString = $Session->TransientKey().'?Target='.urlencode($this->SelfUrl);  ?>

   <tr class="<?php if ($Alt) echo 'Alt '; ?>">
      
      <td><strong><?php echo Anchor($Subscription->Name, 'subscription/'.$Subscription->SubscriptionID, 'Title'); ?></strong></td>
      
      <td><?php echo Gdn_Format::Text($Subscription->Body); ?></td>
      
      <td><?php echo Gdn_Format::Text($Subscription->RoleName); ?></td>
      
      <td><?php echo Gdn_Format::Text('0'); ?></td>
      
      <td><?php echo Gdn_Format::Text('0'); ?></td> 
      
      <td><?php 
         echo Anchor(T('Edit'), 'subscription/manage/'.$Subscription->SubscriptionID, 'EditSubscription SmallButton Popup');
         echo Anchor(T('Delete'), 'subscription/delete/'.$Subscription->SubscriptionID.'/'.$AjaxString, 'DeleteSubscription Popup SmallButton'); ?>
      </td>
      
   </tr>

<?php endforeach; ?>