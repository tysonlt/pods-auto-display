<?php

/**
 * Displays various POD data types.
 * 
 * @author Tyson
 */
class PodsAutoDisplayDefaultFormatter {

	/**
	 * Print link to media file.
	 * 
	 * @param mixed $content
	 * @param array $args
	 * @return string
	 */	
	public function formatFile($content, $args) {
		$title = $args['raw']['post_title'];
		return "<a href='$content' target='new'>$title</a>"; //TODO: optional target
	}
	
	/**
	 * Just prints a little span with the color.
	 * 
	 * It is not really intended for live use, just a demo.
	 * 
	 * @param mixed $content 
	 * @param array $args
	 * @return string
	 */
	public function formatColor($content, $args) {
		return "<span style='background:$content'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
	}
	
	/**
	 * Turns website field into an href.
	 * 
	 * @param string $content
	 * @param array $args
	 * @return string
	 */
	public function formatWebsite($content, $args) {
		return "<a href='$content' target='new'>$content</a>";
	}
	
	/**
	 * Looks for pages and prints a page link.
	 * 
	 * @param mixed $content
	 * @param array $args
	 * @return string
	 */
	public function formatPick($content, $args) {
		$result = $content;
		//format page links
		if ('post_type' == $args['field']['pick_object']) {
			if ('page' == $args['field']['pick_val']) {
				$result = "<a href='". get_page_link($args['raw']['ID']) ."'>$content</a>";
			/*
			} else if	(...) {
				//TODO: possibly link to custom post type permalink
			*/				
			}
		}
 
		return $result;
	}

	/**
	 * 
	 * @param unknown_type $content
	 * @param unknown_type $args
	 * @return string
	 */
	public function formatEmail($content, $args) {
		return $this->hide_email($content);
	}
	
	/**
	 * http://www.maurits.vdschee.nl/php_hide_email/
	 * 
	 * @param unknown_type $email
	 * @return string
	 */
	private function hide_email($email) { 
		$character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
		$key = str_shuffle($character_set); $cipher_text = ''; $id = 'e'.rand(1,999999999);
		for ($i=0;$i<strlen($email);$i+=1) $cipher_text.= $key[strpos($character_set,$email[$i])];
		$script = 'var a="'.$key.'";var b=a.split("").sort().join("");var c="'.$cipher_text.'";var d="";';
		$script.= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
		$script.= 'document.getElementById("'.$id.'").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
		$script = "eval(\"".str_replace(array("\\",'"'),array("\\\\",'\"'), $script)."\")";
		$script = '<script type="text/javascript">/*<![CDATA[*/'.$script.'/*]]>*/</script>';
		return '<span id="'.$id.'">[javascript protected email address]</span>'.$script;
	}	
	
}