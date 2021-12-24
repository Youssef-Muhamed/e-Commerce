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
// $('.cat h3').click(function(){
//     $(this).next('.full-view').fadeToggle(200);
// });

// Confirm Delete

// document.querySelector('.confirm').addEventListener('click', ()=>{
//     return confirm('Do You Want To Delete This Member?');
// });
