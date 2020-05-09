<?php
/////////////// Upload FILE CLASS ////////////////
//************************************************
//************************************************
//************** CREATED BY VK *******************
//************ message 20151118 ******************
///////////////////////////////////////////////////
class Upload {
    public static $SUCCESS = 'File is uploaded successfully.';
    public static $FILESIZE_ERROR = 'Exceeded file size limit.';
    public static $INVALIDPARAMETERS_ERROR = 'Invalid parameters.';
    public static $UNKNOWN_ERROR = 'Unknown errors.';
    public static $INVALIDFORMAT_ERROR = 'Invalid file format.';
    public static $MOVE_ERROR = 'Failed to move uploaded file.';
    public static $FILEEXISTS_ERROR = 'Case already exists!';
    public static $NOTVALID_ESSTCASE = 'Case is not valid esst case!';
    public static $NOTVALID_ESSTCASE_LATEST = 'Case is not valid esst 2.7.0. case!';
    public static $FILE_SIZE = 10240000000000;

    private $UPLOADED_FOLDER;
    private $UPLOAD_FILE;
    
    var $fieldname;
	var $type;
	var $upload_dir;
	private $filename;
    private $filenameNoExt;
	
	function __construct( $n_fieldname, $n_type, $n_upload_dir, $n_action ){
		
		$this->fieldname = $n_fieldname;
		$this->type = $n_type;
		$this->upload_dir = $n_upload_dir;
        $this->filename = $_FILES[$this->fieldname]["name"];
        $this->action = $n_action;


        $this->UPLOAD_FILE = $this->upload_dir.$this->filename;
        $this->filenameNoExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $this->filename);
        $this->UPLOADED_FOLDER = $this->upload_dir.$this->filenameNoExt;
	}

	function uploaded(){
       try{
            // Undefined | Multiple Files | $_FILES Corruption Attack
            // If this request falls under any of them, treat it invalid.
            if (!isset($_FILES[$this->fieldname]['error']) || is_array($_FILES[$this->fieldname]['error'])){
                throw new RuntimeException(Upload::$INVALIDPARAMETERS_ERROR);
            }
            
            // Check $_FILES['upfile']['error'] value.
            switch ($_FILES[$this->fieldname]['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('No file sent.');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException(Upload::$FILESIZE_ERROR);
                default:
                    throw new RuntimeException(Upload::$UNKNOWN_ERROR);
            }
        
            // You should also check filesize here.
            if ($_FILES[$this->fieldname]['size'] > Upload::$FILE_SIZE){
                throw new RuntimeException(Upload::$FILESIZE_ERROR);
            }
            // DO NOT TRUST $_FILES[$this->fieldname]['mime'] VALUE !!
            // Check MIME Type by yourself.
            // $ZIP = array('zip' => 'application/zip', 'rar' => 'application/x-rar');
            switch($this->type){
                default:
                break;	
                case 'ZIP':
                    $TYPE = array('zip' => 'application/zip');                    
                break;
                case 'XLS': 
                    $TYPE = array(
                        'xls' => 'application/vnd.ms-excel',
                        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    );           
                break;
            }


            $finfo = new finfo(FILEINFO_MIME_TYPE);
            
            if (false === $ext = array_search($finfo->file($_FILES[$this->fieldname]['tmp_name']), $TYPE, true)){
                throw new RuntimeException(Upload::$INVALIDFORMAT_ERROR);
            }
            		

            switch($this->action){
                default:
                break;	
                case 'esstRestore':
                    if (!file_exists($this->UPLOADED_FOLDER)){
                        if (!move_uploaded_file($_FILES[$this->fieldname]['tmp_name'], $this->UPLOAD_FILE)){
                            throw new RuntimeException(Upload::$MOVE_ERROR);
                        }

                        else{
                            $zip = new ZipArchive;
                            $path = pathinfo(realpath($this->UPLOAD_FILE), PATHINFO_DIRNAME);
                            if ($zip->open($this->UPLOAD_FILE) === TRUE) {
                                    $esstTest1 = $zip->getFromName($this->UPLOAD_FILE);
                                    $file = $zip->getFromName($this->filenameNoExt . '/'.$this->filenameNoExt.'.xml');
                                    $xml = simplexml_load_string($file);
                                    $ver = (string)$xml->Case->version;
                                    if($ver == '2.7.0.'){
                                        //mkdir($this->UPLOADED_FOLDER, 0777, true);
                                        //$zip->extractTo($this->UPLOAD_FOLDER);
                                        $zip->extractTo($path);
                                        $zip->close();
                                        chmod($this->UPLOAD_FILE,0777);
                                        unlink($this->UPLOAD_FILE);
                                     }
                                     else{
                                          $zip->close();
                                         chmod($this->UPLOAD_FILE,0777);
                                         unlink($this->UPLOAD_FILE);
                                         throw new RuntimeException(Upload::$NOTVALID_ESSTCASE_LATEST);
                                     }
                            } 
                        }
                    }
                    else{
                        throw new RuntimeException(Upload::$FILEEXISTS_ERROR);
                    }                   
                break;
                case 'hDataUpload': 
                    if (!is_dir($this->upload_dir.$_SESSION['us'].'/'.$_SESSION['case'].'/hSimulation')) {
                        mkdir($this->upload_dir.$_SESSION['us'].'/'.$_SESSION['case'].'/hSimulation');         
                    }
                    
                    if (!file_exists($this->upload_dir.$_SESSION['us'].'/'.$_SESSION['case'].'/hSimulation'.'/'.$_FILES[$this->fieldname]['name'])){
                        if (!move_uploaded_file($_FILES[$this->fieldname]['tmp_name'],$this->upload_dir.$_SESSION['us'].'/'.$_SESSION['case'].'/hSimulation'.'/'.$_FILES[$this->fieldname]['name'])){
                            throw new RuntimeException(Upload::$MOVE_ERROR);
                        }
                    }
                    else{
                        throw new RuntimeException(Upload::$FILEEXISTS_ERROR);
                    }          
                break;
            }

        }
        catch (RuntimeException $e){
            throw $e;
        }
	}
}
?>
