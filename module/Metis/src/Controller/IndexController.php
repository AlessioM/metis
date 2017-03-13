<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Metis\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Metis\Entities\Person;

/**
* controller that displays actual content
*/
class IndexController extends AbstractActionController
{
     /**
      * Entity manager.
      * @var Doctrine\ORM\EntityManager
      */
     private $em;

     public function __construct(\Doctrine\ORM\EntityManager $entityManager, \Zend\ServiceManager\ServiceManager $sm)
     {
        $this->em = $entityManager;
        $this->config = $sm->get('config');
     }

     /**
     * given one mark down file get view config for this element
     * @var string $filename name of markdown file
     * assumes $filename exists
     */
     protected function getTextElement($filename)
     {
       return [[
           'id' => $this->getFileID($filename),
           'type' => 'text',
           'downloads' => [],
           'images' => [],
           'text' =>  \Parsedown::instance()->setBreaksEnabled(true)->text(file_get_contents($filename))
         ], []];
     }

     /**
     * returns a "random" but unique ID for a filename
     * @var string $filename name of file
     * assumes $filename exists
     */
     protected function getFileID($filename)
     {
       //actual function doesn't really matter as long as it returns always the same id
       return md5(realpath($filename));
     }

     /**
     * converts number of bytes to human readable format
     */
     protected function human_filesize($bytes, $decimals = 2)
     {
       $sz = 'BKMGTP';
       $factor = floor((strlen($bytes) - 1) / 3);
       return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor] . 'B';
     }

     /**
     * given a directory returns the view config
     *    concatenates all markdown files
     *    adds all images to image carousel (png and jpg)
     *    adds download links for all other files
     */
     protected function getDirElement($data_dir)
     {
       $scanned_directory = array_diff(scandir($data_dir), ['..', '.']);

       $text = [];
       $images = [];
       $downloads = [];
       $files = [];

       foreach ($scanned_directory as $filename) {
         $filename = $data_dir . '/' . $filename;
         $fileinfo = pathinfo($filename);

         //don't allow recursion
         if(!is_file($filename)) {
           continue;
         }

         if($fileinfo['extension'] == 'md') {
           $text[] = \Parsedown::instance()->setBreaksEnabled(true)->text(file_get_contents($filename));
         } elseif(in_array($fileinfo['extension'], ['png', 'jpg'])) {
           $id = $this->getFileID($filename);
           $images[] = $id;
           $files[$id] = $filename;
         } else {
           $id = $this->getFileID($filename);
           $files[$id] = $filename;
           $downloads[] = [
             'name' => basename($filename),
             'id' => $id,
             'size' => $this->human_filesize(filesize($filename))
           ];
         }

       }

       //we use different templates depending if there is only text, one image or more images
       $type = 'text';
       if(count($images) == 1) {
         $type = 'single';
       } elseif(count($images) > 1) {
         $type = 'multiple';
       }

       return [[
         'id' => $this->getFileID($data_dir),
         'type' =>  $type,
         'images' => $images,
         'downloads' => $downloads,
         'text' => implode('',$text)
       ], $files];
     }

     /**
     * parses the data directory specified in the config and returns the
     * data for the view
     */
     protected function getElements($data_dir)
     {
       $elements = [];
       $files = [];
       $scanned_directory = array_diff(scandir($data_dir), array('..', '.'));
       foreach ($scanned_directory as $filename) {
         $filename = $data_dir . '/' . $filename;
         $fileinfo = pathinfo($filename);
         if(is_file($filename) && $fileinfo['extension'] == 'md') {
           $elements[] = $this->getTextElement($filename)[0];
         } elseif(is_dir($filename)) {
           $e = $this->getDirElement($filename);
           $elements[] = $e[0];
           $files = array_merge($files, $e[1]);
         }
       }

       return [$elements, $files];
     }


     /**
     * sends the requested file to the browser
     * only sends files in the data dir (from config) and its sub directories
     */
     public function downloadAction()
     {
       $data_dir = $this->config['metis']['data_dir'];
       $files = $this->getElements($data_dir)[1];

       $fileID = $this->params()->fromRoute('id');

       if(!isset($files[$fileID])) {
         //throw 404
         $this->getResponse()->setStatusCode(404);
         return;
       } else {
         $filename = $files[$fileID];
         $response = new \Zend\Http\Response\Stream();
         $response->setStream(fopen($filename, 'r'));
         $response->setStatusCode(200);

         $headers = new \Zend\Http\Headers();
         $headers->addHeaderLine('Content-Type', mime_content_type($filename))
               ->addHeaderLine('Content-Disposition', 'attachment; filename="' . basename($filename) . '"')
               ->addHeaderLine('Content-Length', filesize($filename));

         $response->setHeaders($headers);
         return $response;
       }
     }

     /**
     * renders all elements in the given data dir
     */
     public function indexAction()
     {
       $data_dir = $this->config['metis']['data_dir'];
       $elements = $this->getElements($data_dir)[0];
       return ["elements" => $elements];
     }
}
