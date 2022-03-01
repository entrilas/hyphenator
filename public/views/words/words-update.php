<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>A Basic HTML5 Template</title>
    <meta name="description" content="A simple HTML5 Template for new projects.">
    <meta name="author" content="SitePoint">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<?php include(__DIR__ . '/../navbar.php'); ?>
<div class="container">
    <form id="wordSubmitForm">
        <div class="form-group">
            <label for="inputWord">Word</label>
            <input type="text" class="form-control" id="inputWord">
        </div>
        <div class="form-group">
            <label for="inputHyphenatedWord">Hyphenated Word</label>
            <input type="text" class="form-control" id="inputHyphenatedWord">
        </div>
        <button type="submit" id="submitButton" class="btn btn-primary">Update</button>
    </form>
</div>
<?php
echo '<script>';
echo sprintf("var id = %s", $data['id']);
echo '</script>';
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="../../js/word-fetch.js"></script>
</body>
</html>