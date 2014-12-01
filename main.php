<html>
<head>
	<?php include 'connection.php' ?>
    
    <meta charset="UTF-8">
	
        <link rel="shortcut icon" href="../favicon.ico">
        <link href='http://fonts.googleapis.com/css?family=Flamenco' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" type="text/css" href="css/normalize.css" />
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/slideshow.css" />
        <script src="js/snap.svg-min.js"></script>
        <script src="js/modernizr.custom.js"></script>

	<script type="text/javascript" src="jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="handlebars-v2.0.0.js"></script>
	<script type="text/javascript" src="query2.js"></script>
</head>
<body>
    <!--
<div class="container">
    
    <h1>Search for an Artist/Album/Track</h1>
    <form id="search-form">
        <input type="text" id="query" value="hans zimmer" class="form-control" />
        <input type="submit" id="search" class="btn btn-primary" value="Search" />
    </form>
    <div id="results"></div>

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

<!--Template_Artist-->
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
<script id="track-partial" type="text/x-handlebars-template">
    {{#if tracks.items}}
        {{#each tracks.items}}
            <div class="track" id="{{id}}" style="width:50%; float:left">
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
        {{{add_tracks "+ Tracks" tracks.next}}}
    {{else}}
    <p>No se encontró ningún track :(</p>
    {{/if}}
</script>

<script id="artist-partial" type="text/x-handlebars-template">
    {{#if artists.items}} 
        {{#each artists.items}}
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
        {{/each}}
        {{{add_artists "+ Artists" artists.next}}}
    {{else}}
        <p>No se encontró ningún artista :(</p>
    {{/if}}
</script>

<script id="album-partial" type="text/x-handlebars-template">
    {{#if albums.items}}
        {{#each albums.items}}
            <div class="album" id="{{id}}" style="width:50%; float:left">
                <div style="background-image:url({{images.0.url}}); float:left" class="cover"></div>
                <div id="album_info" style="float:left">
                    ID:{{id}}
                    <br>
                    ALBUM_NAME:{{name}}
                </div>
            </div>
            {{{album_songs id}}}
        {{/each}}
        {{{add_albums "+ Albums" albums.next}}}
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

<!--Testing-->
<div class="container">
            <div id="slideshow" class="slideshow">
                <ul>
                    <li>
                        <div class="slide" style="overflow-x:auto">
                            <h1>Search for an Artist/Album/Track</h1>
                            <form id="search-form">
                                <input type="text" id="query" value="hans zimmer" class="form-control" />
                                <input type="submit" id="search" class="btn btn-primary" value="Search" />
                            </form>
                            <div id="track_results"></div>
                        </div>
                    </li>
                    <li>
                        <div class="slide">
                            <div id="artist_results"></div>
                        </div>
                    </li>
                    <li>
                        <div class="slide">
                            <div id="album_results"></div>
                        </div>
                    </li>
                    <li>
                        <div class="slide">
                            <img class="icon" src="img/icons/football.svg" alt="Football Icon"/>
                            <blockquote>
                                <p>Procrastination is the art of keeping up with yesterday.</p>
                            </blockquote>
                            <p>Don Marquis</p>
                        </div>
                    </li>
                    <li>
                        <div class="slide">
                            <img class="icon" src="img/icons/match.svg" alt="Match Icon"/>
                            <blockquote>
                                <p>I'm an idealist. I don't know where I'm going, but I'm on my way.</blockquote>
                            <p>Carl Sandburg</p>
                        </div>
                    </li>
                    <li>
                        <div class="slide">
                            <img class="icon" src="img/icons/watch.svg" alt="Watch Icon"/>
                            <blockquote>
                                <p>I refuse to join any club that would have me as a member.</blockquote>
                            <p>Groucho Marx</p>
                        </div>
                    </li>
                    <li>
                        <div class="slide">
                            <div class="codrops-links">
                                <a class="codrops-icon codrops-icon-prev" href="http://tympanus.net/Development/TooltipStylesInspiration/"><span>Previous Demo</span></a>
                                <a class="codrops-icon codrops-icon-drop" href="http://tympanus.net/codrops/?p=20714"><span>Back to the Codrops Article</span></a>
                            </div>
                            <div class="related">
                                <p>If you enjoyed this demo you might also like:</p>
                                <a href="http://tympanus.net/Tutorials/PagePreloadingEffect/">
                                    <img src="img/related/PagePreloadingEffect.png" />
                                    <h3>Page Preloading Effect</h3>
                                </a>
                                <a href="http://tympanus.net/Development/ButtonComponentMorph/">
                                    <img src="img/related/MorphingButtons.png" />
                                    <h3>Morphing Buttons</h3>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div><!-- /container -->
        <script src="js/classie.js"></script>
        <script src="js/sliderFx.js"></script>
        <script>
            (function() {
                new SliderFx( document.getElementById('slideshow'), {
                    easing : 'cubic-bezier(.8,0,.2,1)'
                } );
            })();
        </script>
<!--Fin-Testing-->

</body>
</html>
