<?php
/**
 * test/libs/TemplaterTest.php
 *
 * Usage (run from Neechy root dir):
 * > phpunit test/libs/TemplaterTest
 *
 */
require_once('../core/libs/templater.php');


class NeechyTemplaterTest extends PHPUnit_Framework_TestCase {

    /**
     * Test Fixtures
     */
    public function setUp() {
        $this->templater = new NeechyTemplater();
    }

    public function tearDown() {
        $_SERVER = array();
        $this->templater = null;
    }

    /**
     * Tests
     */
    public function testLink() {
        $link = $this->templater->link('http://github.com/', 'github');
        $this->assertEquals('<a href="http://github.com/">github</a>', $link);

        $link = $this->templater->link('/', 'home', array(
            'title' => 'go home',
            'class' => 'link',
            'id' => 'home-link'
        ));
        $expect = '<a href="/" title="go home" class="link" id="home-link">home</a>';
        $this->assertEquals($expect, $link);
    }

    public function testSetLayoutTokens() {
        $tokens = array(
            'head' => '<title>Neechy</title>',
            'top' => '<h1>Neechy</h1>',
            'middle' => 'middle',
            'bottom' => '<footer>bottom</footer>'
        );

        $templater = new NeechyTemplater('no-theme');

        foreach ( $tokens as $token => $content ) {
            $templater->set($token, $content);
        }

        $output = $templater->render();

        foreach ( $tokens as $token => $content ) {
            $this->assertContains($content, $output);
        }
    }

    public function testInstantiates() {
        $this->assertInstanceOf('NeechyTemplater', $this->templater);
    }
}