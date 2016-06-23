<?php
$id_place = $_GET['id'];

?>


<!DOCTYPE HTML>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pannellum</title>
  <link type="text/css" rel="Stylesheet" href="lib/css/pannellum.css"/>
  <link type="text/css" rel="Stylesheet" href="lib/standalone.css"/>
</head>
<body>
<div id="container">
  <noscript>
    <div class="pnlm-info-box">
      <p>Javascript is required to view this panorama.<br>(It could be worse; you could need a plugin.)</p>
    </div>
  </noscript>
</div>
<script type="text/javascript" src="lib/js/libpannellum.js"></script>
<script type="text/javascript" src="lib/js/RequestAnimationFrame.js"></script>
<script type="text/javascript" src="lib/js/pannellum.js"></script>
<script>
function anError(error) {
    var errorMsg = document.createElement('div');
    errorMsg.className = 'pnlm-info-box';
    errorMsg.innerHTML = '<p>' + error + '</p>';
    document.getElementById('container').appendChild(errorMsg);
}

function parseURLParameters() {

    var URL = decodeURI(window.location.href).split('?');
    URL.shift();
    if (URL.length < 1) {
        // Display error if no configuration parameters are specified
        anError('No configuration options were specified.');
        return;
    }

    URL = URL[0].split('&');
    var json = '{';
    for (var i = 0; i < URL.length; i++) {
        var option = URL[i].split('=')[0];
        var value = URL[i].split('=')[1];
        console.log(value);
        json += '"' + option + '":';
        switch(option) {
            case 'hfov': case 'pitch': case 'yaw': case 'haov': case 'vaov':
            case 'vOffset': case 'autoRotate': case 'id':
                json += value;
                break;
            case 'autoLoad': case 'ignoreGPanoXMP':
                json += JSON.parse(value);
                break;
            case 'tour':
                console.log('The `tour` parameter is deprecated and will be removed. Use the `config` parameter instead.')
            case 'author': case 'title': case 'firstScene': case 'fallback':
            case 'preview': case 'panorama': case 'config':
                json += '"' + decodeURIComponent(value) + '"';
                break;
            default:
                anError('An invalid configuration parameter was specified: ' + option);
        }
        if (i < URL.length - 1) {
            json += ',';
        }
    }

    json += '}';
    //console.log(json);
    var configFromURL = JSON.parse(json);

    var request;

    // Check for JSON configuration file
    if (configFromURL.tour) {
        configFromURL.config = configFromURL.tour;
    }
    configFromURL.config = configFromURL.config + "?id=<?php print $id_place ?>";
    console.log("configFromURL.config =" + configFromURL.config);
    if (configFromURL.config) {
        // Get JSON configuration file
        request = new XMLHttpRequest();
        request.onload = function() {
            if (request.status != 200) {
                // Display error if JSON can't be loaded
                var a = document.createElement('a');
                a.href = configFromURL.config;
                a.innerHTML = a.href;
                anError('The file ' + a.outerHTML + ' could not be accessed.');
                return;
            }

            var responseMap = JSON.parse(request.responseText);

            // Set JSON file location
            responseMap.basePath = configFromURL.config.substring(0, configFromURL.config.lastIndexOf('/')+1);

            // Merge options
            for (var key in responseMap) {
                if (configFromURL.hasOwnProperty(key)) {
                    continue;
                }
                configFromURL[key] = responseMap[key];
            }

            // Set title
            if ('title' in configFromURL)
                document.title = configFromURL.title;

            // Create viewer
            pannellum.viewer('container', configFromURL);
        };
        request.open('GET', configFromURL.config);
        request.send();
        return;
    }

    // Set title
    if ('title' in configFromURL)
        document.title = configFromURL.title;

    // Create viewer
    pannellum.viewer('container', configFromURL);
}

// Display error if opened from local file
if (window.location.protocol == 'file:') {
    anError('Due to browser security restrictions, Pannellum can\'t be run ' +
        'from the local filesystem; some sort of web server must be used.');
} else {
    // Initialize viewer
    parseURLParameters();
}</script>
</body>
</html>


