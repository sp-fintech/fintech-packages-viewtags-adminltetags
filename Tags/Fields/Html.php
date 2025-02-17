<?php

namespace Apps\Fintech\Packages\Adminltetags\Tags\Fields;

use Apps\Fintech\Packages\Adminltetags\Adminltetags;

class Html
{
    protected $view;

    protected $tag;

    protected $links;

    protected $escaper;

    protected $params;

    protected $fieldParams;

    protected $content;

    protected $adminLTETags;

    public function __construct($view, $tag, $links, $escaper, $params, $fieldParams)
    {
        $this->adminLTETags = new Adminltetags();

        $this->view = $view;

        $this->tag = $tag;

        $this->links = $links;

        $this->escaper = $escaper;

        $this->params = $params;

        $this->fieldParams = $fieldParams;

        $this->fieldParams['fieldHtmlContent'] =
            isset($this->params['fieldHtmlContent']) ?
            $this->params['fieldHtmlContent'] :
            '';

        $this->fieldParams['fieldHtmlAdditionalClass'] =
            isset($this->params['fieldHtmlAdditionalClass']) ?
            $this->params['fieldHtmlAdditionalClass'] :
            '';

        $this->generateContent();
    }

    public function getContent()
    {
        return $this->content;
    }

    protected function generateContent()
    {
        $this->content .=
            '<div ' . $this->fieldParams['fieldBazPostOnCreate'] . ' ' . $this->fieldParams['fieldBazPostOnUpdate'] . ' ' . $this->fieldParams['fieldBazScan'] . ' data-type="html" class="' . $this->fieldParams['fieldHtmlAdditionalClass'] . '" ' . $this->fieldParams['fieldDataAttributes'] . ' ' . $this->fieldParams['fieldId'] . '">' .
                $this->fieldParams['fieldHtmlContent'] .
            '</div>';
    }
}