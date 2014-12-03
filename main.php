<html>
<head>
	<?php include 'connection.php' ?>
    
    <meta charset="UTF-8">
        <link rel="shortcut icon" href="../favicon.ico">
        <!--<link href='http://fonts.googleapis.com/css?family=Raleway:400,800,300' rel='stylesheet' type='text/css'>-->
        <link rel="stylesheet" type="text/css" href="css/normalize.css" />
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/set1Custom.css" />
        <!--<link rel="stylesheet" type="text/css" href="css/set2.css" />-->

	<script type="text/javascript" src="jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="handlebars-v2.0.0.js"></script>
	<script type="text/javascript" src="query2.js"></script>
</head>
<body>
<div class="container">

    <h1>Search for an Artist/Album/Track</h1>
    <form id="search-form">
        <input type="text" id="query" value="hans zimmer" class="form-control" />
        <input type="submit" id="search" class="btn btn-primary" value="Search" />
    </form>
    <div id="results"></div>
    <div id="tracks_results" style="clear:both"></div>
    <div id="artists_results"style="clear:both"></div> 
    <div id="albums_results" style="clear:both"></div>

</div>

<!--Template
<script id="results-template" type="text/x-handlebars-template">
    <h1>Tracks</h1>
    <div id="tracks_results">
        {{> track}}
    </div>
    <br>
    {{{add_tracks "+ Tracks" tracks.next}}}
    <br>

    <h1>Artists</h1>
    <div id="artists_results">
        {{> artist}}
    </div>
    <br>
    {{{add_artists "+ Artists" artists.next}}}
    <br>

    <h1>Albums</h1>
    <div id="albums_results">
        {{> album}}
    </div>
    <br>
    {{{add_albums "+ Albums" albums.next}}}
    <br>
</script>

<!--Template_Artist
<script id="artist-template" type="text/x-handlebars-template">
    {{{name_artist tracks.0.artists.0.name}}}
    <h1>Top Tracks</h1>
    <div id="top_tracks">
        {{> topTracks}}
    </div>
    <br>
    <h1>Albums</h1>
    <div id="albumsByArtist">  
        {{> albums_byArtist}}
    </div>
    <br>
    {{{add_albumsByArtist "+ Albums" next}}}
</script>

<!--Partials-->
<script id="track-template" type="text/x-handlebars-template">
    <h1>Tracks</h1>
    {{#if tracks.items}}
        {{#each tracks.items}}
            <div class="grid">
                <figure class="effect-sadie">
                    <div class="track" id="{{id}}">
                        <img src="{{album.images.0.url}}" alt="img01"/>
                        <figcaption>
                            <h2>{{name}}</h2>
                            <p>{{#each artists}}
                                {{name}}<br>
                                {{/each}} 
                                with {{album.name}}</p>
                           
                        </figcaption>     
                    </div>    
                </figure>
            </div>
        <!--
        <figure class="effect-lily">
            <div class="track" id="{{id}}" style="width:50%; float:left">
                <div style="background-image:url({{album.images.0.url}})" class="cover"></div>    
                <figcaption>
                    <div id="track_info">
                    ID:{{id}}
                    <br>
                    TRACK_NAME:{{name}}
                    <br>
                    ARTISTS:
                        {{#each artists}}
                            {{name}}<br>
                        {{/each}}
                    ALBUM:{{album.name}}
                    </div>
                </figcaption>
            </div>
            <figure>-->
        {{/each}}
    {{else}}
    <p>No se encontró ningún track :(</p>
    {{/if}}
</script>

<script id="artist-template" type="text/x-handlebars-template">
    <h1>Artists</h1>
    {{#if artists.items}} 
        {{#each artists.items}}
            <div class="grid">
                <figure class="effect-romeo">
                    <div class="artist" id="{{id}}">
                        <img src="{{#if images.0.url}}{{images.0.url}}
                            {{else}}http://static2.businessinsider.com/image/4e1b276e49e2ae487d020000-480/spotify-horns.jpg{{/if}}"/>
                        <figcaption>
                            <h2>{{name}}</h2>
                        </figcaption>
                    </div>
                </figure>
            </div>
            <!--
            <div class="artist" id="{{id}}" style="width:50%; float:left">
                <div style="background-image:url(
                        {{#if images.0.url}}
                            {{images.0.url}}
                        {{else}}
                            https://ssl.gstatic.com/accounts/ui/avatar_2x.png
                        {{/if}});
                    float:left"  class="cover">
                </div>
                <div id="artist_info" style="float:left">
                    ID:{{id}}
                    <br>
                    ARTIST_NAME:{{name}}
                </div>
            </div>
            -->
        {{/each}}
    {{else}}
        <p>No se encontró ningún artista :(</p>
    {{/if}}
</script>

<script id="album-partial" type="text/x-handlebars-template">
    {{#if albums.items}}
        {{#each albums.items}}
            <div class="grid">
                <figure class="effect-sadie">
                    <div class="album" id="{{id}}">
                        <img src="{{images.0.url}}" />
                        <figcaption>
                        </figcaption>
                    </div>
                </figure>
            </div>
            <!--
            <div class="album" id="{{id}}" style="width:50%; float:left">
                <div style="background-image:url({{images.0.url}}); float:left" class="cover"></div>
                <div id="album_info" style="float:left">
                    ID:{{id}}
                    <br>
                    ALBUM_NAME:{{name}}
                </div>
            </div>
            -->
            {{{album_songs id}}}
        {{/each}}
    {{else}}
        <p>No se encontró ningún album :(</p>
    {{/if}}
</script>

<script id="albums_byArtist-partial" type="text/x-handlebars-template">
    {{#if items}}
        {{#each items}}
            <div class="album" id="{{id}}" style="width:50%; float:left">
                <div style="background-image:url({{images.0.url}}); float:left" class="cover"></div>
                <div id="album_info" style="float:left">
                    ID:{{id}}
                    <br>
                    ALBUM_NAME:{{name}}
                </div>
            </div>
        {{/each}}
    {{else}}
        <p>No se encontró ningún album :(</p>
    {{/if}}
</script>

<script id="top_tracks-partial" type="text/x-handlebars-template">
    {{#if tracks}}
        {{#each tracks}}
            <div class="top_track" id="{{id}}" style="width:50%; float:left">
                <div style="background-image:url({{album.images.0.url}}); float:left" class="cover"></div>
                <div id="track_info" style="float:left">
                    ID:{{id}}
                    <br>
                    TRACK_NAME:{{name}}
                    <br>
                    ARTISTS:
                        {{#each artists}}
                            {{name}}<br>
                        {{/each}}
                    ALBUM:{{album.name}}
                </div>
            </div>
        {{/each}}
    {{else}}
        <p>NO se encontró ningún track</p>
    {{/if}}
</script>

<style type="text/css">
	body {
        color:white;
    padding: 20px;
    background: #333;
    //background: #2f3238;
}

#search-form, .form-control {
    margin-bottom: 20px;
}

.cover {
    width: 150px;
    height: 150px;
    display: inline-block;
    background-size: cover;
}

.cover:hover {
    cursor: pointer;
}

.cover.playing {
    border: 5px solid #e45343;
}
</style>
alexgarciadel@hotmail.com <--enviar proyecto telefono y correo
</body>
</html>
