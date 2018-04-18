<?php
/* Wiky.php - A tiny PHP "library" to convert Wiki Markup language to HTML
 * Author: Karun Gautam
 *
 * Code usage under any of these licenses:
 * Apache License 2.0, http://www.apache.org/licenses/LICENSE-2.0
 * Mozilla Public License 1.1, http://www.mozilla.org/MPL/1.1/
 * GNU Lesser General Public License 3.0, http://www.gnu.org/licenses/lgpl-3.0.html
 * GNU General Public License 2.0, http://www.gnu.org/licenses/gpl-2.0.html
 * Creative Commons Attribution 3.0 Unported License, http://creativecommons.org/licenses/by/3.0/
 */
namespace App\Library {
class Wiky {
	private $patterns, $replacements;

	public function __construct($analyze=false) {
		$this->patterns=array(
			"/\r\n/",
			
			// Headings
			"/^==== (.+?) ====$/m",						// Subsubheading H5
			"/^=== (.+?) ===$/m",						// Subheading H4
			"/^== (.+?) ==$/m",						// Heading H3
			"/^= (.+?) =$/m",	                    // Heading H2
	
			// Formatting
			"/\'\'\'\'\'(.+?)\'\'\'\'\'/s",					// Bold-italic
			"/\'\'\'(.+?)\'\'\'/s",						// Bold
			"/\'\'(.+?)\'\'/s",						// Italic
	
			// Special
			"/^----+(\s*)$/m",						// Horizontal line
			"/\[\[(img):((ht|f)tp(s?):\/\/(.+?))( (.+))*\]\]/i",
			"/\[\[(file):((ht|f)tp(s?):\/\/(.+?))( (.+))*\]\]/i",	// (File|img):(http|https|ftp) aka image
			//"/\[((news|(ht|f)tp(s?)|irc):\/\/(.+?))( (.+))\]/i",	
			"/\[((news|(ht|f)tp(s?)|irc):\/\/(.+?)) ([a-z0-9A-z\s]+)\]/i",
			"/\[((news|(ht|f)tp(s?)|irc):\/\/(.+?))\]/i",			// Other urls without text
	
			// Indentations
			"/[\n\r]: *.+([\n\r]:+.+)*/",					// Indentation first pass
			"/^:(?!:) *(.+)$/m",						// Indentation second pass
			"/([\n\r]:: *.+)+/",						// Subindentation first pass
			"/^:: *(.+)$/m",						// Subindentation second pass
	
			// Ordered list
			"/[\n\r]?#.+([\n|\r]#.+)+/",					// First pass, finding all blocks
			"/[\n\r]#(?!#) *(.+)(([\n\r]#{2,}.+)+)/",			// List item with sub items of 2 or more
			"/[\n\r]#{2}(?!#) *(.+)(([\n\r]#{3,}.+)+)/",			// List item with sub items of 3 or more
			"/[\n\r]#{3}(?!#) *(.+)(([\n\r]#{4,}.+)+)/",			// List item with sub items of 4 or more
	
			// Unordered list
			"/[\n\r]?\*.+([\n|\r]\*.+)+/",					// First pass, finding all blocks
			"/[\n\r]\*(?!\*) *(.+)(([\n\r]\*{2,}.+)+)/",			// List item with sub items of 2 or more
			"/[\n\r]\*{2}(?!\*) *(.+)(([\n\r]\*{3,}.+)+)/",			// List item with sub items of 3 or more
			"/[\n\r]\*{3}(?!\*) *(.+)(([\n\r]\*{4,}.+)+)/",			// List item with sub items of 4 or more
	
			// List items
			"/^[#\*]+ *(.+)$/m",						// Wraps all list items to <li/>
	
			// Newlines (TODO: make it smarter and so that it groupd paragraphs)
			"/^(?!<li|dd).+(?=(<a|strong|em|img)).+$/mi",			// Ones with breakable elements (TODO: Fix this crap, the li|dd comparison here is just stupid)
			"/^[^><\n\r]+$/m",						// Ones with no elements
		);
		$this->replacements=array(
			"\n",
			
			// Headings
			"<h5>$1</h5>",
			"<h4>$1</h4>",
			"<h3>$1</h3>",
			"<h2>$1</h2>",
	
			//Formatting
			"<strong><em>$1</em></strong>",
			"<strong>$1</strong>",
			"<em>$1</em>",
	
			// Special
			"<hr/>",
			"<img src=\"$2\" alt=\"$6\"/>",
			"<a href=\"$2\">$2</a>",
			//"<img src=\"$2\" alt=\"$6\"/>",
			"<a href=\"$1\">$6</a>",
			"<a href=\"$1\">$1</a>",
	
			// Indentations
			"\n<dl>$0\n</dl>", // Newline is here to make the second pass easier
			"<dd>$1</dd>",
			"\n<dd><dl>$0\n</dl></dd>",
			"<dd>$1</dd>",
	
			// Ordered list
			"\n<ol>\n$0\n</ol>",
			"\n<li>$1\n<ol>$2\n</ol>\n</li>",
			"\n<li>$1\n<ol>$2\n</ol>\n</li>",
			"\n<li>$1\n<ol>$2\n</ol>\n</li>",
	
			// Unordered list
			"\n<ul>\n$0\n</ul>",
			"\n<li>$1\n<ul>$2\n</ul>\n</li>",
			"\n<li>$1\n<ul>$2\n</ul>\n</li>",
			"\n<li>$1\n<ul>$2\n</ul>\n</li>",
	
			// List items
			"<li>$1</li>",
	
			// Newlines
			"$0<br/><br/>",
			"$0<br/><br/>",
		);
		if($analyze) {
			foreach($this->patterns as $k=>$v) {
				$this->patterns[$k].="S";
			}
		}
	}
	public function parse($input) {
		$output = $input;

			$m = preg_match_all( "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/", $output, $match);
				
			if ($m) {
				$links = $match[0];
				foreach($links as $link) {
					$link = trim(strip_tags($link));
					$extension = strtolower(trim(@end(explode(".",$link))));
					switch($extension) {
						case 'gif':
						case 'png':
						case 'jpg':
						case 'jpeg':
							$output = str_replace($link, '<img src="'.$link.'">', $output);       
							break;
						break;
					}
				}
			}

		if(!empty($output))
			$output=preg_replace($this->patterns,$this->replacements,$output);
		else
			$output=false;

		return $output;
	}
}
}
