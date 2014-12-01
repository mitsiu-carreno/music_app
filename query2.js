$(document).ready(function(){
    var global_url = 'http://localhost/R&D/spotify/2-JSFIDLE/testing/';
    var api_spotify = 'https://api.spotify.com/v1/';

	var template_source = document.getElementById('results-template').innerHTML,
        artist_template_source = document.getElementById('artist-template').innerHTML,

		partial_track_source = document.getElementById('track-partial').innerHTML,
		partial_artist_source = document.getElementById('artist-partial').innerHTML,
		partial_album_source = document.getElementById('album-partial').innerHTML,
        partial_top_source = document.getElementById('top_tracks-partial').innerHTML,

		template = Handlebars.compile(template_source),
        artist_template = Handlebars.compile(artist_template_source),

		partial_track = Handlebars.compile(partial_track_source),
		partial_artist = Handlebars.compile(partial_artist_source),
		partial_album = Handlebars.compile(partial_album_source),
        partial_top_tracks = Handlebars.compile(partial_top_source),
		resultsPlaceholder = document.getElementById('results');


	Handlebars.registerPartial("track", partial_track);

	Handlebars.registerPartial("artist", partial_artist);

	Handlebars.registerPartial("album", partial_album);

    Handlebars.registerPartial("topTracks", partial_top_tracks);

    //Reaizar Busqueda
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
    
    //Buscar más resutados
    $(document).on('click', '.search_more_btn', function(){
        search_more($(this).attr('path'), $(this).attr('searchType'), $(this).attr('id'));
    });

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

    $(document).on('click', '.track, .album', function(){
        var id=$(this).attr("id"),
            type = $(this).attr("class");
        $.ajax({
            url:api_spotify + type + "s/"+ id,
            success: function(response){
                
                $.ajax({
                    type: "POST",
                    url: global_url + "insert.php",
                    data: response,
                    
                    success: function(result){
                        console.log(result);
                    },
                    error: function (request, status, errorThrown){
                        console.log("Saving error:" + status, errorThrown);
                    }
                });
            }
        });
    });

    $(document).on('click', '.artist', function(){
        searchByArtist($(this).attr("id"));
    });

    var searchByArtist = function(id){
        var topTracks;
        var albums;
        $.ajax({
            url: api_spotify + "artists/"+ id + "/top-tracks?country=MX",
            success : function(response){
                topTracks = response;
            }
        });
        $.ajax({
            url: api_spotify + "artists/" + id + "/albums",
            success : function (response){
                albums = response;
                console.log(topTracks + albums);
            }
        });
        
    }   

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

    Handlebars.registerHelper("add_top_tracks", function (text, url){
        var button = $('<button></button>').text(text).attr({class: 'search_more_btn', path:url});
        return $('<div></div>').append(button).html();
    });
});