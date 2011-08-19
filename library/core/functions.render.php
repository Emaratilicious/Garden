<?php if (!defined('APPLICATION')) exit();
/*
Copyright 2008, 2009 Vanilla Forums Inc.
This file is part of Garden.
Garden is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Garden is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Garden.  If not, see <http://www.gnu.org/licenses/>.
Contact Vanilla Forums Inc. at support [at] vanillaforums [dot] com
*/

/**
* 1. <li<?php echo Alternate()?>>
* Result: <li class="Alt"> and <li>
* 2. <li class="<?php echo Alternate('AltA', 'AltB')"?>>
* Result: <li class="AltA"> and <li class="AltB">
*/
if (!function_exists('Alternate')) {
   function Alternate($Odd = 'Alt', $Even = '', $AttributeName = 'class'){
      static $i = 0;
      $Value = $i++ % 2 ? $Odd : $Even;
      if($Value != '' && $Even == '')
         $Value = ' '.$AttributeName.'="'.$Value.'"';
      return $Value;
   }
}

if (!function_exists('CountString')) {
   function CountString($Number, $Url = '', $Options = array()) {
      if (is_string($Options))
         $Options = array('cssclass' => $Options);
      $Options = array_change_key_case($Options);
      $CssClass = GetValue('cssclass', $Options, '');

      if ($Number === NULL && $Url) {
         $CssClass = ConcatSep(' ', $CssClass, 'Popin TinyProgress');
         $Url = htmlspecialchars($Url);
         $Result = "<span class=\"$CssClass\" rel=\"$Url\"></span>";
      } elseif ($Number) {
         $Result = " <span class=\"Count\">$Number</span>";
      } else {
         $Result = '';
      }
      return $Result;
   }
}

/**
 * Writes an anchor tag
 */
if (!function_exists('Anchor')) {
   /**
    * Builds and returns an anchor tag.
    */
   function Anchor($Text, $Destination = '', $CssClass = '', $Attributes = '', $ForceAnchor = FALSE) {
      if (!is_array($CssClass) && $CssClass != '')
         $CssClass = array('class' => $CssClass);

      if ($Destination == '' && $ForceAnchor === FALSE)
         return $Text;
      
      if ($Attributes == '')
         $Attributes = array();
			
		$SSL = GetValue('SSL', $Attributes, NULL);
		if ($SSL)
			unset($Attributes['SSL']);
		
		$WithDomain = GetValue('WithDomain', $Attributes, FALSE);
		if ($WithDomain)
			unset($Attributes['WithDomain']);

      $Prefix = substr($Destination, 0, 7);
      if (!in_array($Prefix, array('https:/', 'http://', 'mailto:')) && ($Destination != '' || $ForceAnchor === FALSE))
         $Destination = Gdn::Request()->Url($Destination, $WithDomain, $SSL);

      return '<a href="'.htmlspecialchars($Destination, ENT_COMPAT, 'UTF-8').'"'.Attribute($CssClass).Attribute($Attributes).'>'.$Text.'</a>';
   }
}

/**
 * English "possessive" formatting.
 * This can be overridden in language definition files like:
 * /applications/garden/locale/en-US/definitions.php.
 */
if (!function_exists('FormatPossessive')) {
   function FormatPossessive($Word) {
		if(function_exists('FormatPossessiveCustom'))
			return FormatPossesiveCustom($Word);
			
      return substr($Word, -1) == 's' ? $Word."'" : $Word."'s";
   }
}

if (!function_exists('HoverHelp')) {
   function HoverHelp($String, $Help) {
      return Wrap($String.Wrap($Help, 'span', array('class' => 'Help')), 'span', array('class' => 'HoverHelp'));
   }
}

/**
 * Writes an Img tag.
 */
if (!function_exists('Img')) {
   /**
    * Returns an img tag.
    */
   function Img($Image, $Attributes = '', $WithDomain = FALSE) {
      if ($Attributes == '')
         $Attributes = array();

      if ($Image != '' && substr($Image, 0, 7) != 'http://' && substr($Image, 0, 8) != 'https://')
         $Image = SmartAsset($Image, $WithDomain);

      return '<img src="'.$Image.'"'.Attribute($Attributes).' />';
   }
}

if (!function_exists('IPAnchor')) {
   /**
    * Returns an IP address with a link to the user search.
    */
   function IPAnchor($IP, $CssClass = '') {
      if ($IP)
         return Anchor(htmlspecialchars($IP), '/user/browse?keywords='.urlencode($IP), $CssClass);
      else
         return $IP;
   }
}

if (!function_exists('Meta')) {
   function Meta($Data, $Name, $Options = NULL) {
      $Label = GetValue('Label', $Options, '');
      $HasLabel = !empty($Label);
      $Wrap = TRUE;
      $Value = GetValue($Name, $Data, NULL);
      
      if ($Options === NULL) {
         // Check to see if there are options defined for this name in the controller.
         $Options = Gdn::Controller()->Data("_MetaFormat.$Name", NULL);
         
         if ($Options === NULL) {
            // Try and infer a format based on the name.
            if (StringBeginsWith($Name, 'Date')) {
               $Options = array('Format' => 'Date');
            } elseif (StringBeginsWith($Name, $Data)) {
               $Options = array('Format' => 'BigNumber');
            } else {
               $Options = array();
            }
         }
      }
      
      if (is_string($Options) || (is_array($Options) && isset($Options[0])))
         $Options = array('Format' => (array)$Options);
      
      $Format = (array)GetValue('Format', $Options);
      
      switch (strtolower($Format[0])) {
         case 'bignumber':
            $Item = Gdn_Format::BigNumber($Value);
            $HasLabel = TRUE;
            break;
         case 'callback':
            $Item = call_user_func($Format[1], $Name, $Data);
            break;
         case 'date':
            $Item = Gdn_Format::Date($Value, 'html');
            $HasLabel = TRUE;
            break;
         case 'plural':
            $Item = Plural($Value, $Format[1], $Format[2], GetValue(3, $Format, NULL));
            break;
         case 'sprintf':
            $Item = sprintf($Format[1], $Value);
            break;
         case 'tag':
            $Wrap = FALSE;
            if (!is_array($Value))
               $Value = $Value ? array($Label ? $Label : $Name) : array();
            $Label = FALSE;
         
            $Item = array();
            foreach ($Value as $Tag) {
               $Item[] = Wrap(htmlspecialchars(T($Tag)), 'span', array('class' => "Tag Tag-$Tag"));
            }
            $Item = implode(' ', $Item);
            break;
         case 'user':
            $Item = UserAnchor($Data, 'User-Inline', array('Prefix' => $Name, 'Photo' => TRUE));
            $HasLabel = TRUE;
            break;
         default:
            $Item = $Value;
            $HasLabel = TRUE;
      }
      
      if (empty($Item))
         return NULL;
      
      $Result = '';
      if ($HasLabel && $Label !== FALSE) {
         if ($Label)
            $Label = T($Label);
         else {
            $Label = T($Name, '');
            if (!$Label)
               $Label = UnCamelCase($Label);
         }
      }
      if ($Label) {
         $Result .= Wrap(htmlspecialchars($Label), 'span', array('class' => 'Meta-Label')).' ';
         $ValueClass = 'Meta-Value';
      } else {
         $ValueClass = 'Meta-Value Meta-NameValue';
      }
      
      if ($Wrap)
         $Result .= Wrap($Item, 'span', array('class' => $ValueClass));
      else
         $Result .= $Item;
      
      return Wrap($Result, 'span', array('class' => 'Meta'));
   }
}

if (!function_exists('MetaList')) {
   function MetaList($Data, $MetaFormat = NULL) {
      if (!$MetaFormat)
         $MetaFormat = Gdn::Controller()->Data('_MetaFormat');
      
      $Result = array();
      foreach ($MetaFormat as $Name => $Options) {
         $Meta = Meta($Data, $Name, $Options);
         if ($Meta)
            $Result[] = $Meta;
      }
      $Result = '<div class="MetaList">'.implode("\n", $Result).'</div>';
      return $Result;
   }
}

/**
 * English "plural" formatting.
 * This can be overridden in language definition files like:
 * /applications/garden/locale/en-US/definitions.php.
 */
if (!function_exists('Plural')) {
   function Plural($Number, $Singular, $Plural, $Zero = NULL) {
      if ($Zero === NULL)
         $Zero = $Plural;
		
      // Make sure to fix comma-formatted numbers
      $WorkingNumber = str_replace(',', '', $Number);
      
      switch ($WorkingNumber) {
         case 0:
            return $Zero ? sprintf(T($Zero), $Number) : '';
         case 1:
            return sprintf(T($Singular), $Number);
         default;
            return sprintf(T($Plural), $Number);
      }
   }
}

function UnCamelCase($Str) {
   $Str = preg_replace('`(?<![A-Z0-9])([A-Z0-9])`', ' $1', $Str);
   $Str = preg_replace('`([A-Z0-9])(?=[a-z])`', ' $1', $Str);
   $Str = trim($Str);
   return $Str;
}

/**
 * Takes a user object, and writes out an achor of the user's name to the user's profile.
 */
if (!function_exists('UserAnchor')) {
   function UserAnchor($User, $CssClass = '', $Options = NULL) {
      if (is_string($Options)) {
         $Prefix = $Options;
         $Options = array();
      } elseif (is_array($Options)) {
         $Prefix = GetValue('Prefix', $Options, '');
      } else {
         $Prefix = '';
         $Options = array();
      }
      
      if ($Prefix)
         $User = UserBuilder($User, $Prefix);
      
      $Name = GetValue('Name', $User, T('Unknown'));
      if (!$Name)
         return '';
      else
         $Name = htmlspecialchars($Name);
      
      if (GetValue('Photo', $Options)) {
         $Name = UserPhoto($User, array('Link' => FALSE, 'ImageClass' => 'ProfilePhotoInline')).$Name;
      }

      if ($CssClass != '')
         $CssClass = ' class="'.$CssClass.'"';

      return '<a href="'.htmlspecialchars(Url(UserUrl($User))).'"'.$CssClass.'>'.$Name.'</a>';
   }
}

/**
 * Takes an object & prefix value, and converts it to a user object that can be
 * used by UserAnchor() && UserPhoto() to write out anchors to the user's
 * profile. The object must have the following fields: UserID, Name, Photo.
 */
if (!function_exists('UserBuilder')) {
   function UserBuilder($Object, $UserPrefix = '') {
		$Object = (object)$Object;
      $User = new stdClass();
      $UserID = $UserPrefix.'UserID';
      $Name = $UserPrefix.'Name';
      $Photo = $UserPrefix.'Photo';
      $User->UserID = $Object->$UserID;
      $User->Name = $Object->$Name;
      $User->Photo = property_exists($Object, $Photo) ? $Object->$Photo : '';
      $User->Email = GetValue($UserPrefix.'Email', $Object, NULL);
		return $User;
   }
}

/**
 * Takes a user object, and writes out an anchor of the user's icon to the user's profile.
 */
if (!function_exists('UserPhoto')) {
   function UserPhoto($User, $Options = array()) {
      if ($Px = GetValue('Prefix', $Options)) {
         $User = UserBuilder($User, $Px);
      } else {
         $User = (object)$User;
      }
      if (is_string($Options))
         $Options = array('LinkClass' => $Options);
      
      $LinkClass = GetValue('LinkClass', $Options, 'ProfileLink');
      $ImgClass = GetValue('ImageClass', $Options, 'ProfilePhotoMedium');
      
      $LinkClass = $LinkClass == '' ? '' : ' class="'.$LinkClass.'"';

      $Photo = $User->Photo;
      if (!$Photo && function_exists('UserPhotoDefaultUrl'))
         $Photo = UserPhotoDefaultUrl($User);

      if ($Photo) {
         if (!preg_match('`^https?://`i', $Photo)) {
            $PhotoUrl = Gdn_Upload::Url(ChangeBasename($Photo, 'n%s'));
         } else {
            $PhotoUrl = $Photo;
         }
         
         $Img = Img($PhotoUrl, array('alt' => htmlspecialchars($User->Name), 'class' => $ImgClass));
         
         if (GetValue('Link', $Options, TRUE)) {
            return '<a title="'.htmlspecialchars($User->Name).'" href="'.Url('/profile/'.$User->UserID.'/'.rawurlencode($User->Name)).'"'.$LinkClass.'>'
               .$Img
               .'</a>';
         } else {
            return $Img;
         }
      } else {
         return '';
      }
   }
}

if (!function_exists('UserUrl')) {
   /**
    * Return the url for a user.
    * @param array|object $User The user to get the url for.
    * @return string The url suitable to be passed into the Url() function.
    */
   function UserUrl($User) {
      return '/profile/'.rawurlencode(GetValue('Name', $User));
   }
}


/**
 * Wrap the provided string in the specified tag. ie. Wrap('This is bold!', 'b');
 */
if (!function_exists('Wrap')) {
   function Wrap($String, $Tag = 'span', $Attributes = '') {
		if ($Tag == '')
			return $String;
		
      if (is_array($Attributes))
         $Attributes = Attribute($Attributes);
         
      return '<'.$Tag.$Attributes.'>'.$String.'</'.$Tag.'>';
   }
}
/**
 * Wrap the provided string in the specified tag. ie. Wrap('This is bold!', 'b');
 */
if (!function_exists('DiscussionLink')) {
   function DiscussionLink($Discussion, $Extended = TRUE) {
      $DiscussionID = GetValue('DiscussionID', $Discussion);
      $DiscussionName = GetValue('Name', $Discussion);
      $Parts = array(
         'discussion',
         $DiscussionID,
         Gdn_Format::Url($DiscussionName)
      );
      if ($Extended) {
         $Parts[] = ($Discussion->CountCommentWatch > 0) ? '#Item_'.$Discussion->CountCommentWatch : '';
      }
		return Url(implode('/',$Parts), TRUE);
   }
}

if (!function_exists('RegisterUrl')) {
   function RegisterUrl($Target = '') {
      return '/entry/register'.($Target ? '?Target='.urlencode($Target) : '');
   }
}

if (!function_exists('SignInUrl')) {
   function SignInUrl($Target = '') {
      return '/entry/signin'.($Target ? '?Target='.urlencode($Target) : '');
   }
}

if (!function_exists('SignOutUrl')) {
   function SignOutUrl($Target = '') {
      return '/entry/signout?TransientKey='.urlencode(Gdn::Session()->TransientKey()).($Target ? '&Target='.urlencode($Target) : '');
   }
}