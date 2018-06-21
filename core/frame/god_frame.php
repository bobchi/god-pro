<?php
namespace core\frame;
class god_frame
{
    public $project_folder = '';
    public $project_main = '';

    public function __construct($prjName){
        $this->project_folder = getcwd().'/'.$prjName;
        $this->project_main = $this->project_folder.'/index.php';
    }
    public function run(){
        !file_exists($this->project_folder) && mkdir($this->project_folder);
        !file_exists($this->project_main) && file_put_contents($this->project_main, '');
    }
}