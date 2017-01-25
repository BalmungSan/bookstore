<!DOCTYPE html>

<html lang="es"><head>
<header>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<title>Books | SharedBooks</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="/assets/css/bootstrap.css" type="text/css" media="screen">
</header>

<body>
	<div class="container">
		<h2>Listing <span class='muted'>Sharedbooks</span></h2>
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
					<td><?php echo $book[2]; ?></td>
					<td><?php echo $book[3]; ?></td>
					<td><?php echo $book[5]; ?></td>
					<td><?php echo $book[9]; ?></td>
					<td><?php echo $book[7]; ?></td>
					<td>
						<div class="btn-toolbar">
							<div class="btn-group">
								<?php echo Html::anchor('book/view/'.$book[0], '<i class="icon-wrench"></i> View', array('class' => 'btn btn-default btn-sm')); ?>
								<?php echo Html::anchor('book/buy/'.$book[0], '<i class="icon-eye-open"></i> Buy', array('class' => 'btn btn-success btn-sm')); ?>
							</div>
						</div>

					</td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php echo Html::anchor('store', '<i class="icon-wrench"></i> Back', array('class' => 'btn btn-primary')); ?>
	</div>
</body>
