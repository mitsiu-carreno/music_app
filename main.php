<html>
<head>
	<?php include 'connection.php' ?>
    
    <meta charset="UTF-8">
	
	<script type="text/javascript" src="jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="handlebars-v2.0.0.js"></script>
	<script type="text/javascript" src="query2.js"></script>
</head>
<body>
<div class="container">
    <h1>Search for an Artist/Album/Track</h1>
    <form id="search-form">
        <input type="text" id="query" value="" class="form-control" />
        <input type="submit" id="search" class="btn btn-primary" value="Search" />
    </form>
    <div id="results"></div>
</div>

<!--Template-->
<script id="results-template" type="text/x-handlebars-template">
    <h1>Tracks</h1>
    <div id="tracks_results">
        {{> track}}
    </div>
    <br>
    {{{add_tracks "+ Tracks" tracks.next}}}
    <br>

    <h1>Artists</h1>
    <div id="artist_resutls">
        {{> artist}}
    </div>
    <br>
    {{{add_artists "+ Artists" artist.next}}}
    <br>

    <h1>Albums</h1>
    <div id="albums_results">
        {{> album}}
    </div>
    <br>
    {{{add_albums "+ Albums" albums.next}}}
    <br>
</script>

<!--Partials-->
<script id="track-partial" type="text/x-handlebars-template">
    {{#each tracks.items}}
        <div id="track" style="width:50%; float:left">
            <div style="background-image:url({{album.images.0.url}}); float:left" data-track-id="{{id}}" class="cover"></div>    
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
</script>

<script id="artist-partial" type="text/x-handlebars-template">
    {{#each artists.items}}
        <div id="artist" style="width:50%; float:left">
            <div style="background-image:url(
                    {{#if images.0.url}}
                        {{images.0.url}}
                    {{else}}
                        https://ssl.gstatic.com/accounts/ui/avatar_2x.png
                    {{/if}});
                float:left" data-artist-id="{{id}}" class="cover">
            </div>
            <div id="artist_info" style="float:left">
                ID:{{id}}
                <br>
                ARTIST_NAME:{{name}}
            </div>
        </div>
    {{/each}}
</script>

<script id="album-partial" type="text/x-handlebars-template">
    {{#each albums.items}}
        <div id="album" style="width:50%; float:left">
            <div style="background-image:url({{images.0.url}}); float:left" 1data-album-id="{{id}}" class="cover"></div>
            <div id="album_info" style="float:left">
                ID:{{id}}
                <br>
                ALBUM_NAME:{{name}}
            </div>
        </div>
    {{/each}}
</script>

<!--
<script id="results-template" type="text/x-handlebars-template">
    <p>Tracks</p>
    <div id="tracks_results"></div>
        {{#each tracks.items}}
            <div id="track" style="width:50%; float:left">
                <div style="background-image:url({{album.images.0.url}}); float:left" data-track-id="{{id}}" class="cover"></div>
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
    </div>
    <br>
    {{{add_tracks "+ Tracks" tracks.next }}}
    <br>

    <p>Artists</p>
    {{#each artists.items}}
        <div style="width:50%; float:left">
            <div style="background-image:url(
                    {{#if images.0.url}}
                        {{images.0.url}}
                    {{else}}
                        https://ssl.gstatic.com/accounts/ui/avatar_2x.png
                    {{/if}}); 
                float:left" data-artist-id="{{id}}" class="cover">
            </div>
            <div sytle="float:left">
                ID:{{id}}
                <br>
                ARTISTS_NAME:{{name}}
                <!--<br>
                GENRES:{{genres}}--><!--
            </div>
        </div>
    {{/each}}
    <br>
    {{{add_artists "+ Artists" artists.next }}}
    <br>

    <p>Albums</p>
    {{#each albums.items}}
        <div style="width:50%; float:left">
            <div style="background-image:url({{images.0.url}}); float:left" data-album-id="{{id}}" class="cover"></div>
            <div sytle="float:left">
                ID:{{id}}
                <br>
                ALBUM_NAME:{{name}}
            </div>
        </div>
    {{/each}}
    <br>
    {{{add_albums "+ Albums" albums.next}}}
    <br>
</script>
-->

<style type="text/css">
	body {
    padding: 20px;
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

</body>
</html>
