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
   
  /**
   * Add and edit book form
   */
  echo Form::open(array('method' => 'post', 'autocomplete' => 'on', 'id' => 'form_id', 'enctype' => 'multipart/form-data'));
  echo Form::hidden('userid', $userId, array('required' => 'required'));

  //name
  echo '<p>';
    echo Form::label('Name ', 'namenewbook', array('class' =>'uname', 'data-icon' => 'b'));
    echo Form::input('namenewbook', isset($book) ? $book['name'] : '', array('required' => 'required', 'type' => 'text', 'placeholder'=>'SpeakOut Upper Intermediate'));
  echo '</p>';

  //category
  echo '<p>';
    echo Form::label('Category', 'categorynewbook', array('class'=>'uname', 'data-icon'=>'c'));
    echo Form::select('categorynewbook', isset($book) ? $book['category'] : $categories[0], array_combine($categories, $categories), array('id' => 'citysignup'));
  echo '</p>';

  //author
  echo '<p>';
    echo Form::label('Author', 'authornewbook', array('class'=>'uname', 'data-icon'=>'u'));
    echo Form::input('authornewbook', isset($book) ? $book['author'] : '', array('required'=>'required','pattern'=>'[a-zA-Z _-]+$', 'type'=>'text', 'placeholder'=>'France Eales'));
  echo '</p>';

  //price
  echo '<p>';
    echo Form::label('Price', 'pricenewbook', array('class'=>'uname', 'data-icon'=>'d'));
    echo Form::input('pricenewbook', isset($book) ? $book['price'] : '', array('required'=>'required', 'required pattern'=>'[0-9]', 'type'=>'number', 'placeholder'=>'160.000'));
  echo '</p>';

  //units
  echo '<p>';
    echo Form::label('Units', 'unitsnewbook', array('class'=>'uname', 'data-icon'=>'n'));
    echo Form::input('unitsnewbook', isset($book) ? $book['units'] : '', array('required'=>'required', 'pattern'=>'[0-9]', 'type' =>'number', 'placeholder'=>'4'));
  echo '</p>';

  //is new
  echo '<P>';
    echo Form::label('Is New?', 'isnewnewbook', array('class'=>'uname'));
    echo '<P>';
      echo Form::radio('isnew', '1', isset($book) && !$book['isNew'] ? false : true);
      echo Form::label(' New ', 'isnew');
      echo '<br>';
      echo Form::radio('isnew', '0', isset($book) && !$book['isNew'] ? true : false);
      echo Form::label(' Second Hand ', 'isnew');
    echo '</p>';
  echo '</P>';

  //preview
  echo '<p>';
    echo Form::label('Preview', 'pewviewnewbook', array('class'=>'uname', 'data-icon' => 'f'));
    echo Form::file('previewnewbook',  array('size'=>'500', 'required'=>'required'));
  echo '</p>';

  //back & submit
  echo Html::anchor('profile', 'Back', array('class' => 'btn btn-info', 'id'=>'back-button'));
  echo '<p class=\'signin button\'>';
  echo Form::submit('submit', 'OK');
  echo '</p>';
  echo Form::close();
?>