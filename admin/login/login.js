
$(document).ready(function () {
       
    $('#btnLogin').click(function () {
        
        var userName = $('#user').val();
        var password = $('#password').val();
        
        var parameters = {
            header: {
                country: "CRI"
            }, 
            body: {
                userName: userName, 
                password: password
            }
        };
        
        login(parameters);
    });
    
});

function login(parameters) {   
	
    $.ajax({
        method: "POST",
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        url: '../../services/player/login',
        data: JSON.stringify(parameters),      
        success: function(result) {
            
            if(result.header.status === 'success'){
                createSession(result);
                

            } else {
                console.log(result.header.message);
            }
        },
        error:function(jqXHR, textStatus, errorThrown){

            console.log("Error: ----> " + jqXHR.responseText);
        }
    });
                
}

function createSession(result){
    var data = {
        
        idPlayer: result.body.idPlayer,
        token: result.header.token,
        country: result.header.country
        
    };
    
    $.ajax({
        method: "POST",
        dataType: 'json',
        contentType: "application/json; charset=utf-8",
        url: '../security.php',
        data: JSON.stringify(data),      
        success: function (result) {
            
            if (result.header.status === 'success') {
                window.location.href = "../index.php";             

            } else {
                console.log(result.header.message);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {

            console.log("Error: ----> " + jqXHR.responseText);
        }
    });
}