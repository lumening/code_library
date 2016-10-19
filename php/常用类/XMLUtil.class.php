<?php
/**
 * @date: 2016/10/14 21:26
 * @author: Tan <tanda517886160@163.com>
 * @copyright © 2003-2016 Kankan.com
 */

class XMLUtil {
    /**
     * 解析大型XML文件成数组
     *
     * @param  string $xml_file
     * @param  string $nodeName
     * @param  array|string $callback
     * @return array
     * @throws \Exception
     */
    public static function ParseBigXML($xml_file, $nodeName, $callback) {
        $data = array();
        $reader = new XMLReader();
        $reader->open($xml_file);

        while ($reader->read()) {
            if($reader->nodeType == XMLREADER::ELEMENT){
                //每个url节点对应一个文档
                if($nodeName && $reader->localName == $nodeName){
                    $node = $reader->expand();
                    //print_r(self::XMLNode2Array($node));die;
                    $dom = new DomDocument();
                    $dom->appendChild($dom->importNode($node,true));
                    $xml_dom = $dom->saveXML($dom, LIBXML_NOEMPTYTAG);
                    unset($dom);
                    //回调
                    call_user_func_array($callback,array($xml_dom));
                }
            }
        }
        $reader->close();
        return $data;
    }

    /**
     * 解析XML文件成数组
     *
     * @param $xml_dom
     * @param array $force_array_fields  强制解析为数组  如: url.data.display.serialinfo.detail
     * @return array|bool
     */
    public static function parseXML2Array($xml_dom, $force_array_fields = array()){
        $xmlobject = self::parseXML($xml_dom);
        if(is_object($xmlobject)){
            $xml_arr =  self::xmlObject2Array($xmlobject);

            //强制解析为数组
            foreach($force_array_fields as $force_fields){
                $point_arr = &$xml_arr;
                $keys = explode('.', $force_fields);
                while (count($keys) >= 1) {
                    $key = array_shift($keys);
                    if (! isset($point_arr[$key])) {
                        break;
                    }
                    $point_arr = &$point_arr[$key];
                }

                if(! isset($point_arr[0])){
                    $tmp_arr = $point_arr;
                    $point_arr = array();
                    $point_arr[] = $tmp_arr;
                }
            }
            return $xml_arr;

        }else{
            return (false === $xmlobject) ? false : array();
        }
    }

    /**
     * 解析XML
     *
     * @param $xml
     * @return bool|\SimpleXMLElement
     */
    public static  function parseXML($xml) {
        try{
            //LIBXML_NOCDATA Merge CDATA as text nodes
            return simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        }catch (Exception $e){
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * 对象转数组
     *
     * @param $xmlobject
     * @return array|string
     */
    public static function xmlObject2Array ($xmlobject)
    {
        //解决了xml空标签形成的空对象的返回数组的问题
        if(is_object($xmlobject) && empty(get_object_vars($xmlobject))) {
            $xmlobject = (string) $xmlobject;
        }

        $arr = is_object($xmlobject) ? get_object_vars($xmlobject) : $xmlobject;
        if (is_array($arr)) {
            return array_map(__METHOD__, $arr);
        } else {
            return $arr;
        }
    }
}

//$file =  file_get_contents('http://expand.video.iqiyi.com/xunlei/tv/xunlei_tv_add_08.xml');
//file_put_contents('./xunlei_tv_add_08.xml', $file);
/*$data = XMLUtil::ParseBigXML('./xunlei_tv_add_08.xml' , 'url' , function($xml){
    print_r(XMLUtil::parseXML2Array($xml, array('data.display.serialinfo.detail')));
    echo "\r\n";
});
print_r($data);*/