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

  //Imports
  use \Model\BookModel;
  use \Model\BookDTO;

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
      	$view->data = array('categories' => BookModel::getCategories(), 'userId' => $userId);
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
	  	//config for files
		  $config = array(
		  	'path' => 'books/',
	      'change_case' => 'lower',
	    	'normalize' => true,
	      'auto_process' => true,
	      'normalize_separator' => '-',
	      'ext_whitelist' => array('pdf')
	    );
	    Upload::process($config);

    	//check if there are valid files
    	if (Upload::is_valid()) {
    		//save them according to the config
	    	Upload::save();

	    	//grab the file name of the preview
	    	$preview = Upload::get_files()[0]['saved_as'];
    	}

		  //set the book data
		  $book = new BookDTO();
		  $book->setUser(Input::post('userid'));
		  $book->setName(Input::post('namenewbook'));
		  $book->setAuthor(Input::post('authornewbook'));
		  $book->setIsNew(Input::post('isnew'));
		  $book->setCategory(Input::post('categorynewbook'));
		  $book->setPrice(Input::post('pricenewbook'));
		  $book->setPreview($preview);
		  $book->setUnits(Input::post('unitsnewbook'));
	  
		  //try to save the book
		  if (BookModel::registerBook($book)) {
		    //if works, tell the user the add book worked
		    echo '<script>alert("New Book added");</script>';
		    Response::redirect('profile', 'refresh');
		  } else {
				//if not print an error message
				echo '<script language="javascript">';
	      echo 'alert("Sorry, there was a problem. Please try again later")';
	      echo '</script>';
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
      	$book = BookModel::getBook($bookId);
      	if ($userId == $book->getUser()) {
	        //if yes, print the edit book page
	        $view = View::forge('book/edit');
	      	$view->data = array('categories' => BookModel::getCategories(), 'userId' => $userId, 'book' => $book->toArray());
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
		public function post_edit($bookId = null) {
		  //if no book id passed, return
		  is_null($bookId) and Response::redirect('/', 'location');
	
			//check that the user editing this book is the owner of the book
			$book = BookModel::getBook($bookId);
			$bookUserId = $book->getUser();
			if ($bookUserId != Input::post('userid')) {
				//if not, go to the loggin page
        echo '<script>alert("You don\'t have the permissions to edit this book");</script>';
        Response::redirect('/', 'refresh');	
			}
	
		  //config for files
		  $config = array(
		  	'path' => 'books/',
	      'change_case' => 'lower',
	      'normalize' => true,
	      'auto_process' => true,
	      'normalize_separator' => '-',
	      'ext_whitelist' => array('pdf'),
	    );
	    Upload::process($config);

      //check if there are valid files
      if (Upload::is_valid()) {
		    //save them according to the config
		    Upload::save();
	
		    //grab the file name of the preview
		    $preview = Upload::get_files()[0]['saved_as'];
      }

      //delete the old preview
      File::delete("books/".$book->getPreview());

		  //set the book data
		  $book->setName(Input::post('namenewbook'));
		  $book->setAuthor(Input::post('authornewbook'));
		  $book->setIsNew(Input::post('isnew'));
		  $book->setCategory(Input::post('categorynewbook'));
		  $book->setPrice(Input::post('pricenewbook'));
		  $book->setPreview($preview);
		  $book->setUnits(Input::post('unitsnewbook'));
	  
		  //try to update the book
		  try {
			BookModel::updateBook($book);
		    //if works tell the user the edit book worked
		    echo '<script>alert("Book edited");</script>';
		    Response::redirect('profile', 'refresh');
		  } catch(Exception $e) {
				//if not print an error message
				echo '<script>alert("Sorry, there was a problem. Please try again later");</script>';
	      Response::redirect('/book/edit', 'refresh');
		  }
		}

    /**
     * Delete a book from the database
     * @access  public
     * @return  Response
     */	
		public function action_delete($bookId = null) {
		  //if no book id passed, return
		  is_null($bookId) and Response::redirect('/', 'location');
	
			//check if the user is logged
      if ($userId = $this->checkLogin()) {
      	//if yes, check if the user can edit the book
      	$book = BookModel::getBook($bookId);
      	if ($userId == $book->getUser()) {
	        //if yes delete the preview and the book from the database
		  		File::delete("books/".$book->getPreview());
		  		BookModel::deleteBook($bookId);
	
		  		//reload the profile view
		  		Response::redirect('profile');
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
     * Return to the book list page 
     * @access  public
     * @return  Response
     */
		public function action_back() {
	  	Response::redirect_back('/', 'refresh');
		}
  }
?>