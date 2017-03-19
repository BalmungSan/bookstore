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
 * The User Aux Class.
 *
 * an auxiliary class with all operations over users
 * used to unify the normal and rest controllers
 *
 * @package  app
 */
class User {
  /**
   * Get all data of the current logged user
   * @access app
   * @return an array with all of the data
   * @see UserModel::getUser
   * @see UserDTO::toArray
   */
  public static function getUser() {
    return UserModel::getUser()->toArray();
  }

  /**
   * Edit the data of an user
   * @access app
   * @return true if the update process worked, false otherwise
   * @see UserModel::editUser
   */
  public static function editUser() {
    //get the user data
    $user = new UserDTO();
    $user->setName(Input::post('nameEdit'));
    $user->setCity(Input::post('cityEdit'));
    $user->setAddress(Input::post('addressEdit'));

    //try to update the user in the database
    return UserModel::editUser($user);
  }

  /**
   * Change the password of an user
   * @access app
   * @return true if the update process worked, otherwise flase and an error message
   * @see UserModel::changePassword
   */
  public static function changePassword() {
    //get the user old and new password
    $oldPassword = Input::post('password');
    $newPassword = Input::post('newpassword');

    //try to change the password
    if ($oldPassword == $newPassword) {
      //if both passwords are the same, return an error message
      return array(false, 'Both Passwords are the same');
    } else if (UserModel::changePassword($oldPassword, $newPassword)) {
      //if the password change process worked, return true
      return array(true);
    } else {
      //if the password change process failed, return an error message
      return array(false, 'Old password didn\'t match');
    }
  }

  /**
   * Delete an user account
   * @access app
   * @return true if the delete process worked, otherwise false and an error message
   * @see UserModel::deleteUser
   */
  public static function deleteUser() {
    //get user email and password
    $email    = Input::post('email');
    $password = Input::post('password');

    //try to delete the account
    return UserModel::deleteUser($email, $password);
  }

  /**
   * Get the list of all cities
   * @access app
   * @return an array with all the cities
   * @see UserModel::getCities
   */
  public static function getCities() {
    return UserModel::getCities();
  }
}
?>