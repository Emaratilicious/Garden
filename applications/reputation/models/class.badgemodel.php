<?php if (!defined('APPLICATION')) exit();
/**
 * Badge Model.
 *
 * @package Reputation
 */
 
/**
 * Badge handling.
 *
 * @package Reputation
 */
class BadgeModel extends ReputationModel {
   /**
    * Class constructor. Defines the related database table name.
    * 
    * @access public
    */
   public function __construct() {
      parent::__construct('Badge');
   }
   
   /**
    * Set default select conditions.
    */
   protected function _BeforeGet() {
      
   }
   
   /**
    * Get badges list for viewing.
    */
   public function GetList() {
      if (!CheckPermission('Reputation.Badges.Give') && !CheckPermission('Reputation.Badges.Manage'))
         $this->SQL->Where('Visible', 1);
      
      $this->SQL->OrderBy('Name', 'asc');
      
      return $this->Get();
   }
   
   /**
    * Get badges for dropdown.
    */
   public function GetMenu() {
      $this->SQL
         ->Where('Active', 1)
         ->OrderBy('Name', 'asc');
      return $this->Get();
   }
}