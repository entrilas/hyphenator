<div class="container">
    <div class="title-box">
        <h1>Available Patterns</h1>
        <p class="lead">Here you can insert pattern, which will be used to hyphenate words<br></p>
    </div>
    <a class="btn btn-primary btn-nueva" href="patterns/submit"><b>+</b> Insert Pattern </a>
    <table class="table table-bordered" id="patternsTable">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Pattern</th>
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
<script src="../../js/patterns/patterns-fetch.js"></script>