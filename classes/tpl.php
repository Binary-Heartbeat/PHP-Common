<?php
	class tpl { // functions to ease templating
		public static function wr($_) {
			// small foot-print function for returning the web root. For use in echoing it into a HTML document.
			echo $_['web_root'];
		}
	}
