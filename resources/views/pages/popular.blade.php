@extends('layouts.frontend')
@section('styles')

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
        <div class="d-flex flex-row">
            <div class="w-75 mr-2">
                <div class="row">
                    <!-- Card -->
                    <div class="col-md-3 mb-3" v-for="(article, index) in articles" :key="index">
                        <div class="card">

                            <!-- Card image -->
                            <div class="view overlay">
                                <img class="card-img-top" height="300" :src="`https://image.tmdb.org/t/p/w500${article.poster_path}`">
                                <a :href="`/${article.id }`">
                                    <div class="mask rgba-white-slight"></div>
                                </a>
                            </div>

                            <!-- Card content -->
                            <div class="card-body">

                                <p class="card-title text-truncate" v-if="article.title != ''" data-toggle="tooltip" :title="`${article.title}`"><a><strong>@{{ article.title }}</strong></a></p>
                                <hr>
                                <small v-if="article.release_date != ''">Дата выхода:@{{ article.release_date }}</small>

                            </div>

                            <div class="card-footer d-flex justify-content-center">

                                <button v-show="article.isInFavs" class="btn btn-danger addToFavourite" @click="removeFromFav(article.id)">Удалить</button>
                                <button v-show="!article.isInFavs" class="btn btn-primary addToFavourite" @click="addToFav(index)">Добавить</button>
                            
                            </div>
                        </div>
                        <!-- Card -->
                    </div>
                </div>
                <div class="col-md-12 row">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination pg-blue">
                            @for ($i = 1;$i < 10;$i++)
                                <li class="page-item"><a class="page-link" href="?page={{$i}}">{{$i}}</a></li>
                            @endfor
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="list-group all col-md-3 card" v-show="visible" v-if="favourites.length">
                <div class="list-group-item d-flex flex-row p-1" v-for="(favor,index) in favourites" :key="index">
                    <div class="w-25 d-flex justify-content-center mr-3">
                        <img width="70px" height="100px" :src="`https://image.tmdb.org/t/p/w500${favor.poster_path}`" alt="Card image cap">
                    </div>
                    <div class="w-75">
                        <small class="text-break" v-if="favor.title != ''">Название: <a :href="`/${favor.imdb_id}`">@{{ favor.title }}</a></small><hr>
                        <small v-if="favor.release_date != ''">Дата выхода:@{{ favor.release_date }}</small><hr>
                        <small v-if="favor.vote_average != ''">Средняя оценка: @{{ favor.vote_average }}</small><hr>
                        <button class="btn btn-danger addToFavourite" @click="removeFromFav(favor.id)">-</button>
                    </div>
                    <div class="float-right">
                    </div>
                </div>
            </div>
            <div class="card w-25 empty" v-else-if="favourites.length == 0" v-show="visible">
                <div class="card-body">
                    <p>Список пуст</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
        <script type="text/javascript">
            new Vue({
                el: '#app',
                data: {
                    title: "Newizze Movie List App",
                    articles: [],
                    visible: false,
                    favourites: [],
                },
                created() {
                    let self = this;
                    let imdbMovies = []

                    let pageNumber = 1;
                    let presentUrl = window.location.href;
                    let newData = presentUrl.split("=");
                    pageNumber = newData[newData.length - 1];

                    if(!$.isNumeric(pageNumber)) {
                        pageNumber = 1
                    }

                    $.ajax({
                        async: true,
                        crossDomain: false,
                        url: 'https://api.themoviedb.org/3/movie/popular?api_key=9a5ee1373a374dd337c79bf08b38a072&language=ru-RU&page='+pageNumber,
                        method: "GET",
                        headers: {
                            "Content-Type": "application/json",
                            "Authorization": "Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI5YTVlZTEzNzNhMzc0ZGQzMzdjNzliZjA4YjM4YTA3MiIsInN1YiI6IjVmZDQ5M2MwMDkxZTYyMDA0MTU4Nzg1NiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.hTlKsb3Pq4ClRO-vDJgeAZbzRxvTI3sIRLKS-DcujQg",
                        },
                        processData: false,
                        data: {},
                        success: function (resp) {
                            imdbMovies = resp.results
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                type: "GET",
                                dataType: "json",
                                url: "/list",
                                data: {},
                                success: function (response) {
                                    self.favourites = response
                                    console.log(imdbMovies, self.favourites)
                                    
                                    for (let key=0; key < imdbMovies.length; key++){
                                        let date = new Date(imdbMovies[key].release_date)
                                        imdbMovies[key].release_date = (date.getDate() < 10 ? '0' + date.getDate() : date.getDate() ) + '.' + (date.getMonth()+1 < 10 ? '0' + (date.getMonth()+1) : date.getMonth()+1) + '.' + date.getFullYear()
                                        imdbMovies[key].isInFavs = false

                                        for (let i=0;i < self.favourites.length; i++) {
                                            if (imdbMovies[key].id == self.favourites[i].imdb_id) {
                                                imdbMovies[key].isInFavs = true
                                                imdbMovies[key].db_id = self.favourites[i].id
                                            }
                                        }
                                    }
                                self.articles = imdbMovies;
                                }
                            })
                        }
                    })
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
                        let movie = JSON.stringify(this.articles[index])
                        this.articles[index].isInFavs = !this.articles[index].isInFavs
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
                            data: {},
                            success: response => {
                                if (response.result !== 'undefined') {
                                    for(let i in this.articles) {
                                        if(this.articles[i].id == id){
                                            this.articles[i].isInFavs = !this.articles[i].isInFavs
                                        }
                                    }
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
