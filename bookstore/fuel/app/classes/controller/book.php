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

class Controller_Book extends Controller {
	public function action_create() {
		if(!$user = Session::get('userInfo')){
            echo '<script>alert("Please log in first");</script>';
            Response::redirect('/', 'refresh');
        }

		$data = array('data' => array('categories' => BookModel::getCategories()));
		$view = View::forge('book/create', $data);
		return $view;
	}

	public function post_create() {
		//config for files
		$config = array(
            'path' => 'books/',
            'change_case' => 'lower',
            'normalize' => true,
            'auto_process' => true,
            'normalize_separator' => '-',
            'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png', 'pdf'),
        );
        Upload::process($config);

    	//check if there are valid files
    	if (Upload::is_valid()) {
	        //save them according to the config
	        Upload::save();

	        //grab the file name of the image an the preview
	        $files   = Upload::get_files();
	        $image   = $files[0]['saved_as'];
	        $preview = $files[1]['saved_as'];
    	}

		//save the book
		$user = Session::get('userInfo');
		$book = new BookDTO();
		$book->setUser($user->getId());
		$book->setName(Input::post('namenewbook'));
		$book->setAuthor(Input::post('authornewbook'));
		$book->setIsNew(Input::post('isNew'));
		$book->setCategory(Input::post('categorynewbook'));
		$book->setImg(File::read("books/".$image, true));
		$book->setPrice(Input::post('pricenewbook'));
		$book->setPreview($preview);
		$book->setQuantity(Input::post('unitsnewbook'));
		$result = BookModel::registerBook($book);

		//delete the tmp image
		File::delete("books/".$image);

		//tell the user the add book worked
		echo '<script>alert("New Book added");</script>';
		Response::redirect('store', 'refresh');
	}

	public function action_edit($bookId = null) {
		//if no book id passed, return
		is_null($bookId) and Response::redirect('/', 'location');

		//paint the edit view
		$book = BookModel::getBook($bookId)->toArray();
		$data = array('data' => array('categories' => BookModel::getCategories(), 'book' => $book));
		$view = View::forge('book/edit', $data);
		return $view;
	}

	public function post_edit($bookId = null) {
		//if no book id passed, return
		is_null($bookId) and Response::redirect('/', 'location');

		//config for files
		$config = array(
            'path' => 'books/',
            'change_case' => 'lower',
            'normalize' => true,
            'auto_process' => true,
            'normalize_separator' => '-',
            'ext_whitelist' => array('img', 'jpg', 'jpeg', 'gif', 'png', 'pdf'),
        );
        Upload::process($config);

    	//check if there are valid files
    	if (Upload::is_valid()) {
	        //save them according to the config
	        Upload::save();

	        //grab the file name of the image an the preview
	        $files   = Upload::get_files();
	        $image   = $files[0]['saved_as'];
	        $preview = $files[1]['saved_as'];
    	}

    //delete the old preview
    $book = BookModel::getBook($bookId);
    File::delete("books/".$book->getPreview());

		//update the book
		$book->setName(Input::post('namenewbook'));
		$book->setAuthor(Input::post('authornewbook'));
		$book->setIsNew(Input::post('isNew'));
		$book->setCategory(Input::post('categorynewbook'));
		$book->setImg(File::read("books/".$image, true));
		$book->setPrice(Input::post('pricenewbook'));
		$book->setPreview($preview);
		$book->setQuantity(Input::post('unitsnewbook'));
		$result = BookModel::updateBook($book);

		//delete the tmp image
		File::delete("books/".$image);

		//tell the user the edit book worked
		echo '<script>alert("Book edited");</script>';
		Response::redirect('store', 'refresh');
	}

	public function action_delete($bookId = null) {
		//if no book id passed, return
		is_null($bookId) and Response::redirect('/', 'location');

		//delete the preview
		$book   = BookModel::getBook($bookId);
		File::delete("books/".$book->getPreview());

		//delete the book from the data base
		$result = BookModel::deleteBook($bookId);

		//reload the store preview
		Response::redirect('store');
	}

	public function action_list() {
		$books = Session::get('booksArray');
		$view = View::forge("book/list");
		$view->books = $books;
		return $view;
	}

	public function action_view($bookId = null) {
		//if no book id passed, return
		is_null($bookId) and Response::redirect('/', 'location');

		//get the book data
		$book = BookModel::getBook($bookId);
		Session::set('viewbook', $book->toArray());

		//paint the view book view
		$view = View::forge('book/view');
		return $view;
	}

	public function action_buy($bookId = null) {
		//if no book id passed, return
		is_null($bookId) and Response::redirect('/', 'location');

		//Print an error message an return to list
		echo '<script>alert("Sorry, this feature has not been implemented");</script>';
    	Response::redirect_back('/', 'refresh');
	}

	public function action_back() {
	  Response::redirect('book/list', 'refresh');
	}
}
?>