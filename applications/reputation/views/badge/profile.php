<?php if (!defined('APPLICATION')) exit();

if ($this->BadgeData && $this->BadgeData->NumRows() > 0) : ?>

   <ul class="DataList Badges">
      <?php foreach ($this->BadgeData as $Badge) : ?>
      
      <li class="Item">
         <?php if (CheckPermission('Reputation.Badges.Manage')) : ?>
         <div class="Options">
            <div class="ToggleFlyout OptionsMenu">
               <div class="MenuTitle">Options</div>
               <ul class="Flyout MenuItems">
                  <li><?php echo Anchor(T('Revoke'), 'reputation/achievement/revoke/'.$Badge->UserBadgeID.'/'.$Session->TransientKey(), 'RevokeBadge'); ?></li>
               </ul>
            </div>
         </div>   
         <?php endif; ?>
         
         <div class="ItemContent Badge">
            <?php echo Anchor($Badge->Name, 'achievement/'.$Badge->BadgeID, 'Title'); ?>            
            <div class="Meta">
               <span class="DateInserted"><?php echo T('Earned') . ' ' . Gdn_Format::Date($Badge->DateReceived); ?></span>
               <span class="Reason"><?php echo T('Reason') . ': ' . Gdn_Format::Text($Badge->Reason); ?></span>     
            </div>
         </div>
      </li>
      
      <?php endforeach; ?>
   </ul>

<?php else : ?>

   <div class="Empty"><?php echo T('No achievements yet.'); ?></div>

<?php endif; ?>