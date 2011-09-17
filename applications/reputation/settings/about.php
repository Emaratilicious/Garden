<?php
/**
 * An associative array of information about this application.
 */
$ApplicationInfo['Reputation'] = array(
   'Description' => "A reputation tool that adds badges, subscriptions, and more.",
   'Version' => APPLICATION_VERSION,
   'RegisterPermissions' => array(
      'Reputation.Badges.View',
      'Reputation.Badges.Give',
      //'Reputation.Badges.Receive',
      'Reputation.Badges.Manage',
      'Reputation.Likes.View',
      'Reputation.Likes.Give',
      'Reputation.Subscriptions.Subscribe',
      'Reputation.Subscriptions.Manage'
   ),
   'SetupController' => 'setup',
   'Author' => "Matt Lincoln Russell",
   'AuthorEmail' => 'lincoln@vanillaforums.com',
   'AuthorUrl' => 'http://lincolnwebs.com',
   'License' => 'GNU GPL2'
);