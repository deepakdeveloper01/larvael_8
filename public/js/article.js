'use strict';

var s_blog;

var Article = {
    init: function () {
        s_blog = {
            addTextButton: $('#insert-text'),
            addImageButton: $('#insert-image'),
            addVideoButton: $('#insert-video'),
            articleFormContent: $('.article_form_content'),
            form: $('#article-form'),
            submitButton: $('#submitCmsPageForm'),
            baseUrl: '/admin/cms-page',
        };
        this.bindUIActions();
    },

    rebindUIActions: function () {
        this.unbindUIActions();
        this.bindUIActions();
    },

    unbindUIActions: function () {
        s_blog.submitButton.off('click');
        s_blog.addTextButton.off('click');
        s_blog.addImageButton.off('click');
        s_blog.addVideoButton.off('click');
        s_blog.removeBlogItem.off('click');
        s_blog.articleFormContent.sortable('refresh');
    },

    bindUIActions: function () {
        s_blog.removeBlogItem = $('.remove-blogContent a');

        s_blog.submitButton.on('click', function (e) {
            e.preventDefault();
             console.log('form--------'+s_blog.form);
             
            if (s_blog.form[0].checkValidity()) {
                Article.fixOrder();
                s_blog.form.attr('action', $(this).attr('formaction'));
                s_blog.form.submit();
            } else {
                s_blog.form[0].reportValidity();
            }
        });

        s_blog.addTextButton.on('click', function (e) {
            e.preventDefault();
            Article.addTextBlock(e, this);
        });

        s_blog.addVideoButton.on('click', function (e) {
            e.preventDefault();
            Article.addVideoBlock(e, this);
        });

        s_blog.removeBlogItem.on('click', function (e) {
            e.preventDefault();
            Article.deleteBlock(e, this);
        });

        s_blog.addImageButton.on('click', function (e) {
            e.preventDefault();
            Article.addImageBlock(e, this);
        });

        s_blog.articleFormContent.sortable({
            placeholder: "ui-state-highlight",
            items: "> .sortable-item",
            stop: function(event, ui) {
                var textArea = $(ui.item[0]).find('.tinymce-textarea');
                if (textArea.length !== 0) {
                    var attributeId = textArea.attr('id');
                    tinyMCE.execCommand('mceRemoveEditor', false, attributeId);
                    tinymce.init(
                        {
                            "plugins":
                                ["advlist autolink lists link charmap print preview anchor", "searchreplace visualblocks code fullscreen", " paste"],
                            "toolbar": "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
                            "selector": "#" + attributeId
                        }
                    );
                }

            }
        });
    },

    fixOrder: function () {
        var listElements = $('.article_form_content').children();
        if (listElements.length > 0) {
            listElements.each(function (index) {
                var oldName = $(this).find('.form-order-data').attr('name');
                var regex = /ArticleForm\[\w+\]\[(\d+)\]/i;
                var matches = regex.exec(oldName);
                if (null !== matches && matches.length > 0) {
                    var fullName = matches[0];
                    var timestamp = matches[1];
                    var newName = fullName.replace(timestamp, index);
                    $(this).find('.form-order-data').attr('name', newName);
                    $("input[name*='" + oldName + "']").attr('name', newName);
                    console.log('mycms oldName='+oldName +'new name==>'+newName);
                }
            });
        }
    },

    deleteBlock: function (e, clicked) {
        $(clicked).closest('.sortable-item').remove();
    },

    addTextBlock: function (e, clicked) {
        $(clicked).prop('disabled', true);
        var textBlock = $.ajax({
            url: s_blog.baseUrl + '/render-text-block'
        });

        textBlock.done(function (data) {
            s_blog.articleFormContent.append(data);
            Article.rebindUIActions();
            $(clicked).prop('disabled', false);
        });
    },

    addImageBlock: function (e, clicked) {
        $(clicked).prop('disabled', true);
        var imageBlock = $.ajax({
            url: s_blog.baseUrl + '/render-image-block'
        });

        imageBlock.done(function (data) {
            s_blog.articleFormContent.append(data);
            Article.rebindUIActions();
            $(clicked).prop('disabled', false);
        });
    },

    addVideoBlock: function (e, clicked) {
        $(clicked).prop('disabled', true);
        var videoBlock = $.ajax({
            url: s_blog.baseUrl + '/render-video-block'
        });

        videoBlock.done(function (data) {
            s_blog.articleFormContent.append(data);
            Article.rebindUIActions();
            $(clicked).prop('disabled', false);
        });
    },

};

$(document).ready(function () {
    Article.init();
});
