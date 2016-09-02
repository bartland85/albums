<?php
/**
 * Created by PhpStorm.
 * User: bartek
 * Date: 30.08.16
 * Time: 18:15
 */

namespace AlbumTest\Controller;

use Album\Controller\AlbumController;
use Prophecy\Argument;
use Zend\Stdlib\ArrayUtils;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Album\Model\AlbumTable;
use Zend\ServiceManager\ServiceManager;
use Album\Model\Album;


class AlbumControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    protected $albumTable;

    public function setUp()
    {
        // The module configuration should still be applicable for tests.
        // You can override configuration here with test case specific values,
        // such as sample view templates, path stacks, module_listener_options,
        // etc.
        $configOverrides = [];

        $this->setApplicationConfig(ArrayUtils::merge(
        // Grabbing the full application configuration:
            include __DIR__ . '/../../../../config/application.config.php',
            $configOverrides
        ));
        parent::setUp();

        $this->configureServiceManager($this->getApplicationServiceLocator());
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->albumTable->fetchAll()->willReturn([]);

        $this->dispatch('/album', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Album');
        $this->assertControllerName(AlbumController::class);
        $this->assertControllerClass('AlbumController');
        $this->assertMatchedRouteName('album');
    }

    protected function configureServiceManager(ServiceManager $services)
    {
        $services->setAllowOverride(true);

        $services->setService('config', $this->updateConfig($services->get('config')));
        $services->setService(AlbumTable::class, $this->mockAlbumTable()->reveal());

        $services->setAllowOverride(false);
    }

    protected function updateConfig($config)
    {
        $config['db'] = [];
        return $config;
    }

    protected function mockAlbumTable()
    {
        $this->albumTable = $this->prophesize(AlbumTable::class);
        return $this->albumTable;
    }

    public function testAddActionRedirectsAfterValidPost()
    {
        $this->albumTable
            ->saveAlbum(Argument::type(Album::class))
            ->shouldBeCalled();

        $postData = [
            'title'  => 'Led Zeppelin III',
            'artist' => 'Led Zeppelin',
            'id'     => ''
        ];

        $this->dispatch('/album/add', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        $this->assertRedirectTo('/album');
    }

    public function testGetRequestToAddActionShowsEmptyForm()
    {
        $this->dispatch('/album/add', 'GET');
        $this->assertResponseStatusCode(200);
        $this->assertQueryCount('form[name="album"]', 1);
        $this->assertQueryCount('input[name="title"]', 1);
        $this->assertQueryCount('input[name="artist"]', 1);
        $this->assertQueryCount('input[name="submit"]', 1);

    }

    /**
     * @dataProvider emptyFormData
     */

    public function testCannotAddAlbumWithEmptyData($title, $artist)
    {
        $this->dispatch('/album/add', 'POST', ['title'=>$title, 'artist'=>$artist]);
        $this->assertResponseStatusCode(200);
        $this->assertQueryContentContains('li', 'Value is required and can\'t be empty');
    }

    public function emptyFormData()
    {
        return [
            ['title'=>'', 'artist'=>'Test'],
            ['title'=>'Test', 'artist'=>'']
        ];
    }

    public function testEditActionWithNoIDRedirectsToAddForm()
    {
        $this->dispatch('/album/edit', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertResponseHeaderContains('Location' , '/album/add');
    }

    public function testDeleteActionWithNoIDRedirectsToHome()
    {
        $this->dispatch('/album/delete', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertControllerName(AlbumController::class);
        $this->assertResponseHeaderContains('Location' , '/album');
    }

    public function testNonExistingIDInEditRouteRedirectsToHome()
    {
        $this->dispatch('/album/edit/180', 'GET');
        $this->assertResponseStatusCode(302);
        $this->assertControllerName(AlbumController::class);
        $this->assertResponseHeaderContains('Location' , '/album');
    }
}