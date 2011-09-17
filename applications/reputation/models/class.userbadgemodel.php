<?php if (!defined('APPLICATION')) exit();
/**
 * UserBadge Model.
 *
 * @package Reputation
 */
 
/**
 * Deals with associating users with badges.
 *
 * @package Reputation
 */
class UserBadgeModel extends ReputationModel {
   /**
    * Class constructor. Defines the related database table name.
    * 
    * @access public
    */
   public function __construct() {
      parent::__construct('UserBadge');
   }
   
   /**
    * Get number of badges this user has received.
    * 
    * @since 2.1.0
    * @access public
    */
   public function BadgeCount($UserID = '') {
      return $this->GetCount(array('UserID' => $UserID));
   }
      
   /**
    * Get badges for a single user.
    */
   public function GetBadges($UserID = '') {
      return $this->SQL
         ->Select('b.*')
         ->Select('ub.UserBadgeID')
         ->Select('ub.Reason')
         ->Select('ub.ShowReason')
         ->Select('ub.DateCompleted')
         ->From('UserBadge ub')
         ->Join('Badge b', 'b.BadgeID = ub.BadgeID', 'left')
         ->Where('ua.UserID', $UserID)
         ->OrderBy('b.Name', 'asc')
         ->Get();
   }
   
   /**
    * Get users who have an badge.
    */
   public function GetUsers($BadgeID = '') {
      return $this->SQL
         ->Select('u.*')
         ->From('UserBadge ub')
         ->Join('User u', 'u.UserID = ub.UserID', 'left')
         ->Where('ub.BadgeID', $BadgeID)
         ->OrderBy('ub.DateCompleted', 'asc')
         ->Get();
   }

   /**
    * Get number of users who have this badge.
    * 
    * @since 2.1.0
    * @access public
    *
    * @todo Only count unique UserIDs
    */
   public function RecipientCount($BadgeID = '') {
      return $this->GetCount(array('BadgeID' => $BadgeID));
   }
   
   /**
    * Revoke a badge from a user.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Revoke($UserBadgeID = '') {
      $UserBadge = $this->GetID($UserBadgeID);
      
      // Delete it
      $this->Delete(array('UserBadgeID' => $UserBadgeID));
      
      // Adjust user's badge count
      $BadgeCount = $this->BadgeCount($UserBadge->UserID);
		$this->SQL->Update('User')
			->Set('CountBadges', $BadgeCount)
			->Where('UserID', $UserBadge->UserID)
			->Put();
			
      // Adjust's badge's recipient count
		$RecipientCount = $this->RecipientCount($UserBadge->BadgeID);
		$this->SQL->Update('Badge')
			->Set('CountRecipients', $RecipientCount)
			->Where('BadgeID', $UserBadge->BadgeID)
			->Put();
			
      return $UserBadge->UserID;
   }
   
   /**
    * Save given user badge.
    * 
    * @since 2.1.0
    * @access public
    *
    * @param array $FormPostValues Values submitted via form.
    * @return bool Whether save was successful.
    */
   public function Save($FormPostValues) {
      $Session = Gdn::Session();
      
      // Define the primary key in this model's table.
      $this->DefineSchema();
      
      // Add & apply any extra validation rules
      //$this->Validation->ApplyRule('BadgeID', 'Integer');
      
      // Make sure that there is at least one recipient
      $this->Validation->AddRule('OneOrMoreArrayItemRequired', 'function:ValidateOneOrMoreArrayItemRequired');
      $this->Validation->ApplyRule('RecipientUserID', 'OneOrMoreArrayItemRequired');
      
      // Add insert/update fields
      $this->AddInsertFields($FormPostValues);
      //$this->AddUpdateFields($FormPostValues);
      
      // Validate the form posted values
      $Saved = FALSE;
      if ($this->Validate($FormPostValues)) {
         // Get the form field values
         $Fields = $this->Validation->ValidationFields();         
            
         // Define recipients & make sure no duplicates are in list
         $RecipientUserIDs = ArrayValue('RecipientUserID', $Fields, 0);
         $RecipientUserIDs = array_unique($RecipientUserIDs);
         sort($RecipientUserIDs);
         unset($Fields['RecipientUserID']);
         
         // Insert all of the recipients
         foreach ($RecipientUserIDs as $UserID) {
            $Fields['UserID'] = $UserID;
            $Saved = $this->SQL->Insert($this->Name, $Fields);
            
            // Update the cached badge count per user
      		$BadgeCount = $this->BadgeCount($UserID);
      		$this->SQL->Update('User')
      			->Set('CountBadges', $BadgeCount)
      			->Where('UserID', $UserID)
      			->Put();
            
            // Notify users of their badge.
            AddActivity($Session->UserID, 'Badge', '', $UserID, '/badge/'.$Fields['BadgeID'], FALSE);
         }
         
         // Update the cached recipient count on the badge
   		$RecipientCount = $this->RecipientCount($Fields['BadgeID']);
   		$this->SQL->Update('Badge')
   			->Set('CountRecipients', $RecipientCount)
   			->Where('BadgeID', $Fields['BadgeID'])
   			->Put();
      }
      
      return $Saved;
   }

}