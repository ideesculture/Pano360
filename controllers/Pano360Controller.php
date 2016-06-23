<?php
/* ----------------------------------------------------------------------
 * controllers/CollectionController.php
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
 
	require_once(__CA_LIB_DIR__."/core/ApplicationError.php"); 
	require_once(__CA_LIB_DIR__."/ca/BasePluginController.php");
 	require_once(__CA_APP_DIR__.'/helpers/accessHelpers.php');
 	require_once(__CA_LIB_DIR__.'/ca/Search/CollectionSearch.php');
 	require_once(__CA_MODELS_DIR__.'/ca_collections.php');
 
 	class Pano360Controller extends BasePluginController {
 		# -------------------------------------------------------
 		 
 		# -------------------------------------------------------
 		public function __construct(&$po_request, &$po_response, $pa_view_paths=null) {
 			parent::__construct($po_request, $po_response, $pa_view_paths);
 			
 			 if ($this->request->config->get('pawtucket_requires_login')&&!($this->request->isLoggedIn())) {
                $this->response->setRedirect(caNavUrl($this->request, "", "LoginReg", "LoginForm"));
            }
 			
 			caSetPageCSSClasses(array("pano360"));
 		}
 		# -------------------------------------------------------
 		/**
 		 *
 		 */ 
 		public function Json() {
			header('Content-Type: application/json');
 			$view_json = $this->render("json_html.php");
 			print $view_json;
			die();
 		}
 		public function Pannellum() {
 			$view_pan = $this->render("pannellum_html.php");
 			print $view_pan;
 			die();
 		}
 		# ------------------------------------------------------
 	}
