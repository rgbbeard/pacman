<?php
function dump(...$items) {
	$debug = "<pre style='background-color:#222;color:#0f0;padding:10px;'>";
	
	foreach($items as $item) {
		$value = print_r($item, true);
		
		if(is_bool($item)) {
			$debug .= "bool(" . (intval($value) ? "true" : "false") . ")";
		} elseif(is_numeric($item)) {
			$debug .= "number(" . $value . ")";
		} elseif(is_array($item)) {
			$debug .=  "array[" . count($item) . "](" . replace(
					array(
						"{" => "{\n",
						"}" => "\n}",
						",\"" => ",\n\""
					),
					json_encode($item)
				) . ")";
		} else {
			$debug .= $value;
		}
		
		$debug .= "\n";
	}
	
	$debug .= "</pre>";
	
	echo $debug;
}

function dd(...$items) {
	dump(...$items);
	die();
}

function replace(array $chars, string $target) {
	foreach($chars as $char => $replacement) {
		$target = str_replace($char, $replacement, $target);
	}
	
	return $target;
}

function array_trim(array $target) {
	return array_filter($target, function($item) {
		if(!empty($item)) {
			return $item;
		}
	});
}