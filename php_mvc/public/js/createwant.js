$(function() {
    $("#createwant").click(function() {
        createwant();
        return false;
    });
});

function createwant() {
    var title = $("#title").val();
    var des = $("#des").val();
    var area = $("#area").val();
    var price = $("#price").val();
    var file = $("#file").val();

    $.post("../controller/createwant.php", {
            action: 'createwant',
            title: title,
            des: des,
            area: area,
            price: price,
            file: file
        },
        function(data, status) {
            alert("data: " + data + "status: " + status);
            if ('success' == status) {
                if (data == 1) {
                    
                }
            }
        });
}