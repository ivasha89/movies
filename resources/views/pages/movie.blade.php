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
                <h1></h1>
            </div>
            <div class="col-md-6">
                <button class="float-right btn btn-primary" v-on:click="visible=!visible">@{{visible?'Hide':'Show'}}</button>
            </div>
        </div>
            <!-- Section: Blog v.4 -->
        <section class="my-5">

            <!-- Grid row -->
            <div class="d-flex flex-row">
                <!-- Grid column -->
                <div class="w-50 mr-2">

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
                            <h2 class="font-weight-bold">@{{ movie.title }}</h2>
                            <!-- Data -->
                            <p>Status: <strong>@{{ movie.status }}</strong></p>
                            <!-- Social shares -->
                            <div class="social-counters">
                                <p v-if="movie.original_title != ''">Original title: @{{ movie.original_title }}</p><hr>
                                <p v-if="movie.original_language != ''">Original language: @{{ movie.original_language }}</p><hr>
                                <p v-if="movie.release_date != ''">Release date: @{{ movie.release_date }}</p><hr>
                                <p v-if="movie.vote_average != ''">Vote average: @{{ movie.vote_average }}</p><hr>
                                <button v-if="movieInFavs" class="btn btn-danger addToFavourite" @click="removeFromFav(movie.imdb_id)">-</button>
                                <button v-else class="btn btn-primary addToFavourite" @click="addToFav(index)">+</button>
                            </div>
                            <!-- Social shares -->

                        </div>
                        <!-- Card content -->

                    </div>
                    <!-- Card -->

                    <!-- Excerpt -->
                    <div class="mt-5 card">
                        <p class="card-header">Movie Overview</p>
                        <p class="lead card-body">@{{ movie.overview }}</p>
                    </div>

                </div>
                <div class="w-50" v-show="visible" v-if="favourites.length">
                    <div class="card mb-3" v-for="(favor,index) in favourites" :key="index">
                        <div class="row no-gutters">
                            <div class="col-md-4">
                                <img :src="`https://image.tmdb.org/t/p/w500${favor.poster_path}`" alt="Card image cap">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <small class="text-break card-title" v-if="favor.title != ''">Title: <a :href="`/${favor.imdb_id}`">@{{ favor.title }}</a></small><hr>
                                    <small v-if="favor.release_date != ''">Release date: @{{ favor.release_date}}</small><hr>
                                    <small v-if="favor.vote_average != ''">Vote average: @{{ favor.vote_average }}</small><hr>
                                    <badge class="badge badge-danger addToFavourite" @click="removeFromFav(favor.imdb_id)">Remove</badge>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card w-50 empty" v-else-if="favourites.length == 0" v-show="visible">
                    <div class="card-body">
                        <p>Favourite list is empty</p>
                    </div>
                </div>
                <div class="w-50" v-show="!visible">
                    <div v-for="(video,index) in videos" :key="index">
                        <div class="container z-depth-1 my-5 py-2">
                            <section>
                                <small v-if="video.type != ''">@{{video.site}} @{{video.type}}</small>
                                <iframe width="100%" class="embed-responsive-item" :src="`https://www.youtube.com/embed/${video.key}`" allowfullscreen></iframe>
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
                autoplayTimeout:4000,
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

                self.getList()

                let presentUrl = window.location.href;
                let newData = presentUrl.split("/");
                let movie_id = newData[newData.length - 1];
                let settings = {
                    "async": true,
                    "crossDomain": false,
                    'url': 'https://api.themoviedb.org/3/movie/'+movie_id+'?api_key=9a5ee1373a374dd337c79bf08b38a072&page=1',
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
                    let date = new Date(response.release_date)
                    self.movie.release_date = (date.getDate() < 10 ? '0' + date.getDate() : date.getDate() ) + '.' + (date.getMonth()+1 < 10 ? '0' + (date.getMonth()+1) : date.getMonth()+1) + '.' + date.getFullYear()
                    window.document.title = response.title
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
                        'url': 'https://api.themoviedb.org/3/movie/'+movie_id+'/videos?api_key=9a5ee1373a374dd337c79bf08b38a072',
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
                        'url': 'https://api.themoviedb.org/3/movie/'+movie_id+'/similar?api_key=9a5ee1373a374dd337c79bf08b38a072&page=1',
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
                                '<img class="card-image-drop" width="400px" src="'+src+'"/>' +
                                '<h4><a href="/'+self.similar[i].id+'">'+self.similar[i].title+'</a></h4>' +
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
                        data: {},
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
                    console.log(id)
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "DELETE",
                        url: "/"+id,
                        data: {},
                        success: response => {
                            console.log(response)
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
