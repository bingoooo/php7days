<?php
	session_start();
	if ($_SESSION['username']!='admin'){
		header('Location: index.php');
		exit;
	}
	if ($_POST){
		if (trim($_POST['title'])){
			$fileName = strtolower(trim($_POST['title']));
			$originCharacters = 'àçéèêîôù';
			$destinCharacters = 'aceeeiou';
			$fileName = strtr($fileName,$originCharacters,$destinCharacters);
			$fileName = preg_replace('/[^a-z0-9-]/', '-', $fileName);
			$fileName = 'posts/'.$fileName.'.md';
			$metaData['title'] = $_POST['title'];
			$fileContent = json_encode($metaData)."\n";
			$fileContent.= strip_tags($_POST['content']);
			if (file_put_contents($fileName, $fileContent)){
				header('Location: main.php');
				exit;
			}
		} else {
			$errMSG = '<div class="err-msg">Titre Insuffisant.</div>';
		}
	} elseif (array_key_exists('edition', $_GET) && $_GET['edition']){
		$fileContent = file_get_contents('posts/'.$_GET['edition'].'.md');
		$explodedContent =  explode("\n", $fileContent,2);
		$metaData = json_decode($explodedContent[0], true);
		$content = $explodedContent[1];
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Administration</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="./css/style.css">
</head>
<body>
	<h1>Administration</h1>
	<p><a href="index.php">Terminer la session</a></p>
	<h2>Édition / Création d'articles</h2>
	<?php if (isset($errMSG)) print $errMSG; ?>
	<form method="POST">
		<label for="title">Titre de l'article</label> <input id="title" name="title" <?php if (isset($metaData['title'])) echo 'value="'.$metaData['title'].'"'; ?>><br>
		<label for="content">Contenu</label><br>
		<textarea id="content" rows="25" cols="60"><?php if (isset($content)) echo $content; ?></textarea><br>
		<input type="submit">
	</form>
</body>
</html>