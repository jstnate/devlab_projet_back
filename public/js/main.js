'use strict'
let list = document.getElementById('list')
let currentPage = 1
let genrePage = 1
let minCount = 0
let maxCount = 20
let btn = document.getElementById('btn')
let genre = document.getElementById('genre')
let selectedGenre = 'null'


window.onload = getFilters()

document.getElementById('filters').onsubmit = (e) => {
    e.preventDefault();
    genrePage = 1
    currentPage = 1
    selectedGenre = genre.value
    console.log(selectedGenre)
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
            await fetch('https://api.themoviedb.org/3/discover/movie?api_key=051277f4f78b500821fed3e0e4d59bf4&language=en-US&sort_by=popularity.desc&include_adult=false&include_video=false&page=' + genrePage + '&with_genres=' + selectedGenre + '&with_watch_monetization_types=flatrate')
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
    // let p = document.createElement('p')
    // p.innerHTML = data.overview
    let a = document.createElement('a')
    a.innerHTML = 'Voir le film'
    a.href = 'movie.php?id=' + data.id + '&name=' + data.title

    let addToAlbum = document.createElement('form')
    let link = document.createElement('input')
    let button = document.createElement('button')

    addToAlbum.method = 'POST'

    link.type = 'hidden'
    link.name = 'film-id'
    link.value = data.id

    button.type = 'submit'
    // button.name = 'album.php'
    button.innerHTML = "Tout droit dans l'album.php"

    addToAlbum.appendChild(link)
    addToAlbum.appendChild(button)

    // film.appendChild(img)
    film.appendChild(h1)
    // film.appendChild(p)
    film.appendChild(a)
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
    if (currentPage > 0 && genrePage > 0) {
        if (selectedGenre === 'null') {
            currentPage -= 1
            minCount = (currentPage - 1) * 20
            maxCount = currentPage * 20
            getFilm(minCount, maxCount)
        } else {
            genrePage -= 1
            minCount = (genrePage - 1) * 20
            maxCount = genrePage * 20
            getFilm(0, 20)
        }
    }
})