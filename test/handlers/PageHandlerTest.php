<?php
/**
 * test/handlers/PageHandlerTest.php
 *
 * Usage (run from Neechy root dir):
 * > phpunit --bootstrap test/bootstrap.php handlers/PageHandlerTest
 *
 */
require_once('../core/handlers/page/handler.php');
require_once('../core/neechy/request.php');
require_once('../test/helper.php');
require_once('../test/fixtures/page.php');
require_once('../test/fixtures/user.php');


class PageHandlerTest extends PHPUnit_Framework_TestCase {

    /**
     * Test Fixtures
     */
    public function setUp() {
        NeechyTestHelper::setUp();
        UserFixture::init();
        PageFixture::init();
        $_SESSION['csrf_token'] = 'foo';
    }

    public function tearDown() {
        NeechyTestHelper::tearDown();
        $_SESSION['csrf_token'] = null;
    }

    /**
     * Tests
     */
    public function testInstantiates() {
        $request = new NeechyRequest();
        $handler = new PageHandler($request);
        $this->assertInstanceOf('PageHandler', $handler);
        $this->assertInstanceOf('NeechyHandler', $handler);
    }

    public function testShouldDisplayPage() {
        # Arrange
        $_SERVER['REQUEST_URI'] = '/page/NeechyPage';
        $request = new NeechyRequest();
        $handler = new PageHandler($request);

        # Assume
        $this->assertEquals($handler->request->route, '/page/NeechyPage');
        $this->assertEquals($handler->request->handler, 'page');

        # Act
        $response = $handler->handle();
        $page = Page::find_by_title('NeechyPage');

        # Assert
        $this->assertEquals(200, $response->status);
        $this->assertContains('<div class="tab-pane page active" id="read">',
                              $response->body);
        $this->assertContains($page->body_to_html(), $response->body);
    }
}
