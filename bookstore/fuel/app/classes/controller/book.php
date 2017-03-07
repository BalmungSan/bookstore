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
   * The Book Controller.
   *
   * This controller is in charge of all book actions
   *
   * @package  app
   * @extends  Controller
   */
  class Controller_Book extends Controller_Common {
		/**
     * Book registration page
     * @access  public
     * @return  Response
     */
		public function action_create() {
			//check if the user is logged
      if ($userId = $this->checkLogin()) {
        //if yes, print the create book page
        $view = View::forge('book/create');
      	$view->data = array('categories' => AuxBook::getCategories(), 'userId' => $userId);
	  		return $view;
      } else {
        //if not, go to the loggin page
        echo '<script>alert("You have to Log In first");</script>';
        Response::redirect('/', 'refresh');
      }
		}
	
		/**
     * Register a new book in the database
     * @access  post
     * @return  Response
     */
		public function post_create() {
		  //try to save the book
		  if (AuxBook::createBook()) {
		    //if works, tell the user the add book worked
		    echo '<script>alert("New Book added");</script>';
		    Response::redirect('profile', 'refresh');
		  } else {
				//if not print an error message
				echo '<script>alert("Sorry, there was a problem. Please try again later")</script>';
	      Response::redirect('/book/create', 'refresh');
		  }
		}

  	/**
     * Edit a book page
     * @access  public
     * @return  Response
     */
		public function action_edit($bookId = null) {
	  	//if no book id passed, return
	  	is_null($bookId) and Response::redirect_back('/', 'location');
	  	
	  	//check if the user is logged
      if ($userId = $this->checkLogin()) {
      	//if yes, check if the user can edit the book
      	$book = AuxBook::getBook($bookId);
      	if ($userId == $book['user']) {
	        //if yes, print the edit book page
	        $view = View::forge('book/edit');
	      	$view->data = array('categories' => AuxBook::getCategories(), 'userId' => $userId, 'book' => $book);
		  		return $view;
      	} else {
      		//if not, print an error message
      		echo '<script>alert("You don\'t have the permissions to edit this book");</script>';
        	Response::redirect('/', 'refresh');
      	}
      } else {
        //if not, go to the loggin page
        echo '<script>alert("You have to Log In first");</script>';
        Response::redirect('/', 'refresh');
      }
		}

    /**
     * Save the book changes in the database
     * @access  post
     * @return  Response
     */
		public function post_edit($bookId) {
		  //try to update the book
		  try {
				if (AuxBook::editBook($bookId)) {
		    	//if works, tell the user the edit book worked
		    	echo '<script>alert("Book edited");</script>';
		    	Response::redirect('profile', 'refresh');
				} else {
					//if not, tell the user to change at least one field
        	echo '<script>alert("No data was updated, Please confirm that you change at least one field");</script>';
        	Response::redirect('/book/edit', 'refresh');
				}
		  } catch(Exception $e) {
				//if fail, print an error message
				echo '<script>alert("Sorry, there was a problem. Please try again later");</script>';
	      Response::redirect('profile', 'refresh');
		  }
		}

    /**
     * Delete a book from the database
     * @access  post
     * @return  Response
     */	
		public function post_delete($bookId = null) {
		  //if no book id passed, return
		  is_null($bookId) and Response::redirect('/', 'location');
	    
	    //try to update the book
		  try {
				if (AuxBook::deleteBook($bookId)) {
		    	//if works, tell the user the edit book worked
		    	echo '<script>alert("Book deleted");</script>';
		    	Response::redirect('profile', 'refresh');
				} else {
					//if not, tell the user to change at least one field
        	echo '<script>alert("You don\'t have the permissions to delete this book");</script>';
        	Response::redirect('/book/edit', 'refresh');
				}
		  } catch(Exception $e) {
				//if fail, print an error message
				echo '<script>alert("Sorry, there was a problem. Please try again later");</script>';
	      Response::redirect('profile', 'refresh');
		  }
		}
		
		/**
     * Return to the book list page 
     * @access  public
     * @return  Response
     */
		public function action_back() {
	  	Response::redirect_back('/', 'refresh');
		}
  }
?>