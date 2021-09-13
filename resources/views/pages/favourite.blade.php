@extends('layouts.frontend')
@section('styles')

@endsection

@section('content')
    <div class="container" id="app">
        <div class="row mt-2" v-if="favourites.length !== 0">
            <!-- Card -->
            <div class="list-group all ml-5 col-md-12 mb-3" v-for="(article, index) in favourites" :key="index">
                <div class="list-group-item card d-flex flex-row p-1">

                    <!-- Card image -->
                    <div class="w-25">
                        <img class="card-img-top" :src="`https://image.tmdb.org/t/p/w500/${article.poster_path}`" alt="Card image cap">
                        <a :href="`/movie/${article.imdb_id}`">
                            <div class="mask rgba-white-slight"></div>
                        </a>
                    </div>

                    <!-- Card content -->
                    <div class="w-75 ml-3">

                        <p class="h4 mt-3" v-if="article.title != ''"><a :href="`/${article.imdb_id}`"><strong>@{{ article.title }}</strong></a></p>
                        <hr>
                        <p v-if="article.overview != ''"><a class="font-weight-lighter text-wrap badge badge-primary">Overview:</a> @{{ article.overview }}</p><hr>
                        <p v-if="article.release_date != ''"><a class="font-weight-lighter text-wrap badge badge-primary">Release date:</a> @{{ article.release_date }}</p><hr>
                        <p v-if="article.vote_average != ''"><a class="font-weight-lighter text-wrap badge badge-primary">Vote average:</a> @{{ article.vote_average }}</p><hr>
                        <button class="btn btn-danger addToFavourite" @click="removeFromFav(article.imdb_id)">Remove</button>
                    </div>

                </div>
                <!-- Card -->
            </div>
        </div>
        <div class="card mt-5" v-else-if="favourites.length == 0">
            <div class="card-header">
                <h3>List is empty</h3>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.0/axios.min.js"></script>
    <script type="text/javascript">
        new Vue({
            el: '#app',
            props: ['favourites'],
            data: {
                title: "Newizze Movie List App",
                favourites: this.favourites,
            },
            watch: {
                favourites() {
                    this.getList;
                }
            },
            created() {
                let self = this

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: "/list",
                    data: {},
                    success: function (response) {
                        self.favourites = response
                        for(let i in self.favourites) {
                                let date = new Date(self.favourites[i].release_date)
                                self.favourites[i].release_date_new = (date.getDate() < 10 ? '0' + date.getDate() : date.getDate() ) + '.' + (date.getMonth()+1 < 10 ? '0' + (date.getMonth()+1) : date.getMonth()+1) + '.' + date.getFullYear()
                            }
                        console.log(response.length)
                    }
                })

                let pageNumber = 1;
                let presentUrl = window.location.href;
                let newData = presentUrl.split("=");
                pageNumber = newData[newData.length - 1];

                if(!$.isNumeric(pageNumber)) {
                    pageNumber = 1
                }
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
