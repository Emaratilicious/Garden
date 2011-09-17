<?php if (!defined('APPLICATION')) exit();
/**
 * Reputation Controller.
 *
 * @package Reputation
 */
 
/**
 * Base controller for Reputation app.
 *
 * @since 2.1.0
 * @package Reputation
 */
class ReputationController extends Gdn_Controller {
   /**
    * Models to include.
    * 
    * @since 2.1.0
    * @access public
    * @var array
    */
   public $Uses = array('Database', 'Form', 'BadgeModel', 'UserBadgeModel', 
      'SubscriptionModel', 'SubscriptionCostModel');
   
   /**
    * This is a good place to include JS, CSS, and modules used by all methods of this controller.
    * Always called by dispatcher before controller's requested method.
    * 
    * @since 2.1.0
    * @access public
    */
   public function Initialize() {
      if ($this->DeliveryType() == DELIVERY_TYPE_ALL) {
         $this->Head = new HeadModule($this);
         
         // Vanilla goodness
         $this->AddJsFile('jquery.js');
         $this->AddJsFile('jquery.livequery.js');
         $this->AddJsFile('jquery.form.js');
         $this->AddJsFile('jquery.popup.js');
         $this->AddJsFile('jquery.gardenhandleajaxform.js');
         $this->AddJsFile('global.js');
         $this->AddJsFile('jquery.autogrow.js');
         $this->AddJsFile('jquery.autocomplete.js');
         $this->AddCssFile('admin.css');
         
         // Reputation goodness
         $this->AddCssFile('reputation.css');
         $this->AddJsFile('badge.js');
         $this->AddJsFile('reputation.js');
         $this->AddJsFile('subscription.js');
      }
      
      // Change master template
      $this->MasterView = 'admin';
      
      // Call Gdn_Controller's Initialize() as well.
      parent::Initialize();
   }
   
   /**
    * Configures navigation sidebar in Dashboard.
    * 
    * @since 2.1.0
    * @access public
    *
    * @param $CurrentUrl Path to current location in dashboard.
    */
   public function AddSideMenu($CurrentUrl) {
      // Only add to the assets if this is not a view-only request
      if ($this->_DeliveryType == DELIVERY_TYPE_ALL) {
         $SideMenu = new SideMenuModule($this);
         $SideMenu->HtmlId = '';
         $SideMenu->HighlightRoute($CurrentUrl);
			$SideMenu->Sort = C('Garden.DashboardMenu.Sort');
         $this->EventArguments['SideMenu'] = &$SideMenu;
         $this->FireEvent('GetAppSettingsMenuItems');
         $this->AddModule($SideMenu, 'Panel');
      }
   }
   
   /**
    * Set view to 404.
    * 
    * @since 2.1.0
    * @access public
    */
   public function SetView404() {
      // Set view to 404 since one is required.
      $this->ApplicationFolder = 'dashboard';
      $this->ControllerName = 'Home';
      $this->View = 'FileNotFound';
   }
}
