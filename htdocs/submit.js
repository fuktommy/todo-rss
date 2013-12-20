/*
 * Copyright (c) 2013 Satoshi Fukutomi <info@fuktommy.com>.
 * https://github.com/fuktommy/todo-rss/blob/master/LICENSE
 */
$(function () {
    $('#addform').submit(function (event) {
        $.ajax({
            url: '/add',
            async: false,
            type: 'post',
            data: {
                nickname: $('#nickname').val(),
                body: $('#body').val()
            },
            success: function (data, dataType) {
                event.preventDefault();
                location.href = '/';
            }
        });
    });
});
