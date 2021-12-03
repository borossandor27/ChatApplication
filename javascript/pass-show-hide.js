/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
const pswrdField = document.querySelector(".form .field input[type='password']"),
        toggleBtn = document.querySelector(".form .field i");

toggleBtn.onclick = () => {
    if(pswrdField.type === "password"){
        pswrdField.type = "text";
        toggleBtn.classList.add("active");
    } else {
        pswrdField.type = "password";
        toggleBtn.classList.remove("active");
    }
};

