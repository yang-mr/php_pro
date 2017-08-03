$(function() {
    $("#createwant").click(function() {
        createwant();
        return false;
    })
})

function createwant() {
    var title = $("title").val();
    var des = $("des").val();
    var area = $("area").val();
    var price = $("price").val();

    $.post("../controller/createwant.php", {
            action: 'createwant',
            title: title,
            des: des,
            area: area,
            price: price
        },
        function(data, status) {
            if ('success' == status) {
                if (data == 1) {
                    $("#user_index").html("欢迎来到礼物汇");
                    $("#wrapper").show();
                    $(".quit").hide();
                }
            }
        });
}