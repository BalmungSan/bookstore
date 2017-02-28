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

  //This class exists in the Model namespace
  namespace Model;

  //Import the DB class and the Auth package
  use DB;
  use Auth;

  /**
   * Represent the users table
   * This class is a Model
   * @Author Luis Miguel Mejía Suárez (BalmungSan)
   */
  class UserModel extends \Model {
    /**
     * Login an user
     * @param email the email of the user
     * @param password the password of the user
     * @return the userId if the credential are correct, if not return false
     * @note this function uses the Auth package from FuelPHP
     * @note this function encrypts the password
     * @see Auth
     */
    public static function loginUser($email, $password) {
      //get the user
      $auth = Auth::instance();
      if (!$auth->login($email, $password)) {
        //check if the user doesn't exists
        return false;
      } else {
        //if the user exists, return the user id
        return $auth->get_user_id()[1];
      }
    }
    
    /**
     * Get the actual login hash from a user using his id
     * @param userId the id of the user to get his hash
     * @return the actual login hash of the user
     */
    public static function getHash($userId) {
      return DB::select('login_hash')->from('users')->where('id', '=', $userId)->execute()->get('login_hash');
    }
    
    /**
     * Get the data of the current logged user
     * @return an UserDTO with all the data if there is a logged user, if not return null
     * @note this function uses the Auth package from FuelPHP
     * @see UserDTO
     */
    public static function getUser() {
      //get the user field
      $auth = Auth::instance();
      $fields = $auth->get_profile_fields();
      
      //get the city name
      $city = DB::select('city')->from('cities')->where('city_id', '=', $fields['city_id'])->execute()->get('city');

      //create the user
      $user = new UserDTO();
      $user->setId($auth->get_user_id()[1]);
      $user->setEmail($auth->get_email());
      $user->setName($fields['name']);
      $user->setAddress($fields['address']);
      $user->setCity($city);

      //return the UserDTO
      return $user;
    }

    /**
     * Create a new user in the database
     * @param user an UserDTO with all the data of the user
     * @param password the password for the new user
     * @return true on success false on failure
     * @note this function encrypts the password
     * @note this function uses the Auth package from FuelPHP
     * @note this function sets the id for the new user if succeed
     * @see UserDTO
     */
    public static function registerUser($user, $password) {
      //get the city id of the user's city
      $city = DB::select('city_id')->from('cities')->where('city', '=', $user->getCity())->execute();

      //prepare the columns an values
      $email = $user->getEmail();
      $colums = array(
        'name',
        'city_id',
        'address'
      );
      $values = array(
        $user->getName(),
        $city->get('city_id'),
        $user->getAddress()
      );

      //insert the user
      $auth = Auth::instance();
      $userId = $auth->create_user(
        $email,
        $password,
        $email,
        1,
        array_combine($colums, $values)
      );

      //check if the insert succeed
      if ($userId) {
        $user->setId($userId);
        $auth->login($email, $password);
        return true;
      } else {
        return false;
      }
    }
    
    /**
	   * Update the data of an user in the database
	   * @param user an UserDTO with the new data of the user
	   * @return true if the update process worked, false otherwise
	   * @note this function uses the Auth package from FuelPHP
	   * @see UserDTO
	   */
    public static function editUser($user) {
      //get the city id of the user's city
      $city = DB::select('city_id')->from('cities')->where('city', '=', $user->getCity())->execute();
      
      //update the user data
      $auth = Auth::instance();
      return $auth->update_user(array('name'    => $user->getName(),
                                      'city_id' => $city->get('city_id'),
                                      'address' => $user->getAddress()));
    }
    
    /**
	   * Change the password of an user
	   * @param oldPassword the current user's password
	   * @param newPassword the new password for the user
	   * @return true if the update process worked, otherwise false
	   * @note this function uses the Auth package from FuelPHP
	   */
    public static function changePassword($oldPassword, $newPassword) {
      $auth = Auth::instance();
      return $auth->change_password($oldPassword, $newPassword);
    }
    
    /**
	   * Delete an user from the database
	   * @param email the email of the user
	   * @param password the password of the user
	   * @return true if the delete process worked, otherwise false and an error message
	   * @note this function uses the Auth package from FuelPHP
	   */
    public static function deleteUser($email, $password) {
      //check the user credentials
      $auth = Auth::instance();
      if (!$auth->login($email, $password)) {
        //if they are invalid, return an error message
        return array(false, 'invalid username or password');
      } else if ($auth->delete_user($email)) {
        //if the delete process failed, return true
        return array(true);
      } else {
        //if the delete process failed, return an error message
        return array (false, 'Sorry, there was a problem. Please try again later');
      }
    }

    /**
     * Get all cities saved in the database
     * @return an array with the cities
     */
    public static function getCities() {
      $result = DB::select('city')->from('cities')->execute();
      $cities = array();
      foreach ($result as $r) $cities[] = $r['city'];
      return $cities;
    }
  }
?>