<?php

/**************************************************************************
*  Copyright notice
*
*  Copyright 2011-2012 Logic Works GmbH
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*  http://www.apache.org/licenses/LICENSE-2.0
*  
*  Unless required by applicable law or agreed to in writing, software
*  distributed under the License is distributed on an "AS IS" BASIS,
*  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*  See the License for the specific language governing permissions and
*  limitations under the License.
*  
***************************************************************************/

class lw_pagecomments extends lw_plugin 
{
	public function __construct() 
	{
		parent::__construct();
	}
	
	public function buildPageOutput() 
	{
		$config = lw_registry::getInstance()->getEntry('config');
		$this->comments = new lw_comments($config, lw_registry::getInstance()->getEntry('db'), lw_registry::getInstance()->getEntry('request'));
		$this->comments->setType('cmspage', lw_registry::getInstance()->getEntry('request')->getIndex());
		$this->comments->setSecurityLevel(2);
		if ($this->params['paging']>0) {
		    $this->comments->setPaging($this->params['paging']);
		}
		if ($this->params['pagingtemplate']>0) {
    		$this->comments->setPagingTemplate($this->params['pagingtemplate']);
		}
		$this->comments->useHomepageField(false);
		
		if (lw_registry::getInstance()->getEntry('auth')->getUserdata('id')!=0) {
		    $this->comments->setStaffFlag(true);
		}
		try {
			$this->comments->setBaseURL($config['url']['client'].'index.php?index='.lw_registry::getInstance()->getEntry('request')->getIndex());
			$this->comments->setTemplatePath($config['plugin_path']['lw'].'lw_pagecomments/templates/');
		} 
		catch (Exception $e) {
			die("an error occured: ".$e->getMessage());			
		}

		$this->comments->execute();
		return $this->comments->getOutput();
	}
}
