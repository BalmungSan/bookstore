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

//Import the user and book aux classes
use \Aux\User as AuxUser;
use \Aux\Book as AuxBook;

/**
 * The Profile Controller.
 *
 * The main page of the application
 *
 * @package  app
 * @extends  Controller_Common
 */
class Controller_Profile extends Controller_Common {
  /**
   * The profile page
   * @access  public
   * @return  View
   */
  public function action_index () {
    //check if the user is logged
    if ($userId = $this->checkLogin()) {
      //if yes, print the profile page
      $view = View::forge('profile/profile');
      $view->user       = AuxUser::getUser();
      $view->books      = AuxBook::getUserBooks();
      $view->categories = AuxBook::getCategories();
      return $view;
    } else {
      //if not, go to the loggin page
      echo '<script>alert("You have to Log In first");</script>';
      Response::redirect('/', 'refresh');
    }
  }

  /**
   * The search page
   * @access  post
   * @return  View
   */
  public function post_search() {
    //print the book list view
    $view = View::forge('book/list');
    $view->books = AuxBook::search();
    return $view;
  }

  /**
   * Edit a book
   * @access  post
   * @return  Response
   */
  public function action_editBook($bookId) {
    //check if the user is logged
    if ($this->checkLogin()) {
      //if yes, call the edit book method of the book controller
      Response::redirect("book/edit/".$bookId);
    } else {
      //if not, go to the loggin page
      echo '<script>alert("You have to Log In first");</script>';
      Response::redirect('/', 'refresh');
    }
  }

  /**
   * Logout
   * @access  public
   * @return  Response
   */
  public function action_logOut() {
    \Auth::dont_remember_me();
    \Auth::logout();
    Cookie::delete('user_id');
    Cookie::delete('login_hash');
    Response::redirect('/', 'location');
  }
}
?>