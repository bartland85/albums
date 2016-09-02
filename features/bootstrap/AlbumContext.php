<?php
/**
 * Created by PhpStorm.
 * User: bartek
 * Date: 02.09.16
 * Time: 11:11
 */

use PHPUnit_Framework_Assert as Assertion;

class AlbumContext extends FeatureContext {

    private $frontpage = 'http://localhost:1234';

    private $albumId = null;

    /**
     * @Given I want to open the frontpage
     */

    public function __construct()
    {
        parent::__construct();

    }

    public function iWantToOpenTheFrontpage()
    {
        $this->sessionStart();
    }

    /**
     * @When I visit the frontpage
     */
    public function iVisitTheFrontpage()
    {

        $this->getSession()->visit($this->frontpage);
    }

    /**
     * @Then I see table with albums
     */
    public function iSeeTableWithAlbums()
    {
        $content = $this->getSession()->getPage();

        Assertion::assertTrue($content->hasContent('My albums'));
        Assertion::assertTrue($content->hasContent('Title'));
        Assertion::assertTrue($content->hasContent('Artist'));

    }


    /**
     * @When I click edit link of album ID :arg1
     */
    public function iClickEditLinkOfAlbumId($arg1)
    {
        $this->albumId = $arg1;

        $page = $this->getSession()->getPage();

        $editButton = $page->find('named', ['link', 'edit_link_' .  $this->albumId]);

        $editButton->press();
    }


    /**
     * @Then I visit edit page of album ID :arg1
     */
    public function iVisitEditPageOfAlbumId()
    {
        Assertion::assertSame($this->getSession()->getCurrentUrl(), 'http://localhost:1234/album/edit/' . $this->albumId);
    }

    /**
     * @Then The form contains title field with :arg2 value
     */
    public function theFormContainsTitleFieldWithValue($arg1)
    {
        $page = $this->getSession()->getPage();

        Assertion::assertSame($page->find('named', ['field', 'title'])->getValue(), $arg1);

    }

    /**
     * @Then The form contains artist field with :arg1 value
     */
    public function theFormContainsArtistFieldWithValue($arg1)
    {
        $page = $this->getSession()->getPage();

        Assertion::assertSame($page->find('named', ['field', 'artist'])->getValue(), $arg1);
    }


    /**
     * @Given I visit the add new album page
     */
    public function iVisitTheAddNewAlbumPage()
    {
        $this->getSession()->visit('http://localhost:1234/album/add');
    }

    /**
     * @When I input :arg1 into title field
     */
    public function iInputIntoTitleField($arg1)
    {
        $page = $this->getSession()->getPage();
        $titleField = $page->find('named', ['field', 'title']);
        $titleField->setValue($arg1);
    }

    /**
     * @When I input :arg1 into artist field
     */
    public function iInputIntoArtistField($arg1)
    {
        $page = $this->getSession()->getPage();
        $titleField = $page->find('named', ['field', 'artist']);
        $titleField->setValue($arg1);
    }

    /**
     * @When I click :arg1 button
     */
    public function iClickButton($arg1)
    {
        $page = $this->getSession()->getPage();
        $page->pressButton($arg1);
    }

    /**
     * @Then New position with artist: :arg1, title: :arg2 is present on the list
     */
    public function newPositionWithArtistTitleIsPresentOnTheList($arg1, $arg2)
    {
        $content = $this->getSession()->getPage();

        Assertion::assertTrue($content->hasContent($arg1));
        Assertion::assertTrue($content->hasContent($arg2));
    }



}