<!DOCTYPE html><?php $content = (string) $content;/* コンテンツ部分のViewを先にレンダリング */ ?>
<html>
<head>
	<meta charset="utf-8">
	<title><?php echo View::get('title_bar',__('site.title')); ?></title>

	<?php /* スタイルシートの読み込み　*/ ?>
	<?php Asset::add_path('assets/common/', array('css', 'js', 'img')); ?>
	<?php echo Asset::css('style.css'); /*共通スタイルシート*/ ?>
	<?php echo Asset::render('extra_css'); /*View毎の追加スタイルシート*/ ?>
</head>
<body>
<div class="container"><!-- 共通のHTML構造をあれこれ -->
	<?php echo $content; ?>
</div>
<?php /* 読み込み速度向上のため、スクリプトは後方に記述　*/ ?>
<?php echo Asset::js('//code.jquery.com/jquery-2.1.4.min.js'); /*jQuery*/ ?>
<?php echo Asset::js('bootstrap.min.js'); /*Twitterbootstrap*/ ?>
<?php echo Asset::js('script.js'); /*共通スクリプト*/ ?>
<?php echo Asset::render('extra_js'); /*View毎の追加スクリプト*/ ?>
<?php if ( Fuel::$env !== Fuel::PRODUCTION ){ ?>
<!-- 実行時間: {exec_time}秒 使用メモリ: {mem_usage}バイト -->
<?php } ?>
</body>
</html>
