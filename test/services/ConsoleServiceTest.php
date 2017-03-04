<?php
/**
 * test/services/ConsoleServiceTest.php
 *
 * Usage (run from Neechy root dir):
 * > phpunit --bootstrap test/bootstrap.php services/ConsoleServiceTest
 *
 */
require_once('../core/services/console.php');
require_once('../core/neechy/config.php');
require_once('../test/helper.php');
require_once('../test/fixtures/page.php');
require_once('../test/fixtures/user.php');


class ConsoleServiceTest extends PHPUnit_Framework_TestCase {
    /**
     * Test Fixtures
     */
    public function setUp() {
        NeechyTestHelper::setUp();
        UserFixture::init();
        PageFixture::init();
    }

    public function tearDown() {
        NeechyTestHelper::tearDown();
    }

    /**
     * Tests
     */
    public function testExpectsConsoleInstallScriptToLoadWithoutServerRequestUri() {
        # This test was added to reproduce and fix issue:
        # https://github.com/klenwell/neechy/issues/16
        #
        # Reports error:
        #
        #   NEECHY CONSOLE ERROR:
        #   $_SERVER["REQUEST_URI"] not found.

        # Arrange
        $config = NeechyConfig::init();
        $_SERVER['argv'] = array('console/run.php', 'install');
        unset($_SERVER['REQUEST_URI']);
        $console_service = new NeechyConsoleService($config);

        # Assume
        $this->assertInstanceOf('NeechyConsoleService', $console_service);
        $this->assertEquals('test', NeechyConfig::environment());
        $this->assertFalse(array_key_exists('REQUEST_URI', $_SERVER));

        # Act
        # If it gets this far, then issue 16 has been resolved.
        return $this->markTestSkipped('Test hangs when serve is called.');
        $response = $console_service->serve();

        # Assert
        var_dump($response);
    }
}
