'use strict'
let list = document.getElementById('list')
let currentPage = 1

window.onload = getFilm(0, 20)

async function getFilm(min, max) {
    for (let i = min; i < max; i++) {
        await fetch('https://api.themoviedb.org/3/movie/' + i + '?api_key=051277f4f78b500821fed3e0e4d59bf4&language=en=US')
            .then(result => result.json())
            .then(data => {
                if (`${data.success}` !== 'false') {
                    let film = document.createElement('div')
                    film.id = `${data.id}`
                    let img = document.createElement('img')
                    img.src = 'https://image.tmdb.org/t/p/w500' + `${data['poster_path']}`
                    let h1 = document.createElement('h1')
                    h1.innerHTML = `${data.original_title}`
                    let p = document.createElement('p')
                    p.innerHTML = `${data.overview}`
                    let a = document.createElement('a')
                    a.innerHTML = 'Voir le film'
                    a.href = 'singlemovie.php?id=' + i

                    film.appendChild(img)
                    film.appendChild(h1)
                    film.appendChild(p)
                    film.appendChild(a)
                    list.appendChild(film)
                } else {
                    max += 1
                }
            })
    }
}


let pagePlus = document.getElementById('page-plus')
let pageMinus = document.getElementById('page-minus')

pagePlus.addEventListener('click', () => {
    currentPage+=1
    let minCount = (currentPage - 1) * 20
    let maxCount = currentPage * 20
    list.innerHTML = ''
    getFilm(minCount, maxCount)
})

pageMinus.addEventListener('click', () => {
    if (currentPage > 0) {
        currentPage-=1
        let minCount = (currentPage - 1) * 20
        let maxCount = currentPage * 20
        list.innerHTML = ''
        getFilm(minCount, maxCount)
    }
})

let btn = document.getElementById('btn')
let genre = document.getElementById('genre')

btn.addEventListener('click', (e) => {
        list.innerHTML = ""
        let selectedGenre = genre.value
        let number = 20
        for (let i = 0; i < number; i++) {
            fetch('https://api.themoviedb.org/3/movie/' + i + '?api_key=051277f4f78b500821fed3e0e4d59bf4&language=en=US')
                .then((response) => response.json())
                .then((data) => {
                    if (data.success != false) {
                        let categories = data.genres.length
                        for (let y = 0; y < categories; y++) {
                            if (data.genres[y].name === selectedGenre) {
                                if (`${data.success}` !== 'false') {
                                    let film = document.createElement('div')
                                    film.id = `${data.id}`
                                    let img = document.createElement('img')
                                    img.src = 'https://image.tmdb.org/t/p/w500' + `${data['poster_path']}`
                                    let h1 = document.createElement('h1')
                                    h1.innerHTML = `${data.original_title}`
                                    let p = document.createElement('p')
                                    p.innerHTML = `${data.overview}`
                                    let a = document.createElement('a')
                                    a.innerHTML = 'Voir le film'
                                    a.href = 'singlemovie.php?id=' + i

                                    film.appendChild(img)
                                    film.appendChild(h1)
                                    film.appendChild(p)
                                    film.appendChild(a)
                                    list.appendChild(film)
                                } else {
                                    number += 1
                                }
                            }
                        }

                    }
                })
        }
    }
)


