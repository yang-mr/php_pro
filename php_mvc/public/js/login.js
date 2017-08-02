$(function() {
    $.post("../controller/login.php",
        function(data, status) {
            if ('success' == status) {
                if (data == 0) {
                    $(".quit").hide();
                } else {
                    var tmp = "欢迎<a href='#'>" + data + "</a>来到礼物汇";
                    $("#user_index").html(tmp);
                    $("#wrapper").hide();
                }
            }
        });
    $(".quit").click(function() {
        quit_account();
        return false;
    })
    $("#create").click(function() {
        check_register();
        return false;
    })
    $("#login").click(function() {
        check_login();
        return false;
    })
    $("#toregister").click(function() {
        showres();
        return false;
    })
    $('.message a').click(function() {
        $('form').animate({
            height: 'toggle',
            opacity: 'toggle'
        }, 'slow');
    });
})

function quit_account() {
    $.post("../controller/login.php", {
            action: 'quitaccount'
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

function refreshVerify() {
    var ts = Date.parse(new Date()) / 1000;
    $('#verify_img').attr("src", "/captcha?id=" + ts);
}

function check_login() {
    var name = $("#user_name").val();
    var pass = $("#password").val();
    if ("" == name || "" == pass) {
        showError();
        alert("用户名和密码不能为空");
        return;
    }

    $.post("../controller/login.php", {
            username: name,
            password: pass,
            action: 'login'
        },
        function(data, status) {
            if ('success' == status) {
                if (data == 0) {
                    alert("用户名错误");
                    $("#login_form").removeClass('shake_effect');
                    setTimeout(function() {
                        $("#login_form").addClass('shake_effect')
                    }, 1);
                } else if (data == 1) {
                    +
                    alert("密码错误");
                    $("#login_form").removeClass('shake_effect');
                    setTimeout(function() {
                        $("#login_form").addClass('shake_effect')
                    }, 1);
                } else if (data == 2) {
                    var tmp = "欢迎<a href='#'>" + data + "</a>来到礼物汇";
                    $("#user_index").html(tmp);
                    alert("登录成功");
                    $("#wrapper").hide();
                    $(".quit").show();
                }
            }
        });
}

function check_register() {
    var name = $("#r_user_name").val();
    var pass = $("#r_password").val();
    var pass2 = $("#r_password2").val();
    var type = $(".select_type").val();

    if ("" == name || "" == pass) {
        alert("用户名和密码不能为空");
        showError();
        return;
    }
    if (pass != pass2) {
        alert("两次密码要相同");
        showError();
        return;
    }

    $.post("../controller/login.php", {
            username: name,
            password: pass,
            type: type,
            action: 'register'
        },
        function(data, status) {
            if ('success' == status) {
                if (data == 0) {
                    alert("该用户已存在");
                    showError();
                } else if (data == 1) {
                    var tmp = "欢迎<a href='#'>" + data + "</a>来到礼物汇";
                    $("#user_index").html(tmp);
                    $("#wrapper").hide();
                    $(".quit").show();
                    alert("注册成功");
                } else if (data == 2) {
                    alert("注册失败");
                    showError();
                }
            }
        });
}

function showError() {
    $("#login_form").removeClass('shake_effect');
    setTimeout(function() {
        $("#login_form").addClass('shake_effect')
    }, 1);
}

function showres() {
    $(".login_form #register-form").show();
    $(".login_form #login-form").hide();
}