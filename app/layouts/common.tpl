<!DOCTYPE html>
<html lang="<?=Rum::app()->lang?>" >
<head>
<meta charset="<?=Rum::app()->charset?>" />
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<script src="<?=\Rum::config()->uri?>/assets/js/jquery-2.1.4.min.js"></script>
<link href="<?=\Rum::config()->uri?>/assets/css/combined.css" rel="stylesheet" type="text/css" media="all" />
</head>
<body>

<div id="page">
	<div id="header">
		<a id="logo"><img src="<?=\Rum::config()->uri?>/assets/img/logo.png"/></a>
		<ul id="nav">
			<li><a href="<?=\Rum::config()->uri?>">Home</a></li>
		</ul>
	</div>

	<div id="body">
		<div id="content" class="wide">
			<ul class="messages">
				<?php foreach(\Rum::messages() as $message) : ?>
				<li class="<?=\strtolower($message->type)?>"><?=$message->message?></li>
				<?php endforeach; ?>
			</ul>
			<?php $this->content() ?>
		</div>
		<div style="clear:both"></div>
	</div>

	<div id="footer">
		<span><strong>Framework version:</strong> <?php echo \System\Base\FRAMEWORK_VERSION_STRING ?></span><br />
		<span><strong>Processor:</strong> <?=\number_format(\Rum::app()->timer->elapsed()*1000, 2)?>ms</span>
	</div>

</div>
<script>
	Rum.defaultFlashHandler = function(message, type){
		$(".messages").append('<li class="'+type+'">'+message+'</li>');
	};
	Rum.defaultErrorHandler = function(status){
		if(status==401) {
			alert("Logged out! You have been logged out, please login again.");
			location.reload();
		}
		else {
			alert("The Web server encountered an unexpected condition that prevented it from fulfilling the request!");
		}
	};
</script>

</body>
</html>
