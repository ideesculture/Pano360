<?php
/* ----------------------------------------------------------------------
 * Pano360.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2014-2015 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
 
	class Pano360 extends BaseApplicationPlugin {
		# -------------------------------------------------------
		public function __construct($ps_plugin_path) {
			$this->description = _t('Provides a interactive panorama with points of interest');
			if (__CA_THEME_DIR__.'/conf/Pano360.conf') {
				$this->opo_config = Configuration::load(__CA_THEME_DIR__.'/conf/Pano360.conf');
			} else {
				$this->opo_config = Configuration::load($ps_plugin_path.'/conf/Pano360.conf');
			}
			#$this->opo_config = Configuration::load($ps_plugin_path.'/conf/Pano360.conf');
			parent::__construct();
		}
		# -------------------------------------------------------
		/**
		 * Override checkStatus() to return plugin status
		 */
		public function checkStatus() {
			return array(
				'description' => $this->getDescription(),
				'errors' => array(),
				'warnings' => array(),
				'available' => ((bool)$this->opo_config->get('enabled'))
			);
		}
		# -------------------------------------------------------
		/**
		 * Get plugin user actions
		 */
		static public function getRoleActionList() {
			return array();
		}
		
 		/**
 		 *
 		 */
 		static public function hookCanHandleGetAsLinkTarget(&$pa_params) {
 			return (strtolower($pa_params['target']) == 'pano360');
 		}
 		# ------------------------------------------------------
 		/**
 		 *
 		 */
 		static public function hookGetAsLink(&$pa_params) {
 			
 			$pa_params['tag'] = caNavLink($pa_params['request'], $pa_params['content'], '', '*', '*', '*', array('id' => $pa_params['id']));
 			
 			return $pa_params;
 		}
 		# ------------------------------------------------------
 		/**
 		 *
 		 */
 		public function insertPanoramaHere($place_id) {
 			print '<hr/>
	<p>
		<iframe width="1200" height="600" allowfullscreen style="border-style:none;" src="http://'.__CA_SITE_HOSTNAME__."/".__CA_URL_ROOT__.'/index.php/Pano360/Pano360/Pannellum?config=http://'.__CA_SITE_HOSTNAME__.'/'.__CA_URL_ROOT__.'/index.php/Pano360/Pano360/Json&id='.$place_id.'&autoLoad=true">
    	</iframe>
    </p>
';
 		}

 		# ------------------------------------------------------

	}
