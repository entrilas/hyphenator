<div class="container">
    <form id="wordUpdateForm">
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
echo sprintf("var id = %s", $params['id']);
echo '</script>';
?>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="../../js/error-handler.js"></script>
<script src="../../js/words/word-fetch.js"></script>
<script src="../../js/words/word-update.js"></script>