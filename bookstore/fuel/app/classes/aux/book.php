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
//import the input, upload, FTP and file classes
use \Input;
use \Upload;
use \FTP;
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

    //connect to the server
    $ftp = FTP::forge();

    //upload the book
    if ($ftp->upload('books/' . $preview, getenv('FTP_DIR') . $preview, 'auto', 0444)) {
      //delete the temporary copy of the file
      File::delete("books/" . $preview);
      $ftp->close();

      //save the book in the database
      return BookModel::registerBook($book);
    } else {
      //if the upload failed return an error
      return false;
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
    $ftp = FTP::forge();
    $ftp->delete_file(getenv('FTP_DIR') . $book->getPreview());

    //set the book data
    $book->setName(Input::post('namebook'));
    $book->setAuthor(Input::post('authorbook'));
    $book->setIsNew(Input::post('isnewbook'));
    $book->setCategory(Input::post('categorybook'));
    $book->setPrice(Input::post('pricebook'));
    $book->setPreview($preview);
    $book->setUnits(Input::post('unitsbook'));


    // Upload the book to update it
    if ($ftp->upload('books/' . $preview, getenv('FTP_DIR') . $preview, 'auto', 0444)) {
      //delete the temporary copy of the file
      File::delete("books/" . $preview);
      $ftp->close();

      //update the book in the database
      return BookModel::updateBook($book);
    } else {
      //if the upload failed return an error
      return false;
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

    //delete a file in any of the ftp servers
    $ftp = FTP::forge();
    $ftp->delete_file(getenv('FTP_DIR') . $book->getPreview());
    $ftp->close();

    //delete the book from the database
    return BookModel::deleteBook($bookId);
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
