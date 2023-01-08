let filmSearch = document.getElementById('film-search')

filmSearch.addEventListener("keyup", (event) => {
    result = document.getElementById("film-search").value
    getValue(result)
});

function getValue(result){
    getSection = document.getElementById("list");
    axios.get("https://api.themoviedb.org/3/search/movie?api_key=16eb18763928632ac96b6291fa839732&language=en-US&query="+result)
        .then(function (response) {
            console.log("a")
            while (getSection.firstChild) {
                getSection.removeChild(getSection.firstChild);
            }

            for (let i = 1; i < 20; i++) {
                let result = response.data.results[i]
                let newdiv = document.createElement('div')
                newdiv.classList.add('text-center', 'flex', 'flex-col', 'items-center', 'gap-10', 'h-[500px]')
                let poster = document.createElement('img')
                if (result.poster_path === null) {
                    poster.src = 'img/room.jpeg'
                } else {
                    poster.src = 'https://image.tmdb.org/t/p/w500' + result.poster_path
                }
                poster.classList.add('w-full', 'object-fill', 'h-[350px]', 'aspect-11/16')

                let a = document.createElement('a')
                a.href = 'movie.php?id=' + result.id + '&name=' + result.title

                let h2 = document.createElement('h2')
                h2.innerHTML = result.title
                h2.classList.add('text-3xl', 'font-bold')

                a.appendChild(poster)
                newdiv.appendChild(a)
                newdiv.appendChild(h2)
                getSection.appendChild(newdiv)
            }
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
        });
}