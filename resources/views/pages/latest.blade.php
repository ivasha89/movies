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
                            <img class="card-img-top" :src="`https://image.tmdb.org/t/p/w500/${movie.poster_path ? movie.poster_path : movie.backdrop_path}`" alt="Sample image">
                            <a :href="`/${movie.id }`">
                                <div class="mask rgba-white-slight"></div>
                            </a>
                        </div>

                        <!-- Card content -->
                        <div class="card-body card-body-cascade text-center">

                            <!-- Title -->
                            <h2 class="font-weight-bold"><a>@{{ movie.title }}</a></h2>
                            <!-- Data -->
                            <p>Status <strong>@{{ movie.status }}</strong></p>
                            <!-- Social shares -->
                            <div class="social-counters">
                                <p v-if="movie.original_title != ''"><hr>Original title: @{{ movie.original_title }}</p>
                                <p v-if="movie.original_language != ''"><hr>Original language: @{{ movie.original_language }}</p>
                                <p v-if="movie.release_date != ''"><hr>Release date: @{{ movie.release_date }}</p>
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
                similar: []
            },
            created() {
                let self = this;
                let presentUrl = window.location.href;
                let newData = presentUrl.split("/");
                let movie_id = self.movie.id
                let settings = 

                $.ajax({
                    async: true,
                    crossDomain: false,
                    url: 'https://api.themoviedb.org/3/movie/latest?api_key=9a5ee1373a374dd337c79bf08b38a072&page=1',
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                        "Authorization": "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5YTVlZTEzNzNhMzc0ZGQzMzdjNzliZjA4YjM4YTA3MiIsInN1YiI6IjVmZDQ5M2MwMDkxZTYyMDA0MTU4Nzg1NiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.hTlKsb3Pq4ClRO-vDJgeAZbzRxvTI3sIRLKS-DcujQg",
                    },
                    processData: false,
                    data: {},
                    success: response => {
                        self.movie = response;
                        if (response.release_date !== 'undefined') {
                            let date = new Date(response.release_date)
                            self.movie.release_date = (date.getDate() < 10 ? '0' + date.getDate() : date.getDate() ) + '.' + (date.getMonth()+1 < 10 ? '0' + (date.getMonth()+1) : date.getMonth()+1) + '.' + date.getFullYear()
                        }
                        console.log(response)
                        setTimeout(() => {
                            $.ajax({
                                async: true,
                                crossDomain: false,
                                url: 'https://api.themoviedb.org/3/movie/'+response.id+'/videos?api_key=9a5ee1373a374dd337c79bf08b38a072',
                                method: "GET",
                                headers: {
                                    "Content-Type": "application/json",
                                    "Authorization": "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5YTVlZTEzNzNhMzc0ZGQzMzdjNzliZjA4YjM4YTA3MiIsInN1YiI6IjVmZDQ5M2MwMDkxZTYyMDA0MTU4Nzg1NiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.hTlKsb3Pq4ClRO-vDJgeAZbzRxvTI3sIRLKS-DcujQg",
                                },
                                processData: false,
                                data: {},
                                success: resp => {
                                    self.videos = resp.results;
                                    console.log(resp.results);
                                }
                            })
                        },2000)

                        setTimeout(() => {
                            $.ajax({
                                async: true,
                                crossDomain: false,
                                url: 'https://api.themoviedb.org/3/movie/'+response.id+'/similar?api_key=9a5ee1373a374dd337c79bf08b38a072&page=1',
                                method: "GET",
                                headers: {
                                    "Content-Type": "application/json",
                                    "Authorization": "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5YTVlZTEzNzNhMzc0ZGQzMzdjNzliZjA4YjM4YTA3MiIsInN1YiI6IjVmZDQ5M2MwMDkxZTYyMDA0MTU4Nzg1NiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.hTlKsb3Pq4ClRO-vDJgeAZbzRxvTI3sIRLKS-DcujQg",
                                },
                                processData: false,
                                data: {},
                                success: res => {
                                    self.similar = res.results;
                                    console.log(response.id);
                                    for (let i = 0; i < self.similar.length; i++) {
                                        let src = self.similar[i].backdrop_path ? 'https://image.tmdb.org/t/p/w500'+self.similar[i].backdrop_path : '/img/no-poster.jpg'
                                        let data = '<div class="item">' +
                                            '<img src="'+src+'"/>' +
                                            '<h4><a href="/'+self.similar[i].id+'">'+self.similar[i].title+'</a></h4>' +
                                            '</div>'
                                        $(".owl-carousel").owlCarousel('add',data).owlCarousel('refresh')
                                    }
                                }
                            })
                        },3000)
                    }
                })
            }
        })
    </script>
@endsection
