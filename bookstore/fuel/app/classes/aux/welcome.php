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
  
  //this class lives in the Aux namespace
  namespace Aux;

  //import the user model and dto
  use \Model\UserModel;
  use \Model\UserDTO;
  //import the input class
  use \Input;
  
  /**
   * The Welcome Aux Class.
   *
   * an auxiliary class with the operations of login and sign up
   * used to unify the normal and rest controllers
   *
   * @package  app
   */
  class Welcome {
    /**
	   * Login an user
	   * @access app
	   * @return the user id of the given credentials or false if the credentials are invalid
	   * @see UserModel::loginUser
	   */
    public static function loginUser() {
	    //get the user email and password
      $email    = Input::post('emaillogin');
      $password = Input::post('passwordlogin');
	  
	    //check if the user credentials are correct
      return UserModel::loginUser($email, $password);
    }
    
    /**
	   * Register a new user in the database
	   * @access app
	   * @return a tuple of from (true, user id) if the register process succeed or (false, error) if not
	   * @see UserModel::registerUser
	   */
    public static function registerUser() {
	    //get the user data
      $user = new UserDTO();
      $user->setEmail(Input::post('emailsignup'));
      $user->setName(Input::post('namesignup'));
      $user->setCity(Input::post('citysignup'));
      $user->setAddress(Input::post('addresssignup'));
	  
	    //check that both passwords are the same
      $password1 = Input::post('passwordsignup');
      $password2 = Input::post('passwordsignup_confirm');
      if ($password1 != $password2) {
        //if not, return the error message
        return array(false, 'Sorry, passwords does not match');
      }
	  
	    //try to register the user in the database
      try {
        if (UserModel::registerUser($user, $password1)) {
		    //if works, return the user id
		    return array(true, $user->getId());
		    } else {
		      //if not, return the error message
		      return array(false, 'Sorry, email already exists');
		    }
      } catch(Exception $e) {
		    //if something fail, return an error message
		    return array(false, 'Sorry, there was a problem. Please try again later');
      }
    }
  }
?>