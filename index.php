<?php
    // determine language - either from given URL option or from browser Locale
	if(isset($_GET["lang"])) {
		$langFile = $_GET["lang"];
	} else {
		$langFile = substr(locale_get_default(), 0, 2);
	}
	// only CS and EN are supported now
	switch ($langFile) {
		case 'cs':
			require("lang/cs.php");
			break;
		default:
			require("lang/en.php");
	}
?>

<html lang="cs">
	<head>
		<title>PF-Gen</title>
		<meta charset="UTF-8">
		<meta name="description" content="<?=$lang["dscr"];?>">
		<meta name="author" content="Alois Sečkár">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Expires" content="0">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
		<link href="pfgen.css" rel="stylesheet">
	</head>

<?php
	$message = $lang["message"];
	$alert_type = "alert-info";
	$output = null;
	$img_dir = "tmp/";
	$source_file = $img_dir . "input";
	$target_file = $img_dir . "output.png";
	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	
	if(isset($_FILES["fileToUpload"])) {
		try {
			$mime = $_FILES["fileToUpload"]["type"];
			$check = $mime == "image/jpeg" || $mime == "image/png";
			if($check !== false) {
				
				if (file_exists($source_file)) {
					unlink($source_file);
				}
				if (file_exists($target_file)) {
					unlink($target_file);
				}
				
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $source_file)) {
					
					$im = imagecreatefromstring(file_get_contents($source_file));
					
					$im_x = imagesx($im);
					$im_y = imagesy($im);
					$crop_size = min($im_x, $im_y);
					$crop_x_offset = $im_x > $crop_size ? (($im_x - $crop_size) / 2) : 0;
					$crop_y_offset = $im_y > $crop_size ? (($im_y - $crop_size) / 2) : 0;
					$im = imagecrop($im, ['x' => $crop_x_offset, 'y' => $crop_y_offset, 'width' => $crop_size, 'height' => $crop_size]);
					
					if ($crop_size > 1000) {
						$im = imagescale($im, 1000, 1000, IMG_BICUBIC);
					}
					
					$stamp = imagecreatefrompng($_POST["stamp"]);
					$stamp_size = min($crop_size, 1000);
					
					imagecopyresampled($im, $stamp, 0, 0, 0, 0, $stamp_size, $stamp_size, 1000, 1000);
					imagepng($im, $target_file);
					
					imagedestroy($im);
					
					$output = $target_file;
					
					$filename = htmlspecialchars( basename( $_FILES["fileToUpload"]["name"]));
					$message = str_replace("@file@", $filename, $lang["success"]);
					$alert_type = "alert-success";
					
				} else {
					$message = $lang["error-upload"];
					$alert_type = "alert-danger";
				}
			} else {
				$message = $lang["error-format"];
				$alert_type = "alert-danger";
			}
		} catch (Exception $e) {
			$message = $lang["error-other"];
			$alert_type = "alert-danger";
		} 
	}
?> 
	
	<body>
		<div class="container text-center">
			<div>
				<a href="index.php?lang=cs" title="<?=$lang["lang-cs"];?>"><img src="lang/cs.png" alt="cs" height="24"></a>
				<a href="index.php?lang=en" title="<?=$lang["lang-en"];?>"><img src="lang/en.png" alt="en" height="24"></a>
			</div>
			<h1>PF-Gen</h1>
			<h2><?=$lang["dscr"];?></h2>
			<div class="row">
				<div class="alert <?=$alert_type;?>"><?=$message;?></div>
			</div>
			<div>
				<table>
				<tr><th><?=$lang["image"];?> 1</th><th><?=$lang["image"];?> 2</th><th><?=$lang["image"];?> 3</th><th><?=$lang["image"];?> 4</th></tr>
				<tr><td><img src="bg0.png" alt="IMG 1" class="thumb" /></td><td><img src="bg1.png" alt="IMG 2" class="thumb" /></td><td><img src="bg2.png" alt="IMG 3" class="thumb" /></td><td><img src="bg3.png" alt="IMG 4" class="thumb" /></td></tr>
				</table>
			</div>
			<div class="row">
				<form class="form-inline" action="index.php?lang=<?=$langFile;?>" method="post" enctype="multipart/form-data">
					<p>
					<label for="stamp"><?=$lang["select-frame"];?></label>
					<select name="stamp" id="stamp">
					  <option value="bg0.png" <?php if ($_POST["stamp"] == "bg0.png") echo "selected"; ?>><?=$lang["image"];?> 1</option>
					  <option value="bg1.png" <?php if ($_POST["stamp"] == "bg1.png") echo "selected"; ?>><?=$lang["image"];?> 2</option>
					  <option value="bg2.png" <?php if ($_POST["stamp"] == "bg2.png") echo "selected"; ?>><?=$lang["image"];?> 3</option>
					  <option value="bg3.png" <?php if ($_POST["stamp"] == "bg3.png") echo "selected"; ?>><?=$lang["image"];?> 4</option>
					</select>
					</p>
					<input type="file" name="fileToUpload" id="fileToUpload" style="display:none;" onchange="this.form.submit();" ondrag="this.form.submit();"/>
					<label for="fileToUpload" id="img-area" ondrag="this.form.submit();"><?=$lang["select-file"];?></label>
				</form>
				<p><?=$lang["info-start"];?></p>
				<p><?=$lang["info-crop"];?></p>
			</div>
			<hr />
			<div class="row">
				<?php if (isset($output)) { ?>
					<p><img src="<?=$output . "?" . filemtime($output) ;?>" class="res" /></p>
				<?php } ?>
			</div>
			<hr />
			<div class="row">
				<p><?=$lang["footer-contact"];?></p>
				<p><?=$lang["footer-source"];?> - <a href="https://github.com/AloisSeckar/PF-Gen">https://github.com/AloisSeckar/PF-Gen</a></p>
				<p class="font-weight-light"><a href="http://alois-seckar.cz">Alois Sečkár</a> 2022 | <a href="https://unlicense.org/">UNLICENSE</a></p>
			</div>
		</div>	
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>		
	</body>
</html>