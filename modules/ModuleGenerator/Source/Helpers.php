<?php
/**
 * Created by PhpStorm.
 * User: andrey
 * Date: 6/13/16
 * Time: 1:10 AM
 */

namespace Modules\ModuleGenerator\Source;

class Helpers
{
    static public $mode = 0776;

    static function copy($source, $target) {
        if (!is_dir($source)) {//it is a file, do a normal copy
            if( !copy($source, $target) ){
                return false;
            }
            return true;
        }

        //it is a folder, copy its files & sub-folders
        if( !mkdir($target, self::$mode) ){
            return false;
        }

        $d = dir($source);
        $navFolders = array('.', '..');
        while (false !== ($fileEntry=$d->read() )) {//copy one by one
            //skip if it is navigation folder . or ..
            if (in_array($fileEntry, $navFolders) ) {
                continue;
            }

            //do copy
            $s = "$source/$fileEntry";
            $t = "$target/$fileEntry";
            self::copy($s, $t);
        }
        $d->close();

        return true;
    }

    static function replaseInFile($file, array $arIn, array $arOut)
    {
        $file_contents = file_get_contents($file);
        $file_contents = str_replace($arIn, $arOut, $file_contents);
        return file_put_contents($file, $file_contents);
    }
}