<?php
/*
  $Id: password_funcs.php,v 1.10 2003/02/11 01:31:02 hpdl Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

/**
 * validate password
 * compares a plain text password against an encrypted one
 *
 * @param string $plain
 * @param string $encrypted
 * @return boolean true: match, false:no match
 */
////
// This funstion validates a plain text password with an
// encrpyted password
  function validate_password($plain, $encrypted) {
    if (!is_null($plain) && !is_null($encrypted)) {
// split apart the hash / salt
			
      $stack = explode(':', $encrypted);

      if (sizeof($stack) != 2) return false;
     
      if (md5(trim($stack[1]) . trim($plain)) == $stack[0]) {
        return true;
      }
      
    }

    return false;
  }

////
// This function makes a new password from a plaintext password. 
  function encrypt_password($plain) {

    $salt = substr(md5($plain), 0, 2);

    $password = md5(trim($salt) . trim($plain)) . ':' . $salt;

    return $password;
  }
?>
