<div class="container">
    <div class="title-box">
        <h1>Hyphenated Words</h1>
        <p class="lead">Here you can hyphenate or check already existing hyphenated words<br></p>
    </div>

    <a class="btn btn-primary btn-nueva" href="words/submit"><b>+</b> Hyphenate Word </a>
    <table class="table table-bordered" id="wordsTable">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Word</th>
        <th scope="col">Hyphenated Word</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody></tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination">
            <li class="page-item"><a id="previous" class="page-link" href="">Previous</a></li>
            <li class="page-item"><a id="next" class="page-link" href="">Next</a></li>
        </ul>
    </nav>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="../../js/error-handler.js"></script>
<script src="../../js/pagination.js"></script>
<script src="../../js/words/words-fetch.js"></script>