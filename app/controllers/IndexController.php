<?php
declare(strict_types=1);

class IndexController extends ControllerBase
{

    public function indexAction()
    {

        $cssCollection = $this->assets->collection('cssColection');
        $cssCollection->addCss('css/index.css');

        $jsCollection = $this->assets->collection('jsColection');
        $jsCollection->addJs('js/ajax.js');
        $jsCollection->addJs('js/index.js');

        $ajaxCollection = $this->assets->collection('ajaxColection');
        $ajaxCollection->addJs('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js');

        $bootstrapCollection = $this->assets->collection('bootstrapColection');
        $bootstrapCollection->addJs('js/ajax.js');
        $bootstrapCollection->addCss('https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', false);
        $bootstrapCollection->addCss('https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', false);

        
    }

}

