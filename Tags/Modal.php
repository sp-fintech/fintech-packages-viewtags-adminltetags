<?php

namespace Apps\Fintech\Packages\Adminltetags\Tags;

use Apps\Fintech\Packages\Adminltetags\Adminltetags;

class Modal extends Adminltetags
{
    protected $params;

    protected $content = '';

    protected $modalParams = [];

    public function getContent(array $params)
    {
        $this->params = $params;

        $this->generateContent();

        return $this->content;
    }

    protected function generateContent()
    {
        if (!isset($this->params['modalId'])) {
            $this->content .=
                '<span class="text-uppercase text-danger">modalId missing</span>';
            return;
        }

        $this->modalParams['modalId'] =
            $this->params['componentId'] . '-' . $this->params['sectionId'] . '-' . $this->params['modalId'];

        if (isset($this->params['modalTrigger'])) {
            if ($this->params['modalTrigger'] === 'button') {
                $this->useTag('buttons',
                    [
                        'componentId'           => $this->params['componentId'],
                        'sectionId'             => $this->params['sectionId'] . '-filter',
                        'buttonType'            => 'button',
                        'buttonLabel'           => false,
                        'buttons'               =>
                        [
                            'modalButton' =>
                            [
                                'modalButton'           => true,
                                'id'                    => $this->params['modalId'] . '-button',
                                'title'                 =>
                                    isset($this->params['modalButtonTitle']) ?
                                    $this->params['modalButtonTitle'] :
                                    'modalButtonTitle Missing',
                                'position'              =>
                                    isset($this->params['modalButtonPosition']) ?
                                    $this->params['modalButtonPosition'] :
                                    'left',
                                'size'                  =>
                                    isset($this->params['modalButtonSize']) ?
                                    $this->params['modalButtonSize'] :
                                    'xs',
                                'type'                  =>
                                    isset($this->params['modalButtonType']) ?
                                    $this->params['modalButtonType'] :
                                    'primary',
                                'style'                 =>
                                    isset($this->params['modalButtonStyle']) ?
                                    $this->params['modalButtonStyle'] :
                                    '',
                                'flat'                  =>
                                    isset($this->params['modalButtonFlat']) ?
                                    $this->params['modalButtonFlat'] :
                                    '',
                                'icon'                  =>
                                    isset($this->params['modalButtonIcon']) ?
                                    $this->params['modalButtonIcon'] :
                                    '',
                                'hidden'                =>
                                    isset($this->params['modalButtonHidden']) ?
                                    $this->params['modalButtonHidden'] :
                                    '',
                                'disabled'              =>
                                    isset($this->params['modalButtonDisabled']) ?
                                    $this->params['modalButtonDisabled'] :
                                    '',
                                'block'                 =>
                                    isset($this->params['modalButtonBlock']) ?
                                    $this->params['modalButtonBlock'] :
                                    '',
                                'buttonAdditionalClass' =>
                                    isset($this->params['modalButtonAdditionalClass']) ?
                                    $this->params['modalButtonAdditionalClass'] :
                                    ''
                            ]
                        ]
                    ]
                );
            } else if ($this->params['modalTrigger'] === 'link') {

                $this->modalParams['modalLinkAdditionalClass'] =
                    isset($this->params['modalLinkAdditionalClass']) ?
                    $this->params['modalLinkAdditionalClass'] :
                    '';

                $this->modalParams['modalLinkTitle'] =
                    isset($this->params['modalLinkTitle']) ?
                    $this->params['modalLinkTitle'] :
                    'modalLinkTitle Missing';

                $this->content .=
                    '<a href="#" class="' . $this->modalParams['modalLinkAdditionalClass'] . '" data-toggle="modal" data-target="#' . $this->params['modalId'] . '-modal">' . $this->modalParams['modalLinkTitle'] . '"</a>';
            }
        }

        $this->modalParams['modalAdditionalClasses'] =
            isset($this->params['modalAdditionalClasses']) ?
            $this->params['modalAdditionalClasses'] :
            '';

        $this->modalParams['modalContentAdditionalClasses'] =
            isset($this->params['modalContentAdditionalClasses']) ?
            $this->params['modalContentAdditionalClasses'] :
            '';

        $this->modalParams['modalBodyAdditionalClasses'] =
            isset($this->params['modalBodyAdditionalClasses']) ?
            $this->params['modalBodyAdditionalClasses'] :
            '';

        $this->modalParams['modalFooterAdditionalClasses'] =
            isset($this->params['modalFooterAdditionalClasses']) ?
            $this->params['modalFooterAdditionalClasses'] :
            '';

        $this->modalParams['modalButtonSecondaryTitle'] =
            isset($this->params['modalButtonSecondaryTitle']) ?
            $this->params['modalButtonSecondaryTitle'] :
            'CLOSE';

        $this->modalParams['modalScrollable'] =
            isset($this->params['modalScrollable']) ? 'modal-dialog-scrollable' : '';

        $this->modalParams['modalCentered'] =
            isset($this->params['modalCentered']) ? 'modal-dialog-centered' : '';

        $this->modalParams['modalSize'] =
            isset($this->params['modalSize']) ? 'modal-' . $this->params['modalSize'] : '';

        $this->modalParams['modalEscClose'] =
            isset($this->params['modalEscClose']) ?
            $this->params['modalEscClose'] :
            'false';

        if ($this->modalParams['modalEscClose'] === true) {
            $this->modalParams['modalEscClose'] = 'true';
        }

        $this->modalParams['modalType'] =
            isset($this->params['modalType']) ?
            $this->params['modalType'] :
            'primary';

        $this->modalParams['modalTitle'] =
            isset($this->params['modalTitle']) ?
            $this->params['modalTitle'] :
            'modalTitle missing';

        $this->modalParams['modalCloseButtonTitle'] =
            isset($this->params['modalCloseButtonTitle']) ?
            $this->params['modalCloseButtonTitle'] :
            'Close';

        $this->modalParams['modalCloseButtonSize'] =
            isset($this->params['modalCloseButtonSize']) ?
            $this->params['modalCloseButtonSize'] :
            'sm';

        if (isset($this->params['modalBodyContent'])) {

            $modalBody = $this->params['modalBodyContent'];

        } else if (isset($this->params['modalBodyInclude']) &&
                   isset($this->params['modalBodyIncludeParams'])
        ) {

            $modalBody =
                $this->view->getPartial(
                    $this->params['modalBodyInclude'],
                    $this->params['modalBodyIncludeParams']
                );
        } else if (isset($this->params['modalBodyInclude'])) {

            $modalBody =
                $this->view->getPartial(
                    $this->params['modalBodyInclude'],
                    $this->params
                );
        } else {
            $modalBody = 'modalBodyContent/Include missing';
        }

    // Modal
        $this->content .=
            '<div class="modal fade ' . $this->modalParams['modalAdditionalClasses'] . '" id="' . $this->modalParams['modalId'] . '" tabindex="-1" role="dialog" aria-labelledby="' . $this->modalParams['modalId'] . '-label" aria-hidden="true" data-backdrop="static" data-keyboard="' . $this->modalParams['modalEscClose'] . '">
                <div class="modal-dialog ' . $this->modalParams['modalScrollable'] . ' ' . $this->modalParams['modalCentered'] . ' ' . $this->modalParams['modalSize']. '" role="document">
                    <div class="modal-content rounded-0 ' . $this->modalParams['modalContentAdditionalClasses'] . '">';

                    if (isset($this->params['modalHeader']) && $this->params['modalHeader'] === true) {
                        $this->content .=
                            '<div class="modal-header bg-' . $this->modalParams['modalType'] .' rounded-0">
                                <h6 class="modal-title" id="' . $this->modalParams['modalId'] . '-label">' . $this->modalParams['modalTitle'] . '</h6>';

                        if (isset($this->params['modalHeaderCloseButton']) && $this->params['modalHeaderCloseButton'] === true) {
                            $this->content .=
                                '<button type="button" class="close modal-close text-white ' . $this->params['modalId'] . '-close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>';
                        }
                        $this->content .=
                            '</div>';
                    }

        $this->content .=
            '<div class="modal-body ' . $this->modalParams['modalBodyAdditionalClasses'] . '">' .
                $modalBody .
            '</div>';

        if (isset($this->params['modalFooter']) && $this->params['modalFooter'] === true) {

            $this->content .=
                '<div class="modal-footer ' . $this->modalParams['modalFooterAdditionalClasses'] . '">';

            if (isset($this->params['modalFooterButtons'])) {
            $this->content .=
                '<div class="mr-auto">';

                $this->content .=
                    $this->useTag(
                        'buttons',
                        $this->params['modalFooterButtons']
                    );
            $this->content .=
                '</div>';
            }
            $this->content .=
                '<button type="button" id="' . $this->modalParams['modalId'] . '-button-close" class="btn btn-secondary btn-' . $this->modalParams['modalCloseButtonSize'] . ' modal-close text-uppercase ' . $this->params['modalId'] . '-close" data-dismiss="modal">' . $this->modalParams['modalCloseButtonTitle'] . '</button>';

            $this->content .= '</div>';
        }

        $this->content .= '</div></div></div>';
    }
}