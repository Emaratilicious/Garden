<?php if (!defined('APPLICATION')) exit();

if (!isset($Drop))
   $Drop = FALSE;
   
if (!isset($Explicit))
   $Explicit = FALSE; 

$Database = Gdn::Database();
$SQL = $Database->SQL();
$Construct = $Database->Structure();
$Validation = new Gdn_Validation();

// Badges
$Construct->Table('Badge')
	->PrimaryKey('BadgeID')
   ->Column('Name', 'varchar(64)')
   ->Column('Body', 'text', TRUE)
   ->Column('Points', 'int', TRUE)
   ->Column('Active', 'tinyint', 1)
   ->Column('Visible', 'tinyint', 1)
   ->Column('DateInserted', 'datetime')
   ->Column('DateUpdated', 'datetime', TRUE)
   ->Column('InsertUserID', 'int')
   ->Column('UpdateUser', 'int', TRUE)
   ->Column('CountRecipients', 'int', 0)
   ->Set($Explicit, $Drop);

// User Badges
$Construct->Table('UserBadge')
   ->PrimaryKey('UserBadgeID')
	->Column('UserID', 'int', FALSE, 'key')
	->Column('BadgeID', 'int', FALSE, 'key')
	->Column('Progress', 'varchar(255)', TRUE)
   ->Column('Reason', 'varchar(255)', TRUE)
   ->Column('ShowReason', 'tinyint', 0)
   ->Column('DateCompleted', 'datetime')
   ->Column('InsertUserID', 'int')
   ->Set($Explicit, $Drop);

// Add achievement count to Users
$Construct->Table('User')
   ->Column('CountBadges', 'int', 0)
   ->Set();
   
// Subscription
$Construct->Table('Subscription')
	->PrimaryKey('SubscriptionID')
   ->Column('Name', 'varchar(64)')
   ->Column('Body', 'text', TRUE)
   ->Column('RoleID', 'int', TRUE)
   ->Column('DateInserted', 'datetime')
   ->Column('DateUpdated', 'datetime', TRUE)
   ->Column('InsertUserID', 'int')
   ->Column('UpdateUserID', 'int', TRUE)
   ->Column('ShowActivity', 'tinyint', 0)
   ->Set($Explicit, $Drop);

// Subscription Cost
$Construct->Table('SubscriptionCost')
   ->PrimaryKey('SubscriptionCostID')
   ->Column('SubscriptionID', 'int', FALSE, 'key')
   ->Column('Interval', 'int')
   ->Column('Unit', array('Days','Years'))
   ->Column('Cost', 'decimal(6,2)', TRUE)
   ->Column('Currency', 'varchar(16)', 'USD')
   ->Column('DateInserted', 'datetime')
   ->Column('DateUpdated', 'datetime', TRUE)
   ->Column('InsertUserID', 'int')
   ->Column('UpdateUserID', 'int', TRUE)
   ->Set($Explicit, $Drop);

// User Subscriptions
$Construct->Table('UserSubscription')
	->Column('UserID', 'int', FALSE, 'key')
	->Column('SubscriptionID', 'int', FALSE, 'key')
	->Column('SubscriptionCostID', 'int', FALSE, 'key')
   ->Column('DateInserted', 'datetime')
   ->Column('DateUpdated', 'datetime', TRUE)
   ->Column('DateAlerted', 'datetime', TRUE)
   ->Column('DateExpires', 'datetime', TRUE)
   ->Column('InsertUserID', 'int')
   ->Column('UpdateUserID', 'int', TRUE)
   ->Set($Explicit, $Drop);

// User Likes
$Construct->Table('Discussion')
	->Column('Likes', 'int', 0)
   ->Set();
$Construct->Table('Comment')
	->Column('Likes', 'int', 0)
   ->Set();
$Construct->Table('UserDiscussion')
	->Column('Liked', 'tinyint', 0)
   ->Set();
$Construct->Table('UserComment')
	->Column('Liked', 'tinyint', 0)
   ->Set();   
 
// Insert some activity types
///  %1 = ActivityName
///  %2 = ActivityName Possessive
///  %3 = RegardingName
///  %4 = RegardingName Possessive
///  %5 = Link to RegardingName's Wall
///  %6 = his/her
///  %7 = he/she
///  %8 = RouteCode & Route

// X got an achievement
if ($SQL->GetWhere('ActivityType', array('Name' => 'Badge'))->NumRows() == 0)
   $SQL->Insert('ActivityType', array(
      'AllowComments' => '1', 
      'Name' => 'Badge', 
      'FullHeadline' => '%1$s earned an %8$s.', 
      'ProfileHeadline' => '%1$s earned an %8$s.', 
      'RouteCode' => 'badge', 
      'Public' => '1', 
      'Notify' => '1'
   ));
 