$(document).ready(function() {

    $(document).on('click', "#promote_to_driver", function (e)
    {
        var element = $(this);
        var user_id = $(this).attr('data-user_id');

        $.ajax({
            type: "POST",
            url: "./manage/",
            data:{
                "action": "promote_to_driver",
                "user_id": user_id,
            },
            success: function(data)
            {
                popup(data);
                location.reload();
            }
        });

        return false;
    });



    $(document).on('click', "#remove_driver", function (e)
    {
        var element = $(this);
        var user_id = $(this).attr('data-user_id');

        $.ajax({
            type: "POST",
            url: "./manage/",
            data:{
                "action": "remove_driver",
                "user_id": user_id,
            },
            success: function(data)
            {
                popup(data);
                location.reload();
            }
        });

        return false;
    });




    $(document).on('submit', "#add_dashcash", function (e)
    {
        form = $(this);

        $.ajax({
            type: "POST",
            url: './manage/',
            data: form.serialize() + "&action=add_dashcash",
            dataType: "json",
            success: function(data)
            {
                popup(data.alert);
                $('.section').load(location.href + " .section>*","");
            },
            error: function (xhr, ajaxOptions, thrownError)
            {
                console.log(xhr.responseText + thrownError);
            }
        });
        return false;
    });


    $(document).on('submit', "#user_search", function (e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            type: "POST",
            url: "./admin/userSearch",
            dataType: 'json',
            data: formData,
            success: function(data)
            {
                var results = data.results;
                console.log(results);
                $('#user_table .rows').empty();

                for(var k in results)
                {
                    $('#user_table .rows').append('<div class="row row-collapse"><div class="cell wid-25">' + results[k].email + '</div><div class="cell wid-25"><a href=' + results[k].user_id + '"?module=customer&id=">' + results[k].first_name + ' ' + results[k].last_name + '</a></div><div class="cell wid-25">' + results[k].phone + '</div><div class="cell resp_hide wid-25">' + results[k].reg_date + '</div></div>');
                }
            },
            error: function (data)
            {
                generateError(data);
            }
        });

        return false;
    });

});