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

  //Import the user model and dto
  use \Model\UserModel;
  use \Model\UserDTO;

  /**
   * The Welcome Controller.
   *
   * A basic Login and SignUp page
   *
   * @package  app
   * @extends  Controller_Common
   */
  class Controller_Welcome extends Controller_Common {
    /**
     * The index page
     * @access  public
     * @return  Response
     */
    public function action_index() {
	    //check if the user is logged
      if ($this->check_login()) {
        //if yes go to the profile page
        Response::redirect('/profile', 'location');
      } else {
        //if not, print the loggin page
        $view = View::forge('welcome/index');
        $view->cities = UserModel::getCities();
        return $view;
      }
    }

	  /**
	   * Register a new user in the database
	   * @access post
	   * @return Response
	   */
    public function post_registerUser() {
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
        echo '<script language="javascript">"Sorry, passwords does not match");</script>';
        Response::redirect('/#toregister', 'refresh');
      }
	  
	    //try to register the user in the database
      try {
        if (UserModel::registerUser($user, $password1)) {
		    //if works login the new user
		    $userId = $user->getId();
		    \Auth::remember_me($userId);
		    Cookie::set('user_id', $userId);
		    Cookie::set('login_hash', Session::get('login_hash'));
		    echo '<script language="javascript">alert("Congratulations, you have a new account");</script>';
        Response::redirect('profile', 'location');
		    } else {
		      //if not print an error message
		      echo '<script language="javascript">Sorry, email already exists");</script>';
          Response::redirect('/#toregister', 'refresh');
		    }
      } catch(Exception $e) {
		    //if something fail, print an error message
		    echo '<script language="javascript">alert("Sorry, there was a problem. Please try again later");</script>';
        Response::redirect('/#toregister', 'refresh');
      }
    }

	  /**
	   * Login an user in the store
	   * @access post
	   * @return Response
	   */
    public function post_checkUser() {
	    //get the user email and password
      $email      = Input::post('emaillogin');
      $password   = Input::post('passwordlogin');
	  
	    //check if the user credentials are correct
      $userId = UserModel::loginUser($email, $password);
      if ($userId == null) {
		    //if not print an error message
        $view = View::forge('welcome/index');
        echo '<script language="javascript">alert("Sorry, wrong user and/or password");</script>';
        Response::redirect('/#toregister', 'refresh');
      } else {
		    //if they are login the user
		    \Auth::remember_me($userId);
		    Cookie::set('user_id', $userId);
		    Cookie::set('login_hash', Session::get('login_hash'));
        Response::redirect('profile', 'location');
      }
    }

    /**
     * The 404 action for the application.
     * @access  public
     * @return  Response
     */
    public function action_404() {
      Response::forge(Presenter::forge('welcome/404'), 404);
    }
  }
?>