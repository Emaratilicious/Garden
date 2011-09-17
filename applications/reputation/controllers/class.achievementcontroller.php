<?php if (!defined('APPLICATION')) exit();
/**
 * Achievement Controller.
 * 
 * @package Reputation
 */
 
/**
 * Individual achievements and doling to users.
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
class AchievementController extends ReputationController {   
   /**
    * Before any call to the controller.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Initialize() {
      parent::Initialize();
      $this->Title('Achievements');
   }
   
   /**
    * Manage achievements.
    * 
    * @since 2.1.0
    * @access public
    */
   public function All() {
      $this->Permission('Reputation.Achievements.Manage');
      
      $this->AchievementData = $this->AchievementModel->GetList();
      
      $this->AddSideMenu('reputation/achievement/all'); 
      $this->Render();
   }
   
   /**
    * Delete an achievement & revoke from all users.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Delete($AchievementID = '') {
      $this->Permission('Reputation.Achievements.Manage');
      $Session = Gdn::Session();
      
      // Validate AchievementID
      if (!is_numeric($AchievementID))
         Redirect('reputation/achievement/all');
      
      // Form setup
      $this->Form->SetModel($this->AchievementModel);
      
      // Form submitted (confirmation)
      if ($this->Form->AuthenticatedPostBack()) {
         // Delete & revoke
         $this->AchievementModel->Delete(array('AchievementID' => $AchievementID));
         $this->UserAchievementModel->Delete(array('AchievementID' => $AchievementID));
         
         // Success & redirect
         $this->InformMessage(T('Achievement deleted.'));
         $this->RedirectUrl = Url('reputation/achievement/all');
      }
      else {
         // Get info for confirmation
         $this->Achievement = $this->AchievementModel->GetID($AchievementID);
      }
      
      $this->Render();
   }
   
   /**
    * Disable/enable an achievement from being given. It will still show on users who have it.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Disable($AchievementID = '', $TransientKey = '') {
      $this->Permission('Reputation.Achievements.Manage');
      $Session = Gdn::Session();
      
      if ($Session->ValidateTransientKey($TransientKey) && is_numeric($AchievementID)) {
         // Reverse whether it's active
         $Value = ($this->AchievementModel->GetID($AchievementID)->Active) ? 0 : 1;
         $this->AchievementModel->SetProperty($AchievementID, 'Active', $Value);
         $Message = ($Value) ? 'Achievement disabled.' : 'Achievement enabled.';
         $this->InformMessage($Message);
      }
      
      if ($this->_DeliveryType === DELIVERY_TYPE_ALL)
         Redirect(GetIncomingValue('Target', $this->SelfUrl));
      
      $this->SetView404();
      $this->Render();
   }
   
   /**
    * Give selected achievement to 1 or more users.    
    * 
    * @since 2.1.0
    * @access public
    */
   public function Give($AchievementID = '') {
      $this->Permission('Reputation.Achievements.Give');
      
      // Validate AchievementID
      if (!is_numeric($AchievementID))
         Redirect('reputation/achievement/all');
      
      // Get info & confirm enabled  
      $this->Achievement = $this->AchievementModel->GetID($AchievementID);
      if (!$this->Achievement->Active)
         $this->Form->AddError('Achievement is not available.');
   
      // Form setup
      $this->Form->SetModel($this->UserAchievementModel);
      
      // Form submitted
      if ($this->Form->AuthenticatedPostBack()) {
         // Set AchievementID
         $this->Form->SetFormValue('AchievementID', $AchievementID);
         
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
            $this->InformMessage(T('Gave achievement to users.'));
            $this->RedirectUrl = Url('reputation/achievement/all');
         }
      }
      
      $this->Render();
   }
   
   /**
    * Give any achievement to selected user.
    * 
    * @since 2.1.0
    * @access public
    */
   public function GiveUser($UserID = '', $Username = '') {
      // NOT READY YET
      Redirect('reputation/achievement/all');
      
      $this->Permission('Reputation.Achievements.Give');
      
      // Validate UserID
      if (!is_numeric($UserID))
         Redirect('reputation/achievement/all');
   
      // Form setup
      $this->Form->SetModel($this->UserAchievementModel);
      
      // Form submitted
      if ($this->Form->AuthenticatedPostBack()) {
         // Set UserID
         $this->FormSetValue('RecipientUserID', array($UserID));
      
         // Validation
         $this->UserAchievementModel->ValidateModel();
         
         // Achievement successfully saved
         if ($this->Form->Save()) {
            
         }
      }
      else {
         // Get user data
         $UserModel = new UserModel();
         $this->User = $UserModel->GetID($UserID);
         
         // Get achievement list for dropdown
         $this->AchievementData = $this->AchievementModel->GetMenu();
      }
   }
   
   /**
    * Hide/Unhide an achievement from being listed. It will still show on users who have it.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Hide($AchievementID = '', $TransientKey = '') {
      $this->Permission('Reputation.Achievements.Manage');
      $Session = Gdn::Session();
      
      if ($Session->ValidateTransientKey($TransientKey) && is_numeric($AchievementID)) {
         // Reverse visibility
         $Value = ($this->AchievementModel->GetID($AchievementID)->Visible) ? 0 : 1;
         $this->AchievementModel->SetProperty($AchievementID, 'Visible', $Value);
         $Message = ($Value) ? 'Achievement unhidden.' : 'Achievement hidden.';
         $this->InformMessage($Message);
      }
      
      if ($this->_DeliveryType === DELIVERY_TYPE_ALL)
         Redirect(GetIncomingValue('Target', $this->SelfUrl));
      
      $this->SetView404();
      $this->Render();
   }
   
   /**
    * View an achievement.    
    * 
    * @since 2.1.0
    * @access public
    */
   public function Index($AchievementID = '', $Name = '') {
      $this->Permission('Reputation.Achievements.View');
      
      $this->Achievement = $this->AchievementModel->GetID($AchievementID);
      $this->SetData('Achievement', $this->Achievement);
      
      $this->UserData = $this->UserAchievementModel->GetUsers($AchievementID);
      
      $this->AddModule('GiveAchievementModule');
      
      $this->Render();
   }
   
   /**
    * Create or edit an achievement.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Manage($AchievementID = '') {
      $this->Permission('Reputation.Achievements.Manage');
      
      // Form setup
      $this->Form->SetModel($this->AchievementModel);
      $this->Form->ShowErrors();
            
      $Insert = (is_numeric($AchievementID)) ? FALSE : TRUE;
      
      // Form submitted
      if ($this->Form->AuthenticatedPostBack()) {
         // If editing, set AchievementID
         if (!$Insert)
            $this->Form->SetFormValue('AchievementID', $AchievementID);
      
         // Validation
         //$this->Form->ValidateModel();
         
         // Achievement successfully saved
         if ($this->Form->Save()) {
            // Report success and go to list
            $AchievementName = $this->Form->GetFormValue('Name');
            $Message = ($Insert) ? T('Created new achievement') : T('Updated achievement');
            $Message .= ' &ldquo;' . $AchievementName. '&rdquo;';
            $this->InformMessage($Message);
            $this->RedirectUrl = Url('reputation/achievement/all');
         }
      }
      elseif (!$Insert) {
         // Editing an badge
         $this->Achievement = $this->AchievementModel->GetID($AchievementID);
         $this->Form->SetData($this->Achievement);
      }
      
      $this->Render();
   }
   
   /**
    * Revoke an achievement from a user.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Revoke($UserAchievementID = '', $TransientKey = '') {
      $this->Permission('Reputation.Achievements.Manage');
      $Session = Gdn::Session();
      
      if ($Session->ValidateTransientKey($TransientKey) && is_numeric($UserAchievementID)) {
         $UserID = $this->UserAchievementModel->Revoke($UserAchievementID);
         $this->InformMessage(T('Revoked achievement.'));
         $this->RedirectUrl = Url('profile/achievements/'.$UserID.'/x');
      }
         
      $this->SetView404();
      $this->Render();
   }
   
}