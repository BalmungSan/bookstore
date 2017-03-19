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

//Import the book aux class
use \Aux\Book as AuxBook;

/**
 * Rest Book Controller
 *
 * Provides an API to create, edit and search books
 *
 * @package  app
 * @extends  Controller_Rest
 */
class Controller_api_Book extends Controller_api_Common {
  /**
   * Override the default checkLogin to only authorize the access to petitions with the correct user id setted
   * this only applies to the methods that need the user id
   * @return always true
   * @see self::create()
   * @see self::edit()
   * @see self::delete()
   */
  protected function checkLogin() {
    if (parent::checkLogin()) {
      $userIdLogin = \Auth::get_user_id()[1];
      $userIdPost  = Input::post('useridbook');
      if (isset($userIdPost)) {
        return $userIdLogin == $userIdPost;
      } else {
        return true;
      }
    } else {
      return false;
    }
  }

  /**
   * Get all books of the user
   * @access post
   * @return a json with the books
   */
  public function post_getUserBooks () {
    return $this->response(AuxBook::getUserBooks());
  }

  /**
   * Search books
   * @access post
   * @return a json the books
   */
  public function post_search() {
    $books = AuxBook::search();
    if ($books !== false) {
      return $this->response(array('succeed' => true, 'books' => $books));
    } else {
      return $this->response(array('succeed' => false, 'error' => 'no option was provided to search by'));
    }
  }

  /**
   * Register a new book in the database
   * @access post
   * @return a json with the 'succeed' variable set to true if the creation process worked,
   * otherwise the value will be false and the 'error' variable will be set with the error message
   */
  public function post_create() {
    //try to save the book
    if (AuxBook::createBook()) {
      //if works, return succeed
      return $this->response(array('succeed' => true));
    } else {
      //if not, return an error message
      return $this->response(array('succeed' => true, 'error' => 'Sorry, there was a problem. Please try again later'));
    }
  }

  /**
   * Edit a book
   * @param bookId the id of the book to edit
   * @access post
   * @return a json with the 'succeed' variable set to true if the update process worked,
   * otherwise the value will be false and the 'error' variable will be set with the error message
   */
  public function post_edit($bookId) {
    //try to update the book
    try {
      if (AuxBook::editBook($bookId)) {
        //if works, return succeed
        return $this->response(array('succeed' => true));
      } else {
        //if not, return an error message
        return $this->response(array('succeed' => true, 'error' => 'No data was updated, Please confirm that you change at least one field'));
      }
    } catch(Exception $e) {
      //if fail, return an error message
      return $this->response(array('succeed' => true, 'error' => 'Sorry, there was a problem. Please try again later'));
    }
  }

  /**
   * Delete a book from the database
   * @param bookId the id of the book to delete
   * @access post
   * @return a json with the 'succeed' variable set to true if the delete process worked,
   * otherwise the value will be false and the 'error' variable will be set with the error message
   */
  public function post_delete($bookId) {
    //try to update the book
    try {
      if (AuxBook::deleteBook($bookId)) {
        //if works, return succeed
        return $this->response(array('succeed' => true));
      } else {
        //if not, return an error message
        return $this->response(array('succeed' => true, 'error' => 'You don\'t have the permissions to delete this book'));
      }
    } catch(Exception $e) {
      //if fail, return an error message
      return $this->response(array('succeed' => true, 'error' => 'Sorry, there was a problem. Please try again later'));
    }
  }

  /**
   * Get the list of all book categories
   * @access post
   * @return a json with the categories
   */
  public function post_getCategories() {
    return $this->response(array('categories' => AuxBook::getCategories()));
  }
}
?>