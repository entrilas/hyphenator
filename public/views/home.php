<body>
    <div class="position-relative overflow-hidden p-3 p-md-5 m-md-3 text-center bg-light">
        <div class="col-md-5 p-lg-5 mx-auto my-5">
            <h1 class="display-4 font-weight-normal">Hyphenator</h1>
            <p class="lead font-weight-normal">Hyphenation is the use of a hyphen to break up a word when it reaches the edge of a document or container.</p>
            <a class="btn btn-outline-secondary" href="/words/submit">Hyphenate</a>
        </div>
        <div class="product-device box-shadow d-none d-md-block"></div>
        <div class="product-device product-device-2 box-shadow d-none d-md-block"></div>
    </div>

    <div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">
        <div class="bg-dark mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center text-white overflow-hidden">
            <div class="my-3 py-3">
                <h2 class="display-5">Hyphenate</h2>
                <p class="lead">Hyphenation is what you do when you use a dash-like punctuation mark to join two words into one or separate the syllables of a word.</p>
            </div>
            <div class="bg-light box-shadow mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;"></div>
        </div>
        <div class="bg-light mr-md-3 pt-3 px-3 pt-md-5 px-md-5 text-center overflow-hidden">
            <div class="my-3 p-3">
                <h2 class="display-5">Syllables</h2>
                <p class="lead">In word processing, hyphenation refers to splitting a word that would otherwise extend beyond the right margin..</p>
            </div>
            <div class="bg-dark box-shadow mx-auto" style="width: 80%; height: 300px; border-radius: 21px 21px 0 0;"></div>
        </div>
    </div>

    <footer class="container py-5">
        <div class="row">
            <div class="col-12 col-md">
                <i class="fas fa-dumbbell"></i> Visma Internship (Arnas Dulinskas)
                <small class="d-block mb-3 text-muted">&copy; 2022</small>
            </div>
        </div>
    </footer>
</body>

<script>
</script>
<style>
    .container {
        max-width: 960px;
    }
    .site-header {
        background-color: rgba(0, 0, 0, .85);
        -webkit-backdrop-filter: saturate(180%) blur(20px);
        backdrop-filter: saturate(180%) blur(20px);
    }
    .site-header a {
        color: #999;
        transition: ease-in-out color .15s;
    }
    .site-header a:hover {
        color: #fff;
        text-decoration: none;
    }

    .product-device {
        position: absolute;
        right: 10%;
        bottom: -30%;
        width: 300px;
        height: 540px;
        background-color: #333;
        border-radius: 21px;
        -webkit-transform: rotate(30deg);
        transform: rotate(30deg);
    }
    .product-device::before {
        position: absolute;
        top: 10%;
        right: 10px;
        bottom: 10%;
        left: 10px;
        content: "";
        background-color: rgba(255, 255, 255, .1);
        border-radius: 5px;
    }
    .product-device-2 {
        top: -25%;
        right: auto;
        bottom: 0;
        left: 5%;
        background-color: #e5e5e5;
    }

    .border-top { border-top: 1px solid #e5e5e5; }
    .border-bottom { border-bottom: 1px solid #e5e5e5; }
    .box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
    .flex-equal > * {
        -ms-flex: 1;
        -webkit-box-flex: 1;
        flex: 1;
    }
    @media (min-width: 768px) {
        .flex-md-equal > * {
            -ms-flex: 1;
            -webkit-box-flex: 1;
            flex: 1;
        }
    }
    .overflow-hidden { overflow: hidden; }
</style>