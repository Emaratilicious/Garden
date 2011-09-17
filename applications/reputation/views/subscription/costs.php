<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session(); ?>

<?php foreach ($this->SubscriptionCostData as $SubscriptionCost) : 
   $Alt = $Alt ? FALSE : TRUE;
   $AjaxString = $Session->TransientKey().'?Target='.urlencode($this->SelfUrl);  ?>

   <tr class="<?php if ($Alt) echo 'Alt '; ?>">
      
      <td><strong><?php echo Gdn_Format::Text($SubscriptionCost->Cost .' '. $SubscriptionCost->Currency); ?></strong></td>
      
      <td><?php echo Gdn_Format::Text($SubscriptionCost->Interval .' '. $SubscriptionCost->Unit); ?></td>
      
      <td><?php 
         echo Anchor(T('Edit'), 'subscription/editcost/'.$SubscriptionCost->SubscriptionCostID, 'EditSubscriptionCost SmallButton Popup');
         echo Anchor(T('Delete'), 'subscription/deletecost/'.$SubscriptionCost->SubscriptionCostID.'/'.$AjaxString, 'DeleteSubscriptionCost SmallButton'); ?>
      </td>
      
   </tr>

<?php endforeach; ?>