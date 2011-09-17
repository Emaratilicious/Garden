<?php if (!defined('APPLICATION')) exit();
/**
 * Badge Controller.
 * 
 * @package Reputation
 */
 
/**
 * Individual badges and doling to users.
 * 
 * @since 2.1.0
 * @package Reputation
 * 
 * @todo Reasons
 * @todo Image per
 * @todo Points
 * @todo Requestable
 * @todo Graduated abilities
 */
class BadgeController extends ReputationController {   
   /**
    * Before any call to the controller.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Initialize() {
      parent::Initialize();
      $this->Title('Badges');
   }
   
   /**
    * Manage badges.
    * 
    * @since 2.1.0
    * @access public
    */
   public function All() {
      $this->Permission('Reputation.Badges.Manage');
      
      $this->BadgeData = $this->BadgeModel->GetList();
      
      $this->AddSideMenu('reputation/badge/all'); 
      $this->Render();
   }
   
   /**
    * Delete an badge & revoke from all users.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Delete($BadgeID = '') {
      $this->Permission('Reputation.Badges.Manage');
      $Session = Gdn::Session();
      
      // Validate BadgeID
      if (!is_numeric($BadgeID))
         Redirect('reputation/badge/all');
      
      // Form setup
      $this->Form->SetModel($this->BadgeModel);
      
      // Form submitted (confirmation)
      if ($this->Form->AuthenticatedPostBack()) {
         // Delete & revoke
         $this->BadgeModel->Delete(array('BadgeID' => $BadgeID));
         $this->UserBadgeModel->Delete(array('BadgeID' => $BadgeID));
         
         // Success & redirect
         $this->InformMessage(T('Badge deleted.'));
         $this->RedirectUrl = Url('reputation/badge/all');
      }
      else {
         // Get info for confirmation
         $this->Badge = $this->BadgeModel->GetID($BadgeID);
      }
      
      $this->Render();
   }
   
   /**
    * Disable/enable an badge from being given. It will still show on users who have it.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Disable($BadgeID = '', $TransientKey = '') {
      $this->Permission('Reputation.Badges.Manage');
      $Session = Gdn::Session();
      
      if ($Session->ValidateTransientKey($TransientKey) && is_numeric($BadgeID)) {
         // Reverse whether it's active
         $Value = ($this->BadgeModel->GetID($BadgeID)->Active) ? 0 : 1;
         $this->BadgeModel->SetProperty($BadgeID, 'Active', $Value);
         $Message = ($Value) ? 'Badge disabled.' : 'Badge enabled.';
         $this->InformMessage($Message);
      }
      
      if ($this->_DeliveryType === DELIVERY_TYPE_ALL)
         Redirect(GetIncomingValue('Target', $this->SelfUrl));
      
      $this->SetView404();
      $this->Render();
   }
   
   /**
    * Give selected badge to 1 or more users.    
    * 
    * @since 2.1.0
    * @access public
    */
   public function Give($BadgeID = '') {
      $this->Permission('Reputation.Badges.Give');
      
      // Validate BadgeID
      if (!is_numeric($BadgeID))
         Redirect('reputation/badge/all');
      
      // Get info & confirm enabled  
      $this->Badge = $this->BadgeModel->GetID($BadgeID);
      if (!$this->Badge->Active)
         $this->Form->AddError('Badge is not available.');
   
      // Form setup
      $this->Form->SetModel($this->UserBadgeModel);
      
      // Form submitted
      if ($this->Form->AuthenticatedPostBack()) {
         // Set BadgeID
         $this->Form->SetFormValue('BadgeID', $BadgeID);
         
         // Set recipients
         $RecipientUserIDs = array();
         $To = explode(',', $this->Form->GetFormValue('To', ''));
         $UserModel = new UserModel();
         foreach ($To as $Name) {
            if (trim($Name) != '') {
               $User = $UserModel->GetByUsername(trim($Name));
               if (is_object($User))
                  $RecipientUserIDs[] = $User->UserID;
            }
         }
         $this->Form->SetFormValue('RecipientUserID', $RecipientUserIDs);
         
         // Give to named users
         if ($this->Form->Save()) {
            $this->InformMessage(T('Gave badge to users.'));
            $this->RedirectUrl = Url('reputation/badge/all');
         }
      }
      
      $this->Render();
   }
   
   /**
    * Give any badge to selected user.
    * 
    * @since 2.1.0
    * @access public
    */
   public function GiveUser($UserID = '', $Username = '') {
      // NOT READY YET
      Redirect('reputation/badge/all');
      
      $this->Permission('Reputation.Badges.Give');
      
      // Validate UserID
      if (!is_numeric($UserID))
         Redirect('reputation/badge/all');
   
      // Form setup
      $this->Form->SetModel($this->UserBadgeModel);
      
      // Form submitted
      if ($this->Form->AuthenticatedPostBack()) {
         // Set UserID
         $this->FormSetValue('RecipientUserID', array($UserID));
      
         // Validation
         $this->UserBadgeModel->ValidateModel();
         
         // Badge successfully saved
         if ($this->Form->Save()) {
            
         }
      }
      else {
         // Get user data
         $UserModel = new UserModel();
         $this->User = $UserModel->GetID($UserID);
         
         // Get badge list for dropdown
         $this->BadgeData = $this->BadgeModel->GetMenu();
      }
   }
   
   /**
    * Hide/Unhide an badge from being listed. It will still show on users who have it.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Hide($BadgeID = '', $TransientKey = '') {
      $this->Permission('Reputation.Badges.Manage');
      $Session = Gdn::Session();
      
      if ($Session->ValidateTransientKey($TransientKey) && is_numeric($BadgeID)) {
         // Reverse visibility
         $Value = ($this->BadgeModel->GetID($BadgeID)->Visible) ? 0 : 1;
         $this->BadgeModel->SetProperty($BadgeID, 'Visible', $Value);
         $Message = ($Value) ? 'Badge unhidden.' : 'Badge hidden.';
         $this->InformMessage($Message);
      }
      
      if ($this->_DeliveryType === DELIVERY_TYPE_ALL)
         Redirect(GetIncomingValue('Target', $this->SelfUrl));
      
      $this->SetView404();
      $this->Render();
   }
   
   /**
    * View an badge.    
    * 
    * @since 2.1.0
    * @access public
    */
   public function Index($BadgeID = '', $Name = '') {
      $this->Permission('Reputation.Badges.View');
      
      $this->Badge = $this->BadgeModel->GetID($BadgeID);
      $this->SetData('Badge', $this->Badge);
      
      $this->UserData = $this->UserBadgeModel->GetUsers($BadgeID);
      
      $this->AddModule('GiveBadgeModule');
      
      $this->Render();
   }
   
   /**
    * Create or edit an badge.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Manage($BadgeID = '') {
      $this->Permission('Reputation.Badges.Manage');
      
      // Form setup
      $this->Form->SetModel($this->BadgeModel);
      $this->Form->ShowErrors();
            
      $Insert = (is_numeric($BadgeID)) ? FALSE : TRUE;
      
      // Form submitted
      if ($this->Form->AuthenticatedPostBack()) {
         // If editing, set BadgeID
         if (!$Insert)
            $this->Form->SetFormValue('BadgeID', $BadgeID);
      
         // Validation
         //$this->Form->ValidateModel();
         
         // Badge successfully saved
         if ($this->Form->Save()) {
            // Report success and go to list
            $BadgeName = $this->Form->GetFormValue('Name');
            $Message = ($Insert) ? T('Created new badge') : T('Updated badge');
            $Message .= ' &ldquo;' . $BadgeName. '&rdquo;';
            $this->InformMessage($Message);
            $this->RedirectUrl = Url('reputation/badge/all');
         }
      }
      elseif (!$Insert) {
         // Editing an badge
         $this->Badge = $this->BadgeModel->GetID($BadgeID);
         $this->Form->SetData($this->Badge);
      }
      
      $this->Render();
   }
   
   /**
    * Revoke an badge from a user.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Revoke($UserBadgeID = '', $TransientKey = '') {
      $this->Permission('Reputation.Badges.Manage');
      $Session = Gdn::Session();
      
      if ($Session->ValidateTransientKey($TransientKey) && is_numeric($UserBadgeID)) {
         $UserID = $this->UserBadgeModel->Revoke($UserBadgeID);
         $this->InformMessage(T('Revoked badge.'));
         $this->RedirectUrl = Url('profile/badges/'.$UserID.'/x');
      }
         
      $this->SetView404();
      $this->Render();
   }
   
}