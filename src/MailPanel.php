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
			return "<strong>Mailer</strong>";
		} else {
			return "Mailer";
		}
	}

	function getPanel(){
		$out = array();
		$out[] = '<div style="overflow: auto;">';
		$out[] = '<div class="tracy-MailPanel">';
		if ($this->mailer && ($this->mailer->body_html || $this->mailer->body)) {
			$out[] = sprintf('<div class="panel-heading"><strong>%s</strong></div>', _("Headers"));
			$out[] = '<code id="tracy_panel_mailer_body_headers"><pre class="tracy-dump">';
			$out[] = sprintf("Subject: %s", $this->mailer->subject);
			$out[] = sprintf("From: %s", $this->mailer->from);
			$out[] = sprintf("To: %s", $this->mailer->to);
			$out[] = sprintf("Cc: %s", $this->mailer->cc);
			$out[] = sprintf("Bcc: %s", $this->mailer->bcc);
			$out[] = sprintf("Content-Type: %s", $this->mailer->content_type);
			$out[] = sprintf("Content-Charset: %s", $this->mailer->content_charset);
			$out[] = "</pre></code>";
			if ($this->mailer->body) {
				$out[] = sprintf('<div class="panel-heading"><strong>%s</strong></div>', _("Plain text body"));
				$out[] = '<code id="tracy_panel_mailer_body_plain">';
				$out[] = '<pre class="tracy-dump">';
				$out[] = htmlspecialchars($this->mailer->body);
				$out[] = "</pre>";
				$out[] = "</code>";
			}
			if ($this->mailer->body_html) {
				$out[] = sprintf('<div class="panel-heading"><strong>%s</strong></div>', _("HTML body"));
				$out[] = '<div class="tracy-dump">';
				$out[] = '<iframe style="width: 100%; height: 450px;" src="data:text/html;charset=utf-8;base64,'.base64_encode($this->mailer->body_html).'"></iframe>';
				$out[] = "</div>";
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
}
