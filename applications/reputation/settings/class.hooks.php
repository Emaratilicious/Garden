<?php if (!defined('APPLICATION')) exit();
/**
 * Places that Reputation hooks into other applications.
 *
 * @package Reputation
 * 
 * @todo UserModel Search - match by badge name
 */
class ReputationHooks implements Gdn_IPlugin {   
   /**
	 * Adds items to Dashboard menu.
	 * 
    * @since 2.1.0
	 * @param object $Sender DashboardController.
	 */
   public function Base_GetAppSettingsMenuItems_Handler(&$Sender) {
      $Menu = &$Sender->EventArguments['SideMenu'];
      $Menu->AddLink('Reputation', T('Badges'), 'reputation/badge/all', 'Reputation.Badges.Manage');
      $Menu->AddLink('Reputation', T('Subscriptions'), 'reputation/subscription/all', 'Reputation.Subscriptions.Manage');
      //$Menu->AddLink('Reputation', T('Likes'), 'reputation/', 'Reputation.');
   }
   
   /**
	 * Evaluate subscription status at sign in.
	 * 
    * @since 2.1.0
	 * @param object $Sender EntryController.
	 */
   public function EntryController_SignIn_Handler($Sender) {
      // @todo Probably should go at Session start instead.
   }
   
   /**
    * Add 'Subscriptions' to profile menu.
    *
    * @since 2.1.0
    * @access public
    */
   public function ProfileController_AfterAddSideMenu_Handler(&$Sender) {
      $Session = Gdn::Session();
      if ($Session->IsValid() && $Session->UserID == $Sender->User->UserID) {
         $SideMenu = $Sender->EventArguments['SideMenu'];
         $SideMenu->AddLink('Options', T('My Subscriptions'), '/profile/subscriptions/'.$Sender->User->Name, 'Reputation.Subscriptions.Subscribe');
         $Sender->EventArguments['SideMenu'] = $SideMenu;
      }
   }
   
   /**
    * Allow user to view current & available subscriptions.
    *
    * @since 2.1.0
    * @access public
    */
   public function ProfileController_Subscriptions_Create($Sender) {
      $Session = Gdn::Session();
      $SubscriptionModel = new SubscriptionModel();
      $SubscriptionCostModel = new SubscriptionCostModel();
      
      $Sender->SubscriptionData = $SubscriptionModel->Get();
      $Sender->Subscriptions = array();
      foreach ($Sender->SubscriptionData as $Subscription) {
         $Sender->Subscriptions = $Subscription;
         $Sender->Subscriptions->CostData = $SubscriptionCostModel->GetBySubscription($Subscription->SubscriptionID);
      }
      
      $Sender->MySubscriptionData = $SubscriptionModel->GetByUser($Session->UserID);
      
      //$Sender->GetUserInfo($UserReference, $Username, $UserID);
      
      // Mess to set the view
      $Sender->ApplicationFolder = 'Reputation';
      $Sender->ControllerName = 'Subscription';
      $Sender->View = 'profile';
      $Sender->Render();
   }
   
   /**
    * Purchase or renew a subscription.
    *
    * @since 2.1.0
    * @access public
    */
   public function ProfileController_Subscribe_Create($Sender) {
      $SubscriptionModel = new SubscriptionModel();
      $SubscriptionCostModel = new SubscriptionCostModel();
      
      $SubscriptionCostID = ArrayValue(0, $Sender->RequestArgs, '');
      
      $Sender->SubscriptionCost = $SubscriptionCostModel->GetID($SubscriptionCostID);
      $Sender->Subscription = $SubscriptionModel->GetID($Sender->SubscriptionCost->SubscriptionID);
      
      $Sender->PaymentLink = 'http://google.com';
      
      // Mess to set the view
      $Sender->ApplicationFolder = 'Reputation';
      $Sender->ControllerName = 'Subscription';
      $Sender->View = 'profilesubscribe';
      $Sender->Render();
   }
   
   /**
    * Add 'Badges' tab to profiles.
    *
    * @since 2.1.0
    * @access public
    */
   public function ProfileController_Render_Before($Sender) {
      if (CheckPermission('Reputation.Badges.View')) {
         $Badges = T('Badges');
         $BadgesHtml = $Badges;
         
         // Count
         $CountBadges = $Sender->User->CountBadges;
         if (is_numeric($CountBadges) && $CountBadges > 0)
            $BadgesHtml .= '<span>'.$CountBadges.'</span>';
         
         $Sender->AddProfileTab($Badges, 'profile/badges/'.$Sender->User->UserID.'/'.urlencode($Sender->User->Name), 'Badges', $BadgesHtml);
      }
   }
   
   /**
    * Show user's badges in profile.
    */
   public function ProfileController_Badges_Create($Sender) {
      $Sender->Permission('Reputation.Badges.View');
      
      // User data
      $UserReference = ArrayValue(0, $Sender->RequestArgs, '');
		$Username = ArrayValue(1, $Sender->RequestArgs, '');
		
      // Tell the ProfileController what tab to load
		$Sender->GetUserInfo($UserReference, $Username);
      $Sender->SetTabView('Badges', 'profile', 'Badge', 'Reputation');
      
      // Get User's badges
      $UserBadgeModel = new UserBadgeModel();
      $Sender->BadgeData = $UserBadgeModel->GetBadges($Sender->User->UserID);
      
      $Sender->Render();
   }
   
   /**
    * Add 'Give badge' button profiles.
    */
   public function ProfileController_X_Hander($Sender) {
      if (CheckPermission('Reputation.Badges.Give')) {
         // @todo
      }
   }
   
   /**
    * Allow users to 'like' discussions.
    *
    * @todo Complete
    */
   public function DiscussionController_Like_Create($Sender) {
      $Sender->Permission('Reputation.Likes.Give');
      list($DiscussionID, $TransientKey) = $Sender->RequestArgs;
      $Session = Gdn::Session();
      
      $State = FALSE;
      if (is_numeric($DiscussionID) && $DiscussionID > 0 && $Session->ValidateTransientKey($TransientKey)) {
         $Discussion = NULL;
         $State = $Sender->DiscussionModel->LikeDiscussion($DiscussionID, $Session->UserID, $Discussion);
      }
      
      $this->SetJson('State', $State);
      $this->SetJson('ButtonLink', T($State ? 'Unlike' : 'Like'));
      $this->SetJson('AnchorTitle', T($State ? 'Unlike' : 'Like'));
      
      $this->Render();
   }
   
   /**
    * Allow users to 'like' comments.
    */
   public function CommentController_Like_Create($Sender) {
      
   }
   
   /**
    * Get 'like' data for a discussion's comments.
    */
   public function DiscussionModel_GetCommentLikes_Create($Sender) {
      
   }
   
   /**
    * Get 'Likes' count with discussion data.
    */
   public function DiscussionModel_AfterDiscussionSummaryQuery_Handler($Sender) {
      //$Sender->SQL->Select('d.Likes');
   }
   
   /**
    * Current user like/unlike a discussion.
    */
   public function DiscussionModel_LikeDiscussion_Create($Sender) {
      
   }
   
   /**
    * Current user like/unlike a comment.
    */
   public function CommentModel_LikeComment_Create($Sender) {
      
   }
   
   /**
    * Special function automatically run upon clicking 'Enable' on your application.
    */
   public function Setup() {
      include(PATH_APPLICATIONS . DS . 'reputation' . DS . 'settings' . DS . 'structure.php');
   }
}