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

  //Import the user aux class
  use \Aux\Welcome as AuxWelcome;
  use \Model\UserModel;

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
      if ($this->checkLogin()) {
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
     * Set the login cookies and redirects to the profile page
     * @note this is an aux method
     * @access private
     * @return Response
     * @see this::post_checkUser
     * @see this::post_registerUser
     */
    private function login($userId) {
      \Auth::remember_me($userId);
		  Cookie::set('user_id', $userId);
		  Cookie::set('login_hash', Session::get('login_hash'));
      Response::redirect('profile', 'location');
    }
    
    /**
	   * Login an user in the store
	   * @access post
	   * @return Response
	   */
    public function post_checkUser() {
	    //check if the user credentials are correct
      if ($userId = AuxWelcome::loginUser()) {
        //if they are login the user
        $this->login($userId);
      } else {
        //if not print an error message
        echo '<script language="javascript">alert("Sorry, wrong user and/or password");</script>';
        Response::redirect('/#toregister', 'refresh');
      }
    }

	  /**
	   * Register a new user in the database
	   * @access post
	   * @return Response
	   */
    public function post_registerUser() {
      //try to register the user
      $status = AuxWelcome::registerUser();
      if ($status[0]) {
        //if the register process worked, login the user
        $this->login($status[1]);
      } else {
        //if not, print the error message
        echo '<script language="javascript">alert("'.$status[1].'");</script>';
        Response::redirect('/#toregister', 'refresh');
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