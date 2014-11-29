$(document).ready(function(){
    var global_url = 'http://localhost/R&D/spotify/2-JSFIDLE/testing/';
    var api_spotify = 'https://api.spotify.com/v1/';

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
            url: api_spotify + "search",
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

    var search_more = function (url, type, btn_id){
        $.ajax({
            url: url,
            success: function (response){
            	$("#"+btn_id).removeAttr("path");
            	switch (type){
            		case "tracks_results": 
            			$("#"+type).append(partial_track(response));
            			$("#"+btn_id).attr("path", response.tracks.next);
            			break; 
            		case "artists_results":
            			$("#"+type).append(partial_artist(response));
            			$("#"+btn_id).attr("path", response.artists.next);
            			break;
            		case "albums_results":
            			$("#"+type).append(partial_album(response));
            			$("#"+btn_id).attr("path", response.albums.next);
            			break;
            		default:
            			console.log("Sh*t didn't work");
            	}
            }
        });
    }   

    $(document).on('click', '.search_more_btn', function(){
        search_more($(this).attr('path'), $(this).attr('searchType'), $(this).attr('id'));
    });

    $(document).on('click', '.track, .album, .artist', function(){
        var id=$(this).attr("id"),
            type = $(this).attr("class");
        $.ajax({
            url:api_spotify + type + "s/"+ id,
            success: function(response){
                $.ajax({
                    type: "POST",
                    url: global_url + "insert.php",
                    data: response,
                    dataType: "json",
                    success: function(result){
                        console.log("success");
                    },
                    error: function (request, status, errorThrown){
                        console.log("Saving error:" + status, errorThrown);
                    }
                });
            }
        });
    });

    Handlebars.registerHelper("add_tracks", function (text, url) {
        var button = $('<button></button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'tracks_results', id:'add_tracks'});
        return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("add_artists", function (text, url){
        var button = $('<button></button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'artists_results', id:'add_artists'});
        return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("add_albums", function (text, url){
    	var button = $('<button></button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'albums_results', id:'add_albums'});
    	return $('<div></div>').append(button).html();
    });
});