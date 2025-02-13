<?php

namespace Apps\Fintech\Packages\Adminltetags\Tags;

use Apps\Fintech\Packages\Adminltetags\Adminltetags;

class Notes extends Adminltetags
{
    protected $params;

    protected $notesParams;

    protected $notesPaginationCounters;

    protected $content = '';

    public function getContent(array $params)
    {
        $this->params = $params;

        $this->notesParams = [];

        $this->notesSettings = $this->basepackages->notes->getNotesSettings();

        $this->compSecId = $this->params['componentId'] . '-' . $this->params['sectionId'];

        $this->buildLayout();

        return $this->content;
    }

    protected function buildLayout()
    {
        if (!isset($this->params['packageName'])) {
            throw new \Exception('Error: packageName parameter missing');
        }

        if (!isset($this->params['notes'])) {
            throw new \Exception('Error: notes (array) missing');
        }

        $this->notesParams['thumbnailSize'] =
            isset($this->notesSettings['thumbnailSize']) ?
            $this->notesSettings['thumbnailSize'] :
            80;

        $this->notesParams['lightboxSize'] =
            isset($this->notesSettings['lightboxSize']) ?
            $this->notesSettings['lightboxSize'] :
            1200;

        if (isset($this->params['allowedUploads'])) {
            $this->notesParams['allowedUploads'] = $this->params['allowedUploads'];
        } else if (isset($this->notesSettings['allowedUploads'])) {
            $this->notesParams['allowedUploads'] = $this->notesSettings['allowedUploads'];
        } else {
            $this->notesParams['allowedUploads'] = true;
        }

        $this->useStorage($this->notesSettings['useStorage']);

        if ($this->params['notes'] && count($this->params['notes']) > 0) {
            $formHidden = 'hidden';
            $icon = 'eye';
        } else {
            $formHidden = '';
            $icon = 'eye-slash';
        }

        if (!isset($this->params['onlyHistory'])) {
            $this->content .=
            '<div id="notes-form" class="row vdivide" ' . $formHidden . '>
                <div class="col">' .
                    '<div class="row">
                        <div class="col">' .
                            $this->useTag('fields',
                                [
                                    'component'                             => $this->params['component'],
                                    'componentName'                         => $this->params['componentName'],
                                    'componentId'                           => $this->params['componentId'],
                                    'sectionId'                             => $this->params['sectionId'],
                                    'fieldId'                               => 'note_type',
                                    'fieldLabel'                            => 'Note Type',
                                    'fieldType'                             => 'select2',
                                    'fieldHelp'                             => true,
                                    'fieldHelpTooltipContent'               => 'Select Note Type',
                                    'fieldRequired'                         => false,
                                    'fieldBazScan'                          => true,
                                    'fieldBazJstreeSearch'                  => true,
                                    'fieldBazPostOnCreate'                  => true,
                                    'fieldBazPostOnUpdate'                  => true,
                                    'fieldDataSelect2Options'               => $this->notesSettings['noteTypes'],
                                    'fieldDataSelect2OptionsKey'            => 'id',
                                    'fieldDataSelect2OptionsValue'          => 'name',
                                    'fieldDataSelect2OptionsArray'          => true,
                                    'fieldDataSelect2OptionsSelected'       => '1'
                                ]
                            ) . '
                        </div>
                        <div class="col">' .
                            $this->useTag('fields',
                                [
                                    'component'                             => $this->params['component'],
                                    'componentName'                         => $this->params['componentName'],
                                    'componentId'                           => $this->params['componentId'],
                                    'sectionId'                             => $this->params['sectionId'],
                                    'fieldId'                               => 'note_app_visibility',
                                    'fieldLabel'                            => 'App Visibility',
                                    'fieldType'                             => 'select2',
                                    'fieldHelp'                             => true,
                                    'fieldHelpTooltipContent'               => 'Select app visibility. Visible on all apps if none selected.',
                                    'fieldRequired'                         => false,
                                    'fieldBazScan'                          => true,
                                    'fieldBazJstreeSearch'                  => true,
                                    'fieldBazPostOnCreate'                  => true,
                                    'fieldBazPostOnUpdate'                  => true,
                                    'fieldDataSelect2Multiple'              => true,
                                    'fieldDataSelect2Options'               => $this->apps->apps,
                                    'fieldDataSelect2OptionsKey'            => 'id',
                                    'fieldDataSelect2OptionsValue'          => 'name',
                                    'fieldDataSelect2OptionsArray'          => true,
                                    'fieldDataSelect2OptionsSelected'       => ''
                                ]
                            ) . '
                        </div>
                        <div class="col">' .
                            $this->useTag('fields',
                                [
                                    'component'                             => $this->params['component'],
                                    'componentName'                         => $this->params['componentName'],
                                    'componentId'                           => $this->params['componentId'],
                                    'sectionId'                             => $this->params['sectionId'],
                                    'fieldId'                               => 'is_private',
                                    'fieldLabel'                            => 'Is Private',
                                    'fieldType'                             => 'checkbox',
                                    'fieldHelp'                             => true,
                                    'fieldHelpTooltipContent'               => 'Only you can access the note.',
                                    'fieldRequired'                         => false,
                                    'fieldBazScan'                          => true,
                                    'fieldBazJstreeSearch'                  => true,
                                    'fieldBazPostOnCreate'                  => true,
                                    'fieldBazPostOnUpdate'                  => true
                                ]
                            ) . '
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">' .
                            $this->useTag('fields',
                                [
                                    'component'                             => $this->params['component'],
                                    'componentName'                         => $this->params['componentName'],
                                    'componentId'                           => $this->params['componentId'],
                                    'sectionId'                             => $this->params['sectionId'],
                                    'fieldId'                               => 'note',
                                    'fieldLabel'                            => 'Note',
                                    'fieldType'                             => 'trumbowyg',
                                    'fieldHelp'                             => true,
                                    'fieldDisabled'                         => false,
                                    'fieldHelpTooltipContent'               => 'Note content',
                                    'fieldRequired'                         => false,
                                    'fieldBazScan'                          => true,
                                    'fieldBazJstreeSearch'                  => true,
                                    'fieldBazPostOnCreate'                  => true,
                                    'fieldBazPostOnUpdate'                  => true
                                ]
                            ) . '
                        </div>
                    </div>';

                    //Uploads
                    if (isset($this->notesParams['allowedUploads']) && $this->notesParams['allowedUploads'] === true) {
                        $this->content .=
                            '</div>
                            <div class="col-md-4">' .
                                $this->useTag('fields',
                                    [
                                        'component'                     => $this->params['component'],
                                        'componentName'                 => $this->params['componentName'],
                                        'componentId'                   => $this->params['componentId'],
                                        'sectionId'                     => $this->params['sectionId'],
                                        'fieldId'                       => 'note_attachments',
                                        'fieldType'                     => 'files/dropzone',
                                        'fieldLabel'                    => false,
                                        'fieldDropzoneLabel'            => 'Note Attachments',
                                        'fieldRequired'                 => false,
                                        'fieldHelp'                     => true,
                                        'fieldHelpTooltipContent'       => 'Max: 5 attachments.',
                                        'fieldBazScan'                  => true,
                                        'fieldBazJstreeSearch'          => true,
                                        'fieldBazPostOnCreate'          => true,
                                        'fieldBazPostOnUpdate'          => true,
                                        'maxAttachments'                => 5,
                                        'storage'                       => $this->packagesData->storage,
                                        'upload'                        => true,
                                        'attachments'                   => []
                                     ]
                                ) .
                            '</div>
                        </div>';
                    } else {
                        $this->content .=
                            '</div>
                        </div>';
                    }

                    $this->content .=
                        '<div class="row">
                            <div class="col">' .
                                $this->useTag('buttons',
                                    [
                                        'component'                     => $this->params['component'],
                                        'componentName'                 => $this->params['componentName'],
                                        'componentId'                   => $this->params['componentId'],
                                        'sectionId'                     => $this->params['sectionId'],
                                        'buttonType'                    => 'button',
                                        'buttons'                       =>
                                            [
                                                'show-hide'             => [
                                                    'title'             => 'Form',
                                                    'size'              => 'xs',
                                                    'type'              => 'info',
                                                    'icon'              => $icon
                                                ]
                                            ]
                                    ]
                                ) .
                            '</div>
                        </div><hr>';
        }

        if (count($this->params['notes']) > 0) {
            $this->generateNotesContent();
        }

        if (isset($this->params['notes']['paginationCounters'])) {
            $this->notesPaginationCounters = $this->helper->encode($this->params['notes']['paginationCounters']);
        } else {
            $this->notesPaginationCounters = $this->helper->encode([]);
        }

        $this->content .= $this->inclNotesJs();
    }

    protected function generateNotesContent()
    {
        $this->content .=
            '<div class="row">
                <div id="notes-logs" class="col">';

        if (isset($this->params['notes']['paginationCounters'])) {
            if ($this->params['notes']['paginationCounters']['first'] === $this->params['notes']['paginationCounters']['current']) {
                $start = 1;
                $to = $this->params['notes']['paginationCounters']['limit'];
            } else {
                $start = (($this->params['notes']['paginationCounters']['current'] * $this->params['notes']['paginationCounters']['limit']) - $this->params['notes']['paginationCounters']['limit']) + 1;
                $to = $this->params['notes']['paginationCounters']['current'] * $this->params['notes']['paginationCounters']['limit'];
            }

            if ($to > $this->params['notes']['paginationCounters']['filtered_items'] &&
                $this->params['notes']['paginationCounters']['last'] === $this->params['notes']['paginationCounters']['current']
            ) {
                $start = (($this->params['notes']['paginationCounters']['current'] * $this->params['notes']['paginationCounters']['limit']) - $this->params['notes']['paginationCounters']['limit']) + 1;
                $to = $this->params['notes']['paginationCounters']['filtered_items'];
            }

            $this->content .=
                '<div class="row ml-4 mr-4">
                    <div class="col">
                        Showing <span class="notes-shown">' . $start . ' to ' . $to . '</span> out of <span class="active-logs-total">' . $this->params['notes']['paginationCounters']['filtered_items'] . '</span>
                    </div>
                    <div class="col">';

            $leftDisabled = '';
            $rightDisabled = '';

            if ($this->params['notes']['paginationCounters']['first'] === 1 &&
                $this->params['notes']['paginationCounters']['current'] === 1 &&
                $this->params['notes']['paginationCounters']['last'] === 1
            ) {
                $leftDisabled = 'disabled';
                $rightDisabled = 'disabled';
            } else if ($this->params['notes']['paginationCounters']['first'] === 1 &&
                       $this->params['notes']['paginationCounters']['current'] === 1 &&
                       $this->params['notes']['paginationCounters']['last'] > 1
            ) {
                $leftDisabled = 'disabled';
                $rightDisabled = '';
            } else if ($this->params['notes']['paginationCounters']['current'] === $this->params['notes']['paginationCounters']['last']) {
                $leftDisabled = '';
                $rightDisabled = 'disabled';
            }

            $this->content .=
                '<ul class="pagination pagination-sm float-right">
                    <li class="page-item">
                        <a class="page-link notes-previous ' . $leftDisabled . '" href="#"><i class="fas fa-chevron-left"></i></a>
                    </li>
                    <li class="page-item">
                        <a class="page-link notes-next ' . $rightDisabled . '" href="#"><i class="fas fa-chevron-right"></i></a>
                    </li>
                </ul>';

            $this->content .=
                    '</div>
                </div>';
        }

        $this->content .=
            '<div class="row">
                <div class="col">
                    <div id="' . $this->compSecId . '-history-timeline" class="timeline">';

                        foreach ($this->params['notes'] as $notesKey => $notes) {
                            if ($notesKey === 'paginationCounters') {
                                continue;
                            }
                            //Private & App
                            if ($notes['note_type'] == '1') {
                                $timelineIcon = 'file-alt';
                                $timelintBg = 'primary';
                            } else if ($notes['note_type'] == '2' || $notes['note_type'] == '3') {
                                $timelineIcon = 'phone';
                                if ($notes['note_type'] == '2') {
                                    $timelintBg = 'primary';
                                } else {
                                    $timelintBg = 'info';
                                }
                            } else if ($notes['note_type'] == '4' || $notes['note_type'] == '5') {
                                $timelineIcon = 'envelope';
                                if ($notes['note_type'] == '4') {
                                    $timelintBg = 'primary';
                                } else {
                                    $timelineIcon = 'envelope';
                                    $timelintBg = 'info';
                                }
                            }

                            $title = '';

                            if (isset($notes['account_id']) && $notes['account_id'] == 0) {
                                $title .= '<span><i class="fas fa-fw fa-robot"></i> ' . $notes['account_full_name'] . ' </span>';
                            } else {
                                $title .= '<span><i class="fas fa-fw fa-user"></i> ' . $notes['account_full_name'] . ' (' . $notes['account_email'] . ') </span>';
                            }

                            if ($notes['is_private'] == '1') {
                                $title .= '<span><i class="fas fa-fw fa-eye-slash"></i> Private</span>';
                            } else if ($notes['note_app_visibility'] &&
                                is_array($notes['note_app_visibility']) &&
                                count($notes['note_app_visibility']) > 0
                            ) {
                                $title .= '<span><i class="fas fa-fw fa-eye"></i> ' . join(',', $notes['note_app_visibility']) . ' </span>';
                            } else {
                                $title .= '<span><i class="fas fa-fw fa-eye"></i> All Apps</span>';
                            }

                            $footer = '';

                            if ($notes['note_attachments'] && is_array($notes['note_attachments']) && count($notes['note_attachments']) > 0) {
                                $footer .=
                                    '<div class="row">
                                        <div class="col">
                                            <ul class="mailbox-attachments d-flex align-items-stretch clearfix">';

                                foreach ($notes['note_attachments'] as $attachmentKey => $attachment) {
                                    if ($attachment['type'] === 'application/pdf') {
                                        $icon = 'file-pdf';
                                    } else if ($attachment['type'] === 'text/plain') {
                                        $icon= 'file';
                                    } else if ($attachment['type'] === 'application/msword' ||
                                               $attachment['type'] === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                                              ) {
                                        $icon= 'file-word';
                                    } else if ($attachment['type'] === 'application/vnd.ms-excel' ||
                                               $attachment['type'] === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                              ) {
                                        $icon= 'file-excel';
                                    } else if ($attachment['type'] === 'application/vnd.ms-powerpoint' ||
                                               $attachment['type'] === 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                                              ) {
                                        $icon= 'file-powerpoint';
                                    } else if ($attachment['type'] === 'application/zip') {
                                        $icon = 'file-archive';
                                    } else if ($attachment['type'] === 'text/csv') {
                                        $icon = 'file-csv';
                                    } else {
                                        $icon = 'file';
                                    }

                                    if (in_array($attachment['type'], $this->packagesData->storage['allowed_file_mime_types'])) {
                                        $download = true;

                                        $footer .=
                                            '<li style="background: #f8f9fa;">
                                              <span class="mailbox-attachment-icon" style="font-size:40px;"><i class="far fa-fw fa-' . $icon . '"></i></span>
                                              <div class="mailbox-attachment-info">
                                                <a href="#" class="mailbox-attachment-name d-inline-block text-truncate" style="max-width: 150px;">' . $attachment['org_file_name'] . '</a>
                                                    <span class="mailbox-attachment-size clearfix mt-1">
                                                      <span>' . round((int) $attachment['size']/1000, 1) . 'KB</span>
                                                      <a target="_blank" href="' . $this->links->url('system/storages/q/uuid/' . $attachment['uuid']) . '" class="btn btn-primary btn-sm float-right"><i class="fas fa-download"></i></a>
                                                    </span>
                                              </div>
                                            </li>';

                                    } else if (in_array($attachment['type'], $this->packagesData->storage['allowed_image_mime_types'])) {
                                        $download = false;

                                        $footer .=
                                            '<li style="background: #f8f9fa;">
                                              <span class="mailbox-attachment-icon has-img">';

                                        if ($this->packagesData->storage['permission'] === 'public') {
                                            if (!isset($attachment['links'][$this->notesParams['lightboxSize']])) {
                                                $this->notesParams['lightboxSize'] = $this->helper->lastKey($attachment['links']);
                                            }
                                            $footer .=
                                            '<a class="chocolat-image" title="' . $attachment['org_file_name'] . '" href="' . $attachment['links'][$this->notesParams['lightboxSize']] . '">
                                                <img class="img-fluid img-thumbnail" alt="' . $attachment['org_file_name'] . '" src="' . $attachment['links'][$this->notesParams['thumbnailSize']] . '">
                                            </a>';

                                        } else {
                                            $footer .=
                                                '<a class="chocolat-image" href="' . $this->links->url('system/storages/q/uuid/' . $attachment['uuid'] . '/w/' . $this->notesParams['lightboxSize']) . '">
                                                    <img class="img-fluid img-thumbnail" alt="' . $attachment['org_file_name'] . '" src="' . $this->links->url('system/storages/q/uuid/' . $attachment['uuid'] . '/storagetype/private/w/' . $this->notesParams['thumbnailSize']) . '">
                                                </a>';
                                        }

                                        $footer .=
                                            '</span>
                                                <div class="mailbox-attachment-info">
                                                <span class="mailbox-attachment-name d-inline-block text-truncate" style="max-width: 150px;">' . $attachment['org_file_name'] . '</span>
                                                    <span class="mailbox-attachment-size clearfix mt-1">
                                                        <span>' . round((int) $attachment['size']/1000, 1) . 'KB</span>
                                                    </span>
                                                </div>
                                            </li>';
                                    }
                                }

                                $footer .=
                                            '</ul>
                                        </div>
                                    </div>';
                            }

                            $this->content .=
                                '<div>
                                    <i class="fas fa-fw fa-' . $timelineIcon . ' bg-' . $timelintBg . '"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fas fa-fw fa-clock"></i> ' . $notes['created_at'] .'</span>
                                        <h6 class="timeline-header text-secondary">' .  $title . '</h6>
                                        <div class="timeline-body">' . $notes['note'] . '</div>';

                            if ($notes['note_attachments'] && is_array($notes['note_attachments']) && count($notes['note_attachments']) > 0) {
                                $this->content .=
                                            '<div class="timeline-footer chocolat-parent">' . $footer . '</div></div></div>';
                            } else {
                                $this->content .=
                                    '</div>
                                </div>';

                            }
                        }

        $this->content .=
                    '
            <div>
                <i class="fas fa-fw fa-clipboard-list bg-secondary"></i>
            </div>
            </div></div></div></div></div>';
    }

    protected function inclNotesJs()
    {
        $inclJs =
            '<script type="text/javascript">
            var dataCollectionComponent, dataCollectionSection, dataCollectionSectionForm;

            if (!window["dataCollection"]["' . $this->params['componentId'] . '"]) {
                dataCollectionComponent =
                    window["dataCollection"]["' . $this->params['componentId'] . '"] = { };
            } else {
                dataCollectionComponent =
                    window["dataCollection"]["' . $this->params['componentId'] . '"];
            }
            if (!dataCollectionComponent["' . $this->compSecId . '"]) {
                dataCollectionSection =
                    dataCollectionComponent["' . $this->compSecId . '"] = { };
            } else {
                dataCollectionSection =
                    dataCollectionComponent["' . $this->compSecId . '"];
            }
            if (!dataCollectionSection["' . $this->compSecId . '-form"]) {
                dataCollectionSectionForm =
                    dataCollectionSection["' . $this->compSecId . '-form"] = { };
            } else {
                dataCollectionSectionForm =
                    dataCollectionSection["' . $this->compSecId . '-form"];
            }

            dataCollectionSection =
                $.extend(dataCollectionSection, {
                    "' . $this->compSecId . '-note_type"                    : {
                        placeholder : "SELECT NOTE TYPE"
                    },
                    "' . $this->compSecId . '-note_app_visibility"          : {
                        placeholder : "SELECT APP VISIBILITY"
                    },
                    "' . $this->compSecId . '-is_private"                   : {
                    },
                    "' . $this->compSecId . '-note"                         : {
                        afterInit : function() {
                            $("#' . $this->compSecId . '-is_private").click(function() {
                                if ($(this)[0].checked === true) {
                                    $("#' . $this->compSecId . '-note_app_visibility").val(0);
                                    $("#' . $this->compSecId . '-note_app_visibility").trigger("change");
                                    $("#' . $this->compSecId . '-note_app_visibility").attr("disabled", true);
                                } else {
                                    $("#' . $this->compSecId . '-note_app_visibility").val(0);
                                    $("#' . $this->compSecId . '-note_app_visibility").trigger("change");
                                    $("#' . $this->compSecId . '-note_app_visibility").attr("disabled", false);
                                }
                            });

                            function toggleNoteField(status) {
                                $("#' . $this->compSecId . '-add-note").attr("disabled", status);
                                if (status === true) {
                                    // $("#' . $this->compSecId . '-note").trumbowyg("disable");
                                    // $("#' . $this->compSecId . '-note_attachments-dropzone-upload").addClass("disabled");
                                    $("#' . $this->compSecId . '-note_type").val(0);
                                    $("#' . $this->compSecId . '-note_type").trigger("change");
                                    $("#' . $this->compSecId . '-note_app_visibility").val(0);
                                    $("#' . $this->compSecId . '-note_app_visibility").trigger("change");
                                    $("#' . $this->compSecId . '-is_private")[0].checked = false;
                                    $("#' . $this->compSecId . '-note").val("");
                                }
                            }

                            function initChocolat() {
                                if ($("#' . $this->compSecId . '-history-timeline .chocolat-parent").length > 0 ||
                                    $("#' . $this->compSecId . '-history-timeline .chocolat-image").length > 0
                                ) {
                                    if (dataCollectionSection["' . $this->compSecId . '-note_attachments"]["chocolat"]) {
                                        dataCollectionSection["' . $this->compSecId . '-note_attachments"]["chocolat"].destroy();
                                    }
                                    dataCollectionSection["' . $this->compSecId . '-note_attachments"]["chocolat"] =
                                        Chocolat(document.querySelectorAll("#' . $this->compSecId . '-history-timeline .chocolat-parent .chocolat-image"));
                                }
                            }

                            initChocolat();
                        }
                    }
                });

                $("#' . $this->compSecId . '-show-hide").click(function() {
                    if ($("#notes-form").attr("hidden") === "hidden") {
                        $(this).children("i").removeClass("fa-eye").addClass("fa-eye-slash");
                        $("#notes-form").attr("hidden", false);
                    } else {
                        $(this).children("i").removeClass("fa-eye-slash").addClass("fa-eye");
                        $("#notes-form").attr("hidden", true);
                    }
                });

                var paginationCounters = JSON.parse(\'' . $this->notesPaginationCounters . '\');

                $(".notes-previous, .notes-next").click(function(e) {
                    e.preventDefault();

                    var url = "' . $this->links->url('crypto/trades/getnotes') . '";

                    var postData = { };
                    postData[$("#security-token").attr("name")] = $("#security-token").val();
                    postData["id"] = $("#' . $this->params['componentId'] . '-main-id").val();

                    if ($(this).is(".notes-previous")) {
                        postData["page"] = paginationCounters["previous"];
                    } else if ($(this).is(".notes-next")) {
                        postData["page"] = paginationCounters["next"];
                    }

                    $.post(url, postData, function(response) {
                        console.log(response);
                        if (response.responseCode == 0) {
                            if (response.responseData) {
                                $("#notes-logs").empty().html(response.responseData.logs);
                            }
                        }
                    }, "json");
                });
            </script>';

        return $inclJs;
    }
}