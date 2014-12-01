
$(document).ready(function(){
//window.onload = function(){
    // find template and compile it
    var templateSource = document.getElementById('results-template').innerHTML,
        template = Handlebars.compile(templateSource),
        partialSource = document.getElementById('track-partial').innerHTML,
        resultsPlaceholder = document.getElementById('results');

    document.getElementById('search-form').addEventListener('submit', function (e) {
        e.preventDefault();
        searchAll(document.getElementById('query').value);
    }, false);

    var searchAll = function (query){
        $.ajax({
            url: 'https://api.spotify.com/v1/search',
            data:{
                q: query,
                type: 'track',
                limit: 4
            },
            success: function (response){
                resultsPlaceholder.innerHTML = template(response);
            }
        });
    }

    var search_more = function (url, type){
        console.log("eureka:" + url + "  type=" + type);
        $.ajax({
            url: url,
            success: function (response){
                $("#track").append(template(response));
                //resultsPlaceholder.innerHTML += template(response); //<----ERROR HERE
            }
        });
    }   

    $(document).on('click', '.search_more_btn', function(){
        search_more($(this).attr('path'), $(this).attr('searchType'));
    });

    Handlebars.registerHelper("add_tracks", function (text, url) {
        //console.log(url);
        var button = $('<button></button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'track'});
        return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("add_artists", function (text, url){
        var button = $('<button></button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'artist'});
        return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("add_albums", function (text, url){
        var button = $('<button></button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'album'});
        return $('<div></div>').append(button).html();
    });

    Handlebars.registerPartial("track", $("#track-partial").html())
});


