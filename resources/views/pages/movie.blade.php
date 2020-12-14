@extends('layouts.frontend')
@section('styles')
    <link rel="stylesheet" href="{{asset('/assets/css/docs.theme.min.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('/assets/css/owl.theme.default.min.css')}}">
    <style>
        .owl-stage-outer.owl-height{
            height: 350px !important;
        }
    </style>
@endsection

@section('content')
    <div class="container" id="app">
        <div class="row">
            <div class="col-md-6">
                <h1>@{{ title }}</h1>
            </div>
            <div class="col-md-6">
                <button class="float-right btn btn-primary" v-on:click="visible=!visible">@{{visible?'Скрыть':'Отобразить'}}</button>
            </div>
        </div>
            <!-- Section: Blog v.4 -->
        <section class="my-5">

            <!-- Grid row -->
            <div class="row">
                <!-- Grid column -->
                <div class="col-md-6">

                    <!-- Card -->
                    <div class="card card-cascade wider reverse">

                        <!-- Card image -->
                        <div class="view view-cascade overlay">
                            <img class="card-img-top" :src="`https://image.tmdb.org/t/p/w500/${movie.backdrop_path}`" alt="Sample image">
                            <a href="#!">
                                <div class="mask rgba-white-slight"></div>
                            </a>
                        </div>

                        <!-- Card content -->
                        <div class="card-body card-body-cascade text-center">

                            <!-- Title -->
                            <h2 class="font-weight-bold"><a>@{{ movie.title }}</a></h2>
                            <!-- Data -->
                            <p>Статус <a><strong>@{{ movie.status }}</strong></a>, @{{ movie.release_date }}</p>
                            <!-- Social shares -->
                            <div class="social-counters">
                                <small v-if="movie.original_title != ''">Оригинальное имя: @{{ movie.original_title }}</small><hr>
                                <small v-if="movie.original_language != ''">Оригинальный язык: @{{ movie.original_language }}</small><hr>
                                <small v-if="movie.release_date != ''">Дата выхода: @{{ movie.release_date }}</small><hr>
                                <small v-if="movie.vote_average != ''">Средняя оценка: @{{ movie.vote_average }}</small><hr>
                                <button v-if="movieInFavs" class="btn btn-danger addToFavourite" @click="removeFromFav(movie.id)">-</button>
                                <button v-else class="btn btn-primary addToFavourite" @click="addToFav(index)">+</button>
                            </div>
                            <!-- Social shares -->

                        </div>
                        <!-- Card content -->

                    </div>
                    <!-- Card -->

                    <!-- Excerpt -->
                    <div class="mt-5">

                        <p>@{{ movie.overview }}</p>
                    </div>

                </div>
                <div class="list-group all col-md-4 offset-md-2 card" v-show="visible" v-if="favourites.length">
                    <div class="list-group-item d-flex flex-row p-1" v-for="(favor,index) in favourites" :key="index">
                        <div class="w-25 d-flex justify-content-center mr-3">
                            <img width="80px" :src="`https://image.tmdb.org/t/p/w500${favor.poster_path}`" alt="Card image cap">
                        </div>
                        <div class="w-75">
                            <small class="text-break" v-if="favor.title != ''">Название: <a :href="`/${favor.imdb_id}`">@{{ favor.title }}</a></small><hr>
                            <small v-if="favor.release_date != ''">Дата выхода: @{{ favor.release_date }}</small><hr>
                            <small v-if="favor.vote_average != ''">Средняя оценка: @{{ favor.vote_average }}</small><hr>
                            <button class="btn btn-danger addToFavourite" @click="removeFromFav(favor.id)">-</button>
                        </div>
                        <div class="float-right">
                        </div>
                    </div>
                </div>
                <div class="col-md-6" v-show="!visible" v-if="!favourites.length">
                    <div v-for="(video,index) in videos" :key="index">
                        <div class="container z-depth-1 my-5 py-5">
                            <section>
                                <iframe width="100%" class="embed-responsive-item" :src="`https://www.youtube.com/embed/${video.key}`" allowfullscreen></iframe>
                                <small v-if="video.type != ''">Тип видео: @{{video.type}}</small><hr>
                                <small v-if="video.site != ''">Источник видео: @{{video.site}}</small>
                            </section>
                        </div>
                    </div>
                </div>
                <!-- Grid column -->

            </div>
            <!-- Grid row -->

            <hr class="mb-5 mt-4">
        </section>

        <section>
            <div class="row">
                <div class="large-12 columns">
                    <div class="owl-carousel owl-theme">
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://cdn.boomcdn.com/libs/owl-carousel/2.3.4/owl.carousel.js"></script>
    <script>
        $(function(){
            $(".owl-carousel").owlCarousel({
                loop: true,
                margin:10,
                items:3,
                autoplay:true,
                autoplayTimeout:2000,
                autoHeight:true
            })
        })
    </script>
    <script type="text/javascript">
        new Vue({
            el: '#app',
            data: {
                title: "Newizze Movie List App",
                movie: {},
                videos: [],
                similar: [],
                visible: false,
                favourites: [],
                movieInFavs: false,
            },
            created() {
                let self = this;

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    dataType: "json",
                    url: "/list",
                    data: "{}",
                    success: function (response) {
                        self.favourites = response
                        console.log(response)
                    }
                })

                let presentUrl = window.location.href;
                let newData = presentUrl.split("/");
                let movie_id = newData[newData.length - 1];
                let settings = {
                    "async": true,
                    "crossDomain": false,
                    'url': 'https://api.themoviedb.org/3/movie/'+movie_id+'?api_key=9a5ee1373a374dd337c79bf08b38a072&language=ru-RU&page=1',
                    //'url': 'https://api.themoviedb.org/3/movie/popular?api_key=9a5ee1373a374dd337c79bf08b38a072&language=ru-RU&page='+pageNumber,
                    "method": "GET",
                    "headers": {
                        "Content-Type": "application/json",
                        "Authorization": "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5YTVlZTEzNzNhMzc0ZGQzMzdjNzliZjA4YjM4YTA3MiIsInN1YiI6IjVmZDQ5M2MwMDkxZTYyMDA0MTU4Nzg1NiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.hTlKsb3Pq4ClRO-vDJgeAZbzRxvTI3sIRLKS-DcujQg",
                    },
                    "processData": false,
                    "data": "{}"
                }

                $.ajax(settings).done(function (response) {
                    self.movie = response;
                    for(let j = 0;j < self.favourites.length;j++) {
                        if (self.favourites[j].imdb_id == response.id) {
                            self.movieInFavs = true
                        }
                    }
                    //console.log(self.movie);
                })

                setTimeout(() => {
                    let video = {
                        "async": true,
                        "crossDomain": false,
                        'url': 'https://api.themoviedb.org/3/movie/'+movie_id+'/videos?api_key=9a5ee1373a374dd337c79bf08b38a072&language=en-US',
                        "method": "GET",
                        "headers": {
                            "Content-Type": "application/json",
                            "Authorization": "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5YTVlZTEzNzNhMzc0ZGQzMzdjNzliZjA4YjM4YTA3MiIsInN1YiI6IjVmZDQ5M2MwMDkxZTYyMDA0MTU4Nzg1NiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.hTlKsb3Pq4ClRO-vDJgeAZbzRxvTI3sIRLKS-DcujQg",
                        },
                        "processData": false,
                        "data": "{}"
                    }

                    $.ajax(video).done(function (response) {
                        self.videos = response.results;
                        //console.log(self.videos[0]);
                    });
                },2000)

                setTimeout(() => {
                    let similar = {
                        "async": true,
                        "crossDomain": false,
                        'url': 'https://api.themoviedb.org/3/movie/'+movie_id+'/similar?api_key=9a5ee1373a374dd337c79bf08b38a072&language=ru-RU&page=1',
                        "method": "GET",
                        "headers": {
                            "Content-Type": "application/json",
                            "Authorization": "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5YTVlZTEzNzNhMzc0ZGQzMzdjNzliZjA4YjM4YTA3MiIsInN1YiI6IjVmZDQ5M2MwMDkxZTYyMDA0MTU4Nzg1NiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.hTlKsb3Pq4ClRO-vDJgeAZbzRxvTI3sIRLKS-DcujQg",
                        },
                        "processData": false,
                        "data": "{}"
                    }

                    $.ajax(similar).done(function (response) {
                        self.similar = response.results;
                        console.log(self.similar);
                        for (let i = 0; i < self.similar.length; i++) {
                            let src = self.similar[i].backdrop_path ? 'https://image.tmdb.org/t/p/w500'+self.similar[i].backdrop_path : '/img/no-poster.jpg'
                            let data = '<div class="item">' +
                                '<img width="400px" src="'+src+'"/>' +
                                '<h4>'+self.similar[i].title+'</h4>' +
                                '</div>'
                            $(".owl-carousel").owlCarousel('add',data).owlCarousel('refresh')
                        }
                    });
                },3000)
            },
            methods: {
                getList() {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "GET",
                        dataType: "json",
                        url: "/list",
                        data: "{}",
                        success: response => {
                            this.favourites = response
                        }
                    })
                },
                addToFav(index) {
                    let movie = JSON.stringify(this.movie);
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
                                this.getList()
                                alert(response.result)
                            }
                        }
                    })
                },
                removeFromFav(id) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "DELETE",
                        url: "/"+id,
                        data: "{}",
                        success: response => {
                            if (response.result !== 'undefined') {
                                this.getList()
                                alert(response.result)
                            }
                            else if (response.error !== 'undefined'){
                                alert(response.error)
                            }
                        }
                    })
                }
            }
        })
    </script>
@endsection
