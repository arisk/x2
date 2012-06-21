<?php
App::uses('Metadata', 'Model');

/**
 * Metadata Test Case
 *
 */
class MetadataTest extends CakeTestCase {
/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array('app.metadata', 'app.photo', 'app.album', 'app.perm', 'app.user');

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Metadata = ClassRegistry::init('Metadata');
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

}
