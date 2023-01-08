'use strict'
let list = document.getElementById('list')
let currentPage = 1
let genrePage = 1
let minCount = 0
let maxCount = 20
let genre = document.getElementById('genre')
let selectedGenre = 'null'
let sort = 'popularity.desc'

console.log(currentPage)
console.log(genrePage)

window.onload = getFilters()
window.onload = getFilm(0, 20)

// On filters submit, get movies, sort them and reset page values
document.getElementById('filters').onsubmit = (e) => {
    e.preventDefault();
    document.getElementById('sort-tag').style.display = 'flex'
    sort = 'popularity.desc'
    genrePage = 1
    currentPage = 1
    selectedGenre = genre.value
    getFilm(0, 20)
}

// Call api with condition if a filter is selected
async function getFilm(min, max) {
    list.innerHTML = ''
    for (let i = min; i < max; i++) {
        if (selectedGenre === 'null') {
            // Basic api call
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
            // API call by filters
            await fetch('https://api.themoviedb.org/3/discover/movie?api_key=051277f4f78b500821fed3e0e4d59bf4&language=en-US&sort_by=' + sort + '&include_adult=false&include_video=false&page=' + genrePage + '&with_genres=' + selectedGenre + '&with_watch_monetization_types=flatrate')
                .then(async result => await result.json())
                .then(data => {
                    let infos = data.results[i]
                    showFilm(infos)
                })
        }
    }
}

// Show film infos
function showFilm(data) {
    // Create film div
    let film = document.createElement('div')
    film.id = data.id
    film.classList.add('w-[250px]', 'flex', 'flex-col', 'items-center', 'justify-between', 'gap-[20px]', 'h-[600px]',)

    // Add poster of the film
    let img = document.createElement('img')
    if (data.poster_path === null) {
        img.src = 'img/room.jpeg'
    } else {
        img.src = 'https://image.tmdb.org/t/p/w500' + data.poster_path
    }
    img.classList.add('w-full', 'object-fill', 'h-[350px]')

    film.appendChild(img)

    // Add title of the film
    let h2 = document.createElement('h2')
    h2.innerHTML = data.title
    h2.classList.add('text-3xl', 'text-center', 'font-bold')

    film.appendChild(h2)

    // Add link to see all movie infos
    let a = document.createElement('a')
    a.classList.add('hover:text-[#e40b18]')
    a.innerHTML = 'Voir le film'
    a.href = 'movie.php?id=' + data.id + '&name=' + data.title

    film.appendChild(a)

    // Add div of action buttons
    let buttonDiv = document.createElement('div')
    buttonDiv.classList.add('flex', 'gap-[30px]')

    // Add to "Ma liste" button
    let addToLiked = document.createElement('form')
    let likedId = document.createElement('input')
    let addLiked = document.createElement('button')

    addToLiked.method = 'POST'

    addLiked.type = 'submit'
    addLiked.name = 'liked-mark'
    addLiked.innerHTML = '<i class="fa-solid fa-heart text-2xl hover:text-[#e40b18]"></i>'

    likedId.value = data.id
    likedId.name = 'film-id'
    likedId.type = 'hidden'

    addToLiked.appendChild(likedId)
    addToLiked.appendChild(addLiked)

    buttonDiv.appendChild(addToLiked)

    // Add to "Films visionés" button
    let addToWatched = document.createElement('form')
    let addWatched = document.createElement('button')
    let watchedId = document.createElement('input')

    addToWatched.method = 'POST'

    addWatched.type = 'submit'
    addWatched.name = 'watched-mark'
    addWatched.innerHTML = '<i class="fa-solid fa-eye text-2xl hover:text-[#e40b18]"></i>'

    watchedId.value = data.id
    watchedId.name = 'film-id'
    watchedId.type = 'hidden'

    addToWatched.appendChild(watchedId)
    addToWatched.appendChild(addWatched)

    buttonDiv.appendChild(addToWatched)

    // Add to specified album button
    let addToAlbum = document.createElement('form')
    let button = document.createElement('button')
    let filmId = document.createElement('input')

    addToAlbum.method = 'GET'
    button.type = 'submit'
    button.innerHTML = '<i class="fa-solid fa-folder-plus text-2xl hover:text-[#e40b18]"></i>'
    filmId.value = data.id
    filmId.name = 'film-id'
    filmId.type = 'hidden'

    addToAlbum.appendChild(filmId)
    addToAlbum.appendChild(button)

    buttonDiv.appendChild(addToAlbum)

    film.appendChild(buttonDiv)

    // Insert film div in html
    list.appendChild(film)
}
// Create option for each movie genre
let filters = document.getElementById('genre')

// Get movies genre from API
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
    if (currentPage > 1 || genrePage > 1) {
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

// Function to sort movies if filter is set
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
        sort = button.value
        getFilm(0,20)
    })
})



