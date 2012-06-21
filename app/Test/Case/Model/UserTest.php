<?php
App::uses('User', 'Model');

/**
 * User Test Case
 *
 */
class UserTestCase extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.user', 'app.comment', 'app.item', 'app.album', 'app.permission', 'app.album_story', 'app.watermark', 'app.location', 'app.country', 'app.coordinate', 'app.favorite', 'app.history', 'app.item_story', 'app.metadatum', 'app.rating', 'app.tag', 'app.items_tag');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->User);

		parent::tearDown();
	}

}
