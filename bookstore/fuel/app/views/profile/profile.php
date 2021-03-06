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

<html lang="es">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <title>BookStore</title>
    <meta charset="utf-8">

    <!-- Linking styles -->
    <link rel="stylesheet" href="/assets/css/reset.css" type="text/css" media="screen">
    <link rel="stylesheet" href="/assets/css/style_store.css" type="text/css" media="screen">
    <link rel="stylesheet" href="/assets/css/nivo-slider.css" type="text/css" media="screen">
    <link rel="stylesheet" href="/assets/css/bootstrap.css" type="text/css" media="screen">

    <!-- Linking scripts -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.js"></script>
    <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/assets/img/favicon.ico" type="image/x-icon">
  </head>

  <body>
    <div class="container">
      <header>
        <div class="top_head">
          <div id="salir">
            <button type="button" class="btn btn-danger" onclick="window.location.href='profile/logOut';"><strong>Log Out</strong></button>
          </div>

          <div class="logo">
            <img id="logo" src="assets/img/logo.png">
          </div>
        </div>
      </header>

      <div id="main">
        <section id="content">
          <div id="upper">
            <h3>User Info</h3>
            <ul>
              <li><strong>Account:</strong> <?=$user['email']?></li>
              <li><strong>Name:</strong> <?=$user['name']?></li>
              <li><strong>City:</strong> <?=$user['city']?></li>
              <li><strong>Address:</strong> <?=$user['address']?></li>
            </ul>
          </div>
          <div id="left">
            <h3>My Books</h3>
            <ul>
              <?php foreach ($books as $book) {?>
                <li>
                  <div class="well" id="book">
                    <div class="info">
                      <a class="title"><?=$book['name']?></a>
                      <div id="info<?=$book['id']?>">
                        <p style="font-size: 17px; margin-top: 15px;"><span class="st">Author: </span><strong><?=$book['author']?></strong></p>
                        <p style="font-size: 17px; margin-top: 15px;"><span class="st">Category: </span><strong><?=$book['category']?></strong></p>
                        <p style="font-size: 17px; margin-top: 15px;"><span class="st">Units: </span><strong><?=$book['units']?></strong></p>
                        <p style="font-size: 17px; margin-top: 15px;"><span class="st">Price: </span><strong>$<?=$book['price']?></strong></p>
                        <p style="font-size: 17px; margin-top: 15px;"><span class="st"><?=Html::anchor("http://" . getenv('FTP_HOST') . '/' . $book['preview'], 'View', array('target' => '_blank'))?></span></p>
                      </div>
                      <div class="actions">
                        <button type="button" class="btn btn-info details" onclick="window.location.href='profile/editBook/<?=$book['id']?>';">Edit</button>
                        <?php
                        echo Form::open(array('method' => 'post', 'id' => 'form_id', 'enctype' => 'multipart/form-data', 'action' => 'book/delete/'.$book['id']), array('useridbook' => $user['id']));
                        echo Form::submit('submit', 'Delete', array('class' =>'btn btn btn-danger'));
                        echo Form::close();
                        ?>
                      </div>
                    </div>
                  </div>
                </li>
              <?php }?>
            </ul>
          </div>
          <div id="rightUpper">
            <h3>Search By:</h3>
            <form id="search_form" method="post" name="search_form" autocomplete="on" action="profile/search">
              <input id="r2" type="radio" name="searchBy" value="name" onclick="search()" checked> Name<br>
              <input id="name" name="name" class="form-control" placeholder="Las Mil y una Noches" style="display: block;"/>
              <input id="r0" type="radio" name="searchBy" value="category" onclick="search()"> Category<br>
              <select id="categorynewbook" name="category" class="form-control" style="display: none;">
                <?php foreach ($categories as $category) {echo "<option value='".$category."'>".$category."</option>";}?>
              </select>
              <input id="r1" type="radio" name="searchBy" value="author" onclick="search()"> Author<br>
              <input id="author" name="author" class="form-control"  placeholder="Edgar Allan Poe" style="display: none;"/>
              <input id="r3" type="radio" name="searchBy" value="price" onclick="search()"> Price<br>
              <div id="prices" style="display: none;">
                <input type="number" id="priceL" name="priceL" class="form-control"  placeholder="$60000"/>
                <input type="number" id="priceU" name="priceU" class="form-control"  placeholder="$170000"/>
              </div>
              <p class="signin">
                <input type="submit" value="Search" class="btn btn-primary" id="search_button" />
              </p>
            </form>
          </div>
          <div id="rightDown">
            <h3>Actions:</h3>
            <?php
            echo Html::anchor('book/create', 'Add New Book', array('class' => 'btn btn-success', 'id' => 'btnAddBook'));
            echo Html::anchor('user/edit', 'Edit Profile', array('class' => 'btn btn-success', 'id' => 'btnEditProfile'));
            //echo Html::anchor('user/changePassword', 'Change Password', array('class' => 'btn btn-info', 'id' => 'btnChangePassword'));
            //echo Html::anchor('user/delete', 'Delete Account', array('class' => 'btn btn-danger', 'id' => 'btnDeleteProfile'));
            ?>
            <button class="btn btn-info" id="btnChangePassword" onclick="changepass()">Change Password
            </button>
            <button class="btn btn-danger" id="btnDeleteProfile" onclick="deleteprofile()">Delete Profile
            </button>
          </div>
        </section>
      </div>
      <footer>
        <div id="privacy">
          BookStore © 2017
        </div>
      </footer>
    </div>
  </body>
</html>
