<?php

class lw_pagecomments extends lw_plugin {
	
	public function __construct() {
		parent::__construct();
	}
	
	public function buildPageOutput() {
		$reg 	= lw_registry::getInstance();
		$config = $reg->getEntry('config');
		$this->comments = new lw_comments($config, $reg->getEntry('db'), $reg->getEntry('request'));
		$this->comments->setType('cmspage', $reg->getEntry('request')->getIndex());
		$this->comments->setSecurityLevel(2);
		if ($this->params['paging']>0) $this->comments->setPaging($this->params['paging']);
		if ($this->params['pagingtemplate']>0) $this->comments->setPagingTemplate($this->params['pagingtemplate']);
		$this->comments->useHomepageField(false);
		
		if ($reg->getEntry('auth')->getUserdata('id')!=0) $this->comments->setStaffFlag(true);
		try {
			$this->comments->setBaseURL($config['url']['client'].'index.php?index='.$reg->getEntry('request')->getIndex());
			$this->comments->setTemplatePath($config['plugin_path']['lw'].'lw_pagecomments/templates/');
		} catch (Exception $e) {
			die("an error occured: ".$e->getMessage());			
		}

		$this->comments->execute();
		return $this->comments->getOutput();
	}
}
