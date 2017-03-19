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
 * SimpleAuth driver configuration
 */
return array(
  //user table configuration
  'db_connection' => 'mysql',
  'table_name' => 'users',
  'table_columns' => array('*'),

  //no guest login and no multiple logins
  'guest_login' => false,
  'multiple_logins' => false,

  //login configuration
  'username_post_key' => 'emaillogin',
  'password_post_key' => 'passwordlogin',
  'login_hash_salt' => '3wXhNjtvaASQ3KPc1dq5',

  //remenber me configuration
  'remember_me' => array(
    'enabled' => true,
    'cookie_name' => 'bookstore_login',
    'expiration' => 3600
  ),

  //groups and roles (None)
  'groups' => array(),
  'roles' => array()
);
?>