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

<html lang="es" class="no-js">

<head>
    <meta charset="UTF-8" />
    <title>Login and Registration BookStore</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="stylesheet" type="text/css" href="assets/css/demo.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/style_login.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/animate-custom.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css" />
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
</head>

<body>
    <div class="container">
        <header>
            <h1>Login and Sign up<span> BookStore</span></h1>
        </header>

        <section>
            <div id="container_demo" >
                <a class="hiddenanchor" id="toregister"></a>
                <a class="hiddenanchor" id="tologin"></a>
                <div id="wrapper">
                    <div id="login" class="animate form">
                        <?php
                            /**
                             * Login Form
                             */
                            echo Form::open(array('method' => 'post', 'autocomplete' => 'on', 'id' => 'form_id', 'enctype' => 'multipart/form-data', 'action' => 'welcome/checkUser'));
                            echo '<h1>Log in</h1>';
                            
                            //email
                            echo '<p>';
                            echo Form::label('Your email', 'emaillogin', array('class' => 'uname', 'data-icon' => 'u'));
                            echo Form::input('emaillogin', '', array('required' => 'required', 'type' => 'email', 'placeholder' => 'user@example.com', 'pattern' => '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$'));
                            echo '</p>';
                            
                            //password
                            echo '<p>';
                            echo Form::label('Your password', 'passwordlogin', array('class' => 'youpasswd', 'data-icon' => 'p'));
                            echo Form::input('passwordlogin', '', array('name' => 'passwordlogin', 'required' => 'required', 'type' => 'password', 'placeholder' => 'X8df!90EO'));
                            echo '</p>';
                            
                            //login button
                            echo '<p class=\'login button\'>';
                            echo Form::submit('submit', 'Login');
                            echo '</p>';
                            echo Form::close();
                            
                            //go to register
                            echo '<p class=\'change_link\'>';
                            echo 'Not a member yet ?';
                            echo Html::anchor('#toregister', 'Join us', array('class' => 'to_register'));
                            echo '</p>';
                        ?>
                    </div>

                    <div id="register" class="animate form">
                        <?php
                            /**
                             * Register Form
                             */
                            echo Form::open(array('method' => 'post', 'autocomplete' => 'on', 'id' => 'form_id', 'enctype' => 'multipart/form-data', 'action' => 'welcome/registerUser'));
                            echo '<h1>Sign up</h1>'; 
                            
                            //email
                            echo '<p>';
                            echo Form::label('Email', 'emailsignup', array('class' => 'uname', 'data-icon' => 'e'));
                            echo Form::input('emailsignup', '', array('required' => 'required', 'type' => 'email', 'placeholder' => 'user@example.com', 'pattern' => '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$'));
                            echo '</p>';
                            
                            //name
                            echo '<p>';
                            echo Form::label('Name', 'namesignup', array('class' => 'uname', 'data-icon' => 'u'));
                            echo Form::input('namesignup', '', array('required' => 'required', 'type' => 'text', 'placeholder' => 'Pepito', 'pattern' => '[a-zA-Z ]+$'));
                            echo '</p>';
                            
                            //city
                            echo '<p>';
                            echo Form::label('City', 'citysignup', array('class'=>'uname', 'data-icon'=>'m'));
                            echo Form::select('citysignup', $cities[0], array_combine($cities, $cities), array('id' => 'citysignup'));
                            echo '</p>';
                            
                            //address
                            echo '<p>';
                            echo Form::label('Address', 'addresssignup', array('class'=>'uname', 'data-icon'=>'a'));
                            echo Form::input('addresssignup', '', array('required' => 'required', 'type' => 'text', 'placeholder' => 'Cr 54 N° 27'));
                            echo '</p>';
                            
                            //password
                            echo '<p>';
                            echo Form::label('Your password', 'passwordsignup', array('class'=>'uname', 'data-icon'=>'p'));
                            echo Form::input('passwordsignup', '', array('required' => 'required', 'type' => 'password', 'placeholder' => 'X8df!90EO'));
                            echo '</p>';
                            
                            //password
                            echo '<p>';
                            echo Form::label('Please confirm your password', 'passwordsignup_confirm', array('class'=>'uname', 'data-icon'=>'p'));
                            echo Form::input('passwordsignup_confirm', '', array('required' => 'required', 'type' => 'password', 'placeholder' => 'X8df!90EO'));
                            echo '</p>';
                            
                            //login button
                            echo '<p class=\'login button\'>';
                            echo Form::submit('submit', 'Sign up');
                            echo '</p>';
                            echo Form::close();
                            
                            //go to login
                            echo '<p class=\'change_link\'>';
                            echo 'Already a member ?';
                            echo Html::anchor('#tologin', 'Go and log in', array('class' => 'to_login'));
                            echo '</p>';
                        ?>
                   </div>
               </div>
           </div>
       </section>
    </div>
</body>
</html>