<?php

namespace Bio\Tool;

class Log
{
	public static function h1()
	{
		$str = "#####################################\n"; 
		$fh = fopen(__LOG__, 'a');
		fwrite($fh, $str);
		fclose($fh);
		if(__DEBUG__) self::drop($str);

	}
	public static function info($text, $quite = false)
	{
		$str = self::text($text);
		$fh = fopen(__LOG__, 'a');
		fwrite($fh, $str);
		fclose($fh);
		if(__DEBUG__ && $quite == false) self::drop($str);
	}

	public static function error($text)
	{
		$str = sprintf("[%s]\tERROR: %s\n",date("Y.m.d - H:i:s"), $text);
		$fh = fopen(__LOG__, 'a');
		fwrite($fh, $str) ;
		fclose($fh);
		if(__DEBUG__) self::drop($str);
	}

	public static function state($text, $quite = false)
	{
		$str = self::arrow($text);
		$fh = fopen(__LOG__, 'a');
		fwrite($fh, $str);
		fclose($fh);
		if(__DEBUG__ && $quite == false) self::drop($str);
	}


	private static function text($text)
	{
		return sprintf("[%s]\t%s\n",date("Y.m.d - H:i:s"), $text);
	}

	private static function arrow($text)
	{
		return sprintf("[%s]\t-->\t%s\n",date("Y.m.d - H:i:s"), $text);
	}

	private static function drop($text)
	{
		print($text);
	}
}