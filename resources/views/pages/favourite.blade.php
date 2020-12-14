@extends('layouts.frontend')
@section('styles')

@endsection

@section('content')
    <div class="container" id="app">
        <div class="row">
            <div class="col-md-6">
                <h1>@{{ title }}</h1>
            </div>
        </div>
        <div class="row" v-if="favourites.length !== 0">
            <!-- Card -->
            <div class="list-group all ml-5 col-md-12" v-for="(article, index) in favourites" :key="index">
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

                        <hr>
                        <p class="card-title" v-if="article.title != ''"><a :href="`/${article.imdb_id}`"><strong>@{{ article.title }}</strong></a></p>
                        <hr>
                        <p class="text-monospace" v-if="article.overview != ''">Описание: @{{ article.overview }}</p><hr>
                        <p v-if="article.release_date != ''">Дата выхода: @{{ article.release_date }}</p><hr>
                        <p v-if="article.vote_average != ''">Средняя оценка: @{{ article.vote_average }}</p><hr>
                        <button class="btn btn-danger addToFavourite" @click="removeFromFav(article.id)">-</button>
                    </div>

                </div>
                <!-- Card -->
            </div>
        </div>
        <div class="card" v-else-if="favourites.length == 0">
            <div class="card-header">
                <h3>Список пуст</h3>
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
            data: {
                title: "Newizze Movie List App",
                favourites: [],
            },
            created() {
                let self = this

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "GET",
                    url: "/list",
                    data: "{}",
                    success: function (response) {
                        self.favourites = response
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
                async getList() {
                    await axios.get("/list")
                        .then(resp => {
                            this.favourites = resp.data
                            console.log(resp.data.length)
                        })
                        .catch(error => {
                            alert(error)
                        })
                },
                async removeFromFav(id) {
                    let self = this
                    await axios.delete("/"+id)
                        .then(async response => {
                            if (response.data.result !== 'undefined') {
                                alert(response.data.result)
                                await self.getList()
                            }
                            else if (response.data.error !== 'undefined') {
                                alert(response.data.error)
                            }
                    })
                }
            }
        })
    </script>
    <script>
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
@endsection
