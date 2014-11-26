<?php
/**
 * A panel for Tracy Debugger showing sent email
 *
 * Basic usage:
 *
 *	$bar = Tracy\Debugger::getBar();
 *	$bar->addPanel(new MailPanel($dbmole));
 *
 *
 * Usage in an ATK14 built upon Atk14Skelet:
 *
 *	// file: config/settings.php 
 *	define("DBMOLE_COLLECT_STATICTICS",DEVELOPMENT);
 *
 *	// file: app/controllers/application_base.php
 *	function _application_after_filter(){
 *		if(DBMOLE_COLLECT_STATICTICS){
 *			$bar = Tracy\Debugger::getBar();
 *			$bar->addPanel(new DbMolePanel($this->dbmole));
 *		}
 *	}
 */
class MailPanel implements Tracy\IBarPanel{
	function __construct($mailer){
		$this->mailer = $mailer;
	}

	function getTab(){
		if ($this->mailer->body || $this->mailer->body_html) {
			return "<strong>Mailer</strong>";
		} else {
			return "Mailer";
		}
	}

	function getPanel(){
		$out = array();
		$out[] = '<div style="height: 500px; width: 800px; overflow:scroll;">';
		if ($this->mailer->body_html) {
			$out[] = '<code id="tracy_panel_mailer_body_html">';
			$out[] = "<strong>HTML body</strong><hr>";
			$out[] = $this->mailer->body_html;
			$out[] = "</code>";
		}
		if ($this->mailer->body) {
			$out[] = '<code id="tracy_panel_mailer_body_plain"><pre>';
			$out[] = "<strong>Plain text body</strong><hr>";
			$out[] = $this->mailer->body;
			$out[] = "</pre></code>";
		}
		$out[] = '</div>';
		return join("\n",$out);
	}
}
