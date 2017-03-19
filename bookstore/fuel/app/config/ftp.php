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
 * FTP Configuration file
 */
return array(
  // FTP_HOST and FTP_PORT are enviroment variables
  'default' => array(
    'hostname' => getenv('FTP_HOST'),
    'username' => 'user',
    'password' => 'bookstore',
    'timeout'  => 90,
    'port'     => getenv('FTP_PORT'),
    'passive'  => true,
    'ssl_mode' => false,
    'debug'    => false
  )
);
?>
