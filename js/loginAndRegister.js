$(document).ready(function () {

    const signUpButton = $('#signUp');
    const signInButton = $('#signIn');
    const main = $('#main');

    signUpButton.on('click', function () {
        main.addClass('right-panel-active');
    });

    signInButton.on('click', function () {
        main.removeClass('right-panel-active');
    });
});
