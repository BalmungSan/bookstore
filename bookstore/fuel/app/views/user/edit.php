<!DOCTYPE html>

<!--
     Copyright 2017 BookStore

     Licensed under the Apache License, Version 2.0 (the "License");
     you may not use this file except in compliance with the License.
     You may obtain a copy of the License at

     http://www.apache.org/licenses/LICENSE-2.0

     Unless required by applicable law or agreed to in writing, software
     distributed under the License is distributed on an "AS IS" BASIS,
     WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
     See the License for the specific language governing permissions and
     limitations under the License.
-->

<html lang="en" class="no-js">
  <head>
    <meta charset="UTF-8" />
    <title>Edit Profile BookStore</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/assets/css/demo.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/style_login.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/animate-custom.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
    <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/assets/img/favicon.ico" type="image/x-icon">
  </head>

  <body>
    <div class="container">
      <header>
        <h1>BookStore</h1>
      </header>
      <div id="container_demo" >
        <div id="wrapper">
          <div id="login" class="animate form">
            <h1>Edit profile</h1>
            <?php
            /**
             * Edit Form
             */
            echo Form::open(array('method' => 'post', 'autocomplete' => 'on', 'id' => 'form_id', 'enctype' => 'multipart/form-data', 'action' => '/user/edit'));

            //name
            echo '<p>';
            echo Form::label('Name', 'nameEdit', array('class' => 'uname', 'data-icon' => 'u'));
            echo Form::input('nameEdit', $user['name'], array('required' => 'required', 'type' => 'text', 'placeholder' => 'Pepito', 'pattern' => '[a-zA-Z ]+$'));
            echo '</p>';

            //city
            echo '<p>';
            echo Form::label('City', 'cityEdit', array('class'=>'uname', 'data-icon'=>'m'));
            echo Form::select('cityEdit', $user['city'], array_combine($cities, $cities), array('id' => 'citysignup'));
            echo '</p>';

            //address
            echo '<p>';
            echo Form::label('Address', 'addressEdit', array('class'=>'uname', 'data-icon'=>'a'));
            echo Form::input('addressEdit', $user['address'], array('required' => 'required', 'type' => 'text', 'placeholder' => 'Cr 54 N° 27'));
            echo '</p>';

            //back & submit button
            echo Html::anchor('profile', 'Back', array('class' => 'btn btn-info', 'id'=>'back-button'));
            echo '<p class=\'signin button\'>';
            echo Form::submit('submit', 'OK');
            echo '</p>';
            echo Form::close();
            ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
