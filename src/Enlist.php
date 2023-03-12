<?php

namespace Spoova\Enlist;

use ErrorException;
use Exception;

/**
 * This package is used mainly to rename multiple files at once
 * 
 * @author Akinola Saheed <teymss@gmail.com>
 */
class Enlist{

	private $action;
	private string $url;
	private string $prefix = '';
    private bool|int $debug;
	private bool $active = false;
	private mixed $error = '';
	private array|string|null $ext;
	private int $counter = 1;
	private $espace;
	private $reNumber;
	public bool $smartUrl;
	public string $session_name;
    public array $backTrace = [];

	private function processUrl($url){
       
        if(!is_dir($url)){ 
            $this->active = false;
            $this->error("invalid url supplied"); 
            return false; 
        } 

        $url = str_replace("\\", "/", $url);    
        $this->url = rtrim($url,"/");
        return true;	

	}

	/**
	 * set source path
	 *
	 * @param string source $url
	 * @param array|string source $ext
	 * @return Enlist
	 */
	public function source($url, array|string $ext = null) : Enlist {
       
       if(!$this->processUrl($url)){ return false; }

       $this->ext  = $ext;

       $this->active = true;

       return $this; 

	}

	/**
	 * Add a prefix to a naming convention
	 *
	 * @param string $prefix
	 * @return void
	 */
	public function prefix($prefix){
		$this->prefix = $prefix;
	}

	/**
	 * Point from which an incremental naming should start from
	 *
	 * @param integer $startpoint
	 * @return Enlist
	 */
	public function startFrom(int $startpoint) : Enlist {
       $startpoint = $startpoint == 0 ? 1 : $startpoint;
       $this->counter = $startpoint;
       return $this;
	}

	/**
	 * Replace space with character during renaming process
	 *
	 * @param string $replace character to be used to replace spaces
	 * @return Enlist
	 */
	public function reSpace(string $replace = null) : Enlist {
		$replace = ($replace == null)? "_" : $replace;
		if($replace == "_" || $replace = "-"){
			$this->espace = $replace;
		}
        return $this;
	}

	/**
	 * Reduce special character in old file name when renaming
	 *
	 * @return void
	 */
	public function smartUrl(){
		$this->smartUrl = true;
	}

	/**
	 * Allow renaming to re-number the files in a directory
	 *
	 * @param boolean $reNumber
	 * @return Enlist
	 */
	public function reNumber($reNumber = true) : Enlist{
		$this->reNumber = (bool) $reNumber;
        return $this;
	}

	/**
	 * Resolve Enlist::rename() mode to return files only without any active renaming process
	 *
	 * @param string $type
	 * @return Enlist
	 */
	public function view(bool $bool = true) : Enlist {
		$this->action = ($bool)? 'view' : '';
        return $this;
	}

	/**
	 * Display files in a directory
	 *
	 * @param string|array $extension allowed file extensions
	 * @param boolean $fullpath show full file path when set as true
	 * @return array
	 */
	public function dirFiles(string|array $extension = [], $fullpath = false) : array {
		$url  = $this->url;
		$files = [];
		$ext = (array) $extension;

        $dirNormal = glob($url.'/*')?: [];
        $dirHidden = glob($url.'/.*')?: [];

        $dirFiles = array_merge($dirHidden, $dirNormal);


		foreach($dirFiles as $ifile) {

            $baseName = basename($ifile);

			if(!empty($ext) and is_file($ifile)){
				$fileExt = pathinfo($ifile,PATHINFO_EXTENSION);
                if(in_array(".*", $ext)){
					$files[] = ($fullpath === true)? $ifile : str_replace(str_replace("\\","/",__DIR__.'/'), '', $ifile);
                }elseif(in_array($fileExt, $ext)){
					$files[] = ($fullpath === true)? $ifile : str_replace(str_replace("\\","/",__DIR__.'/'), '', $ifile);
				}elseif(in_array('.', $ext) && (substr($baseName, 0, 1) === '.')){
					$files[] = ($fullpath === true)? $ifile : str_replace(str_replace("\\","/",__DIR__.'/'), '', $ifile);
                }
			}elseif(empty($ext)){
				$files[] = $ifile;
			}	

		}
		return $files;
	}

	/**
	 * Renaming directive
     *  - Note that hidden files starting with dot character will not be renamed.
	 *
	 * @param string|boolean $finalExt 
     *  - string is file extension name without a dot prefix
     * @throws Exception if extension supplied is not accepted
	 * @return array|false
     *  - false is returned if error occurs.
	 */
	public function rename(string|bool $finalExt = true, &$results = []) : array|false {

	  if(!$this->active){ return false; }
	  $url  = $this->url;
	  $ext  = (array) $this->ext;
	  $counter = $this->counter;
	  $prefix = $this->prefix;
	  $action = $this->action;
	  $espace = $this->espace;
	  $reNumber = $this->reNumber;
      $files = []; 
      $hiddenFiles = []; $hiddenMap = [];

      $firstVal = ($ext[0] ?? '');

      $hiddenItems = ['.', '.*'];

      if(in_array($firstVal, $hiddenItems)){
        //only hidden
        $hiddenFiles = array_filter(glob($url.'/.*') ?? [], 'is_file');

        $counti = 0;
        if(count($hiddenFiles) > 1){
            foreach($hiddenFiles as $hiddenFile){
                $hiddenMap[$hiddenFile] = pathinfo($hiddenFile, PATHINFO_FILENAME).".".pathinfo($hiddenFile, PATHINFO_EXTENSION)?: $counti;
                $counti++;
            }
        }
      }

      if($firstVal !== '.'){
          foreach(glob($url.'/*') as $ifile) {
    
              if(is_file($ifile)){
                  $files[] = $ifile;
              }	
    
          }
      }

      $files = array_merge($hiddenFiles, $files);

	  natsort($files);

      $fUrls = []; $count = 0;

	  foreach ($files as $file) {

        $file_ext =  pathinfo($file, PATHINFO_EXTENSION);
	  	$fileExt = ($finalExt === true)? $file_ext : $finalExt;

        $invalidExts = ['*',':','?','|','.', ' '];
        $excludes = ['.','*','.*'];

        if((!empty($ext) && in_array($file_ext, $ext)) || ((count($ext) == '1') && (in_array($ext[0], $excludes)))) {
            //explode the first names
            $directory = explode("/",$file, -1);
            $dir = implode("/", $directory);

            if($reNumber){
                $newfile =  $prefix.$counter; 
            }else{
                $newfile = str_replace($dir."/", '', $file);
                $newfile = pathinfo($newfile,PATHINFO_FILENAME);
            }


            $newfile = ($espace)? preg_replace("/\s+/", $espace, $newfile) : $newfile;
            if(isset($this->smartUrl)){
                //strip off unnecessary characters from url.
                $newfile = preg_replace('~[^0-9a-z_]+~i', '_', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($newfile, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'));
                $newfile = rtrim(preg_replace('/_+/', '_', $newfile),"_");
            }	  			
            

            $newfile = $dir.'/'.$newfile.'.'.$fileExt;
            if(array_key_exists($file, $hiddenMap)) { 
                $newfile = $dir.'/'.$hiddenMap[$file];            

                if($reNumber) $newfile .= $counter;
                if($fileExt && ($fileExt !== pathinfo($newfile, PATHINFO_EXTENSION))) $newfile .= ".".$fileExt;                
                $lasthChar = substr($newfile, -1);
                if(in_array($lasthChar, $invalidExts)){
                    $this->error('invalid character file extension supplied for file "'.$newfile.'" '); 
                    return false;
                }                
            }

            $lastChar = substr($fileExt, -1);

            if(in_array($lastChar, $invalidExts)){
               $this->error('invalid character file extension supplied for file "'.$newfile.'" '); 
               return false;
            }
            
            $fUrls[$file] = $newfile;

            if($action != 'view'){
                if(strtolower($file) !== strtolower($newfile)){
                    if(isset($this->session_name))$_SESSION[$this->session_name][$file] = $newfile;
                    rename($file, $newfile);
                }
            }
        }elseif(empty($ext)){

            if($count == 0){
                $this->error('The extension names of files to be renamed is not defined!');
                return false;
            }
        }
        $count++;
	  	$counter++;
	  }
      $results = [];
	  return $fUrls;
      
	}

    public function withSession(string $session_name) : Enlist {

        if(!isset($_SESSION)) session_start();
        $this->session_name = $session_name;

        return $this;

    }

    /**
     * Reverse renamed files through session storage
     *
     * @param array $reversals
     * @param array $session_name specify a session name
     * @return void
     */
    public function reverse(array|null &$reversals = [], string $session_name = '') {

        if(isset($_SESSION)){    
            $session_name = (func_num_args() > 1)? $session_name : $this->session_name;
            
            $reversed_items = $_SESSION[$this->session_name] ?? [];
    
            foreach($reversed_items as $old => $new){
    
                if(is_file($new)){
                    $reversals[] = $new;
                    rename($new, $old);
                }
                
            }
    
            if($reversed_items) unset($_SESSION[$this->session_name]);
        }
        
    }

	/**
	 * Sets and returns an error encountered during processing
	 *
	 * @param string $error  
     *  If argument is supplied, sets $error by overiding last error, if any. 
	 * @return mixed 
     *  - If not modified, default error is returned as a string.
	 */
	public function error($error = null){
        if(func_num_args() > 0){

            $this->error = $error;

            if(isset($this->debug)) {
                $backTrace = (debug_backtrace());
                unset($backTrace[0]);
                $backTrace = array_values($backTrace);
                $this->backTrace = ($backTrace);
                if($this->error && ($this->debug === 2)) {
                    throw new ErrorException ($backTrace[0]['object']->error, 0, E_USER_NOTICE, $backTrace[0]['file'], $backTrace[0]['line']);
                }
            }

        }
		return $this->error;
	}

    /**
     * Turns debugging on. 
     *  This must be used before  Enlist::rename() function is called.
     *
     * @param boolean|integer $debug
     *  - if $debug is set as true, debugging will store all available errors and can be fetched from Enlist::all_errors() method. 
     *  - if $debug is set as 2, an ErrorException will be thrown if error occurs 
     * @return void
     */
    public function debug(bool|int $debug = true){
        $this->debug = $debug;
    }

    /**
     * Returns all back traces where error exists.
     *
     * @param boolean $debug array list of debugs
     * @return array
     */
    public function debugs(&$debugs = []){

        if(!isset($this->debug)) trigger_error('debug should be turned on before "rename()" to use "all_errors()"');

        $backTraces = $this->backTrace; 
        $traces = count($backTraces);
        $keys = ['file','line','function', 'class', 'error'];

        $errors = [];

        for($i = 0; $i<= $traces; $i++){

            if(isset($backTraces[$i]['object']->error)){
                $traced = $backTraces[$i];
                $errors[$i]['file'] = $traced['file'] ?? '';
                $errors[$i]['line'] = $traced['line'] ?? '';
                $errors[$i]['function'] = $traced['function'] ?? '';
                $errors[$i]['class'] = $traced['class'] ?? '';
                $errors[$i]['error'] = $backTraces[$i]['object']->error;
            }

        }

        return $debugs = $errors;

    }

}

?>