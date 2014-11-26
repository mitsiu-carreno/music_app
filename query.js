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

var search_more = function (query, type){
    $.ajax({
        url: 'https://api.spotify.com/v1/search',
        data:{
            q: query,
            type: type,
            offset: 20
        },
        success: function (response){
            resultsPlaceholder.innerHTML = template(response);
        }
    });
};

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

Handlebars.registerHelper("more_tracks", function (text) {
    console.log(text);
    var load_tracks_button = $('<button></button>').text(text).attr('onclick', 'load_tracks()');
    return $('<div></div>').append(load_tracks_button).html();
});

var load_tracks = function () {
    search_more(document.getElementById('query').value, "track");
};


