<html>
<head>
	<?php include 'connection.php' ?>
    
    <meta charset="UTF-8">
	<link rel="shortcut icon" href="../favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="css/demo.css" />
    <link rel="stylesheet" type="text/css" href="css/component.css" />
    <script src="js/snap.svg-min.js"></script>
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

<!--TEsting-->

<section id="grid" class="grid clearfix">
                <a href="#" data-path-hover="m 180,34.57627 -180,0 L 0,0 180,0 z">
                    <figure>
                        <img src="img/1.png" />
                        <svg viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M 180,160 0,218 0,0 180,0 z"/></svg>
                        <figcaption>
                            <h2>Crystalline</h2>
                            <p>Soko radicchio bunya nuts gram dulse.</p>
                            <button>View</button>
                        </figcaption>
                    </figure>
                </a>
                <a href="#" data-path-hover="m 180,34.57627 -180,0 L 0,0 180,0 z">
                    <figure>
                        <img src="img/3.png" />
                        <svg viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M 180,160 0,218 0,0 180,0 z"/></svg>
                        <figcaption>
                            <h2>Cacophony</h2>
                            <p>Two greens tigernut soybean radish.</p>
                            <button>View</button>
                        </figcaption>
                    </figure>
                </a>
                <a href="#" data-path-hover="m 180,34.57627 -180,0 L 0,0 180,0 z">
                    <figure>
                        <img src="img/5.png" />
                        <svg viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M 180,160 0,218 0,0 180,0 z"/></svg>
                        <figcaption>
                            <h2>Languid</h2>
                            <p>Beetroot water spinach okra water.</p>
                            <button>View</button>
                        </figcaption>
                    </figure>
                </a>
                <a href="#" data-path-hover="m 180,34.57627 -180,0 L 0,0 180,0 z">
                    <figure>
                        <img src="img/7.png" />
                        <svg viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M 180,160 0,218 0,0 180,0 z"/></svg>
                        <figcaption>
                            <h2>Serene</h2>
                            <p>Water spinach arugula pea tatsoi.</p>
                            <button>View</button>
                        </figcaption>
                    </figure>
                </a>
                <a href="#" data-path-hover="m 180,34.57627 -180,0 L 0,0 180,0 z">
                    <figure>
                        <img src="img/2.png" />
                        <svg viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M 180,160 0,218 0,0 180,0 z"/></svg>
                        <figcaption>
                            <h2>Nebulous</h2>
                            <p>Pea horseradish azuki bean lettuce.</p>
                            <button>View</button>
                        </figcaption>
                    </figure>
                </a>
                <a href="#" data-path-hover="m 180,34.57627 -180,0 L 0,0 180,0 z">
                    <figure>
                        <img src="img/4.png" />
                        <svg viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M 180,160 0,218 0,0 180,0 z"/></svg>
                        <figcaption>
                            <h2>Iridescent</h2>
                            <p>A grape silver beet watercress potato.</p>
                            <button>View</button>
                        </figcaption>
                    </figure>
                </a>
                <a href="#" data-path-hover="m 180,34.57627 -180,0 L 0,0 180,0 z">
                    <figure>
                        <img src="img/6.png" />
                        <svg viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M 180,160 0,218 0,0 180,0 z"/></svg>
                        <figcaption>
                            <h2>Resonant</h2>
                            <p>Chickweed okra pea winter purslane.</p>
                            <button>View</button>
                        </figcaption>
                    </figure>
                </a>
                <a href="#" data-path-hover="m 180,34.57627 -180,0 L 0,0 180,0 z">
                    <figure>
                        <img src="img/8.png" />
                        <svg viewBox="0 0 180 320" preserveAspectRatio="none"><path d="M 180,160 0,218 0,0 180,0 z"/></svg>
                        <figcaption>
                            <h2>Zenith</h2>
                            <p>Salsify taro catsear garlic gram.</p>
                            <button>View</button>
                        </figcaption>
                    </figure>
                </a>
            </section>

            <script>
            (function() {
    
                function init() {
                    var speed = 250,
                        easing = mina.easeinout;

                    [].slice.call ( document.querySelectorAll( '#grid > a' ) ).forEach( function( el ) {
                        var s = Snap( el.querySelector( 'svg' ) ), path = s.select( 'path' ),
                            pathConfig = {
                                from : path.attr( 'd' ),
                                to : el.getAttribute( 'data-path-hover' )
                            };

                        el.addEventListener( 'mouseenter', function() {
                            path.animate( { 'path' : pathConfig.to }, speed, easing );
                        } );

                        el.addEventListener( 'mouseleave', function() {
                            path.animate( { 'path' : pathConfig.from }, speed, easing );
                        } );
                    } );
                }

                init();

            })();
        </script>
<!--End TEsting-->

</body>
</html>
