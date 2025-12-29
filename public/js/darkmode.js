document.addEventListener("DOMContentLoaded", function(){
    const toggle = document.getElementById("darkToggle");
    if(localStorage.theme==="dark") document.documentElement.classList.add("dark");

    if(toggle){
        toggle.addEventListener("change", ()=>{
            if(toggle.checked){
                document.documentElement.classList.add("dark");
                localStorage.theme="dark";
            } else {
                document.documentElement.classList.remove("dark");
                localStorage.theme="light";
            }
        });
    }
});
