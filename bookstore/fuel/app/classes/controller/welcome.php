<?php
  /**
   * Copyright 2017 Luis Miguel Mejía Suárez (BalmungSan)
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
   * @extends  Controller
   */
  class Controller_Welcome extends Controller {
    /**
     * The basic welcome message
     * @access  public
     * @return  Response
     */
    public function action_index() {
      if(!$user = Session::get('userInfo')){
        $view = View::forge('welcome/index');
        $view->cities = UserModel::getCities();
        return $view;
      }

      Response::redirect('store','location');
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
      if(!($password1 == $password2)){
        echo '<script language="javascript">';
        echo 'alert("Sorry, passwords does not match")';
        echo '</script>';
        Response::redirect_back('/', 'refresh');
      }
	  
	  //try to register the user in the database
      try{
        $result = UserModel::registerUser($user, $password1);
		echo '<script>alert("Congratulations, you have a new account");</script>';
        Response::redirect('welcome', 'refresh');
      }catch(Exception $e){
        echo '<script language="javascript">';
        echo 'alert("Sorry, email already exists")';
        echo '</script>';
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
      $userResult = UserModel::getUser($email, $password);
      if ($userResult == null) {
		//if not print an error message
        $view = View::forge('welcome/index');
        echo '<script language="javascript">';
        echo 'alert("Sorry, wrong user and/or password")';
        echo '</script>';
        Response::redirect('/#toregister', 'refresh');
      } else {
		//if they are login the user
        Session::create();
        Session::set('userInfo', $userResult);
        Response::redirect('store', 'location');
      }
    }

    /**
     * The 404 action for the application.
     * @access  public
     * @return  Response
     */
    public function action_404() {
      return Response::forge(Presenter::forge('welcome/404'), 404);
    }
  }
?>