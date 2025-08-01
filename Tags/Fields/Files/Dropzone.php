<?php

namespace Apps\Fintech\Packages\Adminltetags\Tags\Fields\Files;

use Apps\Fintech\Packages\Adminltetags\Adminltetags;

class Dropzone
{
    protected $view;

    protected $tag;

    protected $links;

    protected $escaper;

    protected $params;

    protected $fieldParams;

    protected $content;

    protected $adminLTETags;

    protected $compSecId;

    public function __construct($view, $tag, $links, $escaper, $params, $fieldParams)
    {
        $this->adminLTETags = new Adminltetags();

        $this->view = $view;

        $this->tag = $tag;

        $this->links = $links;

        $this->escaper = $escaper;

        $this->params = $params;

        $this->fieldParams = $fieldParams;

        $this->compSecId = $this->params['componentId'] . '-' . $this->params['sectionId'];

        $this->generateContent();
    }

    public function getContent()
    {
        return $this->content;
    }

    protected function generateContent()
    {
        if (!isset($this->params['storage'])) {
            throw new \Exception('storage information missing for dropzone.');
        }

        $this->fieldParams['fieldDropzoneLabel'] =
            isset($this->params['fieldDropzoneLabel']) ?
            $this->params['fieldDropzoneLabel'] :
            false;

        $this->fieldParams['fieldHelpTooltipContent'] =
            isset($this->params['fieldHelpTooltipContent']) ?
            $this->params['fieldHelpTooltipContent'] :
            '';

        $this->fieldParams['fieldRequired'] =
            isset($this->params['fieldRequired']) && $this->params['fieldRequired'] === true ?
            true :
            false;

        $this->fieldParams['fieldBazJstreeSearch'] =
            isset($this->params['fieldBazJstreeSearch']) && $this->params['fieldBazJstreeSearch'] === true ?
            true :
            false;

        $this->fieldParams['fieldBazPostOnCreate'] =
            isset($this->params['fieldBazPostOnCreate']) && $this->params['fieldBazPostOnCreate'] === true ?
            true :
            false;

        $this->fieldParams['fieldBazPostOnUpdate'] =
            isset($this->params['fieldBazPostOnUpdate']) && $this->params['fieldBazPostOnUpdate'] === true ?
            true :
            false;

        $this->fieldParams['sortable'] =
            isset($this->params['sortable']) ?
            $this->params['sortable'] :
            false;

        $this->fieldParams['tableMaxHeight'] =
            isset($this->params['tableMaxHeight']) ?
            $this->params['tableMaxHeight'] :
            300;

        $this->fieldParams['thumbnailSize'] =
            isset($this->params['thumbnailSize']) ?
            $this->params['thumbnailSize'] :
            30;

        $this->fieldParams['lightboxSize'] =
            isset($this->params['lightboxSize']) ?
            $this->params['lightboxSize'] :
            1200;

        $this->fieldParams['maxAttachments'] =
            isset($this->params['maxAttachments']) ?
            $this->params['maxAttachments'] :
            5;

        $this->fieldParams['noPreview'] =
            isset($this->params['noPreview']) && $this->params['noPreview'] === true ?
            $this->params['noPreview'] = 'hidden' :
            $this->params['noPreview'] = '';

        $this->fieldParams['uploadDirectory'] =
            isset($this->params['uploadDirectory']) ?
            $this->params['uploadDirectory'] = strtolower($this->params['uploadDirectory']) :
            $this->params['uploadDirectory'] = strtolower($this->params['componentName']);

        if (isset($this->params['allowedUploads']) && $this->params['allowedUploads'] === 'images') {
            $this->fieldParams['allowedImageMimeType'] =
                $this->adminLTETags->helper->encode($this->params['storage']['allowed_image_mime_types']);

            $this->fieldParams['allowedFileMimeType'] = $this->adminLTETags->helper->encode([]);
        } else if (isset($this->params['allowedUploads']) && $this->params['allowedUploads'] === 'files') {
            $this->fieldParams['allowedImageMimeType'] = $this->adminLTETags->helper->encode([]);

            $this->fieldParams['allowedFileMimeType'] =
                $this->adminLTETags->helper->encode($this->params['storage']['allowed_file_mime_types']);
        } else {
            $this->params['allowedUploads'] = 'files';

            $this->fieldParams['allowedImageMimeType'] =
                $this->adminLTETags->helper->encode($this->params['storage']['allowed_image_mime_types']);

            $this->fieldParams['allowedFileMimeType'] =
                $this->adminLTETags->helper->encode($this->params['storage']['allowed_file_mime_types']);
        }

        $dropzoneButtons = [];

        if (isset($this->params['setOrphan']) && $this->params['setOrphan'] === false) {
            $this->params['setOrphan'] = 'false';
        } else {
            $this->params['setOrphan'] = 'true';
        }

        if (isset($this->params['isBackupFile']) && $this->params['isBackupFile'] === true) {
            $this->params['isBackupFile'] = 'true';
            $this->params['isPointer'] = true;
        } else {
            $this->params['isBackupFile'] = 'false';
        }

        if (isset($this->params['isPointer']) && $this->params['isPointer'] === true) {
            $this->params['isPointer'] = 'true';
        } else {
            $this->params['isPointer'] = 'false';
        }

        if (isset($this->params['upload']) && $this->params['upload'] === true) {
            $dropzoneButtons =
                array_merge(
                    $dropzoneButtons,
                    [
                        $this->params['fieldId'] . '-dropzone-upload' =>
                        [
                            'title'                     => false,
                            'position'                  => 'left',
                            'icon'                      => 'upload',
                            'size'                      => 'xs',
                            'buttonAdditionalClass'     => 'mr-1 ml-1 btn-file'
                        ],
                        $this->params['fieldId'] . '-dropzone-save' =>
                        [
                            'title'                     => false,
                            'position'                  => 'left',
                            'icon'                      => 'save',
                            'hidden'                    => true,
                            'size'                      => 'xs',
                            'buttonAdditionalClass'     => 'mr-1 ml-1'
                        ],
                        $this->params['fieldId'] . '-dropzone-cancel' =>
                        [
                            'title'                     => false,
                            'type'                      => 'secondary',
                            'position'                  => 'left',
                            'icon'                      => 'times',
                            'hidden'                    => true,
                            'size'                      => 'xs',
                            'buttonAdditionalClass'     => 'mr-1 ml-1'
                        ]
                    ]
                );
        }

        $this->content .=
            '<div style="max-height: ' . $this->fieldParams['tableMaxHeight'] . 'px;overflow-y: scroll;overflow-x: hidden">
                <div class="row">
                    <div class="col mb-2">' .
                        $this->adminLTETags->useTag('fields',
                            [
                                'component'                     => $this->params['component'],
                                'componentName'                 => $this->params['componentName'],
                                'componentId'                   => $this->params['componentId'],
                                'sectionId'                     => $this->params['sectionId'],
                                'fieldId'                       => $this->params['fieldId'],
                                'fieldLabel'                    => $this->fieldParams['fieldDropzoneLabel'],
                                'fieldType'                     => 'html',
                                'fieldHelp'                     => true,
                                'fieldHelpTooltipContent'       => $this->fieldParams['fieldHelpTooltipContent'],
                                'fieldAdditionalClass'          => 'mb-0',
                                'fieldRequired'                 => $this->fieldParams['fieldRequired'],
                                'fieldBazScan'                  => true,
                                'fieldBazJstreeSearch'          => $this->fieldParams['fieldBazJstreeSearch'],
                                'fieldBazPostOnCreate'          => $this->fieldParams['fieldBazPostOnCreate'],
                                'fieldBazPostOnUpdate'          => $this->fieldParams['fieldBazPostOnUpdate']
                            ]
                        ) .
                        $this->adminLTETags->useTag('buttons',
                            [
                                'componentId'                   => $this->params['componentId'],
                                'sectionId'                     => $this->params['sectionId'],
                                'buttonLabel'                   => false,
                                'buttonType'                    => 'button',
                                'buttons'                       => $dropzoneButtons
                            ]
                        ) .
                        '<span id="' . $this->compSecId . '-dropzone-save-warning" class="dropzone-save-warning text-danger" hidden>Save/Cancel Image</span>
                    </div>
                </div>
                <div class="row pb-2">
                    <div class="col">
                        <ul class="list-group" id="' . $this->compSecId . '-' . $this->params['fieldId'] . '-upload-previews">
                            <li class="list-group-item list-group-item-secondary rounded-0 ' . $this->compSecId . '-' . $this->params['fieldId'] . '-upload-template" area-disabled="false">
                                <div class="row">
                                    <div class="col">
                                        <span class="filename"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2 p-1 text-center">
                                        <span class="preview">
                                            <img alt="" data-dz-thumbnail />
                                        </span>
                                    </div>
                                    <div class="col-sm-8 p-1">
                                        <div class="progress" style="position: relative;top: 8px;" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                                            <div class="progress progress-bar progress-bar-striped bg-success" style="width:0%;" data-dz-uploadprogress></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 p-1 text-center">
                                        <button data-dz-remove class="btn btn-danger delete p-1">
                                            <i class="fa fa-fw fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row border-top pt-2" id="' . $this->compSecId . '-' . $this->params['fieldId'] . '-attachments" ' . $this->params['noPreview'] . '>
                    <div class="col">
                        <ul class="list-group" id="' . $this->compSecId . '-' . $this->params['fieldId'] . '-sortable-attachments">';

                            if ($this->params['attachments'] && count($this->params['attachments']) > 0) {
                                $this->content .=
                                    '<div class="list-group-item list-group-item-secondary no-data rounded-0" id="' . $this->compSecId . '-' . $this->params['fieldId'] . '-nodata" hidden>
                                        <div class="row">
                                            <div class="col text-uppercase">
                                                <i class="fa fa-fw fa-exclamation"></i> Add ' . $this->params['allowedUploads'] . '
                                            </div>
                                        </div>
                                    </div>';

                                foreach ($this->params['attachments'] as $attachmentKey => $attachment) {
                                    if ($attachment['type'] === 'application/pdf') {
                                        $src = $this->links->images('/general/pdf.png');
                                        $alt = 'pdf';
                                    } else if ($attachment['type'] === 'text/plain') {
                                        $src = $this->links->images('/general/file.png');
                                        $alt = 'file';
                                    } else if ($attachment['type'] === 'application/msword' ||
                                               $attachment['type'] === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                                              ) {
                                        $src = $this->links->images('/general/file.png');
                                        $alt = 'file';
                                    } else if ($attachment['type'] === 'application/vnd.ms-excel' ||
                                               $attachment['type'] === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                              ) {
                                        $src = $this->links->images('/general/excel.png');
                                        $alt = 'excel';
                                    } else if ($attachment['type'] === 'application/vnd.ms-powerpoint' ||
                                               $attachment['type'] === 'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                                              ) {
                                        $src = $this->links->images('/general/powerpoint.png');
                                        $alt = 'powerpoint';
                                    } else if ($attachment['type'] === 'application/zip') {
                                        $src = $this->links->images('/general/zip.png');
                                        $alt = 'zip';
                                    } else if ($attachment['type'] === 'text/csv') {
                                        $src = $this->links->images('/general/csv.png');
                                        $alt = 'csv';
                                    } else {
                                        $src = $this->links->images('/general/file-unknown.png');
                                        $alt = 'Unknwon File Type';
                                    }

                                    $this->content .=
                                        '<li class="list-group-item list-group-item-secondary rounded-0" area-disabled="false" style="cursor: pointer; border: 1px solid rgba(0, 0, 0, 0.125);" data-uuid="' . $attachment['uuid'] . '">';

                                            if (isset($this->params['sortable']) &&
                                                $this->params['sortable'] === true
                                            ) {
                                                $this->content .=
                                                    '<i class="fa fa-sort fa-fw handle"></i>';
                                            }

                                            $this->content .=
                                                $attachment['org_file_name'] .
                                                '<div class="row mt-2">
                                                    <div class="col chocolat-parent">';

                                                        if (in_array($attachment['type'], $this->params['storage']['allowed_file_mime_types'])) {
                                                            $this->content .=
                                                                '<img alt="' . $alt . '" src="' . $src . '" class="img-fluid img-thumbnail">';
                                                            $download = true;
                                                        } else if (in_array($attachment['type'], $this->params['storage']['allowed_image_mime_types'])) {
                                                            $download = false;

                                                            if ($this->params['storage']['permission'] === 'public') {
                                                                if (!isset($attachment['links'][$this->fieldParams['lightboxSize']])) {
                                                                    $this->fieldParams['lightboxSize'] = $this->adminLTETags->helper->lastKey($attachment['links']);
                                                                }
                                                                $this->content .=
                                                                '<a class="chocolat-image" title="' . $attachment['org_file_name'] . '" href="' . $attachment['links'][$this->fieldParams['lightboxSize']] . '">
                                                                    <img alt="' . $attachment['org_file_name'] . '" src="' . $attachment['links'][$this->fieldParams['thumbnailSize']] . '" class="img-fluid img-thumbnail">
                                                                </a>';

                                                            } else {
                                                                $this->content .=
                                                                    '<a class="chocolat-image" href="' . $this->links->url('system/storages/q/uuid/' . $attachment['uuid'] . '/w/' . $this->fieldParams['lightboxSize']) . '">
                                                                        <img alt="' . $attachment['org_file_name'] . '" src="' . $this->links->url('system/storages/q/uuid/' . $attachment['uuid'] . '/storagetype/private/w/' . $this->fieldParams['thumbnailSize']) . '" class="img-fluid img-thumbnail">
                                                                    </a>';
                                                            }

                                                        } else {
                                                            $download = true;
                                                            $this->content .=
                                                                '<img alt="' . $alt . '" src="' . $src . '" class="img-fluid img-thumbnail">';
                                                        }

                                                    $this->content .=
                                                        '</div>
                                                        <div class="col">
                                                            <a data-uuid="' . $attachment['uuid'] . '" class="uploads-delete btn btn-danger btn-xs float-right" href="#">
                                                                <i class="fa fa-fw fa-trash"></i>
                                                            </a>';

                                                            if (isset($download) && $download === true) {
                                                                if ($this->params['storage']['permission'] === 'public') {

                                                                    $this->content .=
                                                                        '<a data-uuid="' . $attachment['uuid'] . '" class="uploads-download btn btn-primary btn-xs float-right mr-2" href="' . $attachment['links']['data'] . '" target="_blank">
                                                                            <i class="fa fa-fw fa-download"></i>
                                                                        </a>';

                                                                } else {
                                                                    $this->content .=
                                                                        '<a data-uuid="' . $attachment['uuid'] . '" class="uploads-download btn btn-primary btn-xs float-right mr-2" href="' . $this->links->url('system/storages/q/storagetype/private/uuid/' . $attachment["uuid"]) .'" target="_blank">
                                                                            <i class="fa fa-fw fa-download"></i>
                                                                        </a>';
                                                                }
                                                            }

                                                    $this->content .=
                                                        '</div>
                                                    </div>
                                                </li>';
                                }
                            } else {
                                $this->content .=
                                    '<div class="list-group-item list-group-item-secondary no-data rounded-0" id="' . $this->compSecId . '-' . $this->params['fieldId'] . '-nodata">
                                        <div class="row">
                                            <div class="col text-uppercase">
                                                <i class="fa fa-fw fa-exclamation"></i> Add ' . $this->params['allowedUploads'] . '
                                            </div>
                                        </div>
                                    </div>';
                            }

                        $this->content .= '
                        </ul>
                    </div>
                </div>
            </div>' .

        $this->inclJs();
    }

    protected function inclJs()
    {
        $inclJs =
            '<script type="text/javascript">
                if (!window["dataCollection"]["' . $this->params['componentId'] . '"]) {
                    window["dataCollection"]["' . $this->params['componentId'] . '"] = { };
                }

                window["dataCollection"]["' . $this->params['componentId'] . '"]["' . $this->compSecId . '"] =
                $.extend(window["dataCollection"]["' . $this->params['componentId'] . '"]["' . $this->compSecId . '"], {
                    "' . $this->compSecId . '-' . $this->params['fieldId'] . '" : {
                        afterInit: function() {
                            var initialFiles = $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-sortable-attachments li").length;
                            var deleteUUIDs = [];
                            var filesLimit = parseInt("' . $this->fieldParams['maxAttachments'] . '") - initialFiles;
                            var problemWithUpload = false;
                            var sortable = Boolean(' . $this->fieldParams['sortable'] . ');

                            var fileMimeTypes = JSON.parse(\'' . $this->fieldParams['allowedFileMimeType'] . '\');
                            var imageMimeTypes = JSON.parse(\'' . $this->fieldParams['allowedImageMimeType'] . '\');

                            var maxImageFileSize = parseInt(' . $this->params['storage']['max_image_file_size'] . ');
                            var maxFileSize = parseInt(' . $this->params['storage']['max_data_file_size'] . ');

                            var previewNode = document.querySelector(".' . $this->compSecId . '-' . $this->params['fieldId'] . '-upload-template");
                            if (previewNode) {
                                previewNode.id = "";
                                var previewTemplate = previewNode.parentNode.innerHTML;
                                $(document).ready(function() {
                                    $(previewNode.parentNode).children("li").remove();
                                });
                            }

                            var fieldId =
                                window["dataCollection"]["' . $this->params['componentId'] . '"]["' . $this->compSecId . '"]["' . $this->compSecId . '-' . $this->params['fieldId'] . '"];

                            initDropzone();

                            function initDropzone() {
                                if (fieldId["dropzone"]) {
                                    fieldId["dropzone"].destroy()
                                }

                                fieldId["dropzone"] = new Dropzone("#' . $this->compSecId . '-' . $this->params['fieldId'] . '", {
                                    url                 : "' . $this->links->url('system/storages/add') . '",
                                    timeout             : 3600000,
                                    thumbnailWidth      : ' . $this->fieldParams['thumbnailSize'] . ',
                                    thumbnailHeight     : ' . $this->fieldParams['thumbnailSize'] . ',
                                    parallelUploads     : 5,
                                    maxFiles            : filesLimit,
                                    previewTemplate     : previewTemplate,
                                    autoQueue           : false,
                                    previewsContainer   : "#' . $this->compSecId . '-' . $this->params['fieldId'] . '-upload-previews",
                                    clickable           : "#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-upload"
                                });

                                initChocolat();
                                initEvents();
                                registerDeleteButtons();
                                collectData();

                                $("#body").trigger(
                                    {
                                        "type"      : "dropzoneInitDone",
                                        "fieldId"   : "' . $this->params['fieldId'] . '"
                                    }
                                );
                            }

                            function initEvents() {
                                fieldId["dropzone"].on("addedfile", function(file) {
                                    if (file.size > maxFileSize) {
                                        fieldId["dropzone"].removeFile(file);
                                        PNotify.error({
                                            title: file.name,
                                            text: "File size exceeds allowed size! File not added."
                                        });

                                        return;
                                    }

                                    registerSaveCancel();

                                    var src, alt;
                                    var indexOfFile = fileMimeTypes.indexOf(file.type);
                                    var indexOfImage = imageMimeTypes.indexOf(file.type);

                                    if (file.type === "application/pdf") {
                                        src = "' . $this->links->images('/general/pdf.png') . '";
                                        alt = "pdf";
                                    } else if (file.type === "text/plain") {
                                        src = "' . $this->links->images('/general/file.png') . '";
                                        alt = "file";
                                    } else if (file.type === "application/msword" ||
                                               file.type === "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                    ) {
                                        src = "' . $this->links->images('/general/file.png') . '";
                                        alt = "file";
                                    } else if (file.type === "application/vnd.ms-excel" ||
                                               file.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                    ) {
                                        src = "' . $this->links->images('/general/excel.png') . '";
                                        alt = "excel";
                                    } else if (file.type === "application/vnd.ms-powerpoint" ||
                                               file.type === "application/vnd.openxmlformats-officedocument.presentationml.presentation"
                                    ) {
                                        src = "' . $this->links->images('/general/powerpoint.png') . '";
                                        alt = "powerpoint";
                                    } else if (file.type === "application/zip" || file.type === "application/gzip") {
                                        src = "' . $this->links->images('/general/zip.png') . '";
                                        alt = "zip";
                                    } else if (file.type === "text/csv") {
                                        src = "' . $this->links->images('/general/csv.png') . '";
                                        alt = "csv";
                                    } else {
                                        src = "' . $this->links->images('/general/file-unknown.png') . '";
                                        alt = "Unknwon File Type";
                                    }

                                    if (indexOfFile !== -1 || indexOfImage !== -1) {
                                        $(file.previewElement).children().find(".filename").html(file.name);
                                        $(file.previewElement).children().find("[data-dz-thumbnail]").attr("src", src);
                                        $(file.previewElement).children().find("[data-dz-thumbnail]").attr("alt", alt);

                                    } else if (indexOfFile === -1 && indexOfImage === -1) {
                                        fieldId["dropzone"].removeFile(file);
                                        PNotify.error({
                                            title: file.name,
                                            text: "Extension not allowed! File not added."
                                        });

                                        return;
                                    }

                                    if (fieldId["onAddedFile"]) {
                                        fieldId["onAddedFile"]();
                                    }
                                });

                                fieldId["dropzone"].on("removedfile", function(file) {
                                    $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-upload").attr("disabled", false);
                                    $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").children("i").removeClass("fa-cog fa-spin").addClass("fa-save");
                                    $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").attr("disabled", false);

                                    if (fieldId["dropzone"].getAcceptedFiles().length === 0) {
                                        $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").attr("hidden", true);
                                        $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-cancel").attr("hidden", true);
                                    }

                                    if (fieldId["onRemovedFile"]) {
                                        fieldId["onRemovedFile"](file);
                                    }
                                });

                                fieldId["dropzone"].on("queuecomplete", function() {
                                    initChocolat();

                                    if (fieldId["onQueueComplete"]) {
                                        fieldId["onQueueComplete"]();
                                    }

                                    if (deleteUUIDs.length > 0) {
                                        processDeleteUUIDs();
                                    } else {
                                        initDropzone();
                                    }
                                });

                                fieldId["dropzone"].on("success", function(file, response) {
                                    $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-cancel").attr("hidden", true);
                                    $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-upload").attr("disabled", false);
                                    $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").attr("hidden", true);
                                    $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").children("i").removeClass("fa-cog fa-spin").addClass("fa-save");
                                    $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").attr("disabled", false);

                                    if (response.tokenKey && response.token) {
                                        $("#security-token").attr("name", response.tokenKey);
                                        $("#security-token").val(response.token);
                                    }

                                    if (file.accepted && file.status === "success") {
                                        if (response.responseCode != 0) {
                                            problemWithUpload = true;
                                            PNotify.error({
                                                title: file.name,
                                                text: response.responseMessage
                                            });
                                        } else {
                                            $(file.previewTemplate).remove();

                                            var src, alt, download;
                                            var indexOfFile = fileMimeTypes.indexOf(file.type);
                                            var indexOfImage = imageMimeTypes.indexOf(file.type);

                                            if (file.type === "application/pdf") {
                                                src = "' . $this->links->images('/general/pdf.png') . '";
                                                alt = "pdf";
                                            } else if (file.type === "text/plain") {
                                                src = "' . $this->links->images('/general/file.png') . '";
                                                alt = "file";
                                            } else if (file.type === "application/msword" ||
                                                       file.type === "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                                            ) {
                                                src = "' . $this->links->images('/general/file.png') . '";
                                                alt = "file";
                                            } else if (file.type === "application/vnd.ms-excel" ||
                                                       file.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                            ) {
                                                src = "' . $this->links->images('/general/excel.png') . '";
                                                alt = "excel";
                                            } else if (file.type === "application/vnd.ms-powerpoint" ||
                                                       file.type === "application/vnd.openxmlformats-officedocument.presentationml.presentation"
                                            ) {
                                                src = "' . $this->links->images('/general/powerpoint.png') . '";
                                                alt = "powerpoint";
                                            } else if (file.type === "application/zip" ||file.type === "application/gzip") {
                                                src = "' . $this->links->images('/general/zip.png') . '";
                                                alt = "zip";
                                            } else if (file.type === "text/csv") {
                                                src = "' . $this->links->images('/general/csv.png') . '";
                                                alt = "csv";
                                            } else {
                                                src = "' . $this->links->images('/general/file-unknown.png') . '";
                                                alt = "Unknwon File Type";
                                            }

                                            var newList = "";

                                            newList +=
                                                \'<li class="list-group-item list-group-item-secondary rounded-0" area-disabled="false" style="cursor: pointer; border: 1px solid rgba(0, 0, 0, 0.125);" data-uuid="\' + response.responseData.uuid + \'">\';

                                            if (sortable === true) {
                                                newList +=
                                                    \'<i class="fa fa-sort fa-fw handle"></i>\';
                                            }

                                            newList +=
                                                file.name +
                                                \'<div class="row mt-2">\' +
                                                    \'<div class="col chocolat-parent">\';

                                            if (indexOfFile !== -1) {
                                                newList +=
                                                    \'<img alt="\' + alt + \'" src="\' + src + \'" class="img-fluid img-thumbnail">\';
                                                download = true;
                                            } else if (indexOfImage !== -1) {
                                                newList +=';
                                                if ($this->params['storage']['permission'] === 'public') {
                                                    $inclJs .=
                                                        '\'<a class="chocolat-image" title="\' + file.name + \'" href="\' + response.responseData.publicLinks[1][Object.keys(response.responseData.publicLinks[1])[0]] + \'">\' +
                                                            \'<img alt="\' + file.name + \'" src="\' + response.responseData.publicLinks[0][Object.keys(response.responseData.publicLinks[0])[0]] + \'" class="img-fluid img-thumbnail">\' +
                                                        \'</a>\';';
                                                } else {
                                                    $inclJs .=
                                                        '\'<a class="chocolat-image" title="\' + file.name + \'" href="' . $this->links->url('system/storages/q/uuid/\' + response.responseData.uuid + \'/storagetype/private/w/' . $this->fieldParams['lightboxSize']) . '">\' +
                                                            \'<img alt="\' + file.name + \'" src="' . $this->links->url('system/storages/q/uuid/\' + response.responseData.uuid + \'/storagetype/private/w/' . $this->fieldParams['thumbnailSize']) . '" class="img-fluid img-thumbnail">\' +
                                                        \'</a>\';';
                                                }
                                                $inclJs .=
                                                    'download = false;
                                            } else {
                                                newList +=
                                                    \'<img alt="\' + alt + \'" src="\' + src + \'" class="img-fluid img-thumbnail">\';
                                                download = true;
                                            }

                                            newList +=
                                                \'</div>\' +
                                                    \'<div class="col">\' +
                                                        \'<a data-uuid="\' + response.responseData.uuid + \'" class="uploads-delete btn btn-danger btn-xs float-right" href="#">\' +
                                                            \'<i class="fa fa-fw fa-trash"></i>\' +
                                                        \'</a>\';

                                            if (download) {
                                                newList +=';

                                                if ($this->params['storage']['permission'] === 'public') {
                                                    $inclJs .=
                                                        '\'<a data-uuid="\' + response.responseData.uuid + \'" class="uploads-download btn btn-primary btn-xs float-right mr-2" href="\' + response.responseData.publicLinks[0] + \'" target="_blank">\' +
                                                            \'<i class="fa fa-fw fa-download"></i>\' +
                                                        \'</a>\';';
                                                } else {
                                                    $inclJs .=
                                                        '\'<a data-uuid="\' + response.responseData.uuid + \'" class="uploads-download btn btn-primary btn-xs float-right mr-2" href="' . $this->links->url('system/storages/q/uuid/\' + response.responseData.uuid + \'') . '" target="_blank">\' +
                                                            \'<i class="fa fa-fw fa-download"></i>\' +
                                                        \'</a>\';';
                                                }

                                            $inclJs .=
                                            '}

                                                newList +=
                                                    \'</div>\' +
                                                \'</div>\' +
                                            \'</li>\';

                                            if (!$("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-nodata").attr("hidden")) {
                                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-nodata").attr("hidden", true);
                                            }

                                            $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-sortable-attachments").append(newList);

                                            filesLimit = filesLimit - 1
                                        }
                                    }

                                    if (fieldId["onSuccess"]) {
                                        fieldId["onSuccess"](file, response);
                                    }
                                });

                                fieldId["dropzone"].on("error", function(file, response) {
                                    if (response.tokenKey && response.token) {
                                        $("#security-token").attr("name", response.tokenKey);
                                        $("#security-token").val(response.token);
                                    }

                                    if (file.accepted === false) {
                                        fieldId["dropzone"].removeFile(file);
                                        PNotify.error({
                                            title: "Limit Reached!",
                                            text: response + " Did not add file " + file.name + " to the queue."
                                        });
                                        return;
                                    }

                                    //Fatal Error
                                    if (file.status === "error") {
                                        PNotify.error({
                                            title: "ERROR",
                                            text: "Contact Administrator!"
                                        });

                                        fieldId["dropzone"].removeAllFiles(true);
                                        $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-upload").attr("disabled", false);
                                        $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-cancel").attr("hidden", true);
                                        $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").attr("hidden", true);
                                        $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").children("i").removeClass("fa-cog fa-spin").addClass("fa-save");
                                        $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").attr("disabled", false);

                                        return;
                                    }

                                    if (fieldId["onError"]) {
                                        fieldId["onError"]();
                                    }
                                });
                            }

                            function initChocolat() {
                                if ($("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-attachments .chocolat-parent").length > 0 ||
                                    $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-attachments .chocolat-image").length > 0
                                ) {
                                    if (fieldId["chocolat"]) {
                                        fieldId["chocolat"].destroy();
                                    }
                                    fieldId["chocolat"] =
                                        Chocolat(document.querySelectorAll("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-attachments .chocolat-parent .chocolat-image"));
                                }
                            }

                            function registerDeleteButtons() {
                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-attachments .uploads-delete").each(function(index,button) {
                                   $(button).off();
                                   $(button).removeClass("disabled");

                                   $(button).click(function(e) {
                                        e.preventDefault();

                                        $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").attr("hidden", false);
                                        $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-cancel").attr("hidden", false);

                                        $(this).addClass("disabled");
                                        deleteUUIDs.push($(this).data("uuid"));
                                        registerSaveCancel();
                                    });
                                });
                            }

                            function registerSaveCancel() {
                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").attr("hidden", false);
                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").off();
                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").click(function() {
                                    $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-upload").attr("disabled", true);
                                    $(this).attr("disabled", true);
                                    $(this).children("i").removeClass("fa-save").addClass("fa-cog fa-spin");
                                    fieldId["save"]();
                                });

                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-cancel").attr("hidden", false);
                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-cancel").off();
                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-cancel").click(function() {
                                    $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-upload").attr("disabled", false);
                                    $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").children("i").removeClass("fa-cog fa-spin").addClass("fa-save");
                                    $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").attr("disabled", false);
                                    fieldId["cancel"]();
                                });
                            }

                            fieldId["save"] = function() {
                                if (fieldId["dropzone"].files.length > 0) {
                                    fieldId["dropzone"].options.params = {
                                        "upload"        : "true",
                                        "directory"     : "' . $this->fieldParams['uploadDirectory'] . '",
                                        "storagetype"   : "' . $this->params['storage']['permission'] . '",
                                        "setOrphan"     : "' . $this->params['setOrphan'] . '",
                                        "isPointer"     : "' . $this->params['isPointer'] . '",
                                        "isBackupFile"  : "' . $this->params['isBackupFile'] . '"
                                    };

                                    if ("' . $this->params['storage']['permission'] . '" === "public") {
                                        fieldId["dropzone"].options.params["getpubliclinks"] =
                                            "' . $this->fieldParams['thumbnailSize'] . ',' . $this->fieldParams['lightboxSize'] . '";
                                    }

                                    fieldId["dropzone"].options.params[$("#security-token").attr("name")] = $("#security-token").val();

                                    fieldId["dropzone"].enqueueFiles(
                                        fieldId["dropzone"].getFilesWithStatus(Dropzone.ADDED)
                                    );
                                } else if (deleteUUIDs.length > 0) {
                                    processDeleteUUIDs();
                                }

                                if (fieldId["onSave"]) {
                                    fieldId["onSave"](fieldId["dropzone"].files);
                                }
                            }

                            fieldId["cancel"] = function() {
                                fieldId["dropzone"].removeAllFiles(true);

                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").attr("hidden", true);
                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-cancel").attr("hidden", true);
                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-upload").attr("disabled", false);
                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").children("i").removeClass("fa-cog fa-spin").addClass("fa-save");
                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").attr("disabled", false);

                                deleteUUIDs = [];
                                registerDeleteButtons();
                            }

                            function processDeleteUUIDs() {
                                var deleteUUIDsLength = deleteUUIDs.length;

                                if (deleteUUIDs.length > 0) {
                                    $(deleteUUIDs).each(function(index, uuid) {
                                        deleteFile(uuid);
                                    });
                                }
                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").attr("hidden", true);
                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-cancel").attr("hidden", true);
                                deleteUUIDs = [];
                                filesLimit = filesLimit + deleteUUIDsLength;
                                initDropzone();
                            }

                            function deleteFile(uuid) {
                                var postData = { };
                                postData["uuid"] = uuid;
                                postData[$("#security-token").attr("name")] = $("#security-token").val();
                                postData["storagetype"]  = "' . $this->params['storage']['permission'] . '";

                                $.post("' . $this->links->url('system/storages/remove') . '", postData, function(response) {
                                    if (response.tokenKey && response.token) {
                                        $("#security-token").attr("name", response.tokenKey);
                                        $("#security-token").val(response.token);
                                    }

                                    if (response.responseCode != 0) {
                                        PNotify.error({
                                            title: "Error:",
                                            text: response.responseMessage
                                        });
                                    } else {
                                        $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-sortable-attachments").find(\'[data-uuid="\' + uuid + \'"]\')[0].remove();

                                        if ($("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-sortable-attachments").find("li").length === 0) {
                                            $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-sortable-attachments .no-data").attr("hidden", false);
                                        }
                                    }
                                    collectData();
                                }, "json");
                            }

                            function collectData() {
                                if (!window["dataCollection"]["' . $this->params['componentId'] . '"]["' . $this->compSecId . '"]["data"]) {
                                    window["dataCollection"]["' . $this->params['componentId'] . '"]["' . $this->compSecId . '"]["data"] = { };
                                }
                                if (!window["dataCollection"]["' . $this->params['componentId'] . '"]["' . $this->compSecId . '"]["data"]["' . $this->params['fieldId'] . '"]) {
                                    window["dataCollection"]["' . $this->params['componentId'] . '"]["' . $this->compSecId . '"]["data"]["' . $this->params['fieldId'] . '"] = { };
                                }

                                if (sortable) {
                                    var el = document.getElementById("' . $this->compSecId . '-' . $this->params['fieldId'] . '-sortable-attachments");
                                    fieldId["sortable"] = null;
                                    fieldId["sortable"] = Sortable.create(el, {
                                        dataIdAttr : "data-uuid",
                                        onEnd: function() {
                                            window["dataCollection"]["' . $this->params['componentId'] . '"]["' . $this->compSecId . '"]["data"]["' . $this->params['fieldId'] . '"] =
                                                fieldId["sortable"].toArray();

                                            initChocolat();
                                        }
                                    });
                                    window["dataCollection"]["' . $this->params['componentId'] . '"]["' . $this->compSecId . '"]["data"]["' . $this->params['fieldId'] . '"] =
                                        fieldId["sortable"].toArray();
                                } else {
                                    fieldId["uuidData"] = [];
                                    $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-sortable-attachments li").each(function(index, li) {

                                        fieldId["uuidData"].push($(li).data("uuid"));
                                    });

                                    window["dataCollection"]["' . $this->params['componentId'] . '"]["' . $this->compSecId . '"]["data"]["' . $this->params['fieldId'] . '"] =
                                        fieldId["uuidData"];
                                }

                                $("body").trigger(
                                    {
                                        "type"      : "dropzoneCollectDataDone",
                                        "fieldId"   : "' . $this->params['fieldId'] . '"
                                    }
                                );
                            }

                            fieldId["reset"] = function() {
                                delete window["dataCollection"]["' . $this->params['componentId'] . '"]["' . $this->compSecId . '"]["data"]["' . $this->params['fieldId'] . '"];

                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-sortable-attachments li").remove();
                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-sortable-attachments .no-data").attr("hidden", false);

                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-save").attr("hidden", true);
                                $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-dropzone-cancel").attr("hidden", true);

                                fieldId["dropzone"].removeAllFiles(true);

                                initialFiles = $("#' . $this->compSecId . '-' . $this->params['fieldId'] . '-sortable-attachments li").length;
                                filesLimit = parseInt("' . $this->fieldParams['maxAttachments'] . '") - initialFiles;
                                deleteUUIDs = [];
                                problemWithUpload = false;

                                collectData();
                            }
                        }
                    }
                });
            </script>';

        return $inclJs;
    }
}