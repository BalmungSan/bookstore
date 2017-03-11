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
  
  //this class lives in the Aux namespace
  namespace Aux;

  //import the book model and dto
  use \Model\BookModel;
  use \Model\BookDTO;
  //import the input, upload and file classes
  use \Input;
  use \Upload;
  use \File;
  
  /**
   * The Book Aux Class.
   *
   * an auxiliary class with all operations over books
   * used to unify the normal and rest controllers
   *
   * @package  app
   */
  class Book {
    /**
     * Transform an array of bookDTO into an array of books
     * @param booksDTO the array to transform
     * @note this is an aux method
     * @access private
     * @return the transformed array
     * @see BookDTO::toArray()
     * @see self::getUserBooks()
     * @see self::search()
     */
    private static function toBooksArray($booksDTO) {
      return array_map(function ($b){return $b->toArray();}, $booksDTO);
    }
    
    /**
	   * Get all books of the current logged user
	   * @access app
	   * @return an array with the books
	   * @see BookModel::getUserBooks()
	   * @see self::toBooksArray($booksDTO)
	   */
    public static function getUserBooks() {
  	  return self::toBooksArray(BookModel::getUserBooks());
    }
    
    /**
	   * Get all books of the current logged user
	   * @access app
	   * @return an array with the books
	   * @see BookModel::searchByCategory($category);
	   * @see BookModel::searchByAuthor($author);
	   * @see BookModel::searchByName($name);
	   * @see BookModel::searchByPrice($priceL, $priceU);
	   * @see self::toBooksArray($booksDTO)
	   */
    public static function search() {
      //get the search option
      $searchBy  = Input::post("searchBy");
      $resultDTO = null;
      switch ($searchBy) {
        case 'category':
          //search by category
          $category = Input::post("category");
          $resultDTO = BookModel::searchByCategory($category);
          break;
          
        case 'author':
          //search by author
          $author = Input::post("author");
          $resultDTO = BookModel::searchByAuthor($author);
          break;
          
        case 'name':
          //search by name
          $name = Input::post("name");
          $resultDTO = BookModel::searchByName($name);
          break;
          
        case 'price':
          //search by price
          $priceL = Input::post("priceL");
          $priceU = Input::post("priceU");
          $resultDTO = BookModel::searchByPrice($priceL, $priceU);
          break;
          
        default:
          //fail
          return false;
          break;
      }
      
      return self::toBooksArray($resultDTO);
    }

    /**
	   * Get a book
	   * @param BookId the id of the book to retrieve
	   * @access app
	   * @return an array with all book data
	   * @see BookModel::registerBook($book)
	   * @see BookDTO::toArray()
	   */
    public static function getBook($bookId) {
      return BookModel::getBook($bookId)->toArray();
    }
    
    /**
	   * Register a new book in the database
	   * @access app
	   * @return true on success, false otherwise
	   * @see BookModel::registerBook($book)
	   */
    public static function createBook() {
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
		  $book->setUser(Input::post('useridbook'));
		  $book->setName(Input::post('namebook'));
		  $book->setAuthor(Input::post('authorbook'));
		  $book->setIsNew(Input::post('isnewbook'));
		  $book->setCategory(Input::post('categorybook'));
		  $book->setPrice(Input::post('pricebook'));
		  $book->setPreview($preview);
		  $book->setUnits(Input::post('unitsbook'));
		  
		  //save the book in the database
		  return BookModel::registerBook($book);

      // create an ftp object, but don't connect
      $ftp = Ftp::forge(array(
      'hostname' => getenv('FTP_HOST_M'),
      'username' => 'user',
      'password' => 'bookstore',
      'timeout'  => 90,
      'port'     => getenv('FTP_PORT'),
      'passive'  => true,
      'ssl_mode' => false,
      'debug'    => false
      ), false);

      //ftp object2, slave 1
      $ftp2 = Ftp::forge(array(
      'hostname' => getenv('FTP_HOST_S1'),
      'username' => 'user',
      'password' => 'bookstore',
      'timeout'  => 90,
      'port'     => getenv('FTP_PORT'),
      'passive'  => true,
      'ssl_mode' => false,
      'debug'    => false
      ), false);

      //ftp object3, slave 2
      $ftp3 = Ftp::forge(array(
      'hostname' => getenv('FTP_HOST_S2'),
      'username' => 'user',
      'password' => 'bookstore',
      'timeout'  => 90,
      'port'     => getenv('FTP_PORT'),
      'passive'  => true,
      'ssl_mode' => false,
      'debug'    => false
      ), false);

      // now connect to the server
      if($ftp->connect();){
        // Upload the book 
        $ftp->upload('books/' + $book->getName() + '.pdf', getenv('FTP_DIR'), auto , 0666);
        $ftp->close();
      }elseif($ftp2->connect();){
        $ftp2->upload('books/' + $book->getName() + '.pdf', getenv('FTP_DIR'), auto , 0666);
        $ftp2->close();
      }elseif($ftp3->connect();){
        $ftp3->upload('books/' + $book->getName() + '.pdf', getenv('FTP_DIR'), auto , 0666);
        $ftp3->close();
      }else{
        $message = "Failed to connect to the FTP servers. Try again or later";
        echo "<script type='text/javascript'>alert('$message');</script>";
      }
    }

    /**
	   * Edit the data of a book
	   * @param bookId the id of the book to update
	   * @access app
	   * @return true on success, false otherwise
	   * @see BookModel::updateBook($book)
	   */     
    public static function editBook($bookId) {
      //check that the user editing this book is the owner of the book
			$book = BookModel::getBook($bookId);
			if ($book->getUser() != Input::post('useridbook')) {
			  return false;
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
		  $book->setName(Input::post('namebook'));
		  $book->setAuthor(Input::post('authorbook'));
		  $book->setIsNew(Input::post('isnewbook'));
		  $book->setCategory(Input::post('categorybook'));
		  $book->setPrice(Input::post('pricebook'));
		  $book->setPreview($preview);
		  $book->setUnits(Input::post('unitsbook'));
		  
		  //update the book in the database
		  return BookModel::updateBook($book);

      if($ftp->connect();){
      // delete a file in master
      if ( ! $ftp->delete_file('books/' + $book->getName() + '.pdf')
      {
        if($ftp2->connect();){
        // delete a file in slave 1
        if ( ! $ftp2->delete_file('books/' + $book->getName() + '.pdf')
        {
          if($ftp3->connect();){
          // delete a file in slave 2
          if ( ! $ftp3->delete_file('books/' + $book->getName() + '.pdf')
          {
          //delete failed
            $message = "Failed to connect to the FTP servers. Try again or later";
            echo "<script type='text/javascript'>alert('$message');</script>";
          }
        }
      }
    

      if($ftp->connect();){
        // Upload the book in master
        $ftp->upload('books/' + $book->getName() + '.pdf', getenv('FTP_DIR'), auto , 0666);
        $ftp->close();
      }elseif($ftp2->connect();){
        // Upload the book in slave 1
        $ftp2->upload('books/' + $book->getName() + '.pdf', getenv('FTP_DIR'), auto , 0666);
        $ftp2->close();
      }elseif($ftp3->connect();){
        // Upload the book in slave 2
        $ftp3->upload('books/' + $book->getName() + '.pdf', getenv('FTP_DIR'), auto , 0666);
        $ftp3->close();
      }else{
        $message = "Failed to connect to the FTP servers. Try again or later";
        echo "<script type='text/javascript'>alert('$message');</script>";
      }
    }
    
    /**
	   * Delete a book
	   * @param bookId the id of the book to delete
	   * @access app
	   * @return true on success, false otherwise
	   * @see BookModel::updateBook($book)
	   */      
    public static function deleteBook($bookId) {
      //check that the user deleting this book is the owner of the book
			$book = BookModel::getBook($bookId);
			if ($book->getUser() != Input::post('useridbook')) {
			  return false;
			}

    //delete the preview and the book from the database
		  File::delete("books/".$book->getPreview());
		  return BookModel::deleteBook($bookId);
    

      if($ftp->connect();){
      // delete a file in master
      if ( ! $ftp->delete_file('books/' + $book->getName() + '.pdf')
      {
        if($ftp2->connect();){
        // delete a file in slave 1
        if ( ! $ftp2->delete_file('books/' + $book->getName() + '.pdf')
        {
          if($ftp3->connect();){
          // delete a file in slave 2
          if ( ! $ftp3->delete_file('books/' + $book->getName() + '.pdf')
          {
          //delete failed
            $message = "Failed to connect to the FTP servers. Try again or later";
            echo "<script type='text/javascript'>alert('$message');</script>";
          }
        }
      }
    }
    
    /**
	   * Get the list of all book categories
	   * @access app
	   * @return an array with the categories
	   * @see BookModel::getCategories()
	   */
    public static function getCategories() {
      return BookModel::getCategories();
    }
  }
?>