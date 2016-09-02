<?php


use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use PHPUnit_Framework_Assert as Assertion;
use Behat\Mink\Session;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    protected $session;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->sessionStart();
    }

    protected function sessionStart()
    {
        if (!$this->session) {
            try{
                $this->session = new Session(new \Behat\Mink\Driver\GoutteDriver());
            } catch(Exception $e) {
                echo $e->getMessage();
            }
        }

        $this->session->start();
    }

    /**
     * @return \Behat\Mink\Session
     * @throws Exception
     */

    public function getSession()
    {
        if (!$this->session) {
            throw new Exception('No session started.');
        }

        return $this->session;
    }

    /**
     * @Then The page displays
     */

    public function pageDisplays()
    {
        Assertion::assertSame($this->getSession()->getStatusCode(), 200);
    }


}
