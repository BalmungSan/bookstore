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
	   * Get all data of the current logged user
	   * @access app
	   * @return an array with all of the data
	   * @see UserModel::getUser
	   * @see UserDTO::toArray
	   */
    public function post_getUser() {
      return $this->response(AuxUser::getUser());
    }
    
    /**
	   * Edit the data of an user
	   * @access app
	   * @return true if the update process worked, false otherwise
	   * @see UserModel::editUser
	   */
    public function post_editUser() {
      return $this->response(array('succeed' => AuxUser::editUser()));
    }
  }
?>