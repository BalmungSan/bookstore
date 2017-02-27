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
  use \Aux\Welcome as AuxWelcome;
  
  /**
   * Rest Welcome Controller
   * 
   * Provides an API to manipulate users data in the app
   *
   * @package  app
   * @extends  Controller_Rest
   */
  class Controller_api_Welcome extends Controller_api_Common {
    /**
     * Override the default checkLogin to always authorize the access
     * as the actions of this controller dont need authentication
     * @return always true
     */
    protected function checkLogin() {
      return true;
    }
    
    /**
	   * Gets the id of user using his email and password
	   * @access post
	   * @return a json with the id of the user or false if credentials are invalid
	   */
    public function post_checkUser() {
      return $this->response(array('user id' => AuxWelcome::loginUser()));
    }
    
    /**
     * Register a new user in the database
	   * @access post
	   * @return a json of from (succeed => true, user id => id) if the register process succeed or (succeed  => false, error => message) if not
     */
    public function post_registerUser() {
      //try to register the user
      $status = AuxWelcome::registerUser();
      if ($status[0]) {
        //if the register process worked, return the user id
        return $this->response(array('succeed' => true,
                                     'user id' => $status[1]));
      } else {
        //if not, return the error message
        return $this->response(array('succeed' => false,
                                     'error'   => $status[1]));
      }
    }
    
    /**
     * Get the list of all cities
	   * @access post
	   * @return a json with all cities
     */
    public function post_getCities() {
      return $this->response(array('cities' => AuxWelcome::getCities()));
    }
  }
?>