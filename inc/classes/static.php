<?php
##
#	Javascript and CSS generator
#	Capcha generator
##

class Statique extends Scanlab{
	
	function GET($matches) {
		if (isset($matches[1]) && !empty($matches[1])){
			switch ($matches[1]){
				case "js":
					$this->view('statique/js.html');
					break;
                case "captcha":
                    $captcha = new SimpleCaptcha();
                    $captcha->wordsFile = '';
                    $captcha->resourcesPath =  getcwd(). "/inc/libraries/captcha/resources";
                    $captcha->CreateImage();
                    break;
				default:
					die();

			}
		}

	}

}
