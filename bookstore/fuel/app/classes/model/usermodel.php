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
     * @return the userID if the credential are correct, if not return null
     * @note this function uses the Auth package from FuelPHP
     * @note this function encrypts the password
     * @see Auth
     */
    public static function loginUser($email, $password) {
      //get the user
      $auth = Auth::instance();
      if (!$auth->login($email, $password)) {
        //check if the user doesn't exists
        return null;
      } else {
        //if the user exists, return the user id
        return $auth->get_user_id()[1];
      }
    }
    
    /**
     * Get the data of the current logged user
     * @return an UserDTO with all the data if there is a logged user, if not return null
     * @note this function uses the Auth package from FuelPHP
     * @see UserDTO
     */
    public static function getUser() {
      //check if there is a logged user
      $auth = Auth::instance();
      if (!$auth->login($email, $password)) {
        //if not return null
        return null;
      } else {
        //get the user field
        $fields = $auth->get_profile_fields();
        
        //get the city name
        $city = DB::select('city')->from('cities')->where('city_id', '=', $fields('city'))->execute()->get('city');
  
        //create the user
        $user = new UserDTO();
        $user->setId($auth->get_user_id()[1]);
        $user->setEmail($auth->get_email());
        $user->setName($fields('name'));
        $user->setAddress($result('address'));
        $user->setCity($city);

        //return the UserDTO
        return $user;
      }
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
        "name",
        "city_id",
        "address"
      );
      $values = array(
        $user->getName(),
        $city->get('city_id'),
        $user->getAddress()
      );

      //insert the user
      $auth = Auth::instance();
      $userID = $auth->create_user(
        $email,
        $password,
        $email,
        1,
        array_combine($colums, $values)
      );

      //check if the insert succeed
      if ($userID) {
        $user->setId($userID);
        return true;
      } else {
        return false;
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