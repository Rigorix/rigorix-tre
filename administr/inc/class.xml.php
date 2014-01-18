<?php

class xml_manager {
	
	protected $Optimize = false; 
	
	function xml_manager() 
	{
		
	}
	
	function load( $File ) 
	{
		if(is_file($File)) {
			$this->file = $File;
			$this->dom = new DomDocument();
			$this->dom->preserveWhiteSpaces = true;
			$this->dom->formatOutput = true;
			$this->dom->load( $this->file );
			$this->xpath = new DOMXPath($this->dom);
		} else 
			return false;
	}
		
	function saveConfiguration()
	{
		$this->dom->save( $this->file );
	}
		
	function getXmlDom ( $File )
	{
		if(is_file($File)) {
			$Doc = new DomDocument();
			$Doc->preserveWhiteSpaces = false;
			$Doc->load( $File );
			return new DOMXPath($Doc);
		} else
			return false;
	}
	
	function query($query, $context = false) 
	{
		if($context !== false)
			return $this->xpath->query($query, $context);
		else 
			return $this->xpath->query($query);
	}	
	
	function getNodeObj ( $Node )
	{
		if($Node != null) {
			$res = array();
					
			// Attributes
			$res['attributes'] = array();
			foreach ($Node->attributes as $name => $attr)
				$res['attributes'][$name] = $attr->value;
				
			// Nodevalue
			$res['nodevalue'] = trim($Node->nodeValue);
			
			// Childrens
			if($Node->hasChildNodes()) {
				$res['childrens'] = array();
				foreach ($Node->childNodes as $childNode) {
					if($childNode->tagName != null) {
						$res['childrens'][count($res['childrens'])] = $this->getNodeObj($childNode);
					}
				}
			}		
			return $res;
		} else 
			return false;
	}
	
	function removeTextChilds($nodeList)
	{
		for ($i=0; $i<$nodeList->length; $i++) {
			if($nodeList->item($i)->nodeName == '#text') 
				$nodeList->item($i)->parentNode->removeChild($nodeList->item($i));
		}
		return $nodeList;
	}
	
	function getNodeChildrensAsObj($path, $options)
	{
		$res = array();
		$Parent = $this->query($path);
		foreach ($Parent->item(0)->childNodes as $Child) {
			if($Child->tagName != "") {
				if(in_array('nodevalueOnly', $options)) {
					// Voglio solo i valori di ogni singolo nodo
					$res[$Child->tagName] = trim($Child->textContent);
				} else {
					$res[$Child->tagName] = $this->getNodeObj($Child);
				}
			}
		}
		return $res;
	}
	
}

?>
