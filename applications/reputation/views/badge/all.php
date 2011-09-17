<?php if (!defined('APPLICATION')) exit(); ?>

<h1><?php echo T('Manage Badges'); ?></h1>
<div class="Info">
   <?php echo Anchor(T('Add Badge'), 'reputation/achievement/manage', 'Popup SmallButton'); ?>
</div>
<table id="Users" class="AltColumns">
   <thead>
      <tr>
         <th><?php echo T('Name'); ?></th>
         <?php if (CheckPermission('Reputation.Badges.Give')) : ?>
            <th></th>
         <?php endif; ?>
         <th class="Alt"><?php echo T('Description'); ?></th>
         <th><?php echo T('Recipients'); ?></th>
         <th class="Alt"><?php echo T('Active'); ?></th>
         <th><?php echo T('Visible'); ?></th>
         <th class="Alt"><?php echo T('Options'); ?></th>
      </tr>
   </thead>
   <tbody>
      <?php
      if ($this->BadgeData && $this->BadgeData->NumRows() > 0) : 
         include($this->FetchViewLocation('achievements'));
      else :
         echo '<tr><td colspan="' . (CheckPermission('Reputation.Badges.Give') ? '7' : '6') . '">' . T('No achievements yet.') . '</td></tr>';
      endif; 
      ?>
   </tbody>
</table>