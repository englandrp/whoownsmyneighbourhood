<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Xml {

    var $parser;
    var $pointer;
    var $dom;

    function xml() {
        $this->pointer =& $this->dom;
        $this->parser = xml_parser_create();
        xml_set_object($this->parser, $this);
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, FALSE);
        xml_set_element_handler($this->parser, "tag_open", "tag_close");
        xml_set_character_data_handler($this->parser, "cdata");
    }

    function parse($data) {
        xml_parse($this->parser, $data);
    }
  
    function makeChildNode() {
        if (!is_array($this->pointer['child_nodes'])){
            $this->pointer['child_nodes'] = array();
        }
        return count($this->pointer['child_nodes']);
    }

    function tag_open($parser, $tag, $attributes) {
        $idx = $this->makeChildNode();
        $this->pointer['child_nodes'][$idx] = array(
            '_idx' => $idx,
            '_parent' => &$this->pointer,
            'tag_name' => $tag,
            'attributes' => $attributes,
        );
        $this->pointer =& $this->pointer['child_nodes'][$idx];
    }

    function cdata($parser, $cdata) {
		//drop text nodes that are just white space formatting characters
		if (trim($cdata) != "") {
			$idx = $this->makeChildNode();
			$this->pointer['child_nodes'][$idx] = $cdata;
		}
	}

    function tag_close($parser, $tag) {
        $idx =& $this->pointer['_idx'];
        $this->pointer =& $this->pointer['_parent'];
        unset($this->pointer['child_nodes'][$idx]['_idx']);
        unset($this->pointer['child_nodes'][$idx]['_parent']);
    }

}
