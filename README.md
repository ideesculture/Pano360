# Plugin Pano360 for pawtucket2

Pano360 is a plugin for Pawtucket2. This plugin renders a 360° panorama around you on a place item inside Pawtucket2. On this panorama are automatically inserted objects POI depending on each object coordinates and altitude (required metadata.

## Install

- Install the required database profile or add required metadatas (doc to come)
- Download and install this project directory inside pawtucket2/app/plugins
- Modify the ca_places_default_html.php inside your theme dir following the example

## Modifying ca_places_default_html.php

````php
	<?php 
		$place_id = $t_item->get("place_id");
		Pano360::insertPanoramaHere($place_id);
	?>
````