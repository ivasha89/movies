<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>
            @hasSection('title')
                @yield('title')
            @else
                Movie DB API Project Laravel
            @endif
        </title>
        <link rel="stylesheet" href="{{asset('/assets/css/bootstrap.css')}}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
        <!-- Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
        <!-- Material Design Bootstrap -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">

        <style>
            .searchField {
                opacity: 96%;
                display: none;
                width: 100%;
                height: 100%;
                position: fixed;
                background: #333;
                z-index: 99999;
                padding-top: 20%;
                padding-left: 20%;
            }
            #searchInput {
                background: #333;
                width: 65%;
                padding: 20px;
                border: none;
                border-bottom: solid 1px #fff !important;
                font-size: 25px;
                color: #fff;
             }
            .error {
                display: none;
                width: 65%;
                color: red;
            }
            .searchDropdown {
                overflow: scroll !important;
                height: 300px;
                width: 65%;
                display: none;
            }
            .eachResult {
                color: #fff;
            }
            .eachResult img {
                height: 100px;
                width: 70px;
                padding: 8px;
            }
            .empty {
                height: 100px;
            }
        </style>
        @yield('styles')
    </head>
    <body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <a class="navbar-brand" href="/">Newizze</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor01">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Популярные
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/latest">Последний</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/toprated">Самые известные</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/favourites">Избранное</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                    Поделиться ссылкой</a>
                </li>
            </ul>
            <span class="mr-5 searchFieldShow text-white">Поиск</span>
        </div>
    </nav>

    <div class="searchField">
        <form action="" class="form-row searchForm">
            <input type="text" id="searchInput" placeholder="Поиск фильма">
            <span class="text-white searchButton">Поиск</span>
            <span class="error"></span>
        </form>
        <div class="searchDropdown">
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title" id="exampleModalLabel">Ссылка</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @php
                        $link = $_SERVER['SERVER_NAME'] . '/favourites';
                    @endphp
                    <form class="row g-3">
                        <div class="col-auto">
                            <input type="text" class="form-control-plaintext" readonly value="Ссылка на список">
                        </div>
                        <div class="col-auto">
                            <input type="text" value="{{$link}}" class="form-control" >
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

        @yield('content')
        <script src="https://code.jquery.com/jquery-3.5.1.js"
            integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
            integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
            crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
                integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
                crossorigin="anonymous"></script>
        <script>
            let results
            $(".searchFieldShow").on('click', function (e){
                $(".error").hide()
                $(".searchField").fadeIn(500)
                e.stopPropagation()
            })

            $(".searchForm").on('click', function (e) {
                e.stopPropagation()
            })

            $(document).click(function (){
                $(".searchField").hide()
            })

            $(".searchButton").on('click', function (){
                let searchQuery = $("#searchInput").val()
                $(".error").hide()
                if (searchQuery.length >= 3) {
                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        url: "/search/"+searchQuery,
                        data: searchQuery,
                        success: response => {
                            results = response.results
                            $(".searchDropdown").html('')
                            $(".searchDropdown").show()
                            if (!results.length) {
                                $(".searchDropdown").show()
                                $(".searchDropdown").append('<h3 class="eachResult">Нет результатов</h3>')
                                setTimeout(() => {
                                    $(".searchDropdown").fadeOut(300)
                                },3000)
                            }
                            for (let i =0;i < results.length; i++) {
                                $(".searchDropdown").append('<div class="eachResult"><a href="/'+results[i].id+'"><img src="https://image.tmdb.org/t/p/w500'+results[i].poster_path+'"></a>'+results[i].title+'<badge class="badge badge-primary ml-3 addButton" id="'+i+'">+</badge></div>')
                            }
                        }
                    })
                }
                else {
                    $(".error").show()
                    $(".error").html("Введите не менее 3 букв")
                }
            })

            $(document).on('click', '.addButton', function (e) {
                let index
                if(!$(e.target).closest(".addButton").not(this).length) {
                    index = this.id
                }
                let movie = JSON.stringify(results[index])
                console.log(index)
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: "/add",
                    data: {
                        movie: movie
                    },
                    success: response => {
                        if (response.result !== 'undefined') {
                            alert(response.result)
                        }
                    }
                })
            })
        </script>
        @yield('scripts')
    </body>
</html>
