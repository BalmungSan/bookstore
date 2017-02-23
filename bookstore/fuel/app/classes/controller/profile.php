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

  //Import the book model and dto
  use \Model\BookModel;
  use \Model\BookDTO;

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
      if ($userId = $this->check_login()) {
        //if yes, print the profile page
        $view = View::forge('profile/profile');
  	    $booksDTO = BookModel::getBooksByUser($userId);
  	    $books = array_map(function ($b){return $b->toArray();}, $booksDTO);
  	    $view->books = $books;
  	    $categories = BookModel::getCategories();
        $view->categories = $categories;
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
      //get the search option
      $radioButton = Input::post("radioButton");
      $resultDTO = null;
      switch ($radioButton) {
        case 0:
          //search by category
          $category = Input::post("categorynewbook");
          $resultDTO = BookModel::searchByCategory($category);;
          break;
          
        case 1:
          //search by author
          $author = Input::post("author");
          $resultDTO = BookModel::searchByAuthor($author);
          break;
          
        case 2:
          //search by name
          $name = Input::post("name");
          $resultDTO = BookModel::searchByName($name);
          break;
          
        case 3:
          //search by price
          $priceL = Input::post("priceL");
          $priceU = Input::post("priceU");
          $resultDTO = BookModel::searchByPrice($priceL, $priceU);
          break;
          
        default:
          //fail
          Response::redirect_back('/', 'refresh');
          break;
      }

      //print the book list view
      $view = View::forge('book/list');
      $view->books = array_map(function ($b){$b = $b->toArray(); $b[6] = null; return $b;}, $resultDTO);
      return $view;
    }

	  /**
     * Edit a book
     * @access  post
     * @return  Response
     */
    public function action_editBook($bookId) {
      //check if the user is logged
      if ($this->check_login()) {
        //if yes, call the edit book method of the book controller
        Response::redirect("book/edit/".$bookId);
      } else {
        //if not, go to the loggin page
        echo '<script>alert("You have to Log In first");</script>';
        Response::redirect('/', 'refresh');
      }
	  }
	
	  /**
     * Delete a book
     * @access  post
     * @return  Response
     */
	  public function action_deleteBook($bookId) {
	    //check if the user is logged
      if ($this->check_login()) {
        //if yes, call the delete book of the book controller
	      Response::redirect("book/delete/".$bookId."/");
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