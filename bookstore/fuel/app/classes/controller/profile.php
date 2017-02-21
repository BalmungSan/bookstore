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
  use \Model\UserModel;
  use \Model\BookModel;
  use \Model\BookDTO;

  /**
   * The Profile Controller.
   *
   * The main page of the application
   *
   * @package  app
   * @extends  Controller
   */
  class Controller_Profile extends Controller {
    /**
     * The profile page
     * @access  public
     * @return  Response
     */
    public function action_index () {
      //check if the user is logged
      if (\Auth::check()) {
        //if yes, prepare the profile page
  	    $userId = \Auth::get_user_id()[1];
  	    return $this->profile($userId);
      } else {
        //if not, try to login using cookies
        $userId = Cookie::get('user_id', -1);
        $cookie_hash = Cookie::get('login_hash', '');
        $db_hash = UserModel::getHash($userId);
        if ($userId != -1 && $cookie_hash == $db_hash) {
          //if there are valid credentials in the cookies login the user
          \Auth::force_login($userId);
          Cookie::set('login_hash', Session::get('login_hash'));
          return $this->profile($userId);
        } else {
          //if not, redirect to the login screen
          echo '<script>alert("You have to Log In first");</script>';
          Response::redirect('/', 'refresh');
        }
      }
    }
    
    /**
     * The profile page (Aux Function)
     * @access  private
     * @return  Response
     */
    private function profile($userId) {
    	$view = View::forge('profile/profile');
	    $booksDTO = BookModel::getBooksByUser($userId);
	    $books = array_map(function ($b){return $b->toArray();}, $booksDTO);
	    $view->books = $books;
	    $categories = BookModel::getCategories();
      $view->categories = $categories;
      return $view;
    }

	/**
     * The search page
     * @access  post
     * @return  Response
	 */
    public function post_search() {
      $radioButton = Input::post("radioButton");

      if($radioButton == 0){
        $category = Input::post("categorynewbook");
        $resultDTO = BookModel::searchByCategory($category);;
      }else if($radioButton == 1){
        $author = Input::post("author");
        if($author == ""){
          echo '<script>alert("Please fill at least one field");</script>';
            Response::redirect('/', 'refresh');
        }
        $resultDTO = BookModel::searchByAuthor($author);
      }else if($radioButton == 2){
        $name = Input::post("name");
        if($name == ""){
          echo '<script>alert("Please fill at least one field");</script>';
          Response::redirect('/', 'refresh');
        }
        $resultDTO = BookModel::searchByName($name);
      }else if($radioButton == 3){
        $priceL = Input::post("priceL");
        $priceU = Input::post("priceU");
        if($priceL == "" and $priceU == ""){
          echo '<script>alert("Please fill one field");</script>';
          Response::redirect('/', 'refresh');
        }
        $resultDTO = BookModel::searchByPrice($priceL, $priceU);
      }else{
        Response::redirect_back('/', 'refresh');
      }

      $result = array_map(function ($b){$b = $b->toArray(); $b[6] = null; return $b;}, $resultDTO);
      Session::set('booksArray', $result);
      Response::redirect('book/list');
    }

	/**
     * Edit a book
     * @access  post
     * @return  Response
     */
    public function action_editBook($bookId){
	  Response::redirect("book/edit/".$bookId);
	}
	
	/**
     * Delete a book
     * @access  post
     * @return  Response
     */
	public function action_deleteBook($bookId){
	  Response::redirect("book/delete/".$bookId);
	}
	
	/**
     * Logout
     * @access  public
     * @return  Response
     */
    public function action_logOut(){
      \Auth::dont_remember_me();
      \Auth::logout();
      Cookie::delete('user_id');
		  Cookie::delete('login_hash');
      Response::redirect('/', 'location');
    }
  }
?>