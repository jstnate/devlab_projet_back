'use strict'
let list = document.getElementById('list')
let currentPage = 1
let genrePage = 1
let minCount = 0
let maxCount = 20
let btn = document.getElementById('btn')
let genre = document.getElementById('genre')
let selectedGenre = 'null'
let sort = 'popularity.desc'



window.onload = getFilters()

document.getElementById('filters').onsubmit = (e) => {
    e.preventDefault();
    document.getElementById('sort-tag').style.display = 'block'
    sort = 'popularity.desc'
    genrePage = 1
    currentPage = 1
    selectedGenre = genre.value
    getFilm(0, 20)
}

btn.addEventListener('click', getFilm(minCount, maxCount))

// Show film
async function getFilm(min, max) {
    list.innerHTML = ''
    for (let i = min; i < max; i++) {
        if (selectedGenre === 'null') {
            await fetch('https://api.themoviedb.org/3/movie/' + i + '?api_key=051277f4f78b500821fed3e0e4d59bf4&language=en=US')
                .then(async result => await result.json())
                .then(data => {
                    let infos = data
                    if (infos.success !== false) {
                        showFilm(infos)
                    } else {
                        max += 1
                    }
                })
        } else {
            await fetch('https://api.themoviedb.org/3/discover/movie?api_key=051277f4f78b500821fed3e0e4d59bf4&language=en-US&sort_by=' + sort + '&include_adult=false&include_video=false&page=' + genrePage + '&with_genres=' + selectedGenre + '&with_watch_monetization_types=flatrate')
                .then(async result => await result.json())
                .then(data => {
                    let infos = data.results[i]
                    showFilm(infos)
                })
        }
    }
}

function showFilm(data) {
    let film = document.createElement('div')
    film.id = data.id
    // let img = document.createElement('img')
    // img.src = 'https://image.tmdb.org/t/p/w500' + data.poster_path
    let h1 = document.createElement('h1')
    h1.innerHTML = data.title
    h1.classList.add('text-red', 'text-3xl')
    // let p = document.createElement('p')
    // p.innerHTML = data.overview
    let a = document.createElement('a')
    a.innerHTML = 'Voir le film'
    a.href = 'movie.php?id=' + data.id + '&name=' + data.title

    // Add to liked films
    let addToLiked = document.createElement('form')
    let likedId = document.createElement('input')
    let addLiked = document.createElement('button')

    addToLiked.method = 'POST'
    addLiked.type = 'submit'
    addLiked.name = 'liked-mark'
    addLiked.innerHTML = 'Aimer'
    likedId.value = data.id
    likedId.name = 'film-id'
    likedId.type = 'hidden'

    addToLiked.appendChild(likedId)
    addToLiked.appendChild(addLiked)

    // Add to watched films
    let addToWatched = document.createElement('form')
    let addWatched = document.createElement('button')
    let watchedId = document.createElement('input')

    addToWatched.method = 'POST'
    addWatched.type = 'submit'
    addWatched.name = 'watched-mark'
    addWatched.innerHTML = 'Visionner'
    watchedId.value = data.id
    watchedId.name = 'film-id'
    watchedId.type = 'hidden'

    addToWatched.appendChild(watchedId)
    addToWatched.appendChild(addWatched)

    // Add to specified album
    let addToAlbum = document.createElement('form')
    let button = document.createElement('button')
    let filmId = document.createElement('input')

    addToAlbum.method = 'GET'
    button.type = 'submit'
    button.innerHTML = "Tout droit dans l'album"
    filmId.value = data.id
    filmId.name = 'film-id'
    filmId.type = 'hidden'

    addToAlbum.appendChild(filmId)
    addToAlbum.appendChild(button)

    // film.appendChild(img)
    film.appendChild(h1)
    // film.appendChild(p)
    film.appendChild(a)
    film.appendChild(addToLiked)
    film.appendChild(addToWatched)
    film.appendChild(addToAlbum)
    list.appendChild(film)
}
// Create option for each movie genre
let filters = document.getElementById('genre')
async function getFilters() {
    await fetch('https://api.themoviedb.org/3/genre/movie/list?api_key=051277f4f78b500821fed3e0e4d59bf4&language=en=US')
        .then(result => result.json())
        .then(data => {
            for (let i = 0; i < data['genres'].length; i++) {
                let option = document.createElement('option')
                option.innerHTML = data['genres'][i]['name']
                option.value = data['genres'][i]['id']

                filters.appendChild(option)
            }
        })
}

// Page change function
let pagePlus = document.getElementById('page-plus')
let pageMinus = document.getElementById('page-minus')
pagePlus.addEventListener('click', () => {
    if (selectedGenre === 'null') {
        currentPage += 1
        minCount = (currentPage - 1) * 20
        maxCount = currentPage * 20
        getFilm(minCount, maxCount)
    } else {
        genrePage +=1
        minCount = (genrePage - 1) * 20
        maxCount = genrePage * 20
        getFilm(0, 20)
    }
})
pageMinus.addEventListener('click', () => {
    if (currentPage > 1 && genrePage > 1) {
        if (selectedGenre === 'null') {
            currentPage -= 1
            minCount = (currentPage - 1) * 20
            maxCount = currentPage * 20
            getFilm(minCount, maxCount)
        } else {
            genrePage -= 1
            getFilm(0, 20)
        }
    }
})

let sortButton = document.querySelectorAll('#tag')

sortButton.forEach((button) => {
    button.addEventListener('click', () => {
        if (button.value === 'popularity.desc') {
            document.getElementById('sort-infos').innerHTML = 'Popularité Décroissante'
            button.value = 'popularity.asc'
        } else if (button.value === 'popularity.asc') {
            document.getElementById('sort-infos').innerHTML = 'Popularité Croissante'
            button.value = 'popularity.desc'
        } else if (button.value === 'original_title.desc') {
            document.getElementById('sort-infos').innerHTML = 'Nom Décroissant'
            button.value = 'original_title.asc'
        } else if (button.value === 'original_title.asc') {
            document.getElementById('sort-infos').innerHTML = 'Nom Croissant'
            button.value = 'original_title.desc'
        } else if (button.value === 'vote_average.desc') {
            document.getElementById('sort-infos').innerHTML = 'Avis Décroissant'
            button.value = 'vote_average.asc'
        } else if (button.value === 'vote_average.asc') {
            document.getElementById('sort-infos').innerHTML = 'Avis Croissant'
            button.value = 'vote_average.desc'
        }
        console.log(button.value)
        sort = button.value
        getFilm(0,20)
    })
})



