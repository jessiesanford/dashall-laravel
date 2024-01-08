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

});