window.onload = function(){
// find template and compile it
var templateSource = document.getElementById('results-template').innerHTML,
    template = Handlebars.compile(templateSource),
    resultsPlaceholder = document.getElementById('results'),
    playingCssClass = 'playing',
    audioObject = null;

var fetchTracks = function (albumId, callback) {
    console.log("play");
    $.ajax({
        url: 'https://api.spotify.com/v1/albums/' + albumId,
        success: function (response) {
            callback(response);
        }
    });
};

var searchAlbums = function (query) {
    console.log("second");
    $.ajax({
        url: 'https://api.spotify.com/v1/search',
        data: {
            q: query,
            type: 'album'
        },
        success: function (response) {
            resultsPlaceholder.innerHTML = template(response);
        }
    });
};

var searchTracks = function (query) {
    console.log("search tracks");
    $.ajax({
        url: 'https://api.spotify.com/v1/search',
        data: {
            q: query,
            type: 'track'
        },
        success: function (response){
            resultsPlaceholder.innerHTML = template(response);
        }
    });
};

var searchAll = function (query){
    $.ajax({
        url: 'https://api.spotify.com/v1/search',
        data:{
            q: query,
            type: 'track,album,artist'
        },
        success: function (response){
            resultsPlaceholder.innerHTML = template(response);
        }
    });
}

results.addEventListener('click', function(e) {
    var target = e.target;
    if (target !== null && target.classList.contains('cover')) {
        if (target.classList.contains(playingCssClass)) {
            audioObject.pause();
        } else {
            if (audioObject) {
                audioObject.pause();
            }
            fetchTracks(target.getAttribute('data-album-id'), function(data) {            
                audioObject = new Audio(data.tracks.items[0].preview_url);
                audioObject.play();
                target.classList.add(playingCssClass);
                audioObject.addEventListener('ended', function() {
                    target.classList.remove(playingCssClass);
                });
                audioObject.addEventListener('pause', function() {
                    target.classList.remove(playingCssClass);
               });
            });
        }
    }
});

document.getElementById('search-form').addEventListener('submit', function (e) {
    e.preventDefault();
    console.log("fist =" + document.getElementById('query').value);
    //searchAlbums(document.getElementById('query').value);
    searchAll(document.getElementById('query').value);
}, false);


}