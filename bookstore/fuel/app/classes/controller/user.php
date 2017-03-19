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
use \Aux\User as AuxUser;

/**
 * The User Controller.
 *
 * All the actions with the user data
 *
 * @package  app
 * @extends  Controller_Common
 */
class Controller_User extends Controller_Common {
  /**
   * The edit page
   * @access public
   * @return Response
   */
  public function action_edit() {
    //check if the user is logged
    if ($this->checkLogin()) {
      //if yes, print the edit page
      $view = View::forge('user/edit');
      $view->cities = AuxUser::getCities();
      $view->user = AuxUser::getUser();
      return $view;
    } else {
      //if not, go to the loggin page
      echo '<script>alert("You have to Log In first");</script>';
      Response::redirect('/', 'refresh');
    }
  }

  /**
   * Edit the data of an user
   * @access post
   * @return Response
   */
  public function post_edit() {
    //try to edit the user
    if (AuxUser::editUser()) {
      //if the edit process worked, redirect to the profile page
      echo '<script>alert("User data edited");</script>';
      Response::redirect('/profile');
    } else {
      //if not, print the error message
      echo '<script>alert("No data was updated, Please confirm that you change at least one field");</script>';
      Response::redirect('/user/edit', 'refresh');
    }
  }

  /**
   * Change the password of an user
   * @access post
   * @return Response
   */
  public function post_changePassword() {
    //try to change the password
    $status = AuxUser::changePassword();
    if ($status[0]) {
      //if the password change process worked, redirect to the profile page
      echo '<script>alert("Password Changed");</script>';
      Response::redirect('/profile');
    } else {
      //if not, print the error message
      echo '<script>alert("'.$status[1].'");</script>';
      Response::redirect('/profile');
    }
  }

  /**
   * Delete an user account
   * @access post
   * @return Response
   */
  public function post_delete() {
    //try to delete the account
    $status = AuxUser::deleteUser();
    if ($status[0]) {
      //if the delete user process worked, log out
      echo '<script>alert("Account Deleted");</script>';
      Response::redirect('/profile/logOut');
    } else {
      //if not, print the error message
      echo '<script>alert("'.$status[1].'");</script>';
      Response::redirect('/profile');
    }
  }
}
?>