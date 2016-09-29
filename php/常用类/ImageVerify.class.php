<?php
		class ImageVerify
		{
			private $length;
			private $height;
			private $width;
			private $type;
			private $filename;
			private $mode;
			/*构造函数*/
			public function __construct($length='4',$width='60',$height='25',$type='png',$mode='0',$filename=''){
				 $this->length = $length;
				 $this->height = $height;
				 $this->width = $width;
				 $this->type = $type;
				 $this->filename = $filename;
				 $this->mode = $mode;
				
			}
			/*生成验证码函数*/
		   public function  buildImageVerify(){
		   	    //创建画布，设置背景颜色并填充
			    $image = imagecreate($this->width , $this->height)or die("Cannot Initialize new GD image stream");
				$r = Array(225, 255, 255, 223);
        		$g = Array(225, 236, 237, 255);
        		$b = Array(225, 236, 166, 125);
       			$key = mt_rand(0,3);
			    $bgColor = imagecolorallocate($image, $r[$key], $g[$key], $b[$key]);
			    imagefill($image, 1, 1, $bgColor);

			    /*Verify*/
			    //在图片写入字符
			    $string = $this->VerifyString();
			    for ($i=0; $i < $this->length ; $i++) { 
			    	 $fontColor= imagecolorallocate($image, mt_rand(0,255),  mt_rand(0,255),  mt_rand(0,255));
			      $fontx = ($this->width/4)*$i + mt_rand(0,$this->width/8);
			   	  $fonty = ($this->height/5)*mt_rand(1,2);
 			     imagestring($image, 5 , $fontx, $fonty, $string[$i], $fontColor);
			    }
			    //绘制干扰素
			    self::impix($image,$this->width,$this->height);
			    //绘制细线
			    self::imline($image,$this->width,$this->height);
			   //输出图片并销毁
			   self::output($image,$this->type,$this->filename);
			}

			public function VerifyString(){
				$str ='';
				switch ($this->mode) {
					case '1':  //表示大写字母
					    $char = "ABCDEFGHIJKLMNOPQRSTUVWSYZ";
						break;
					case '2':  //表示小写字母
						$char ="abcdefghijklmnopqrstuvwsyz";
						break;
					case '3': //表示大小写混编
						$char = "ABCDEFGHIJKLMNOPQRSTUVWSYZabcdefghijklmnopqrstuvwsyz";
						break;
					default:  //0及其他表示数字
					   $char ="0123456789";
					   break;
				}
				for ($i=0; $i < $this->length ; $i++) { 
							$str .= $char[mt_rand(0,strlen($char)-1)];	
						}
				return $str;
			}

			private static function  impix($image,$width,$height){
				//绘制干扰素
			    $pixColor = imagecolorallocate($image, mt_rand(0,255),mt_rand(0,255), mt_rand(0,255));
			    for ($i=0; $i <100 ; $i++) { 
			    	imagesetpixel($image, mt_rand(1,$width-1), mt_rand(1,$height-1), $pixColor);
			     }
			 }


			private static function imline($image,$width,$height){
				//绘制细线
			    $lineColor = imagecolorallocate($image, mt_rand(0,255),mt_rand(0,255), mt_rand(0,255));
			    for ($i=0; $i <10; $i++) {
			    $randx = mt_rand($width/20,$width*15/20) ;
			    $randy = mt_rand($height/20,$height*15/20) ;
			    imageline($image, $randx, $randy, $randx+10, $randy+10, $lineColor);
			    }
			}

			private static function output($image,$type,$filename){
			  //输出图片
			   header("Content-type: image/".$type);
			  // ob_clean();  //关键代码，防止出现'图像因其本身有错无法显示'的问题。
			   $ImageFun = 'image'.$type;    //imagepng
        	     if (empty($filename)) {
          	       $ImageFun($image);      //imagepng($image);
      	         } else {
          	       $ImageFun($image, $filename);
                 }
                 imagedestroy($image);
			}
		
	}
?>