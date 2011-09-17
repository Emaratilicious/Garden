   <table class="AltColumns Subscription">
   <thead>
      <tr>
         <th colspan="3">
            <em><?php echo Gdn_Format::Text($this->Subscription->Name); ?></em> 
            <?php echo Gdn_Format::Text($this->Subscription->Body); ?>
            
            <?php if ($this->Subscription->DateExpires) : ?>
            
            <span class="Meta">(<?php echo T('Expires') . ': ' . Gdn_Format::Date($this->Subscription->DateExpires); ?>)</span>
            
            <?php endif; ?>
            
         </th>
      </tr>
   </thead>
   <tbody>
   
      <?php foreach ($this->Subscription->CostData as $SubscriptionCost) : ?>
      
      <tr>
         <td><?php echo Gdn_Format::Text($SubscriptionCost->Interval .' '. $SubscriptionCost->Unit); ?></td>
         <td><strong><?php echo Gdn_Format::Text($SubscriptionCost->Cost .' '. $SubscriptionCost->Currency); ?></strong></td>         
         <td><?php echo Anchor(T('Select'), 'profile/subscribe/'.$SubscriptionCost->SubscriptionCostID, 'Button'); ?></td>
      </tr>
      
      <?php endforeach; ?>
      
   </tbody>
   </table>