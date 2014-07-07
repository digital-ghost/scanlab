$(document).ready(function(){
    $('.enable-api').click(function(){
        $.post(rel_url+"root", {action:"enable_api", username:this.title, token:token}).done(function(data){
            if (data == "1") { location.reload();} else { alert("something went wrong"); }
        });
    });
    $('.disable-api').click(function(){
        $.post(rel_url+"root", {action:"disable_api", username:this.title, token:token}).done(function(data){
            if (data == "1") { location.reload();} else { alert("something went wrong"); }
        });
    });
    $('.del-user').click(function(){
        $.post(rel_url+"root", {action:"del_user", username:this.title, token:token}).done(function(data){
            if (data == "1") { location.reload();} else { alert("something went wrong"); }
        });
    });
    $('.del-reports').click(function(){
        $.post(rel_url+"root", {action:"del_reports", username:this.title, token:token}).done(function(data){
            if (data == "1") { location.reload();} else { alert("something went wrong"); }
        });
    });
    $('.count-reports').click(function(){
        $.post(rel_url+"root", {action:"count_reports", username:this.title, token:token}).done(function(data){
            if (data == "1") { location.reload();} else { alert("something went wrong"); }
        });
    });

});
