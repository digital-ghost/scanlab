$(document).ready(function(){
    $('.enable-api').click(function(){
        $.post(rel_url+"root", {action:"enable_api", username:this.title}).done(function(data){
            if (data == "1") { location.reload();} else { alert("something went wrong"); }
        });
    });
    $('.disable-api').click(function(){
        $.post(rel_url+"root", {action:"disable_api", username:this.title}).done(function(data){
            if (data == "1") { location.reload();} else { alert("something went wrong"); }
        });
    });
    $('.del-user').click(function(){
        $.post(rel_url+"root", {action:"del_user", username:this.title}).done(function(data){
            if (data == "1") { location.reload();} else { alert("something went wrong"); }
        });
    });
    $('.del-reports').click(function(){
        $.post(rel_url+"root", {action:"del_reports", username:this.title}).done(function(data){
            if (data == "1") { location.reload();} else { alert("something went wrong"); }
        });
    });
});
