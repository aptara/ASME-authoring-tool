$(document).ready(function () {
    $('.book-text-edit').click(function (e) {
        e.preventDefault();
        $('.book-text-form').show();
        $('.book-text-p').hide();
        $('.book-text-edit').hide();
        $('.book-text-close').show();
    });

    tinymce.init({
        mode: "exact",
        elements: "book_Text",
        theme: "advanced",
        theme_advanced_buttons1: "bold,italic,underline,|,bullist,numlist,|,undo,redo",
        height: '200',
        width: '100%',
        body_class : "book-text-editor"
    });

    tinymce.init({
        mode: "exact",
        elements: "intro_text",
        theme: "advanced",
        theme_advanced_buttons1: "bold,italic,underline,|,bullist,numlist,|,undo,redo",
        height: '200',
        width: '100%',
        body_class : "book-text-editor"
    });

    $('.book-text-close').click(function (e) {
        e.preventDefault();
        $('.book-text-form').hide();
        $('.book-text-p').show();
        $('.book-text-edit').show();
        $('.book-text-close').hide();
    });

    $('.contributors-btn').click(function () {
        var uid = $(this).attr('data-uid');
        var contributorFun = $(this).attr('data-contributor-fun');

        $('#contributorFunContinue').attr('data-uid', uid);
        $('#contributorFunContinue').attr('data-fun', contributorFun);

        switch (contributorFun) {
            case 'activate':
                $('.modal-title').html('This user will be activated');
                $('.modal-body').html('The User will be activated and able to contribute to any chapter once you click continue.<br> Are you sure you want to proceed?');
                break;

            case 'deactivate':
                $('.modal-title').html('This user will be deactivated');
                $('.modal-body').html('The User will be deactivated and no longer to contribute to any chapter once you click continue.<br> Are you sure you want to proceed?');
                break;

            case 'delete':
                $('.modal-title').html('This user will be deleted');
                $('.modal-body').html('The user will be deleted and no loger be able contribute to any chapter and completely removed from the sysytem once you click continue.<br> Are you sure you want to proceed?<br> This action cannot be undone.');
                break;

        }
        $("#contributorFunPopupModal").modal({backdrop: 'static', keyboard: false});
        return false;
    });

    $('#contributorFunContinue').click(function () {
        var uid = $(this).attr('data-uid');
        var fun = $(this).attr('data-fun');

        var url = Laravel.basePath + 'contributors/' + fun + '/' + uid;

        $.ajax(url, {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',  // http method
            success: function (data, status, xhr) {
                if (status == "success") {
                    var editUrl = Laravel.basePath + '/contributors';
                    window.location.href = editUrl
                }
            },
            error: function (jqXhr, textStatus, errorMessage) {

            }
        });
    });

    $(".edit-chapter-btn").click(function () {
        var popupShow = $(this).attr('data-popup-show');
        if (popupShow == "true") {

        }
        else {
            var cid = $(this).attr('data-cid');
            $('#editLockedContinue').attr('data-cid', cid);
            $("#editPopupModal").modal({backdrop: 'static', keyboard: false});
            return false;
        }
    });

    $('#editLockedContinue').click(function () {
        var cid = $(this).attr('data-cid');
        var url = Laravel.basePath + 'chapter/lock/' + cid;
        $.ajax(url, {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',  // http method
            success: function (data, status, xhr) {
                if (status == "success") {
                    var editUrl = Laravel.basePath + 'chapters/edit/' + cid;
                    window.location.href = editUrl
                }
            },
            error: function (jqXhr, textStatus, errorMessage) {

            }
        });
    });

    $('.unlock-chapter-btn').click(function () {
        var cid = $(this).attr('data-cid');
        $('#unlockContinue').attr('data-cid', cid);
        $("#unlockPopupModal").modal({backdrop: 'static', keyboard: false});
        return false;
    });

    $('#unlockContinue').click(function () {
        var cid = $(this).attr('data-cid');
        var url = Laravel.basePath + 'chapters/unlock/' + cid;
        $.ajax(url, {
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',  // http method
            success: function (data, status, xhr) {
                if (status == "success") {
                    var editUrl = Laravel.basePath + 'chapters';
                    window.location.href = editUrl
                }
            },
            error: function (jqXhr, textStatus, errorMessage) {

            }
        });
    });

});
