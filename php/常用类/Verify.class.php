<?php
	class Verify{
		private static $length;//验证码长度
		private static $width;//验证码图片宽度,默认会自动根据验证码长度自动计算
		private static $height;//严重码图片高度,默认为22
		private static $mod;//验证字符串的类型，默认为数字，其他支持类型有0 字母 1 数字 2 大写字母 3 小写字母 4字母与数字混合
		private static $im;//图片资源
		/*
			注意，静态方法里面只能调用静态属性和静态方法
		*/
		public static function buildImageVerify($length=6,$width=0,$height=22,$mod=1,$verifyName='verify'){
			//$verifyName 验证码的SESSION记录名称，默认为verify
			header("Content-type: image/png"); 
			session_start();
			self::$length=$length;
			if($width>0)
				self::$width=$width;
			else
				self::$width=13*$length;
			self::$height=$height;
			self::$mod=$mod;
			self::$im=imagecreate(self::$width,self::$height);
			 $color1=imagecolorallocate(self::$im,rand(0,120),rand(0,255),rand(0,100));
			//后面设置是画笔的颜色
			$color2=imagecolorallocate(self::$im,255,255,rand(0,150));
			$color3=imagecolorallocate(self::$im,255,255,255);
			$code=self::random(self::$length,self::$mod);
			$_SESSION[$verifyName]=strtoupper($code);
			for($i=0;$i<strlen($code);$i++){
				imagestring(self::$im,15,10*$i+5,rand(2,8),substr($code,$i,1),$color2);
			}

			//绘制干扰像素
			for($i=0;$i<100;$i++){
				imagesetpixel(self::$im,rand(10,190),rand(5,45),$color3);
			}
			//输出图形
			imagejpeg(self::$im);
			//销毁图形
			imagedestroy(self::$im);
		}
		
		/*
			生成中文验证码
		*/
		public static function GBVerify($length=4,$width=0,$height=50,$verifyName='verify'){
			//$verifyName 验证码的SESSION记录名称，默认为verify
			header("Content-type: image/png"); 
			session_start();
			self::$length=$length;
			if($width>0)
				self::$width=$width;
			else
				self::$width=40*$length;
			self::$height=$height;
			self::$im=imagecreate(self::$width,self::$height);
			$color1=imagecolorallocate(self::$im,rand(0,120),rand(0,255),rand(0,100));
			//后面设置是画笔的颜色
			$color2=imagecolorallocate(self::$im,255,255,rand(0,150));
			$color3=imagecolorallocate(self::$im,255,255,255);
			$code=self::randomGB(self::$length);
			$_SESSION[$verifyName]=strtoupper($code);
	
			$font = './font/msyhbd.ttf';//字体文件,注意路径，最好使用绝对路径
			for($i=0;$i<mb_strlen($code,'utf8');$i++){
				$char=mb_substr($code,$i,1,'utf8');
				imagettftext(self::$im, 20, 0, 30*$i+30, rand(20,50), $color2, $font, $char);
			}
			//绘制干扰像素
			for($i=0;$i<100;$i++){
				imagesetpixel(self::$im,rand(10,190),rand(5,45),$color3);
			}
			//输出图形
			imagejpeg(self::$im);
			//销毁图形
			imagedestroy(self::$im);
		}

		private static function random($length,$mod) { 
			$hash = '';
			$chars='';
			switch($mod){
				case 0:$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';break;
				case 1:$chars='0123456789';break;
				case 2:$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ';break;
				case 3:$chars='abcdefghijklmnopqrstuvwxyz';break;
				default:$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';

			}
			
			$max = strlen($chars)-1; 
			for($i = 0; $i < $length; $i++) { 
				$hash .= $chars[mt_rand(0, $max)]; 
			} 
			return $hash; 
		} 
		
		private static function randomGB($length) { 
			$hash = ''; 
			$chars = '的一国在人了有中是年和大业不为发会工经上地市要个产这出行作生家以成到日民来我部对进多全建他公开们场展时理新方主企资实学报制政济用同于法高长现本月定化加动合品重关机分力自外者区能设后就等体下万元社过前面';
			$max = mb_strlen($chars,'utf8')-1;
			for($i = 0; $i < $length; $i++) { 
				$hash .= mb_substr($chars,mt_rand(0, $max),1,'utf8'); 
			} 
			return $hash; 
		} 

	}
	
?>