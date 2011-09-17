<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session(); ?>

<?php foreach ($this->BadgeData as $Badge) : 
   $Alt = $Alt ? FALSE : TRUE;
   $AjaxString = $Session->TransientKey().'?Target='.urlencode($this->SelfUrl);  ?>

   <tr class="<?php if ($Alt)echo 'Alt '; if (!$Badge->Visible) echo 'HiddenBadge'; ?>">
      
      <td><strong><?php echo Anchor($Badge->Name, 'badge/'.$Badge->BadgeID, 'Title'); ?></strong></td>           
      
      <?php if (CheckPermission('Reputation.Badges.Give')) : ?>
      <td><?php 
         // Give badge
         if ($Session->CheckPermission('Reputation.Badges.Give') && $Badge->Active)
            echo Anchor(T('Give to Users'), 'reputation/badge/give/'.$Badge->BadgeID, 'GiveBadge SmallButton Popup'); ?>
      </td>
      <?php endif; ?>
      
      <td><?php echo Gdn_Format::Text($Badge->Body); ?></td>
      
      <td><?php echo Gdn_Format::Text($Badge->CountRecipients); ?></td>
      
      <td><?php 
          // Disable badge
         if (CheckPermission('Reputation.Badges.Manage')) {
            echo Anchor(T($Badge->Active ? 'Yes' : 'No'), 
               'reputation/badge/disable/'.$Badge->BadgeID.'/'.$AjaxString, 
               'DisableBadge', array('title'=> ($Badge->Active ? 'Disable' : 'Enable')));
         }
         else
            echo Gdn_Format::Text(($Badge->Active) ? 'Yes' : 'No'); ?>
      </td>
      
      <td><?php 
         // Hide badge
         if (CheckPermission('Reputation.Badges.Manage')) {
            echo Anchor(T($Badge->Visible == '1' ? 'Yes' : 'No'), 
               'reputation/badge/hide/'.$Badge->BadgeID.'/'.$AjaxString, 
               'HideBadge', array('title'=> ($Badge->Visible ? 'Hide' : 'Show')));
         }
         else
            echo Gdn_Format::Text(($Badge->Visible) ? 'Yes' : 'No'); ?>
      </td> 
      
      <td><?php 
         // Edit badge
         if (CheckPermission('Reputation.Badges.Manage'))
            echo Anchor(T('Edit'), 'reputation/badge/manage/'.$Badge->BadgeID, 'EditBadge SmallButton Popup');
         
         // Delete badge
         if (CheckPermission('Reputation.Badges.Manage'))
            echo Anchor(T('Delete'), 'reputation/badge/delete/'.$Badge->BadgeID.'/'.$AjaxString, 'DeleteBadge Popup SmallButton'); ?>
      </td>
      
   </tr>

<?php endforeach; ?>