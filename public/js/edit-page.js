$(window).load(function () {
    $('.tinymce-editor-div').show();
    var value;
    $('.edit-chapter-save').click(function (e) {
        e.preventDefault();
        value = $(this).attr('data-value');
        if (value == 'submit')
            $("#submitPopupModal").modal({backdrop: 'static', keyboard: false});

        if (value == 'draft') {
            ajaxCallTosubmit(value)
        }
    });

    $('#submitPopupContinue').click(function () {
        $('.modal-footer .footer-btn').hide();
        $('.modal-footer .footer-submit-text').show();
        ajaxCallTosubmit(value)
    });

    function ajaxCallTosubmit(value) {
        var url = Laravel.basePath + 'chapter/save';
        var textValue = tinyMCE.activeEditor.getContent();
        var cid = document.getElementById('cid').value;

        $.ajax(url,
            {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',  // http method
                data: {text: textValue, status: value, cid: cid},
                success: function (data, status, xhr) {
                    if (data == 'DataNotFound') {
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
    changeFlag = 'deactive';

    var uName = document.getElementById('uName').value;
    var uId = document.getElementById('uid').value;
    var rid = document.getElementById('rid').value;

    tinymce.init({
        mode: "exact",
        elements: "description_edit",
        theme: "advanced",
        plugins: 'ice,icesearchreplace,table',
        theme_advanced_buttons1: "bold,italic,underline,|,bullist,numlist,|,undo,redo,|,search,replace,ice_toggleshowchanges,|,mainsectionstart,subsection,title",
        theme_advanced_buttons2: "tablecontrols",
        table_styles: "Header 1=header1;Header 2=header2;Header 3=header3",
        table_cell_styles: "Header 1=header1;Header 2=header2;Header 3=header3;Table Cell=tableCel1",
        table_row_styles: "Header 1=header1;Header 2=header2;Header 3=header3;Table Row=tableRow1",
        table_cell_limit: 100,
        table_row_limit: 5,
        table_col_limit: 5,
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        extended_valid_elements: "p,span[*],delete[*],insert[*],mainsectionstart,subsection[p|h3]",
        entity_encoding: 'raw',

        ice: {
            preserveOnPaste: 'p,a[href],i,em,b,span',
            user: {name: uName, id: uId},
            deleteTag: 'delete',
            insertTag: 'insert'
        },
        setup: function (editor) {
            editor.addButton('mainsectionstart',
                {
                    text: 'main-sec-start',
                    title: 'main-section-start',
                    icon: false,
                    label: 'Add main section',
                    onclick: function () {
                        tinymce.activeEditor.execCommand('mceInsertContent', false, "<div class='main-section'></div>");
                        editor.focus();
                    }
                });

            editor.addButton('subsection', {
                text: 'sub-sec',
                title: 'sub-section',
                icon: true,
                label: 'Add sub section',
                onclick: function () {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, "<div class='sub-section'></div>");
                }
            });

            editor.addButton('title', {
                text: 'title',
                title: 'title',
                icon: true,
                label: 'Add title',
                onclick: function () {
                    tinymce.activeEditor.execCommand('mceInsertContent', true, "<h3>");
                }
            });

            editor.onChange.add(function () {
                autosave();
                //changeFlag = 'active';
            });
        },

        schema: "html5",
        end_container_on_empty_block: true,
        height: '650',
        entities: '160,nbsp,38,amp,60,lt,62,gt',
    });

    var date = document.getElementById('expiredOn').value;
    var countDownDate = new Date(date).getTime();
    var countDownDate = date;

    function okClick() {
        $('#timeRemainingContinue').attr('data-flag', 'deactive');
    }

    var x = setInterval(function () {

        var now = new Date().getTime();
        var sdNow = Math.floor(new Date(now).getTime());
        var distance = parseInt(countDownDate) - sdNow;

        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

        var flag = $('#timeRemainingContinue').attr('data-flag');
        if (days > 0) {
            document.getElementById("timeRemaining").innerHTML = "Time remaining for submission: " + days + " days ";
        }
        else if (hours > 0) {
            document.getElementById("timeRemaining").innerHTML = "Time remaining for submission: " + hours + "hrs";
        }
        else {
            document.getElementById("timeRemaining").innerHTML = "Time remaining for submission: " + minutes + "m " + seconds + "s";
        }

        var mins = Math.floor(distance / 60000);
        if (mins <= 30 && flag == 'active' && mins > 0) {
            $("#timeRemainingAlert").modal({backdrop: 'static', keyboard: false});
            $('#timeRemain').text(mins);
        }
        else if (distance < 0) {
            clearInterval(x);
            document.getElementById("timeRemaining").innerHTML = "EXPIRED";
            var url = window.location.href;
            if (url.indexOf('chapters/edit/') >= 0) {
                var cid = url.split('chapters/edit/')[1];
                var url = Laravel.basePath + 'chapter/autosave/' + cid;
                var textValue = tinyMCE.activeEditor.getContent();
                $.ajax(url,
                    {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',  // http method
                        data: {text: textValue, status: 'Edited'},
                        success: function (data, status, xhr) {
                            $("#timeExpireSave").modal({backdrop: 'static', keyboard: false});
                        },
                        error: function (jqXhr, textStatus, errorMessage) {
                            console.log('Error');
                        }
                    });
            }
        }
    }, 1000);

    function autosave() {
        if (window.location.href.indexOf('chapters/edit/') >= 0) {
            var url = window.location.href;
            if (url.indexOf('chapters/edit/') >= 0) {
                var cid = url.split('chapters/edit/')[1];
                var url = Laravel.basePath + 'chapter/autosave/' + cid;

                var textValue = tinyMCE.activeEditor.getContent();
                $.ajax(url,
                    {
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',  // http method
                        data: {text: textValue, status: 'draft', rid: rid},
                        success: function (data, status, xhr) {
                            var currentdate = new Date();
                            document.getElementById("autoSaveTime").innerHTML = 'Saving...';

                            setTimeout(function () {
                                document.getElementById("autoSaveTime").innerHTML = 'Changes have been saved.';
                            }, 1000);

                            /*document.getElementById("autoSaveTime").innerHTML = "Auto saved on: "+
                             currentdate.getHours()+":"+ currentdate.getMinutes() + ":"+ currentdate.getSeconds();*/

                            if (data == 'error') {

                                $("#unlockPopupModalAlert").modal({backdrop: 'static', keyboard: false});
                                /*setTimeout(function () {
                                 var editUrl = Laravel.basePath + 'chapters';
                                 window.location.href = editUrl;
                                 }, 1000);*/
                            }
                        },
                        error: function (jqXhr, textStatus, errorMessage) {
                            console.log('Error');
                        }
                    });
            }
        }
    }
});


