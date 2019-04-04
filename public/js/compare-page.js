$(window).load(function () {
    $('.publish-error-custom').hide();

    $('.tinymce-compare-div').show();
    var value;
    $('.compare-chapter-save').click(function (e) {
        e.preventDefault();

        $('.modal-footer .footer-btn').show();
        $('.modal-footer .footer-submit-text').hide();

        value = $(this).attr('data-value');
        if (value == 'publish')
            $("#comparePublishedModal").modal({backdrop: 'static', keyboard: false});
        else if (value == 'reject')
            $("#compareRejectPopupModal").modal({backdrop: 'static', keyboard: false});
    });

    $('#comparePublishedContinue').click(function () {
        $('.modal-footer .footer-btn').hide();
        $('.modal-footer .footer-submit-text').show();
        ajaxCallToCompare(value);
    });

    $('#compareRejectContinue').click(function () {
        $('.modal-footer .footer-btn').hide();
        $('.modal-footer .footer-submit-text').show();
        ajaxCallToCompare(value);
    });

    function ajaxCallToCompare(value) {
        $('.publish-error-custom').hide();
        var url = Laravel.basePath + 'chapters/compare/save';
        var textValue = tinyMCE.activeEditor.getContent();
        var cid = document.getElementById('cid').value;
        var rid = document.getElementById('rid').value;

        $.ajax(url,
            {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',  // http method
                data: {text: textValue, status: value, cid: cid, rid: rid},
                success: function (data, status, xhr) {
                    if (data == 'error') {
                        $('#comparePublishedModal').modal('hide');
                        $('.publish-error-custom').show();
                    }
                    else if (data == 'DataNotFound') {
                        console.log(data);
                    }
                    else {
                        var editUrl = Laravel.basePath + 'chapters';
                        window.location.href = editUrl;
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    console.log('Error');
                }
            });
    }

});

$(document).ready(function () {
    tinymce.init({
        mode: "exact",
        elements: "updatedText",
        theme: "advanced",
        plugins: 'ice,icesearchreplace,table',
        theme_advanced_buttons1: "iceacceptall,icerejectall,|,bold,italic,underline,|,bullist,numlist,|,undo,redo,code,|,search,replace,|,ice_togglechanges,ice_toggleshowchanges,iceaccept,icereject,",
        theme_advanced_buttons2: "tablecontrols",
        table_styles: "Header 1=header1;Header 2=header2;Header 3=header3",
        table_cell_styles: "Header 1=header1;Header 2=header2;Header 3=header3;Table Cell=tableCel1",
        table_row_styles: "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1",
        table_cell_limit: 100,
        table_row_limit: 5,
        table_col_limit: 5,
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        extended_valid_elements: "p,span[*],delete[*],insert[*]",
        ice: {
            preserveOnPaste: 'p,a[href],i,em,b,span',
            deleteTag: 'delete',
            insertTag: 'insert'
        },

        height: '650',
        entity_encoding: 'raw',
        entities: '160,nbsp,38,amp,60,lt,62,gt',

    });
});
