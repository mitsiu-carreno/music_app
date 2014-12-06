    var global_url = 'http://localhost/R&D/spotify/2-JSFIDLE/testing/';
    var api_spotify = 'https://api.spotify.com/v1/';
    var setLimit = 6;

$(document).ready(function(){

    var artist_template_source = document.getElementById('artists-template').innerHTML,
        track_template_source = document.getElementById('tracks-template').innerHTML,
        album_template_source = document.getElementById('albums-template').innerHTML,
        player_template_source = document.getElementById('player-template').innerHTML,
        artist_info_template_source = document.getElementById('artist_info-template').innerHTML,
        album_info_template_source = document.getElementById('album_info-template').innerHTML,


        partial_track_source = document.getElementById('track-partial').innerHTML,
		partial_artist_source = document.getElementById('artist-partial').innerHTML,
		partial_album_source = document.getElementById('album-partial').innerHTML,
        partial_albumsByArtist = document.getElementById('albums_byArtist-partial').innerHTML,

        artist_template = Handlebars.compile(artist_template_source),
        track_template = Handlebars.compile(track_template_source),
        album_template = Handlebars.compile(album_template_source),
        player_template = Handlebars.compile(player_template_source),
        artist_info_template = Handlebars.compile(artist_info_template_source),
        album_info_template = Handlebars.compile(album_info_template_source),

		partial_track = Handlebars.compile(partial_track_source),
		partial_artist = Handlebars.compile(partial_artist_source),
		partial_album = Handlebars.compile(partial_album_source),
        partial_albumsByArtist = Handlebars.compile(partial_albumsByArtist),

		tracks_area = document.getElementById('tracks_area'),
        artists_area = document.getElementById('artists_area'),
        albums_area = document.getElementById('albums_area'),
        player_area = document.getElementById('player_area');



	Handlebars.registerPartial("track", partial_track);

	Handlebars.registerPartial("artist", partial_artist);

	Handlebars.registerPartial("album", partial_album);

    Handlebars.registerPartial('albums_byArtist', partial_albumsByArtist),

    /////////////////////////////////////////----------GENERAL----------/////////////////////////////////////////
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
                tracks_area.innerHTML = track_template(response);
                artists_area.innerHTML = artist_template(response);
                albums_area.innerHTML = album_template(response);
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
                        response = seachAlbumTracksByArtist(response)
                        $("#"+type).append(partial_albumsByArtist(response));
                        next_url(btn_id, url, response.next);
                        break;
            		default:
            			console.log("Sh*t didn't work");
            	}
            }
        });
    }   

    //Actualiza url de search_more_btn para más busquedas
    var next_url = function (btn_id, url, response){
        if(response != null){
            $("#"+btn_id).removeAttr("path");
            $("#"+btn_id).attr("path", response);
        }
        else{
            $("#"+btn_id).remove();
        }
    }

    //Animación para auto scroll al player
    var go_to_player = function(response){
        player_area.innerHTML =player_template(response);
        $('html, body').animate({     
            scrollTop: $("#player_area").offset().top -200
        }, 500);
    }

    var destroy_div_info = function (id, div_class){
        var resume = true;
        if($("."+div_class).length > 0){
            if($("."+div_class).attr("id")==id){
                resume = false;
            }
            $("."+div_class).fadeOut("fast", function(){
                $(this).remove();
            });
            //$("."+div_class).remove();
        }
        return resume;
    }
    /////////////////////////////////////////----------TRACKS----------/////////////////////////////////////////

    $(document).on('click', '.track', function(e){              //01010100101010 OPTIMIZAR con albums (y artistas??)
        e.preventDefault();
        var id=$(this).attr("id"),
            type = $(this).attr("class");
        $.ajax({
            url:api_spotify + type + "s/"+ id,
            success: function(response){
                go_to_player(response);
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

    /////////////////////////////////////////----------ALBUMS----------/////////////////////////////////////////
    $(document).on('click', '.album', function(){
        var resume = destroy_div_info($(this).attr('id'), "album_info");
        if(resume){
            searchByAlbum($(this).attr("id"), $(this));
        }
    });

    var searchByAlbum = function(id, el){
        $.ajax({
            url: api_spotify + "albums/" + id,
            data:{
                limit: setLimit
            },
            success: function (response){
                //el.append('<div class="arrow-up" style="width:0; height:0px; border-left:15px solid transparent; border-right:15px solid transparent; border-bottom: 15px solid white; position:relative;"></div>')
                el.parent().closest("div").after(album_info_template(response));
                $(".album_info").fadeIn('slow');
            }
        });
    }

    $(document).on('click', '.play_album', function(){
        var uri = {"uri": "spotify:album:" + $(this).attr("id")}
        go_to_player(uri);
    });

    /*  VERIFICAR USO
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
    */

    /////////////////////////////////////////----------ARTISTS----------/////////////////////////////////////////
    //show info of artist
     /*var artist_info = '<figure style="width:900px; max-width:900px" class="album_info">TEST Artist-info</figure>'*/
     $(document).on('click', '.artist', function(){
        var resume = destroy_div_info($(this).attr("id"), "artist_info");
        console.log("resume" + resume);
        if(resume){
            searchByArtist($(this).attr("id"), $(this), $(this).attr("name"));
        }
     });
     
    var searchByArtist = function(id, el, name){
        $.ajax({
            url: api_spotify + "artists/" + id + "/albums",
            data:{
                limit: setLimit/2
            },
            success : function (response){
                //var result = JSON.parse(response);
                response.artist_name=name;  //Adding element wow!!
                response.artist_id= id;
                //response = JSON.stringify(result);
                //console.log(response);
                response = seachAlbumTracksByArtist(response);
                el.parent().closest("div").after(artist_info_template(response));
                $(".artist_info").fadeIn('slow');
                //$(".artist_info").append(album_template(response));
                //resultsPlaceholder.innerHTML = artist_template(response);
                //$("#albumsByArtist").html(partial_albumsByArtist(response));
            }
        });
    } 

    $(document).on("click", ".play_top", function(){
        var uri = {"uri": "spotify:artist:" + $(this).attr("id")}
        go_to_player(uri);
    });

    var seachAlbumTracksByArtist= function(albums){
        //console.log(albums);
        var album;
        jQuery.each(albums.items, function(index, value) {        //PARA CADA ALBUM
            album = value.name;
            //console.log(value);
            $.ajax({
                async:false,
                url: value.href,
                success: function (canciones){
                    var each_song = [];
                    jQuery.each(canciones.tracks.items, function(index2, value2){  //PARA CADA TRACK
                        //albums.items[0]["something"] = "testing";
                        each_song.push({"name": value2.name});
                        //albums.items[index].trackedfuck = "";
                    });
                    albums.items[index].tracks = each_song;
                    //console.log(each_song);

                }
            });
        });
        console.log(albums);
        return albums;
    }
    /////////////////////////////////////////----------HELPERS----------/////////////////////////////////////////

    Handlebars.registerHelper("add_tracks", function (text, url) {
        var button = $('<spotify_button></spotify_button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'tracks_results', id:'add_tracks'});
        return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("add_artists", function (text, url){
        var button = $('<spotify_button></spotify_button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'artists_results', id:'add_artists'});
        return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("add_albums", function (text, url){
    	var button = $('<spotify_button></spotify_button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'albums_results', id:'add_albums'});
    	return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("add_albumsByArtist", function (text, url){
        var button = $('<spotify_button></spotify_button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'albumsByArtist', id: 'add_albumsByArtist'});
        return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("name_artist", function(name){
        var text = $('<h1></h1>').text(name);
        return $('<div></div>').append(text).html();
    });

    Handlebars.registerHelper("moduloIf", function(index_count, aux, mod, block){
        if((parseInt(index_count)+aux)%(mod)===0){
            return block.fn(this);
        }
    });
    
});

    /*
    EXAMPLE:
                    response = {
  "href" : "https://api.spotify.com/v1/artists/0YC192cP3KPCRWx8zr8MfZ/albums?offset=0&limit=3&album_type=single,album,compilation,appears_on",
  "items" : [ {
    
  "tracks" : [{

      "name" : "Look Closely"
    }, {
 
      "name" : "It's the Ripples That Give the Work Meaning"
    }, {

      "name" : "Rise Up"
    }, {
      
      "name" : "Hello You Beauty"
    }, {
      
      "name" : "What's the Best Thing You've Ever Stolen?"
    }, {
      
      "name" : "I Love Blood on the Snow"
    }, {
      
      "name" : "Princess Bed"
    }, {
      
      "name" : "Can You Hear Your Heart?"
    }, {
      "name" : "This Isn't Right"
    }, {
      
      "name" : "You Don't Quit Me, Boy"
    }, {
      
      "name" : "Light as a Feather"
      
    }, {
      
      "name" : "She Was Like a Bright Light"
    }, {
      
      "name" : "The Girl with the Red Hair"
    }, {
      
      "name" : "Becoming Stars"
      
    }, {
      
      "name" : "Miracle"
    } ],

    "album_type" : "album",
    "available_markets" : [ "AD", "AR", "AT", "AU", "BE", "BG", "BO", "BR", "CA", "CH", "CL", "CO", "CR", "CY", "CZ", "DE", "DK", "DO", "EC", "EE", "ES", "FI", "FR", "GB", "GR", "GT", "HK", "HN", "HU", "IE", "IS", "IT", "LI", "LT", "LU", "LV", "MC", "MT", "MX", "MY", "NI", "NL", "NO", "NZ", "PA", "PE", "PH", "PL", "PT", "PY", "RO", "SE", "SG", "SI", "SK", "SV", "TR", "TW", "US", "UY" ],
    "external_urls" : {
      "spotify" : "https://open.spotify.com/album/7a78GiEowpaCa7ZJs44xUU"
    },
    "href" : "https://api.spotify.com/v1/albums/7a78GiEowpaCa7ZJs44xUU",
    "id" : "7a78GiEowpaCa7ZJs44xUU",
    "images" : [ {
      "height" : 640,
      "url" : "https://i.scdn.co/image/ad5515d742f65d0c1754aabdf38d39c3648f639b",
      "width" : 640
    }, {
      "height" : 300,
      "url" : "https://i.scdn.co/image/04ed3455061681bc54f9d5efb258d023f627842c",
      "width" : 300
    }, {
      "height" : 64,
      "url" : "https://i.scdn.co/image/e95b2212f76cd2bd6170ffb1f83cb7162814ec8f",
      "width" : 64
    } ],
    "name" : "Interstellar: Original Motion Picture Soundtrack",
    "type" : "album",
    "uri" : "spotify:album:7a78GiEowpaCa7ZJs44xUU"
  }, {
    "album_type" : "album",
    "available_markets" : [ "AD", "AR", "AT", "AU", "BE", "BG", "BO", "BR", "CA", "CH", "CL", "CO", "CR", "CY", "CZ", "DE", "DK", "DO", "EC", "EE", "ES", "FI", "FR", "GB", "GR", "GT", "HK", "HN", "HU", "IE", "IS", "IT", "LI", "LT", "LU", "LV", "MC", "MT", "MX", "MY", "NI", "NL", "NO", "NZ", "PA", "PE", "PH", "PL", "PT", "PY", "RO", "SE", "SG", "SI", "SK", "SV", "TR", "TW", "US", "UY" ],
    "external_urls" : {
      "spotify" : "https://open.spotify.com/album/1IXbQTfzPJiozrzpjBEbcf"
    },
    "href" : "https://api.spotify.com/v1/albums/1IXbQTfzPJiozrzpjBEbcf",
    "id" : "1IXbQTfzPJiozrzpjBEbcf",
    "images" : [ {
      "height" : 640,
      "url" : "https://i.scdn.co/image/ad5515d742f65d0c1754aabdf38d39c3648f639b",
      "width" : 640
    }, {
      "height" : 300,
      "url" : "https://i.scdn.co/image/04ed3455061681bc54f9d5efb258d023f627842c",
      "width" : 300
    }, {
      "height" : 64,
      "url" : "https://i.scdn.co/image/e95b2212f76cd2bd6170ffb1f83cb7162814ec8f",
      "width" : 64
    } ],
    "name" : "Interstellar: Original Motion Picture Soundtrack (Deluxe Version)",
    "type" : "album",
    "uri" : "spotify:album:1IXbQTfzPJiozrzpjBEbcf"
  }, {
    "album_type" : "album",
    "available_markets" : [ "AD", "AR", "AT", "AU", "BE", "BG", "BO", "BR", "CA", "CH", "CL", "CO", "CR", "CY", "CZ", "DE", "DK", "DO", "EC", "EE", "ES", "FI", "FR", "GB", "GR", "GT", "HK", "HN", "HU", "IE", "IS", "IT", "LI", "LT", "LU", "LV", "MC", "MT", "MX", "MY", "NI", "NL", "NO", "NZ", "PA", "PE", "PH", "PL", "PT", "PY", "RO", "SE", "SG", "SI", "SK", "SV", "TR", "TW", "US", "UY" ],
    "external_urls" : {
      "spotify" : "https://open.spotify.com/album/55yZqIqwPFbrNf3nxUfjQZ"
    },
    "href" : "https://api.spotify.com/v1/albums/55yZqIqwPFbrNf3nxUfjQZ",
    "id" : "55yZqIqwPFbrNf3nxUfjQZ",
    "images" : [ {
      "height" : 640,
      "url" : "https://i.scdn.co/image/9e5b4e45f9d4e7f39839f6fca5e938fff2c44e06",
      "width" : 640
    }, {
      "height" : 300,
      "url" : "https://i.scdn.co/image/fb7adaccfc4b0f6201bfe2e1d5e5bfc20e15b3e5",
      "width" : 300
    }, {
      "height" : 64,
      "url" : "https://i.scdn.co/image/7bc429c43dcc151ba0e5c4e8c4ea43058e5e4100",
      "width" : 64
    } ],
    "name" : "Winter's Tale: Original Motion Picture Soundtrack",
    "type" : "album",
    "uri" : "spotify:album:55yZqIqwPFbrNf3nxUfjQZ"
  } ],
  "limit" : 3,
  "next" : "https://api.spotify.com/v1/artists/0YC192cP3KPCRWx8zr8MfZ/albums?offset=3&limit=3&album_type=single,album,compilation,appears_on",
  "offset" : 0,
  "previous" : null,
  "total" : 303
};
    */