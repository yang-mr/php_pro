<!DOCTYPE doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
            <title>
                jQuery UI 对话框（Dialog） - 模态表单
            </title>
            <link href="//apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css" rel="stylesheet">
                <script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js">
                </script>
                <script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js">
                </script>
                <link href="jqueryui/style.css" rel="stylesheet">
                    <style>
                        body { font-size: 62.5%; }
    label, input { display:block; }
    input.text { margin-bottom:12px; width:95%; padding: .4em; }
    fieldset { padding:0; border:0; margin-top:25px; }
    h1 { font-size: 1.2em; margin: .6em 0; }
    div#users-contain { width: 350px; margin: 20px 0; }
    div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
    div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
    .ui-dialog .ui-state-error { padding: .3em; }
    .validateTips { border: 1px solid transparent; padding: 0.3em; }
                    </style>
                    <script>
                        $(function() {
    var name = $( "#name" ),
      email = $( "#email" ),
      password = $( "#password" ),
      allFields = $( [] ).add( name ).add( email ).add( password ),
      tips = $( ".validateTips" );
 
    function updateTips( t ) {
      tips
        .text( t )
        .addClass( "ui-state-highlight" );
      setTimeout(function() {
        tips.removeClass( "ui-state-highlight", 1500 );
      }, 500 );
    }
 
    function checkLength( o, n, min, max ) {
      if ( o.val().length > max || o.val().length < min ) {
        o.addClass( "ui-state-error" );
        updateTips( "" + n + " 的长度必须在 " +
          min + " 和 " + max + " 之间。" );
        return false;
      } else {
        return true;
      }
    }
 
    function checkRegexp( o, regexp, n ) {
      if ( !( regexp.test( o.val() ) ) ) {
        o.addClass( "ui-state-error" );
        updateTips( n );
        return false;
      } else {
        return true;
      }
    }
 
    $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 300,
      width: 350,
      modal: true,
      buttons: {
        "创建一个帐户": function() {
          var bValid = true;
          allFields.removeClass( "ui-state-error" );
 
          bValid = bValid && checkLength( name, "username", 3, 16 );
          bValid = bValid && checkLength( email, "email", 6, 80 );
          bValid = bValid && checkLength( password, "password", 5, 16 );
 
          bValid = bValid && checkRegexp( name, /^[a-z]([0-9a-z_])+$/i, "用户名必须由 a-z、0-9、下划线组成，且必须以字母开头。" );
          // From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
          bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
          bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "密码字段只允许： a-z 0-9" );
 
          if ( bValid ) {
            $( "#users tbody" ).append( "<tr>" +
              "<td>" + name.val() + "</td>" +
              "<td>" + email.val() + "</td>" +
              "<td>" + password.val() + "</td>" +
            "</tr>" );
            $( this ).dialog( "close" );
          }
        },
        Cancel: function() {
          $( this ).dialog( "close" );
        }
      },
      close: function() {
        allFields.val( "" ).removeClass( "ui-state-error" );
      }
    });
 
    $( "#create-user" )
      .button()
      .click(function() {
        $( "#dialog-form" ).dialog( "open" );
      });
  });
                    </script>
                </link>
            </link>
        </meta>
    </head>
    <body>
        <div id="dialog-form" title="创建新用户">
            <p class="validateTips">
                所有的表单字段都是必填的。
            </p>
            <form>
                <fieldset>
                    <label for="name">
                        名字
                    </label>
                    <input class="text ui-widget-content ui-corner-all" id="name" name="name" type="text">
                        <label for="email">
                            邮箱
                        </label>
                        <input class="text ui-widget-content ui-corner-all" id="email" name="email" type="text" value="">
                            <label for="password">
                                密码
                            </label>
                            <input class="text ui-widget-content ui-corner-all" id="password" name="password" type="password" value="">
                            </input>
                        </input>
                    </input>
                </fieldset>
            </form>
        </div>
        <div class="ui-widget" id="users-contain">
            <h1>
                已有的用户：
            </h1>
            <table class="ui-widget ui-widget-content" id="users">
                <thead>
                    <tr class="ui-widget-header ">
                        <th>
                            名字
                        </th>
                        <th>
                            邮箱
                        </th>
                        <th>
                            密码
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            John Doe
                        </td>
                        <td>
                            john.doe@example.com
                        </td>
                        <td>
                            johndoe1
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <button id="create-user">
            创建新用户
        </button>
    </body>
</html>