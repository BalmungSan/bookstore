<!DOCTYPE html>

<html lang="es"><head>
<header>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>BookStore Search</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="/assets/css/bootstrap.css" type="text/css" media="screen">
</header>

<body>
	<div class="container">
		<h2>Listing <span class='muted'>BookStore</span></h2>
		<br>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Book Name</th>
					<th>Author</th>
					<th>Category</th>
					<th>Units</th>
					<th>Price</th>
					<th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach ($books as $book): ?>
				<tr>
					<td><?php echo $book['name']; ?></td>
					<td><?php echo $book['author']; ?></td>
					<td><?php echo $book['category']; ?></td>
					<td><?php echo $book['units']; ?></td>
					<td><?php echo $book['price']; ?></td>
					<td>
						<div class="btn-toolbar">
							<div class="btn-group">
								<?php echo Html::anchor('books/'.$book['preview'], 'View', array('class' => 'btn btn-default btn-sm', 'target' => '_blank')); ?>
								<?php echo Html::anchor('book/buy/'.$book['id'], 'Buy', array('class' => 'btn btn-success btn-sm')); ?>
							</div>
						</div>

					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php echo Html::anchor('profile', '<i class="icon-wrench"></i> Back', array('class' => 'btn btn-primary')); ?>
	</div>
</body>
