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
                console.log(result.title)
                let newdiv = document.createElement('div')
                let p = document.createElement('p')
                newdiv.appendChild(p)
                p.innerHTML = result.title
                getSection.appendChild(newdiv)
            }
        })
        .catch(function (error) {
            console.log(error);
        })
        .then(function () {
        });
}