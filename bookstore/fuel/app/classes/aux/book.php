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
  //import the input class
  use \Input;
  
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
        case 0:
          //search by category
          $category = Input::post("category");
          $resultDTO = BookModel::searchByCategory($category);
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
          return false;
          break;
      }
      
      return self::toBooksArray($resultDTO);
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