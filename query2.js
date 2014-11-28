$(document).ready(function(){
	/*var templateSource = document.getElementById('results-template').innerHTML,
        template = Handlebars.compile(templateSource),
        partialSource = document.getElementById('track-partial').innerHTML,
        resultsPlaceholder = document.getElementById('results');

	var source = $("#results-template").html(),
		contentSrc = $("#track-partial").html(),
		template = Handlebars.compile(source),
		contentTemplate = Handlebars.compile(contentSrc),
		resultsPlaceholder = document.getElementById('results

	*/

	var templateSource = document.getElementById('results-template').innerHTML,
		partialSource = document.getElementById('track-partial').innerHTML,
		partialSource2 = document.getElementById('artist-partial').innerHTML,
		template = Handlebars.compile(templateSource),
		contentTemplate = Handlebars.compile(partialSource),
		contentTemplate2 = Handlebars.compile(partialSource2),
		resultsPlaceholder = document.getElementById('results');


	Handlebars.registerPartial("track", contentTemplate);

	Handlebars.registerPartial("artist", contentTemplate2);

    document.getElementById('search-form').addEventListener('submit', function (e) {
        e.preventDefault();
        searchAll(document.getElementById('query').value);
    }, false);

    var searchAll = function (query){
        $.ajax({
            url: 'https://api.spotify.com/v1/search',
            data:{
                q: query,
                type: 'track,artist',
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
            	//var newContent = document.getElementById('tracks_results');
            	//newContent.innerHTML= contentTemplate(response);
                //$("#tracks_results").append(contentTemplate (response));
                $("#tracks_results").append(contentTemplate(response));
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
});