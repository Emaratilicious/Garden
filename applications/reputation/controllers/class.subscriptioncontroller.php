<?php if (!defined('APPLICATION')) exit();
/**
 * Subscription Controller.
 *
 * @package Reputation
 */
 
/**
 * Individual subscription and doling to users.
 *
 * @since 2.1.0
 * @package Reputation
 * 
 * @todo Currencies
 */
class SubscriptionController extends ReputationController {
   /**
    * Before any call to the controller.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Initialize() {
      parent::Initialize();
      
      if ($this->DeliveryType() == DELIVERY_TYPE_ALL)
         $this->AddSideMenu('reputation/subscription/all');
      
      $this->Title('Subscriptions');
   }
   
   /**
    * Manage subscriptions.
    * 
    * @since 2.1.0
    * @access public
    */
   public function All() {
      $this->Permission('Reputation.Subscriptions.Manage');
      
      $this->SubscriptionData = $this->SubscriptionModel->Get();
      
      
      $this->Render();
   }
   
   /**
    * Add a subscription cost.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Cost($SubscriptionID = '') {
      $this->Permission('Reputation.Subscriptions.Manage');
      
      // Form setup
      $this->Form->SetModel($this->SubscriptionCostModel);
      $this->Form->ShowErrors();
      $this->Units = array_combine(array('Days', 'Years'), array('Days', 'Years'));
      
      $Insert = (isset($this->SubscriptionCost)) ? FALSE : TRUE;
      
      // Form submitted
      if ($this->Form->AuthenticatedPostBack()) {
         if ($SubscriptionCostID = $this->Form->Save()) {
            // Report success and go to list
            $Message = ($Insert) ? T('Created new subscription cost.') : T('Updated subscription cost.');
            $this->InformMessage($Message);
            $SubscriptionID = $this->Form->GetFormValue('SubscriptionID');
            $this->RedirectUrl = Url('subscription/'.$SubscriptionID);
         }
      }
      elseif (!$Insert) {
         $this->Form->AddHidden('SubscriptionCostID', $this->SubscriptionCost->SubscriptionCostID);
         $this->Form->AddHidden('SubscriptionID', $this->SubscriptionCost->SubscriptionID);
         $this->Form->SetData($this->SubscriptionCost);
      }
      else {         
         $this->Form->AddHidden('SubscriptionID', $SubscriptionID);
      }
      
      $this->Render();
   }
   
   /**
    * Delete a subscription.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Delete($SubscriptionID) {
      $this->Permission('Reputation.Subscriptions.Manage');
      
      $this->Subscription = $this->SubscriptionModel->GetID($SubscriptionID);      
      
      // @todo
      //$this->InformMessage = T('Subscription cost deleted.');
      //$this->RedirectUrl = '/subscription/'.$this->SubscriptionCost->SubscriptionID;
      //$this->SetView404();
      $this->Render();
   }
   
   /**
    * Delete a subscription cost.
    * 
    * @since 2.1.0
    * @access public
    */
   public function DeleteCost($SubscriptionCostID) {
      $this->Permission('Reputation.Subscriptions.Manage');
      
      $this->SubscriptionCost = $this->SubscriptionCostModel->GetID($SubscriptionCostID);
      $this->SubscriptionCostModel->Delete($SubscriptionCostID);
      
      $this->InformMessage = T('Subscription cost deleted.');
      $this->RedirectUrl = '/subscription/'.$this->SubscriptionCost->SubscriptionID;
      $this->SetView404();
      $this->Render();
   }
   
   /**
    * Edit a subscription cost.
    * 
    * @since 2.1.0
    * @access public
    */
   public function EditCost($SubscriptionCostID) {
      $this->Permission('Reputation.Subscriptions.Manage');
      
      $this->SubscriptionCost = $this->SubscriptionCostModel->GetID($SubscriptionCostID);
      
      $this->View = 'cost';
      $this->Cost();
   }
   
   /**
    * View a subscription.
    * 
    * @since 2.1.0
    * @access public
    * @param $SubscriptionID
    */
   public function Index($SubscriptionID = '') {
      if (!$SubscriptionID)
         Redirect('subscription/all');
         
      $this->Subscription = $this->SubscriptionModel->GetID($SubscriptionID);
      $this->SubscriptionCostData = $this->SubscriptionCostModel->GetWhere(array('SubscriptionID' => $SubscriptionID));
      
      if (!$this->Subscription->SubscriptionID)
         throw new Exception(T('Subscription not found.'), 404);
      
      $this->Render();
   }
   
   /**
    * Add or edit a subscription.
    * 
    * @since 2.1.0
    * @access public
    * @param $SubscriptionID
    */
   public function Manage($SubscriptionID = '') {
      $this->Permission('Reputation.Subscriptions.Manage');
      
      // Form setup
      $this->Form->SetModel($this->SubscriptionModel);
      $this->Form->ShowErrors();
            
      $Insert = (is_numeric($SubscriptionID)) ? FALSE : TRUE;
      
      // Roles list
      $RoleModel = new RoleModel();
      $this->Roles = $RoleModel->GetWhere(array('Name not' => array('Guest', 'Administrator', 'Confirm Email')));
      
      // Form submitted
      if ($this->Form->AuthenticatedPostBack()) {
         // If editing, set BadgeID
         if (!$Insert)
            $this->Form->SetFormValue('SubscriptionID', $SubscriptionID);
         
         // Subscription successfully saved
         if ($SubscriptionID = $this->Form->Save()) {
            // Report success and go to list
            $SubscriptionName = $this->Form->GetFormValue('Name');
            $Message = ($Insert) ? T('Created new subscription') : T('Updated subscription');
            $Message .= ' &ldquo;' . $SubscriptionName . '&rdquo;';
            $this->InformMessage($Message);
            $this->RedirectUrl = Url('subscription/'.$SubscriptionID);
         }
      }
      elseif (!$Insert) {
         // Editing an badge
         $this->Subscription = $this->SubscriptionModel->GetID($SubscriptionID);
         $this->Form->SetData($this->Subscription);
      }
      
      $this->Render();
   }
   
   /**
    * Purchase or renew a subscription.
    * 
    * @since 2.1.0
    * @access public
    * @param $SubscriptionID
    */
   public function Subscribe($SubscriptionID) {
      
      $this->Render();
   }
   
}