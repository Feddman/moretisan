<?php

namespace Sven\Moretisan\Tests;

class ViewCreatorTest extends AbstractTestCase
{
    /** @test */
    public function it_creates_a_file()
    {
        $this->view->create('index');

        $this->assertTrue(
            file_exists(__DIR__ . '/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_creates_a_folder_when_using_dot_notation()
    {
        $this->view->create('pages.index');

        $this->assertTrue(
            file_exists(__DIR__ . '/assets/pages/index.blade.php')
        );
    }

    /** @test */
    public function it_adds_a_file_in_an_existing_folder()
    {
        $v1 = $this->getView();
        $v2 = $this->getView();

        $v1->create('pages.index');
        $v2->create('pages.about');

        $this->assertTrue(
            file_exists(__DIR__ . '/assets/pages/index.blade.php')
        );

        $this->assertTrue(
            file_exists(__DIR__ . '/assets/pages/about.blade.php')
        );
    }

    /** @test */
    public function it_extends_a_view()
    {
        $this->view->create('index')->extend('layout');

        $this->assertEquals(
            '@extends(\'layout\')' . PHP_EOL,
            file_get_contents(__DIR__ . '/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_creates_a_section()
    {
        $this->view->create('index')->section('content');

        $this->assertEquals(
            PHP_EOL . '@section(\'content\')' . PHP_EOL . PHP_EOL . '@endsection' . PHP_EOL,
            file_get_contents(__DIR__ . '/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_creates_multiple_sections()
    {
        $this->view->create('index')->sections(['content', 'scripts']);
        $this->view->create('about')->section('content')->section('scripts');

        $this->assertEquals(
            PHP_EOL . '@section(\'content\')' . PHP_EOL . PHP_EOL . '@endsection' . PHP_EOL .
            PHP_EOL . '@section(\'scripts\')' . PHP_EOL . PHP_EOL . '@endsection' . PHP_EOL,
            file_get_contents(__DIR__ . '/assets/index.blade.php')
        );

        $this->assertEquals(
            PHP_EOL . '@section(\'content\')' . PHP_EOL . PHP_EOL . '@endsection' . PHP_EOL .
            PHP_EOL . '@section(\'scripts\')' . PHP_EOL . PHP_EOL . '@endsection' . PHP_EOL,
            file_get_contents(__DIR__ . '/assets/about.blade.php')
        );
    }

    /** @test */
    public function it_extends_a_view_and_has_a_section()
    {
        $this->view->create('index')->extend('app')->section('content');

        $this->assertEquals(
            '@extends(\'app\')' . PHP_EOL .
            PHP_EOL . '@section(\'content\')' . PHP_EOL . PHP_EOL . '@endsection' . PHP_EOL,
            file_get_contents(__DIR__ . '/assets/index.blade.php')
        );
    }

    /** @test */
    public function it_allows_different_extensions()
    {
        $this->view->create('index', 'html');

        $this->assertTrue(
            file_exists(__DIR__ . '/assets/index.html')
        );
    }
}
