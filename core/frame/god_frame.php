<?php
namespace core\frame;
use core\frame\god_mvc;

class god_frame
{
    public $project_folder = '';
    public $project_main = '';

    public function __construct($prjName){
        $prjName = trim(str_replace(['\n','\r','\r\n'],'',$prjName));
        $this->project_folder = getcwd().'/'.$prjName;
        $this->project_main = $this->project_folder.'/index.php';
    }
    public function run(){
        !file_exists($this->project_folder) && mkdir($this->project_folder);
        !file_exists($this->project_main) && file_put_contents($this->project_main, '');
    }
    function compile()
    {
        $_files = scandir($this->project_folder . '/code');
        foreach ($_files as $_file)
        {
            if(preg_match("/\w+\.(var|func|class)\.php$/",$_file)){
                require $this->project_folder . '/code/' . $_file;
                unset($_file);
            }
        }
        unset($_files);

        // generate variables file
        $vars = '<?php' . PHP_EOL
            . 'extract(' . var_export(get_defined_vars(), 1) . ');';
        file_put_contents($this->project_folder .'/vars', $vars);

        // generate functions file
        $funcs = get_defined_functions()['user'];
        $funcs = array_slice($funcs, 6);
        $funcStr = '';
        foreach ($funcs as $func)
        {
            $f = new \ReflectionFunction($func);
            $start = $f->getStartLine();
            $end = $f->getEndLine();
            $funcArr = file($f->getFileName());
            $funcStr .= implode(array_slice($funcArr, $start-1, $end-$start+1));
        }
        $functions = '<?php' . PHP_EOL
            . '// compile by GOD ' . date('Y-m-d') . PHP_EOL
            . $funcStr;
        file_put_contents($this->project_folder . '/functions', $functions);

        // generate classes file
        $classes = get_declared_classes();
        $classes = array_slice($classes, array_search(__CLASS__, $classes)+1);
        $routerArr = [];
        foreach ($classes as $class){
            $mvc = new god_mvc($class);
            if($mvc->isController())
            {
                $rm = $mvc->getRequestMapping();
                $routerArr = array_merge($routerArr,$rm);
            }
        }
        $routers = '<?php' . PHP_EOL
            . 'return ' . var_export($routerArr,1) . ';';
        file_put_contents($this->project_folder . '/request_route', $routers);
    }

}