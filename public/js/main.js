'use strict'
let list = document.getElementById('list')
let currentPage = 1

window.onload = getFilters()
window.onload = getFilm(0, 20)

// Show film
async function getFilm(min, max) {
    for (let i = min; i < max; i++) {
        await fetch('https://api.themoviedb.org/3/movie/' + i + '?api_key=051277f4f78b500821fed3e0e4d59bf4&language=en=US')
            .then(result => result.json())
            .then(data => {
                if (`${data.success}` !== 'false') {
                    let film = document.createElement('div')
                    film.id = `${data.id}`
                    // let img = document.createElement('img')
                    // img.src = 'https://image.tmdb.org/t/p/w500' + `${data['poster_path']}`
                    let h1 = document.createElement('h1')
                    h1.innerHTML = `${data.title}`
                    // let p = document.createElement('p')
                    // p.innerHTML = `${data.overview}`
                    let a = document.createElement('a')
                    a.innerHTML = 'Voir le film'
                    a.href = 'singlemovie.php?id=' + i

                    // film.appendChild(img)
                    film.appendChild(h1)
                    // film.appendChild(p)
                    film.appendChild(a)
                    list.appendChild(film)
                } else {
                    max += 1
                }
            })
    }
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
                option.value = data['genres'][i]['name']

                filters.appendChild(option)
            }
        })
}


// Page change function
let pagePlus = document.getElementById('page-plus')
let pageMinus = document.getElementById('page-minus')

pagePlus.addEventListener('click', () => {
    currentPage += 1
    let minCount = (currentPage - 1) * 20
    let maxCount = currentPage * 20
    list.innerHTML = ''
    getFilm(minCount,maxCount)
})

pageMinus.addEventListener('click', () => {
    if (currentPage > 0) {
        currentPage -= 1
        let minCount = (currentPage - 1) * 20
        let maxCount = currentPage * 20
        list.innerHTML = ''
        getFilm(minCount, maxCount)
    }
})

let btn = document.getElementById('btn')
let genre = document.getElementById('genre')
let selectedGenre = genre.value


async function filterFilm(e) {
    e.preventDefault()
    list.innerHTML = ""
    let number = 20
    for (let i = 0; i < number; i++) {
        await fetch('https://api.themoviedb.org/3/discover/movie?api_key=051277f4f78b500821fed3e0e4d59bf4&language=en-US&sort_by=popularity.desc&include_adult=false&include_video=false&page=' + currentPage + '&with_genres=' + selectedGenre + '&with_watch_monetization_types=flatrate')
            .then(result => result.json())
            .then(data => {
                let infos = data.results[i]
                if (infos.success !== 'false') {
                    let film = document.createElement('div')
                    film.id = infos.id
                    // let img = document.createElement('img')
                    // img.src = 'https://image.tmdb.org/t/p/w500' + infos.poster_path
                    let h1 = document.createElement('h1')
                    h1.innerHTML = infos.title
                    // let p = document.createElement('p')
                    // p.innerHTML = infos.overview
                    let a = document.createElement('a')
                    a.innerHTML = 'Voir le film'
                    a.href = 'singlemovie.php?id=' + infos.id

                    // film.appendChild(img)
                    film.appendChild(h1)
                    // film.appendChild(p)
                    film.appendChild(a)
                    list.appendChild(film)
                } else {
                    number += 1
                }
            })
    }
}

btn.addEventListener('click', filterFilm)


