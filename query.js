
$(document).ready(function(){
//window.onload = function(){
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
                type: 'track,album,artist',
                limit: 4
            },
            success: function (response){
                resultsPlaceholder.innerHTML = template(response);
            }
        });
    }

    var search_more = function (url){
        $.ajax({
            url: url,
            success: function (response){
                resultsPlaceholder.innerHTML += template(response);
            }
        });
    }   

    results.addEventListener('click', function(e) {
        var target = e.target;
        console.log(target);
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
        else if(target.classList.contains('search_more_btn')) {
            var type = target.attributes.searchType.value;
            console.log("type" + type);
            search_more(target.id);
        }
    });



    document.getElementById('search-form').addEventListener('submit', function (e) {
        e.preventDefault();
        //console.log("fist =" + document.getElementById('query').value);
        //searchAlbums(document.getElementById('query').value);
        searchAll(document.getElementById('query').value);
    }, false);



    Handlebars.registerHelper("ad_tracks", function (text, url) {
        console.log(url);
        var button = $('<button></button>').text(text).attr({class: 'search_more_btn', id:url, searchType: 'track'});
        return $('<div></div>').append(button).html();
    });

});


