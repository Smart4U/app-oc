<?php


namespace App\Core\Twig;

use Riimu\Kit\CSRF\CSRFHandler;


/**
 * Class CSRFTwigExtension
 * @package App\Core\Twig
 */
class CSRFTwigExtension extends \Twig_Extension{


    private $csrfHandler;

    /**
     * CSRFTwigExtension constructor.
     * @param CSRFHandler $CSRFHandler
     */
    public function __construct(CSRFHandler $CSRFHandler)
    {
        $this->csrfHandler = $CSRFHandler;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('csrf', [$this, 'csrf'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @return string
     */
    public function csrf() {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($this->csrfHandler->getToken(), ENT_QUOTES | ENT_HTML5, 'UTF-8') . '" />';
    }

}