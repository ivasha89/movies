@extends('layouts.frontend')
@section('styles')

@endsection

@section('content')
    <div class="container" id="app">
        <div class="d-flex mb-5 mt-3">
            <div class="w-75">
                <h1 class="card-header">@{{ title }}</h1>
            </div>
            <div class="w-25">
                <button class="float-right btn btn-primary" v-on:click="visible=!visible">@{{visible?'Hide':'Show'}}</button>
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

                                <p class="card-title text-truncate" v-if="article.title != ''" data-toggle="tooltip" :title="`${article.title}`"><a :href="`/${article.id }`"><strong>@{{ article.title }}</strong></a></p>
                                <hr>
                                <small v-if="article.release_date != ''">Release date:@{{ article.release_date }}</small>
                                <hr>
                                <small v-if="article.vote_average != ''">Vote average: @{{ article.vote_average }}</small>
                            </div>

                            <div class="d-flex">

                                <button v-show="article.isInFavs" class="flex-fill btn btn-danger addToFavourite" @click="removeFromFav(article.id)">Remove</button>
                                <button v-show="!article.isInFavs" class="flex-fill btn btn-primary addToFavourite" @click="addToFav(index)">Add</button>
                            
                            </div>
                        </div>
                        <!-- Card -->
                    </div>
                </div>
                <div class="col-md-12 mt-3 mb-5">
                    <nav aria-label="results pages">
                        <ul class="pagination pg-blue">

                            <li v-if="page > 5"class="page-item">
                                <a class="page-link" href="/">1</a>
                            </li>
                            <li v-if="page > 100"class="page-item">
                                <a class="page-link" :href="`?page=${page-100}`">-100</a>
                            </li>
                            <li v-if="page > 10"class="page-item mr-3">
                                <a class="page-link" :href="`?page=${page-10}`">-10</a>
                            </li>
                            <li v-bind:class="{'disabled': page == 1, 'page-item': true }">
                                <a class="page-link" @click="pageChange(page-1)" :href="`?page=${page}`">Previous</a>
                            </li>
                            <li v-bind:class="{'page-item': true, 'active': page == pageNumber}" v-for="pageNumber in pages.slice(pageStart-1, pageEnd)">
                                <a v-bind:class="{'disabled': page == pageNumber, 'page-link': true }" :href="`?page=${pageNumber}`">@{{pageNumber}}</a>
                            </li>
                            <li v-bind:class="{'disabled': page > pages.length || page == 500, 'page-item': true }">
                                <a class="page-link" @click="pageChange(page+1)" :href="`?page=${page}`">Next</a>
                            </li>
                            <li v-if="page < 490"class="page-item ml-3">
                                <a class="page-link" :href="`?page=${page+10}`">+10</a>
                            </li>
                            <li v-if="page < 400"class="page-item">
                                <a class="page-link" :href="`?page=${page+100}`">+100</a>
                            </li>
                            <li v-if="page < 496" class="page-item">
                                <a class="page-link" href="?page=500">500</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <!--div class="col-md-12 row">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination pg-blue">
                            <li class="page-item" v-for="n in pages"><a class="page-link" :href="`?page=${n}`">@{{n}}</a></li>
                        </ul>
                    </nav>
                </div-->
            </div>
            <div class="w-25" v-show="visible" v-if="favourites.length">
                <div class="card mb-3" v-for="(favor,index) in favourites" :key="index">
                    <div class="row no-gutters">
                        <div class="col-md-8">
                            <div class="card-body">
                                <small class="text-break card-title" v-if="favor.title != ''">Title: <a :href="`/${favor.imdb_id}`">@{{ favor.title }}</a></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div style="position:relative;">
                                <button style="right: 5px;position: absolute;" class="close addToFavourite" @click="removeFromFav(favor.imdb_id)">
                                    <span style="color: red;">&times;</span>
                                </button>
                                <img  width="100%" height="auto" :src="`https://image.tmdb.org/t/p/w500${favor.poster_path}`" alt="Card image cap">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-25 empty" v-else-if="favourites.length == 0" v-show="visible">
                <div class="card-body">
                    <p>Favourites list is empty</p>
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
                props: ['favourites'],
                data: {
                    title: "Popular movies",
                    articles: [],
                    visible: false,
                    favourites: this.favourites,
                    page: 0,
                    pages: [],
                    pageStart: 1,
                    pageEnd: 6,
                },
                watch: {
                    favourites() {
                        this.getList;
                    },
                    articles() {
                        this.getPages;
                    }
                },
                created() {
                    for(let i=1;i<501;i++) {
                        this.pages.push(i)
                    }
                    
                    window.document.title = this.title
                    let self = this;
                    let imdbMovies = []

                    let pageNumber = 1;
                    let presentUrl = window.location.href;
                    let newData = presentUrl.split("=");
                    pageNumber = newData[newData.length - 1];

                    if(!$.isNumeric(pageNumber)) {
                        pageNumber = 1
                    }
                    this.page = pageNumber
                    this.pageChange(pageNumber)
                    $.ajax({
                        async: true,
                        crossDomain: false,
                        url: 'https://api.themoviedb.org/3/movie/popular?api_key=9a5ee1373a374dd337c79bf08b38a072&page='+this.page,
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
                    setPages () {
                        let numberOfPages = Math.ceil(this.posts.length / this.perPage);
                        for (let index = 1; index <= numberOfPages; index++) {
                            this.pages.push(index);
                        }
                    },
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
                        let movie = JSON.stringify(this.articles[index])
                        let fav
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
                                if (typeof response.result !== 'undefined') {
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
                                            if (this.favourites.length){
                                                for (let i in this.favourites) {
                                                    if (this.favourites[i].imdb_id == this.articles[index].id) {
                                                        this.articles[index].isInFavs = !this.articles[index].isInFavs
                                                    }
                                                }
                                            }
                                        }
                                    })
                                    alert(response.result)
                                }
                                else if (typeof response.error !== 'undefined') {
                                    alert(response.error)
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
                    },
                    pageChange(page) {
                        this.page = +page
                        this.pageStart = 1
                        this.pageEnd = 500
                        if (page > 3) {
                            this.pageStart = page - 3
                        }
                        if (page < 497) {
                        this.pageEnd = this.pageStart + 6
                        }
                    }
                }
            })
        </script>
@endsection
