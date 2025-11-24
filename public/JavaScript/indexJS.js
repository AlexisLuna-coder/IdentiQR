/*AQUÍ PONDREMOS LA LÓGICA PARA EL FUNCIONAMIENTO DEL BOTÓN HAMBURGUESA DEL INDEX*/
document.addEventListener('DOMContentLoaded', () => {
        const btnMenu = document.getElementById('btn_menu');
        const nav = document.getElementById('nav');

        if(btnMenu && nav){
            btnMenu.addEventListener('click', () => {
                nav.classList.toggle('active');
            });
        }
    });