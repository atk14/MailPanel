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
 *
 *	TODO: could be nice to create some styles
 */
class MailPanel implements Tracy\IBarPanel{

	var $mailer;

	function __construct($mailer){
		$this->mailer = $mailer;
	}

	function getTab(){
		if ($this->mailer && ($this->mailer->body || $this->mailer->body_html)) {
			if(method_exists($this->mailer,"getSentEmails")){
				$count = max(sizeof($this->mailer->getSentEmails()),1);
			}else{
				$count = 1;
			}
			return "<strong>Mailer</strong> $count";
		} else {
			return "Mailer";
		}
	}

	function getPanel(){
		$emails = $this->mailer && method_exists($this->mailer,"getSentEmails") ? $this->mailer->getSentEmails() : [];
		if(!$emails && $this->mailer && ($this->mailer->body_html || $this->mailer->body)){
			$emails[] = $this->mailer;
		}

		$out = array();
		$out[] = '<div style="overflow: auto;">';
		$out[] = '<div class="tracy-MailPanel">';
		if ($emails) {
			$index = 1;
			foreach($emails as $email){
				if(sizeof($emails)>1){
					$out[] = "<h2>=== Sent email #$index ===</h2>";
				}
				$out[] = $this->_dumpEmail($email);
				$index++;
			}
		} else {
			$out[] = '<code id="tracy_panel_mailer_body_plain">';
			$out[] = '<pre class="tracy-dump">';
			$out[] = _("No mail has been sent");
			$out[] = "</pre>";
			$out[] = "</code>";
		}
		$out[] = "</div>"; # panel panel-default
		$out[] = "</div>";
		return join("\n",$out);
	}

	function _dumpEmail($email){
		$out = [];
		$out[] = sprintf('<div class="panel-heading"><strong>%s</strong></div>', _("Headers"));
		$out[] = '<code id="tracy_panel_mailer_body_headers"><pre class="tracy-dump">';
		$out[] = sprintf("Subject: %s", $email->subject);
		$out[] = sprintf("From: %s", $email->from);
		$out[] = sprintf("To: %s", $email->to);
		$out[] = sprintf("Cc: %s", $email->cc);
		$out[] = sprintf("Bcc: %s", $email->bcc);
		//$out[] = sprintf("Content-Type: %s", $email->content_type);
		//$out[] = sprintf("Content-Charset: %s", $email->content_charset);
		$out[] = "</pre></code>";
		if ($email->body) {
			$out[] = sprintf('<div class="panel-heading"><strong>%s</strong></div>', _("Plain text body"));
			$out[] = '<code id="tracy_panel_mailer_body_plain">';
			$out[] = '<pre class="tracy-dump">';
			$out[] = htmlspecialchars($email->body);
			$out[] = "</pre>";
			$out[] = "</code>";
		}
		if ($email->body_html) {
			$out[] = sprintf('<div class="panel-heading"><strong>%s</strong></div>', _("HTML body"));
			$out[] = '<div class="tracy-dump">';
			$out[] = '<iframe style="width: 100%; height: 450px;" src="data:text/html;charset=utf-8;base64,'.base64_encode($email->body_html).'"></iframe>';
			$out[] = "</div>";
		}
		return join("\n",$out);
	}
}
