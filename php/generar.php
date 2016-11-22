<?php
require 'vendor/autoload.php';
define('DATA_DIR', dirname(dirname(__file__)).'/data/');
define('TMP_DIR', dirname(dirname(__file__)).'/templates/');
define('OUT_DIR', dirname(__file__).'/out/');

use Underscore\Types\Arrays;
use Symfony\Component\Yaml\Yaml;

$config = Yaml::parse(file_get_contents('config.yml'));
//var_dump($config); exit();
foreach ($config['convert'] as $toConvert) {
  $tmp = $toConvert['template'];
  $data = $toConvert['data'];
  if(!file_exists(TMP_DIR.$tmp)){
    throw new Exception(TMP_DIR.$tmp . ' no existe');
  }
  if(!file_exists(DATA_DIR.$data)){
    throw new Exception(DATA_DIR.$data . ' no existe');
  }
  $tmp = file_get_contents(TMP_DIR.$tmp);
  $mustache = new Mustache_Engine;
  $tpl = $mustache->loadTemplate($tmp);

  $data = Yaml::parse(file_get_contents(DATA_DIR.$data));
  //var_dump($data); exit();
  foreach($data['data'] as $item){
    //var_dump($item);
    $toWrite = $tpl->render($item);
    file_put_contents(OUT_DIR.$item['filename'], $toWrite);
    echo 'generado el archivo ' . OUT_DIR.$item['filename'] . "\n";
  }
}

function getDataFiles(){

  $data_files = scandir(DATA_DIR);
  $data_files = Arrays::last($data_files, -2);

    return $data_files;
}
