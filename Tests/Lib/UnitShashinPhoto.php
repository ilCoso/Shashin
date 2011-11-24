<?php

require_once(dirname(__FILE__) . '/../../toppa-plugin-libraries-for-wordpress/ToppaDatabaseFacadeWp.php');
require_once(dirname(__FILE__) . '/../Lib/ShashinPhoto.php');
Mock::generate('ToppaDatabaseFacadeWp');

class UnitLib_ShashinPhoto extends UnitTestCase {
    private $dbFacade;
    private $samplePhotoData;

    public function __construct() {
        $this->UnitTestCase();
    }

    public function setUp() {
        $this->samplePhotoData = array(
            'id' => 1,
            'photoId' => 5590273098322362706,
            'albumId' => 2,
            'filename' => 'IMG_0360.JPG',
            'description' => 'Kai is not so sure about his new friend',
            'linkUrl' => 'https://picasaweb.google.com/michaeltoppa/2011Honolulu',
            'contentUrl' => 'https://lh5.googleusercontent.com/_e1IlgcNcTSg/TZSjw67tQVI/AAAAAAAAIik/LI3EeUEGJYs/IMG_0360.JPG',
            'contentType' => 'image/jpeg',
            'width' => 1024,
            'height' => 768,
            'takenTimestamp' => 1301524665,
            'uploadedTimestamp' => 1301586883,
            'tags' => null,
            'lastSync' => 1304249789,
            'includeInRandom' => 'Y',
            'sourceOrder' => 1,
            'fstop' => 3.2,
            'make' => 'Canon',
            'model' => 'Canon PowerShot SD78',
            'exposure' => 0.0125,
            'focalLength' => 5.9,
            'iso' => 100
        );

        $this->dbFacade = new MockToppaDatabaseFacadeWp();
        $this->dbFacade->setReturnValue('sqlSelectRow', $this->samplePhotoData);
        $this->dbFacade->setReturnValue('getTableNamePrefix', 'wp_');
        $this->dbFacade->setReturnValue('sqlInsert', 1);
        $this->dbFacade->setReturnValue('sqlDelete', true);
    }

    public function testGetRefData() {
        $photo = new Lib_ShashinPhoto($this->dbFacade);
        $refData = $photo->getRefData();
        $this->assertTrue(is_array($refData));
        $this->assertFalse(empty($refData));
    }

    public function testGetTableName() {
        $photo = new Lib_ShashinPhoto($this->dbFacade);
        $this->assertEqual($photo->getTableName(), 'wp_shashin_photo_3alpha');
    }

    public function testMagicSetAndGetWithValidProperty() {
        $photo = new Lib_ShashinPhoto($this->dbFacade);
        $photo->filename = 'test filename';
        $this->assertEqual($photo->filename, 'test filename');
    }

    public function testMagicGetWithInvalidProperty() {
        try {
            $photo = new Lib_ShashinPhoto($this->dbFacade);
            $photo->foobar;
            $this->fail("Exception was expected - invalid __get call");
         }

         catch (Exception $e) {
             $this->pass("received expected exception - invalid __get call");
         }
    }

    public function testMagicSetWithInvalidProperty() {
        try {
            $photo = new Lib_ShashinPhoto($this->dbFacade);
            $photo->foobar = 'test foobar';
            $this->fail("Exception was expected - invalid __set call");
         }

         catch (Exception $e) {
             $this->pass("received expected exception - invalid __set call");
         }
    }

    public function testGetPhoto() {
        $photo = new Lib_ShashinPhoto($this->dbFacade);
        $photoData = $photo->get(1);
        $this->assertEqual($photo->filename, $this->samplePhotoData['filename']);
        $this->assertEqual($photo->width, $this->samplePhotoData['width']);
        $this->assertEqual($this->samplePhotoData, $photoData);
    }

    public function testRefreshPhotoUsingInvalidKey() {
        try {
            $photo = new Lib_ShashinPhoto($this->dbFacade);
            $photo->refresh('hello');
            $this->fail("Exception was expected - invalid test case");
        }

        catch (Exception $e) {
            $this->pass("received expected invalid test case");
        }
    }

    public function testUpdateOfPhotoFields() {
        $originalFields = array('title' => 'old title', 'description' => 'test description');
        $revisedFields = array('title' => 'new title');
        $expectedFinalFields = array('title' => 'new title', 'description' => 'test description');
        $photo = new Lib_ShashinPhoto($this->dbFacade);
        $photo->set($originalFields);
        $photo->set($revisedFields);
        $photoData = $photo->getData();
        $this->assertEqual($expectedFinalFields['filename'], $photo->filename);
        $this->assertEqual($expectedFinalFields['description'], $photo->description);
        $this->assertEqual($expectedFinalFields, $photoData);
    }

    public function testDeletePhoto() {
        $photo = new Lib_ShashinPhoto($this->dbFacade);
        $photo->get(1);
        $photoFilename = $photo->filename;
        $photoData = $photo->delete();
        $this->assertEqual($photoData['filename'], $photoFilename);

        try {
            $photo->filename;
            $this->fail("Exception was expected - invalid test case");
        }

        catch (Exception $e) {
            $this->pass("received expected invalid test case");
        }
    }

    public function testIsVideo() {
        $photo = new Lib_ShashinPhoto($this->dbFacade);
        $photo->get(1);
        $this->assertFalse($photo->isVideo());
    }
}