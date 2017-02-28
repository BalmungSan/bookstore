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
   
  //Import the welcome aux class
  use \Aux\User as AuxUser;
  
  /**
   * Rest User Controller
   * 
   * Provides an API to manipulate users data in the app
   *
   * @package  app
   * @extends  Controller_Rest
   */
  class Controller_api_User extends Controller_api_Common {
    /**
	   * Get all data of the user
	   * @access post
	   * @return a json with all of the user data
	   */
    public function post_getUser() {
      return $this->response(AuxUser::getUser());
    }
    
    /**
	   * Edit the data of an user
	   * @access post
	   * @return a json with the 'succeed' variable set to true if the update process worked, false otherwise
	   */
    public function post_editUser() {
      return $this->response(array('succeed' => AuxUser::editUser()));
    }
    
    /**
	   * Change the password of an user
	   * @access post
	   * @return a json with the 'succeed' variable set to true if the update process worked,
	   * otherwise the value will be false and the 'error' variable will be set with the error message
	   */
    public function post_changePassword() {
      //try to change the password
      $status = AuxUser::changePassword();
      if ($status[0]) {
        //if the password change process worked, return succeed as true
        return $this->response(array('succeed' => true));
      } else {
        //if not, return succeed as false and the error message
        return $this->response(array('succeed' => false, 'error' => $status[1]));
      }
    }
    
    /**
	   * Delete an user account
	   * @access post
	   * @return a json with the 'succeed' variable set to true if the delete process worked,
	   * otherwise the value will be false and the 'error' variable will be set with the error message
	   */
    public function post_delete() {
      //try to delete the account
      $status = AuxUser::deleteUser();
      if ($status[0]) {
        //if the delete process worked, return succeed as true
        return $this->response(array('succeed' => true));
      } else {
        //if not, return succeed as false and the error message
        return $this->response(array('succeed' => false, 'error' => $status[1]));
      }
    }
  }
?>