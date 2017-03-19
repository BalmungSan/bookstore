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

/**
 * Common API Controller
 *
 * Provies common methods for all rest controllers
 *
 * @package  app
 * @extends  Controller_Rest
 */
class Controller_api_Common extends Controller_Rest {
  /**
   * Check if the user credentials are valid
   * @return true if the user credentials are valid, false if not
   * @see Auth
   */
  protected function checkLogin() {
    //get the user email and password
    $email    = Input::post('email');
    $password = Input::post('password');

    //check if the user credentials are correct
    $auth = Auth::instance();
    return $auth->login($email, $password);
  }
}
?>