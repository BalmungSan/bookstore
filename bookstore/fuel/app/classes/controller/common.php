<?php
  /**
   * Copyright 2017 BookStore
   *
   * Licensed under the Apache License, Version 2.0 (the "License");
   * you may not use this file except in compliance with the License.
   * You may obtain a copy of the License at
   *
   *  http://www.apache.org/licenses/LICENSE-2.0
   *
   * Unless required by applicable law or agreed to in writing, software
   * distributed under the License is distributed on an "AS IS" BASIS,
   * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   * See the License for the specific language governing permissions and
   * limitations under the License.
   */

  //Import the user model
  use \Model\UserModel;

  /**
   * Common Controller
   * 
   * Provies common methods for all controllers
   *
   * @package  app
   * @extends  Controller_Common
   */
  class Controller_Common extends Controller {
    /**
     * Check if there is a logged user
     * @return the id of the user logged in, or false if there is no user logged in
     * @see Auth
     * @see UserModel::getHash
     */
    protected function checkLogin() {
      //check if the user is logged by session
      if (\Auth::check()) {
        //if yes, return the id using the Auth package
  	    return \Auth::get_user_id()[1];
      } else {
        //if not, try to login using cookies
        $userId      = Cookie::get('user_id', -1);
        $cookie_hash = Cookie::get('login_hash', '');
        $db_hash     = UserModel::getHash($userId);
        if ($userId != -1 && $cookie_hash == $db_hash) {
          //if there are valid credentials in the cookies login the user and return his id
          \Auth::force_login($userId);
          Cookie::set('login_hash', Session::get('login_hash'));
          return $userId;
        } else {
          //if not, return false
          return false;
        }
      }
    }
  }
?>