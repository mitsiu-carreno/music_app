$(document).ready(function(){

	var template_source = document.getElementById('results-template').innerHTML,
		partial_track_source = document.getElementById('track-partial').innerHTML,
		partial_artist_source = document.getElementById('artist-partial').innerHTML,
		partial_album_source = document.getElementById('album-partial').innerHTML,
		template = Handlebars.compile(template_source),
		partial_track = Handlebars.compile(partial_track_source),
		partial_artist = Handlebars.compile(partial_artist_source),
		partial_album = Handlebars.compile(partial_album_source),
		resultsPlaceholder = document.getElementById('results');


	Handlebars.registerPartial("track", partial_track);

	Handlebars.registerPartial("artist", partial_artist);

	Handlebars.registerPartial("album", partial_album);

    document.getElementById('search-form').addEventListener('submit', function (e) {
        e.preventDefault();
        searchAll(document.getElementById('query').value);
    }, false);

    var searchAll = function (query){
        $.ajax({
            url: 'https://api.spotify.com/v1/search',
            data:{
                q: query,
                type: 'track,artist,album',
                limit: 4
            },
            success: function (response){
                resultsPlaceholder.innerHTML = template(response);
            }
        });
    }

    var search_more = function (url, type, funct){
        //console.log("eureka:" + url + "  type=" + type);
        $.ajax({
            url: url,
            success: function (response){
            	switch (type){
            		case "tracks_results": 
            			$("#"+type).append(partial_track(response));
            			break; 
            		case "artists_results":
            			console.log("debugging");
            			$("#"+type).append(partial_artist(response));
            			break;
            		case "albums_results":
            			$("#"+type).append(partial_album(response));
            			break;
            		default:
            			console.log("Sh*t didn't work");
            	}  
            }
        });
    }   

    $(document).on('click', '.search_more_btn', function(){
        search_more($(this).attr('path'), $(this).attr('searchType'));
    });


    Handlebars.registerHelper("add_tracks", function (text, url) {
        //console.log(url);
        var button = $('<button></button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'tracks_results'});
        return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("add_artists", function (text, url){
        var button = $('<button></button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'artists_results'});
        return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("add_albums", function (text, url){
    	var button = $('<button></button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'albums_results'});
    	return $('<div></div>').append(button).html();
    });
});