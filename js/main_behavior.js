    var global_url = 'http://localhost/R&D/spotify/testing/';
    var api_spotify = 'https://api.spotify.com/v1/';
    var setLimit = 6;

$(document).ready(function(){

    var artist_template_source = document.getElementById('artists-template').innerHTML,
        track_template_source = document.getElementById('tracks-template').innerHTML,
        album_template_source = document.getElementById('albums-template').innerHTML,
        player_template_source = document.getElementById('player-template').innerHTML,
        artist_info_template_source = document.getElementById('artistInfo-template').innerHTML,
        album_info_template_source = document.getElementById('albumInfo-template').innerHTML,
        recomendaciones_template_source = document.getElementById('recomendaciones-template').innerHTML,


        partial_track_source = document.getElementById('track-partial').innerHTML,
		partial_artist_source = document.getElementById('artist-partial').innerHTML,
		partial_album_source = document.getElementById('album-partial').innerHTML,
        partial_albumsByArtist_source = document.getElementById('albumsByArtist-partial').innerHTML,
        //partial_recomendaciones_source = document.getElementById('recomendaciones-partial').innerHTML,

        artist_template = Handlebars.compile(artist_template_source),
        track_template = Handlebars.compile(track_template_source),
        album_template = Handlebars.compile(album_template_source),
        player_template = Handlebars.compile(player_template_source),
        artist_info_template = Handlebars.compile(artist_info_template_source),
        album_info_template = Handlebars.compile(album_info_template_source),
        recomendaciones_template = Handlebars.compile(recomendaciones_template_source),

		partial_track = Handlebars.compile(partial_track_source),
		partial_artist = Handlebars.compile(partial_artist_source),
		partial_album = Handlebars.compile(partial_album_source),
        partial_albumsByArtist = Handlebars.compile(partial_albumsByArtist_source),
        //partial_recomendaciones = Handlebars.compile(partial_recomendaciones_source),

		tracks_area = document.getElementById('tracksArea'),
        artists_area = document.getElementById('artistsArea'),
        albums_area = document.getElementById('albumsArea'),
        player_area = document.getElementById('playerArea');
        recomendaciones1_area = document.getElementById('recoContainer1'),
        recomendaciones2_area = document.getElementById('recoContainer2');



	Handlebars.registerPartial("track", partial_track);

	Handlebars.registerPartial("artist", partial_artist);

	Handlebars.registerPartial("album", partial_album);

    Handlebars.registerPartial('albumsByArtist', partial_albumsByArtist),


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
                $('html, body').animate({
                    scrollTop: $(".vs-content").offset().top
                }, 500);
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
            		case "tracksContainer": 
            			$("#"+type).append(partial_track(response));
                        next_url(btn_id, url, response.tracks.next);
            			break; 
            		case "artistsContainer":
            			$("#"+type).append(partial_artist(response));
                        next_url(btn_id, url, response.artists.next);
            			break;
            		case "albumsContainer":
            			$("#"+type).append(partial_album(response));
                        next_url(btn_id, url, response.albums.next);
            			break;
                    case "albumsByArtistContainer":
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
    var go_to_player = function(response, id_artitst){
        var related = searchRelatedArtists(id_artitst);
        //var top_related = getTopRelated(related);
        //console.log(id_artitst);
        player_area.innerHTML =player_template(response);
        $("#recoContainer1").html(recomendaciones_template(related));
        $("#recoContainer2").html(recomendaciones_template(related));
        $('html, body').animate({     
            scrollTop: $("#playerArea").offset().top -200
        }, 500);
    }

    var createToggleDivInfo = function (id, div_class){
        var resume = true;
        if($("."+div_class).length > 0){
            if($("."+div_class).attr("idAlbum")==id || $("."+div_class).attr("idArtist")){
                resume = false;
                //$("."+div_class).toggle('fast');
            }
            $("."+div_class).fadeOut("fast", function(){
                $(this).remove();
            });
            //$("."+div_class).remove();
        }
        return resume;
    
    }

    var searchRelatedArtists = function (artist_id){
        console.log("artist_id " + artist_id);
        var recommendedTracks =[];
        $.ajax({
            async: false,
            url: api_spotify +"artists/"+ artist_id +"/related-artists",
                success: function(related){
                    console.log(related);
                    jQuery.each(related.artists, function(index, value) {
                        if(index>5){
                            return false;
                        }
                        $.ajax({
                            async:false,
                            url: api_spotify + "artists/"+ value.id +"/top-tracks?country=MX",
                            success: function (topSongs){
                                recommendedTracks.push({"songs": topSongs.tracks[0]});
                            }
                        });

                    });    
                    console.log(recommendedTracks);
                }
        });
        return recommendedTracks;
    }
    /////////////////////////////////////////----------TRACKS----------/////////////////////////////////////////

    $(document).on('click', '.track', function(e){              //01010100101010 OPTIMIZAR con albums (y artistas??)
        e.preventDefault();
        var id=$(this).attr("idTrack");
        var uri = $(this).attr("uri");
        var artist =$(this).attr("idArtist");
        go_to_player(uri, idArtist);
        $.ajax({
            url:api_spotify +"tracks/"+ id,
            success: function(response){
                go_to_player(response, response.artists[0].id);
                response = {"track":response}
                
            }
        });
    });

    /////////////////////////////////////////----------ALBUMS----------/////////////////////////////////////////
    $(document).on('click', '.album', function(){
        var resume = createToggleDivInfo($(this).attr('idAlbum'), "albumInfoContainer");
        if(resume){
            searchByAlbum($(this).attr("idAlbum"), $(this));
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
                $(".albumInfoContainer").fadeIn('slow');
            }
        });
    }

    $(document).on('click', '.playAlbum', function(){
        var idArtist = $(this).attr("idArtist");
        var uri = {"uri": "spotify:album:" + $(this).attr("idAlbum")}
        go_to_player(uri,idArtist);
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
        var resume = createToggleDivInfo($(this).attr("idArtist"), "artistInfoContainer");
        //console.log("resume" + resume);
        if(resume){
            searchByArtist($(this).attr("idArtist"), $(this), $(this).attr("nameArtist"));
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
                $(".artistInfoContainer").fadeIn('slow');
                //$(".artist_info").append(album_template(response));
                //resultsPlaceholder.innerHTML = artist_template(response);
                //$("#albumsByArtist").html(partial_albumsByArtist(response));
            }
        });
    } 

    $(document).on("click", ".playTop", function(){
        var idArtist = $(this).attr("idArtist");
        var uri = {"uri": "spotify:artist:" + idArtist}
        go_to_player(uri, idArtist);
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

    $(document).on("click", ".displayAlbumInfoByArtist", function(){
        $(this).next().toggle();
    });

    /////////////////////////////////////////----------HELPERS----------/////////////////////////////////////////

    Handlebars.registerHelper("addTracks", function (text, url) {
        var button = $('<spotify_button></spotify_button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'tracksContainer', id:'add_tracks'});
        return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("addArtists", function (text, url){
        var button = $('<spotify_button></spotify_button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'artistsContainer', id:'add_artists'});
        return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("addAlbums", function (text, url){
    	var button = $('<spotify_button></spotify_button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'albumsContainer', id:'add_albums'});
    	return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("addAlbumsByArtist", function (text, url){
        var button = $('<spotify_button></spotify_button>').text(text).attr({class: 'search_more_btn', path:url, searchType: 'albumsByArtistContainer', id: 'add_albumsByArtistContainer'});
        return $('<div></div>').append(button).html();
    });

    Handlebars.registerHelper("moduloIf", function(index_count, aux, mod, block){
        if((parseInt(index_count)+aux)%(mod)===0){
            return block.fn(this);
        }
    });
    
});

/*CODING OFF LINE :(

    var searchRelatedArtists = function (artist_id){
        console.log("artist_id " + artist_id);
        var recommendedTracks1 =[];
        var recommendedTracks2 = []:
        $.ajax({
            async: false,
            url: api_spotify +"artists/"+ artist_id +"/related-artists",
                success: function(related){
                    console.log(related);
                    jQuery.each(related.artists, function(index, value) {
                        
                        $.ajax({
                            async:false,
                            url: api_spotify + "artists/"+ value.id +"/top-tracks?country=MX",
                            success: function (topSongs){
                                if(index<=5){
                                    recommendedTracks1.push({"songs": topSongs.tracks[0]});
                                }
                                else if(index>5){
                                    recommendedTracks1.push({"songs": topSongs.tracks[0]});
                                }
                                else if(index>10){
                                    return false;
                                }
                            }
                        });

                    });    
                    console.log(recommendedTracks);
                }
        });
        return [recommendedTracks1, recommendedTracks2];
    }

*/

/*SAVE ON DB CODE
$.ajax({
    type: "POST",
    url: global_url + "beans/insert.php",
    data: response,
    
    success: function(result){
        console.log(result);        //<----INCOMPETE
    },
    error: function (request, status, errorThrown){
        console.log("Saving error:" + status, errorThrown);
    }
});
*/