<html>
<head>
	
    
    <meta charset="UTF-8">
        <link rel="shortcut icon" href="../favicon.ico">
        <link rel="stylesheet" type="text/css" href="css/normalize.css" />
        <link rel="stylesheet" type="text/css" href="css/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/set1Custom.css" />
        <link rel="stylesheet" type="text/css" href="css/component.css" />
	<script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="js/handlebars-v2.0.0.js"></script>
	<script type="text/javascript" src="js/main_behavior.js"></script>
        <script src="js/modernizr.custom.js"></script>
</head>
<body>
    <?php include 'beans/connection.php' ?>
    <div id="vs-container" class="vs-container">
            <header class="vs-header">
                <h1>Search for an Artist/Album/Track</h1>
                    <form id="search-form">
                        <input type="text" id="query" value="hans zimmer" class="form-control" />
                        <input type="submit" id="search" class="btn btn-primary" value="Search" />
                    </form>
                    <div id="grecias_area">
                        <div id="recoContainer1"></div>
                        <div id="playerArea"></div>
                        <div id="recoContainer2"></div>
                    </div>
                <ul class="vs-nav" style="display:inline-block">
                    <li><a href="#section-1">Tracks</a></li>
                    <li><a href="#section-2">Artists</a></li>
                    <li><a href="#section-3">Albums</a></li>
                </ul>
            </header>
            <div class="vs-wrapper">
                <section id="section-1">
                    <div class="vs-content">
                        <h3>"Maybe I'm too busy being <br>yours to fall for somebody new"<br>-Arctic Monkeys</h3>
                        <div id="tracksArea" style="clear:both"></div>
                    </div>
                </section>
                <section id="section-2">
                    <div class="vs-content">
                        <h3>"Lights will guide you home and <br>ignite your bones and i will
                            <br> try to fix you" <br>-Coldplay</h3>
                            <div id="artistsArea"style="clear:both"></div>
                    </div>
                </section>
                <section id="section-3">
                    <div class="vs-content">
                        <h3>"You sit there in your heartache <br>waiting on some beautiful boy to save 
                            <br>you from your old ways"<br>-The Killers</h3>
                            <div id="albumsArea" style="clear:both"></div>
                    </div>
                </section>
            </div>
            <div class="codrops-top clearfix">
                <span class="right">
                    <a href="https://www.spotify.com/" target="_blank">Mitsiu A Carreño Sarabia</a>
                    <a href="https://github.com/mitsiu-carreno/music_app" target="_blank">Andoni Águila García</a>
                    <a href="http://tympanus.net/Development/TripleViewLayout/" target="_blank">Luis E Torres Herrera</a>
            </div>
        </div><!-- /vs-container -->
        <script src="js/classie.js"></script>
        <script src="js/hammer.min.js"></script>
        <script src="js/main.js"></script>

<!--Tracks Section-->
<script id="tracks-template" type="text/x-handlebars-template">
    <h1>Tracks</h1>
    <div id="tracksContainer" class="grid">  
        {{> track}}
    </div>
    <br>
    {{{addTracks "+ Tracks" tracks.next}}}
    <br>
</script>

<script id="track-partial" type="text/x-handlebars-template">
    {{#if tracks.items}}
        {{#each tracks.items}}
            {{#moduloIf @index 0 0}}
                <div id="cut">
            {{/moduloIf}}
            {{#moduloIf @index 0 3}}
                <div id="cut">
            {{/moduloIf}}
                <figure class="effect-sadie track" idTrack="{{id}}">
                        <img src="{{album.images.0.url}}"/>
                        <figcaption>
                            <h2>{{name}}</h2>
                            <p>
                                {{#each artists}}
                                {{name}}<br>
                                {{/each}} 
                                with {{album.name}}
                            </p>
                        </figcaption>      
                </figure>
            {{#moduloIf @index 1 3}}
                </div>
            {{/moduloIf}}
        {{/each}}
    {{else}}
    <p>No se encontró ningún track :(</p>
    {{/if}}
</script>

<!--Artists Sction-->
<script id="artists-template" type="text/x-handlebars-template">
    <h1>Artists</h1>
    <div class="grid" id="artistsContainer">
        {{> artist}}
    </div>
    <br>
    {{{addArtists "+ Artists" artists.next}}}
    <br>
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
                <figure class="effect-romeo artist" idArtist="{{id}}" nameArtist="{{name}}">
                    <img src="{{#if images.0.url}}{{images.0.url}}{{else}}
                        http://static2.businessinsider.com/image/4e1b276e49e2ae487d020000-480/spotify-horns.jpg
                        {{/if}}"/>
                    <figcaption>
                        <h2>{{name}}</h2>
                    </figcaption>
                </figure>
            {{#moduloIf @index 1 3}}
                </div>
            {{/moduloIf}}
        {{/each}}
    {{else}}
        <p>No se encontró ningún artista :(</p>
    {{/if}}
</script>

<script id="artistInfo-template" type="text/x-handlebars-template">
    <figure class="artistInfoContainer" idArtist="{{artist_id}}">
        <h1>{{artist_name}}<h1>
        <spotify_button class="playTop" idArtist="{{artist_id}}">Listen Top Tracks</spotify_button>
        <br>
        <h1>Albums</h1>
        <div id="albumsByArtistContainer">  
            {{> albumsByArtist}}
        </div>
        <br>
        <br>
        {{#if next}}
            <div style="margin-top:5%">{{{addAlbumsByArtist "+ Albums" next}}}</div>
        {{else}}
            Eso es todo lo que hemos podido encontrar :(
        {{/if}}
        <br>
        <br>
    </figure>
</script>

<script id="albumsByArtist-partial" type="text/x-handlebars-template">
    {{#if items}}
        {{#each items}}
            <div class="albumByArtistContainer" idAlbum="{{id}}" style="width:100%; float:left">
                <div style="background-image:url({{images.0.url}}); float:left; margin-left:12%" class="cover"></div>
                <h2 style="word-wrap: break-word;cursor:pointer" class="displayAlbumInfoByArtist">{{name}}</h2>
                <div class="albumInfoByArtist" style="float:right; margin-right:7%; width:60%; display:none">
                    <br>
                    {{#each tracks}}{{name}}<br>{{/each}}   
                    <div style="margin-top:15px"></div>
                </div>
                <spotify_button class="playAlbum" idAlbum="{{id}}" idArtist="{{../artist_id}}">Listen Album</spotify_button>
            </div>
        {{/each}}
    {{else}}
        <p>No se encontró ningún album :(</p>
    {{/if}}
</script>

<!--Albums Section-->
<script id="albums-template" type="text/x-handlebars-template">
    <h1>Albums</h1>
    <div class="grid" id="albumsContainer">
        {{> album}}
    </div>
    <br>
    {{{addAlbums "+ Albums" albums.next}}}
    <br>
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
                <figure class="effect-sarah album" idAlbum="{{id}}">
                    <img src="{{images.0.url}}" />
                    <figcaption>
                        <h2>{{name}}<h2>
                    </figcaption>
                </figure>
            {{#moduloIf @index 1 3}}
                </div>
            {{/moduloIf}}
        {{/each}}
    {{else}}
        <p>No se encontró ningún album :(</p>
    {{/if}}
</script>

<script id="albumInfo-template" type="text/x-handlebars-template">
    <figure class="albumInfoContainer" idAlbum="{{id}}">
        <h1>{{name}}</h1>
        <br>
        <h2>by {{#each artists}}{{name}}<br>{{/each}}</h2>
        <spotify_button class="playAlbum" idAlbum="{{id}}" idArtist="{{artists.0.id}}">Click me to listen</spotify_button>
        <br>
        <br>
        Tracks: 
        <br>
        {{#each tracks.items}}
            {{track_number}}-{{name}}<br>
        {{/each}}
        <br>
    </figure>
</script>

<!--Player Section-->
<script id="player-template"type="text/x-handlebars-template">
    <p style="float:left; width:50%">
    <iframe src="https://embed.spotify.com/?uri={{uri}}" width="300" height="380" frameborder="0" allowtransparency="true">
    </iframe>
    </p>
</script>

<script id="recomendaciones-template" type="text/x-handlebars-template">
    <div style="width:25%; height:330; float:left; background-color:black">
        <h3>We recommend:</h3>
        {{#each this}}
            <div style="border:1px solid yellow">
            hi
            </div>
        {{/each}}
    </div>
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
    width: 194px;
    height: 194px;
    display: inline-block;
    background-size: cover;
}

.cover:hover {
    //cursor: pointer;
}

.cover.playing {
    border: 5px solid #e45343;
}

.albumInfoContainer,
.artistInfoContainer{
    width:95% !important; 
    max-width:95% !important; 
    border:2px solid white; 
    overflow-y:auto !important; 
    display:none;
    cursor:auto !important;
}
.effect-sarah:after {
    content:'';
    top: 100%;
    left: 50%;
    width: 0;
    height: 0;
    border-top: solid 50px #e15915;
    border-left: solid 50px transparent;
    border-right: solid 50px transparent;
}
spotify_button{
    border-radius: 500px;
    border-width: 2px;
    font-size: 18px;
    letter-spacing: 1.2px;
    line-height: 1.5;
    min-width: 130px;
    padding: 8px 25px 4px;
    text-transform: uppercase;
    white-space: normal;
    color: #fff;
    background-color: #84bd00;
    border-color: #84bd00;
    display: inline-block;
    margin-bottom: 0;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
}
spotify_button:hover{
    background-color: #598000;
}
#albums_byArtist_info{

}

</style>

</body>
</html>
