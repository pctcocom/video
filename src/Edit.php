<?php
namespace Pctco\Video;
use Pctco\Verification\Regexp;
use FFMpeg\Format\Video\X264;
use FFMpeg\Format\Video\WMV;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\FFMpeg;
use Naucon\File\File;
class Edit{
   /**
   * @name __construct
   * @param mixed $VideoFile 视频文件
   **/
   function __construct($VideoFile,$SavePath){
      $regexp = new Regexp($VideoFile);
      $binaries = [
         // mac os
         'Darwin'   =>   [
            'ffmpeg.binaries'  => '/usr/local/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/local/bin/ffprobe'
         ]
      ];
      $this->ffmpeg = FFMpeg::create(array_merge($binaries[PHP_OS],[
         'timeout'          => 3600, // 底层进程的超时时间
         'ffmpeg.threads'   => 12,   // FFMpeg 应该使用的线程数
      ]));

      $video = app()->getRootPath().'entrance'.DIRECTORY_SEPARATOR.$VideoFile;
      if ($regexp->check('format.link.video')) $video = $VideoFile;

      $this->video =  $this->ffmpeg->open($video);

      $SavePath = app()->getRootPath().'entrance'.DIRECTORY_SEPARATOR.$SavePath;
      $file = new File(dirname($SavePath));
      if ($file->exists() === false) $file->mkdirs();
      $this->SavePath = $SavePath;
   }
   public function SaveLinkVideo(){
      $this->video->save(
         new X264(),
         $this->SavePath
      );
      return 'Save Link Video '.date('Y-m-d H:m:s',time());
   }
}
