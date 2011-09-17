<?php if (!defined('APPLICATION')) exit();
$Session = Gdn::Session();

if (!function_exists('WriteOptions'))
   include($this->FetchViewLocation('helper_functions'));
   
$this->Title(T('View Badge') . ': ' . $this->Badge->Name); ?>

<h1><?php echo Gdn_Format::Text($this->Badge->Name); ?></h1>
<?php WriteOptions($this->Badge, $this, $Session); ?>
<p><?php echo Gdn_Format::Text($this->Badge->Body); ?></p>

<?php if ($this->UserData) : ?>

<h2><?php echo $this->Badge->CountRecipients . ' ' . T('users have earned this badge'); ?></h2>

<ul>
   <?php foreach($this->UserData as $User) : ?>
   <li><?php echo UserAnchor($User); ?></li>
   <?php endforeach; ?>
</ul>

<?php else : ?>

<p><?php echo T('No one has earned this badge yet.'); ?></p>

<?php endif; ?>

<?php echo Anchor('Back to Badges', 'profile/badges'); ?>