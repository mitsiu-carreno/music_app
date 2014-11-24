<html>
<head>
	<?php include 'connection.php' ?>
    
    <meta charset="UTF-8">
	
	<script type="text/javascript" src="jquery-1.11.1.min.js"></script>
	<script type="text/javascript" src="handlebars-v2.0.0.js"></script>
	<script type="text/javascript" src="query.js"></script>
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
<script id="results-template" type="text/x-handlebars-template">
    
    <p>Tracks</p>
    {{#each tracks.items}}
        <div style="background-image:url({{album.images.0.url}}); background-color:black" data-track-id="{{id}}" class="cover"></div>
    {{/each}}
    
    <p>Artists</p>
    {{#each albums.items}}
        <div style="background-image:url({{images.0.url}})" data-artist-id="{{id}}" class="cover"></div>
    {{/each}}

    <p>Albums</p>
    {{#each albums.items}}
        <div style="background-image:url({{images.0.url}})" data-album-id="{{id}}" class="cover"></div>
    {{/each}}
</script>

<style type="text/css">
	body {
    padding: 20px;
}

#search-form, .form-control {
    margin-bottom: 20px;
}

.cover {
    width: 300px;
    height: 300px;
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

<?php include 'testing.php' ?>