$(document).ready(function () {

    /*toggle between login and sign up form*/
   $("#hideLogin").click(function () {
       $("#loginForm").hide();
       $("#registerForm").show();

   });

    $("#hideRegister").click(function () {
        console.log("work?");
        $("#loginForm").show();
        $("#registerForm").hide();

    });

});