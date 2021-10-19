<?php

if (!function_exists('yaml_parse_file')) {
    function yaml_parse_file($filename)
    {
        //如果没有yaml扩展则使用symfony进行解析yaml
        return Symfony\Component\Yaml\Yaml::parseFile($filename);
    }
}

/**
 * @param resource $from
 * @param resource $to
 */
function io_copy($from, $to)
{
    while (true) {
        $data = fread($from, 8192);
        fwrite($to, $data);
    }
}