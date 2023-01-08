let filmSearch = document.getElementById('film-search')

filmSearch.addEventListener("keyup", (event) => {
    result = document.getElementById("film-search").value
    getValue(result)
});

function getValue(result){
    getSection = document.getElementById("list");
    axios.get("https://api.themoviedb.org/3/search/movie?api_key=16eb18763928632ac96b6291fa839732&language=en-US&query="+result)
        .then(function (response) {
            while (getSection.firstChild) {
                getSection.removeChild(getSection.firstChild);
            }

            for (let i = 1; i < 20; i++) {
                console.log(response)
                let result = response.result.results[filmSearch]
                let film = document.createElement('div')
                film.id = result.id
                film.classList.add('w-screen', 'flex', 'flex-col', 'items-center', 'gap-[20px]')

                let img = document.createElement('img')
                if (result.poster_path === null) {
                    img.src = 'img/room.jpeg'
                } else {
                    img.src = 'https://image.tmdb.org/t/p/w500' + result.poster_path
                }
                img.classList.add('w-full', 'object-fill', 'h-[200px]')

                film.appendChild(img)

                let h2 = document.createElement('h2')
                h2.innerHTML = result.title
                h2.classList.add('text-4xl', 'text-center')

                film.appendChild(h2)

                let a = document.createElement('a')
                a.innerHTML = 'Voir le film'
                a.href = 'movie.php?id=' + result.id + '&name=' + result.title

                film.appendChild(a)

                let buttonDiv = document.createElement('div')
                buttonDiv.classList.add('flex', 'gap-[30px]')

                // Add to liked films button
                let addToLiked = document.createElement('form')
                let likedId = document.createElement('input')
                let addLiked = document.createElement('button')

                addToLiked.method = 'POST'

                addLiked.type = 'submit'
                addLiked.name = 'liked-mark'
                addLiked.innerHTML = '<i class="fa-solid fa-heart text-2xl"></i>'

                likedId.value = result.id
                likedId.name = 'film-id'
                likedId.type = 'hidden'

                addToLiked.appendChild(likedId)
                addToLiked.appendChild(addLiked)

                buttonDiv.appendChild(addToLiked)

                // Add to watched films button
                let addToWatched = document.createElement('form')
                let addWatched = document.createElement('button')
                let watchedId = document.createElement('input')

                addToWatched.method = 'POST'

                addWatched.type = 'submit'
                addWatched.name = 'watched-mark'
                addWatched.innerHTML = '<i class="fa-solid fa-eye text-2xl"></i>'

                watchedId.value = result.id
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
                button.innerHTML = '<i class="fa-solid fa-folder-plus text-2xl"></i>'
                filmId.value = result.id
                filmId.name = 'film-id'
                filmId.type = 'hidden'

                addToAlbum.appendChild(filmId)
                addToAlbum.appendChild(button)

                buttonDiv.appendChild(addToAlbum)

                film.appendChild(buttonDiv)
            }
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
        });
}