<?php if (!defined('APPLICATION')) exit();
/**
 * Subscription Cost Model.
 *
 * @package Reputation
 */
 
/**
 * Terms a subscription may be purchased for.
 *
 * @package Reputation
 */
class SubscriptionCostModel extends ReputationModel {
   /**
    * Class constructor. Defines the related database table name.
    * 
    * @access public
    */
   public function __construct() {
      parent::__construct('SubscriptionCost');
   }
   
   /**
    * Get single subscription's costs.
    * 
    * @access public
    */
   public function GetBySubscription($SubscriptionID) {
      return $this->GetWhere(array('SubscriptionID' => $SubscriptionID));
   }
   
}