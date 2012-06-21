<?php
App::uses('MetadataController', 'Controller');

/**
 * TestMetadataController *
 */
class TestMetadataController extends MetadataController {
/**
 * Auto render
 *
 * @var boolean
 */
	public $autoRender = false;

/**
 * Redirect action
 *
 * @param mixed $url
 * @param mixed $status
 * @param boolean $exit
 * @return void
 */
	public function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

/**
 * MetadataController Test Case
 *
 */
class MetadataControllerTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.metadatum', 'app.item', 'app.album', 'app.permission', 'app.album_story', 'app.watermark', 'app.location', 'app.country', 'app.comment', 'app.user', 'app.favorite', 'app.history', 'app.coordinate', 'app.item_story', 'app.rating', 'app.tag', 'app.items_tag');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Metadata = new TestMetadataController();
		$this->Metadata->constructClasses();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Metadata);

		parent::tearDown();
	}

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {

	}
/**
 * testView method
 *
 * @return void
 */
	public function testView() {

	}
/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {

	}
/**
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {

	}
/**
 * testDelete method
 *
 * @return void
 */
	public function testDelete() {

	}
}
