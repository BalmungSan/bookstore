<?php
  /**
   * Copyright 2016 SharedBooks
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

class Controller_Store extends Controller {
    public function action_index () {
        if(!$user = Session::get('userInfo')){
            echo '<script>alert("You have to Log In first");</script>';
            Response::redirect('/', 'refresh');
        }
		$userId = $user->getId();
		$view = View::forge('store/store');
		$booksDTO = BookModel::getBooksByUser($userId);
		$books = array_map(function ($b){return $b->toArray();}, $booksDTO);
		Session::set('myBooks', $books);
		$categories = BookModel::getCategories();
		$view->categories = $categories;
        return $view;
    }

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

    public function action_editBook($bookId){
		Response::redirect("book/edit/".$bookId);
	}
	public function action_deleteBook($bookId){
		Response::redirect("book/delete/".$bookId);
	}

    public function action_logOut(){
        Session::destroy();
        Response::redirect('/', 'location');
    }
}
?>