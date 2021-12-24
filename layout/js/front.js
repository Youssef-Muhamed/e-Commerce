let input = document.querySelectorAll('input');

input.forEach((e)=>{
    if (e.getAttribute('required')){
        let el = document.createElement('span');
        el.className ='astrek';
        el.textContent="*";
        e.after(el);
       
    }
});

//  Show passsword

let eye = document.querySelector(".show-pass");
let pass = document.querySelector(".password");
eye.addEventListener('click', () => {
    if (pass.type === 'password') {
        pass.type = 'text';
        eye.classList ='show-pass fas fa-eye-slash';
    } else {
        pass.type = 'password';
        eye.classList ='show-pass fas fa-eye';
    }
});

// Login And SignUp

$(function(){
    $('.login-page h1 span').click(function(){
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).fadeIn(100);
    });

    // Live New Add
    $('.live-name').keyup(function(){
        console.log($(this).val());
    });
});