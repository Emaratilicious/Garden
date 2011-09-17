<?php if (!defined('APPLICATION')) exit();
/**
 * Subscription Model.
 *
 * @package Reputation
 */
 
/**
 * Subscription handling.
 *
 * @package Reputation
 */
class SubscriptionModel extends ReputationModel {
   /**
    * Class constructor. Defines the related database table name.
    * 
    * @access public
    */
   public function __construct() {
      parent::__construct('Subscription');
   }
   
   /**
    * Add Role table to Gets.
    * 
    * @access public
    */
   public function _BeforeGet() {
      $this->SQL
         ->Select('Subscription.*')
         ->Select('Role.Name', '', 'RoleName')
         ->Join('Role', 'Role.RoleID = Subscription.RoleID', 'left');
   }
   
   /**
    * Get users current subscriptions.
    * 
    * @access public
    */
   public function GetByUser($UserID) {
      $this->SQL
         ->Select('Subscription.*')
         ->Select('UserSubscription.*')
         ->Join('UserSubscription', 'UserSubscription.SubscriptionID = Subscription.SubscriptionID', 'left');
      return $this->GetWhere(array('UserID' => $UserID));
   }
   
}