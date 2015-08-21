<?php
/*
 *
 * Copyright 2015 Leon van Kammen / Coder of Salvation. All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without modification, are
 * permitted provided that the following conditions are met:
 * 
 *    1. Redistributions of source code must retain the above copyright notice, this list of
 *       conditions and the following disclaimer.
 * 
 *    2. Redistributions in binary form must reproduce the above copyright notice, this list
 *       of conditions and the following disclaimer in the documentation and/or other materials
 *       provided with the distribution.
 * 
 * THIS SOFTWARE IS PROVIDED BY Leon van Kammen / Coder of Salvation AS IS'' AND ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND
 * FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL Leon van Kammen / Coder of Salvation OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 * The views and conclusions contained in the software and documentation are those of the
 * authors and should not be interpreted as representing official policies, either expressed
 * or implied, of Leon van Kammen / Coder of Salvation 
 */

namespace coderofsalvation;

class DataMapper {

	public $mappings = array();
  	public static $onProgress = false;
  
	/**
	 * update pushes realtime info to listeners (Closure defined at self::$onProgress)
	 * 
	 * @param mixed $type 
	 * @param mixed $msg 
	 * @access private
	 * @return void
	 */
	private function update($type, $msg){
	    if( self::$onProgress ){
	        $f = self::$onProgress;
	        $f($type,$msg);
	    }	
	}
	  
	public function addMapping( $name, $mapping ){
	 	$this->mappings[$name] = $mapping;
	}
	
	public function map( $name, $a, &$b ){
	  if( !isset($this->mappings[$name]) ) throw new Exception("MAPPING_NOT_FOUND");
	  foreach( $this->mappings[$name] as $field ){
		$fieldDestination 	= $field['destination'];
		$fieldSource 		= $field['source'];
	    $transform          = $field['transform'];
	    $this->update("DEBUG", sprintf("%-20s %-20s -> %s", "mapping field ", "'".$fieldSource."'", "'".$fieldDestination."'" ) );
		if( is_array($b)  )		$b[ $fieldDestination ] = $transform( $a, $b );
		if( is_object($b) )		$b->$fieldDestination   = $transform( $a, $b );
	  }
	  $this->update("DEBUG", "mapping done");
	  return $b;
	}

}

?>
