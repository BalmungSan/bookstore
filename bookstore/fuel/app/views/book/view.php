<!DOCTYPE html>
<html lang="en" class="no-js">

</html>
<head>
    <meta charset="UTF-8" />
    <title>View | SharedBooks</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="/assets/css/view.css" />
    <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/assets/img/favicon.ico" type="image/x-icon">
    <script src="/assets/js/pdfobject.js"></script>
</head>
<body>
    <div class="container">
        <?php
    	    $book = Session::get('viewbook');
    	    $name = $book[2];
    	    $image = $book[6];
    	    $pdf = $book[8];
    	    $bookId = $book[0];
    	    Session::delete('viewbook');
    	?>

        <h1>Viewing <span class='muted'>Book</span></h1>
        <p>
    	<h2>Title: <?=$name?></h2>
        </p>

        <!-- image & pdf -->
        <div class="row">
    	    <div class="well col-xs-5 col-sm-5 col-md-5 image">
    	        <a href="#" ><img alt="" src="data:image/jpg;base64,<?=base64_encode($image)?>"/></a>

                <!-- buttons -->
    	        <div id="buttons-view">
        	        <?php echo Html::anchor('book/back', 'Back', array( 'class' => 'btn btn-primary btn-md')); ?>
                    <?php echo Html::anchor('book/buy/'.$bookId, 'Buy', array( 'class' => 'btn btn-success btn-md')); ?>
                </div>
            </div>

            <div class=" well col-xs-6 col-sm-6 col-md-6" id="pdf"></div>
            <script>PDFObject.embed("/books/<?=$pdf?>", "#pdf");</script>
        </div>
    </div>
</body>
</html>
