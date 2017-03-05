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
        $this->assertTrue(array_key_exists('argv', $_SERVER));
        $this->assertEquals($_SERVER['argv'][1], 'install');
        $this->assertEquals($console_service->request->handler, 'install');

        # Act
        # Note: to not freeze, must mock out InstallHandler to respond to user prompts.
        # See InstallHandlerTest for example.
        # If test makes it this far, then Issue 16 is resolved.
        $this->markTestIncomplete('Need to mock out handler user prompts.');
        $response = $console_service->serve();

        # Assert
        var_dump($response);
    }
}
