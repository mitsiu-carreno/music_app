<html>
<head>
	
    
    <meta charset="UTF-8">
        <link rel="shortcut icon" href="../favicon.ico">
        <link rel="stylesheet" type="text/css" href="css/normalize.css" />
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/set1Custom.css" />
        <link rel="stylesheet" type="text/css" href="css/component.css" />
	<script type="text/javascript" src="jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="handlebars-v2.0.0.js"></script>
	<script type="text/javascript" src="query2.js"></script>
        <script src="js/modernizr.custom.js"></script>
</head>
<body>
    <!--<?php include 'connection.php' ?>-->
    <div id="vs-container" class="vs-container">
            <header class="vs-header">
                <h1>Search for an Artist/Album/Track</h1>
                    <form id="search-form">
                        <input type="text" id="query" value="hans zimmer" class="form-control" />
                        <input type="submit" id="search" class="btn btn-primary" value="Search" />
                    </form>
                    <div id="player_area"></div>
                <ul class="vs-nav">
                    <li><a href="#section-1">Tracks</a></li>
                    <li><a href="#section-2">Artists</a></li>
                    <li><a href="#section-3">Albums</a></li>
                </ul>
            </header>
            <div class="vs-wrapper">
                <section id="section-1">
                    <div class="vs-content">
                        <h3>"Maybe I'm too busy being <br>yours to fall for somebody new"<br>-Arctic Monkeys</h3>
                        <div id="tracks_area" style="clear:both"></div>
                    </div>
                </section>
                <section id="section-2">
                    <div class="vs-content">
                        <h3>"Lights will guide you home and <br>ignite your bones and i will<br> try to fix you" <br>-Coldplay</h3>
                            <div id="artists_area"style="clear:both"></div>
                    </div>
                </section>
                <section id="section-3">
                    <div class="vs-content">
                        <h3>"You sit there in your heartache <br>waiting on some beautiful boy to save <br>you from your old ways"<br>-The Killers</h3>
                            <div id="albums_area" style="clear:both"></div>
                    </div>
                </section>
            </div>
            <div class="codrops-top clearfix">
                <span class="right"><a href="https://www.spotify.com/" target="_blank">Mitsiu A Carreño Sarabia</a><a href="https://github.com/mitsiu-carreno/music_app" target="_blank">Andoni Águila García</a><a href="http://tympanus.net/Development/TripleViewLayout/" target="_blank">Luis E Torres Herrera</a>
            </div>
        </div><!-- /vs-container -->
        <script src="js/classie.js"></script>
        <script src="js/hammer.min.js"></script>
        <script src="js/main.js"></script>

<!--Template-->
<script id="tracks-template" type="text/x-handlebars-template">
    <h1>Tracks</h1>
    <div class="grid">
        <div id="tracks_results">
        
            {{> track}}
        
        </div>
    </div>
    <br>
    {{{add_tracks "+ Tracks" tracks.next}}}
    <br>
</script>

<script id="artists-template" type="text/x-handlebars-template">
    <h1>Artists</h1>
    <div class="grid">
        <div id="artists_results">
    
            {{> artist}}
        </div>
    </div>
    <br>
    {{{add_artists "+ Artists" artists.next}}}
    <br>
</script>

<script id="albums-template" type="text/x-handlebars-template">
    <h1>Albums</h1>
    <div class="grid">
        <div id="albums_results">
    
        {{> album}}
        </div>
    </div>
    <br>
    {{{add_albums "+ Albums" albums.next}}}
    <br>
</script>

<script id="player-template"type="text/x-handlebars-template">
<p style="text-align:center"><iframe src="https://embed.spotify.com/?uri={{uri}}" width="300" height="380" frameborder="0" allowtransparency="true"></iframe></p>
</script>

<!--Template_Artist-->
<script id="artist_info-template" type="text/x-handlebars-template">
    <figure style="width:900px; max-width:900px" class="album_info" id="{{tracks}}">
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
    <figure>
</script>

<!--Partials-->
<script id="track-partial" type="text/x-handlebars-template">
    {{#if tracks.items}}
        {{#each tracks.items}}
            {{#moduloIf @index 0 0}}
                <div id="cut">
            {{/moduloIf}}
            {{#moduloIf @index 0 3}}
                <div id="cut">
            {{/moduloIf}}
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
            {{#moduloIf @index 1 3}}
                </div>
            {{/moduloIf}}
        {{/each}}
    {{else}}
    <p>No se encontró ningún track :(</p>
    {{/if}}
</script>

<script id="artist-partial" type="text/x-handlebars-template">
    {{#if artists.items}} 
        {{#each artists.items}}
            {{#moduloIf @index 0 0}}
                <div id="cut">
            {{/moduloIf}}
            {{#moduloIf @index 0 3}}
                <div id="cut">
            {{/moduloIf}}
                <figure class="effect-romeo">
                    <div class="artist" id="{{id}}">
                        <img src="{{#if images.0.url}}{{images.0.url}}
                            {{else}}http://static2.businessinsider.com/image/4e1b276e49e2ae487d020000-480/spotify-horns.jpg{{/if}}"/>
                        <figcaption>
                            <h2>{{name}}</h2>
                        </figcaption>
                    </div>
                </figure>
            {{#moduloIf @index 1 3}}
                </div>
            {{/moduloIf}}
        {{/each}}
    {{else}}
        <p>No se encontró ningún artista :(</p>
    {{/if}}
</script>

<script id="album-partial" type="text/x-handlebars-template">
    {{#if albums.items}}
        {{#each albums.items}}
            {{#moduloIf @index 0 0}}
                <div id="cut">
            {{/moduloIf}}
            {{#moduloIf @index 0 3}}
                <div id="cut">
            {{/moduloIf}}
                <figure class="effect-sarah">
                    <div class="album" id="{{id}}">
                        <img src="{{images.0.url}}" />
                        <figcaption>
                            <h2>{{name}}<h2>
                        </figcaption>
                    </div>
                </figure>
            
                {{{album_songs id}}}
                
            {{#moduloIf @index 1 3}}
                </div>
            {{/moduloIf}}
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

</body>
</html>
