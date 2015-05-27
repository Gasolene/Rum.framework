<!DOCTYPE html>
<html lang="<?=Rum::app()->lang?>" >
<head>
<meta charset="<?=Rum::app()->charset?>" />
<title></title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
</head>
<body>

<div id="page">
	<div id="header">
		<a id="logo"><img src="<?=  Rum::config()->uri?>/resources/images/logo.png"/></a>
		<ul id="nav">
			<li><a href="/">Home</a></li>
		</ul>
		<a href="/logout/" class="login">Logout</a>
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
		<span><strong>Framework Version:</strong> <?php echo \System\Base\FRAMEWORK_VERSION_STRING ?></span>
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
