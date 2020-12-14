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
        <h1>@{{ title }}</h1>
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
                <div class="col-md-6">
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
                similar: []
            },
            created() {
                let self = this;
                let presentUrl = window.location.href;
                let newData = presentUrl.split("/");
                let movie_id = self.movie.id
                let settings = {
                    "async": true,
                    "crossDomain": false,
                    'url': 'https://api.themoviedb.org/3/movie/latest?api_key=9a5ee1373a374dd337c79bf08b38a072&language=ru-RU&page=1',
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
                    movie_id = response.id;
                    console.log(self.movie);
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
                        console.log(self.videos[0]);
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
            }
        })
    </script>
@endsection
