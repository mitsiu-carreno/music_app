$(document).ready(function(){
    var global_url = 'http://localhost/R&D/spotify/2-JSFIDLE/testing/';
    var api_spotify = 'https://api.spotify.com/v1/';
    var setLimit = 4;

	var template_source = document.getElementById('results-template').innerHTML,
        artist_template_source = document.getElementById('artist-template').innerHTML,

		partial_track_source = document.getElementById('track-partial').innerHTML,
		partial_artist_source = document.getElementById('artist-partial').innerHTML,
		partial_album_source = document.getElementById('album-partial').innerHTML,
        partial_top_source = document.getElementById('top_tracks-partial').innerHTML,
        partial_albumsByArtist = document.getElementById('albums_byArtist-partial').innerHTML,

		template = Handlebars.compile(template_source),
        artist_template = Handlebars.compile(artist_template_source),

		partial_track = Handlebars.compile(partial_track_source),
		partial_artist = Handlebars.compile(partial_artist_source),
		partial_album = Handlebars.compile(partial_album_source),
        partial_top_tracks = Handlebars.compile(partial_top_source),
        partial_albumsByArtist = Handlebars.compile(partial_albumsByArtist),
		resultsPlaceholder = document.getElementById('results');


	Handlebars.registerPartial("track", partial_track);

	Handlebars.registerPartial("artist", partial_artist);

	Handlebars.registerPartial("album", partial_album);

    Handlebars.registerPartial("topTracks", partial_top_tracks),

    Handlebars.registerPartial('albums_byArtist', partial_albumsByArtist),

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
                limit: setLimit
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
            	//$("#"+btn_id).removeAttr("path");
            	switch (type){
            		case "tracks_results": 
            			$("#"+type).append(partial_track(response));
                        next_url(btn_id, url, response.tracks.next);
            			break; 
            		case "artists_results":
            			$("#"+type).append(partial_artist(response));
                        next_url(btn_id, url, response.artists.next);
            			break;
            		case "albums_results":
            			$("#"+type).append(partial_album(response));
                        next_url(btn_id, url, response.albums.next);
            			break;
                    case "albumsByArtist":
                        $("#"+type).append(partial_albumsByArtist(response));
                        next_url(btn_id, url, response.next);
                        break;
            		default:
            			console.log("Sh*t didn't work");
            	}
            }
        });
    }   

    var next_url = function (btn_id, url, response){
        if(response != null){
            $("#"+btn_id).removeAttr("path");
            $("#"+btn_id).attr("path", response);
        }
        else{
            $("#"+btn_id).remove();
        }
    }

    $(document).on('click', '.track', function(){
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
                        console.log(result);        //<----INCOMPETE
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
        
        $.ajax({
            url: api_spotify + "artists/" + id + "/albums",
            data:{
                limit: setLimit
            },
            success : function (response){
                resultsPlaceholder.innerHTML = artist_template(response);
                $("#albumsByArtist").html(partial_albumsByArtist(response));
            }
        });
        $.ajax({
            url: api_spotify + "artists/"+ id + "/top-tracks",
            data:{
                country :"MX",
                limit: setLimit
            },
            success : function(response){
                
                $("#top_tracks").html(partial_top_tracks(response));
            }
        });

    }   

    $(document).on('click', '.album', function(){
        searchByAlbum($(this).attr("id"));
    });

    var searchByAlbum = function(id){
        $.ajax({
            url: api_spotify + "albums/" + id,
            success: function (response){
                console.log(response);
            }
        });
    }

    var searchSongsByAlbum = function(id){
        var result;
        $.ajax({
            url: api_spotify + "albums/"+id,
            success: function (response){
                result =response;
                return result;
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

    Handlebars.registerHelper("add_albumsByArtist", function (text, url){
        var button = $('<button></button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'albumsByArtist', id: 'add_albumsByArtist'});
        return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("name_artist", function(name){
        var text = $('<h1></h1>').text(name);
        return $('<div></div>').append(text).html();
    });

    Handlebars.registerHelper("album_songs", function(id){
        var result = searchSongsByAlbum(id);
        console.log(result);
        return $('<div></div>').append(result).html();
        //var tr = $("<tr/>").attr()
        //ver plat_web/u2/log/printer.js (11)
    });
    
});