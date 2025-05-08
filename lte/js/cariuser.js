
cariuser.addEventListener("keyup", function (e) {
    if (e.which == 38 || e.which == 40) {
        e.preventDefault();
    } else {
        linkcariuser = document.getElementById('linkcariuser');
        var url = linkcariuser.getAttribute('url');
        var _token = linkcariuser.getAttribute('_token');
        var searchData = e.target.value;
        if (searchData.length < 2) { } else {
            if (reqsent == false) {
                reqsent = true;
                $.ajax({
                    url: url,
                    method: "POST",
                    minLength: 2,
                    data: {
                        '_token': _token,
                        cariuser: searchData
                    },
                    success: function (data) {
                        reqsent = false;
                        var html = '';
                        for (var count = 0; count < data.length; count++) {
                            html += '<option value="' + data[count].id + '" data-id="' + data[
                                count]
                                .id + '">' + data[count].nama + ' / ' + data[count].username +
                                '</option>';
                        }
                        $('#user_id').html(html);
                        $('#user_id').selectpicker('refresh');
                    },
                    error: function (data) {
                        reqsent = false;
                        console.log(data);
                    }
                });
            }
        }
    }
});