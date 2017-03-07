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
  echo Form::open(array('method' => 'post', 'autocomplete' => 'on', 'id' => 'form_id', 'enctype' => 'multipart/form-data'), array('useridbook' => $userId));

  //name
  echo '<p>';
    echo Form::label('Name ', 'namebook', array('class' =>'uname', 'data-icon' => 'b'));
    echo Form::input('namebook', isset($book) ? $book['name'] : '', array('required' => 'required', 'type' => 'text', 'placeholder'=>'SpeakOut Upper Intermediate'));
  echo '</p>';

  //category
  echo '<p>';
    echo Form::label('Category', 'categorybook', array('class'=>'uname', 'data-icon'=>'c'));
    echo Form::select('categorybook', isset($book) ? $book['category'] : $categories[0], array_combine($categories, $categories), array('id' => 'citysignup'));
  echo '</p>';

  //author
  echo '<p>';
    echo Form::label('Author', 'authorbook', array('class'=>'uname', 'data-icon'=>'u'));
    echo Form::input('authorbook', isset($book) ? $book['author'] : '', array('required'=>'required','pattern'=>'[a-zA-Z _-]+$', 'type'=>'text', 'placeholder'=>'France Eales'));
  echo '</p>';

  //price
  echo '<p>';
    echo Form::label('Price', 'pricebook', array('class'=>'uname', 'data-icon'=>'d'));
    echo Form::input('pricebook', isset($book) ? $book['price'] : '', array('required'=>'required', 'required pattern'=>'[0-9]', 'type'=>'number', 'placeholder'=>'160.000'));
  echo '</p>';

  //units
  echo '<p>';
    echo Form::label('Units', 'unitsbook', array('class'=>'uname', 'data-icon'=>'n'));
    echo Form::input('unitsbook', isset($book) ? $book['units'] : '', array('required'=>'required', 'pattern'=>'[0-9]', 'type' =>'number', 'placeholder'=>'4'));
  echo '</p>';

  //is new
  echo '<P>';
    echo Form::label('Is New?', 'isnewbook', array('class'=>'uname'));
    echo '<P>';
      echo Form::radio('isnewbook', '1', isset($book) && !$book['isNew'] ? false : true);
      echo Form::label(' New ', 'isnewbook');
      echo '<br>';
      echo Form::radio('isnewbook', '0', isset($book) && !$book['isNew'] ? true : false);
      echo Form::label(' Second Hand ', 'isnewbook');
    echo '</p>';
  echo '</P>';

  //preview
  echo '<p>';
    echo Form::label('Preview', 'previewbook', array('class'=>'uname', 'data-icon' => 'f'));
    echo Form::file('previewbook',  array('size'=>'500', 'required'=>'required'));
  echo '</p>';

  //back & submit
  echo Html::anchor('profile', 'Back', array('class' => 'btn btn-info', 'id'=>'back-button'));
  echo '<p class=\'signin button\'>';
  echo Form::submit('submit', 'OK');
  echo '</p>';
  echo Form::close();
?>